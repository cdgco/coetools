<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../urls.php');

if ($authMethod !== 'oauth') {
    header("Location: " . $baseUrl . "/#/error?code=400&message=" . urlencode("Invalid Authentication Method") . "&description=" . urlencode("The requested authentication method is not enabled."));
    exit();
}

use League\OAuth2\Client\Provider\GenericProvider;

$oauthSettings['redirectUri'] = $apiUrl . '/auth/oauth/auth.php';
$userGroups = $oauthSettings['userGroups'] ?? [];
$adminGroups = $oauthSettings['adminGroups'] ?? [];
unset($oauthSettings['userGroups']);
unset($oauthSettings['adminGroups']);

/** @disregard undefined type */
$provider = new GenericProvider($oauthSettings);

if (isset($_GET['code'])) {
    if (empty($_GET['state']) || empty($_SESSION['oauth2state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
        if (isset($_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
        }

        session_destroy();
        header("Location: " . $baseUrl . "/#/error?code=500&description=" . urlencode("The state parameter did not match the expected value. This may be a sign of a CSRF attack. Please try logging in again or contact the administrator for assistance."));
        exit();
    }

    /** @disregard undefined type */
    try {
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // You can save the access token in the session or database as needed
        $_SESSION['accessToken'] = $accessToken->getToken();
        $_SESSION['refreshToken'] = $accessToken->getRefreshToken();
        $_SESSION['expires'] = $accessToken->getExpires();

        if (!isset($_SESSION['accessToken']) || empty($_SESSION['accessToken'])) {
            session_destroy();
            header("Location: " . $baseUrl . "/#/error?code=500&description=" . urlencode("Failed to get access token. Please try logging in again or contact the administrator for assistance."));
            exit();
        } elseif (!isset($_SESSION['refreshToken']) || empty($_SESSION['refreshToken'])) {
            session_destroy();
            header("Location: " . $baseUrl . "/#/error?code=500&description=" . urlencode("Failed to get refresh token. Please try logging in again or contact the administrator for assistance."));
            exit();
        } elseif (!isset($_SESSION['expires']) || empty($_SESSION['expires'])) {
            session_destroy();
            header("Location: " . $baseUrl . "/#/error?code=500&description=" . urlencode("Failed to get token expiration. Please try logging in again or contact the administrator for assistance."));
            exit();
        }

        $tokenData = $accessToken->jsonSerialize();

        if (isset($tokenData['id_token'])) {
            $idToken = $tokenData['id_token'];
            $splitToken = explode('.', $idToken);

            if (count($splitToken) === 3) {
                $userInfo = json_decode(base64_decode($splitToken[1]), true);

                if ($userGroups && count($userGroups) > 0 && (!isset($userInfo['groups']) || array_intersect($userGroups, $userInfo['groups']) === [])) {
                    session_destroy();
                    header("Location: " . $baseUrl . "/#/error?code=403&message=" . urlencode("Unauthorized") . "&description=" . urlencode("You are not authorized to access this application. Please check your account or contact the administrator for access."));
                    exit();
                }

                // Determine if user is staff
                if ($adminGroups && count($adminGroups) > 0 && (array_intersect($adminGroups, $userInfo['groups']) != [])) {
                    $isStaff = true;
                } elseif (!isset($adminGroups) || count($adminGroups) === 0) {
                    $isStaff = true;
                }

                $userInfo['isStaff'] = $isStaff;
                
                $_SESSION['oauthUser'] = $userInfo;

                if (isset($_GET['redirect'])) {
                    header('Location: ' . $_GET['redirect']);
                } else {
                    header('Location: ' . $baseUrl);
                }
                exit();
            } else {
                session_destroy();
                header("Location: " . $baseUrl . "/#/error?code=500&descriptino=" . urlencode("Invalid ID token returned from OAuth response. Please make sure the OAuth provider is returning a valid ID token."));
                exit();
            }
        } else {
            session_destroy();
            header("Location: " . $baseUrl . "/#/error?code=500&description=" . urlencode("No ID token was found in the OAuth response. Please make sure the OAuth provider is returning an ID token."));
            exit();
        }

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        session_destroy();
        header("Location: " . $baseUrl . "/#/error?code=500&description=" . urlencode("Failed to get access token: " . $e->getMessage()));
        exit();
    }
} else {
    session_destroy();
    header("Location: " . $baseUrl . "/#/error?code=400&message=" . urlencode("Authentication Request Error") . "&description=" . urlencode("No 'code' parameter was found in the request URL."));
    exit();
}

?>
