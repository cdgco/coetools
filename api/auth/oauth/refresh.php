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

function refreshAccessToken() {
    global $apiUrl, $oauthSettings;

    $oauthSettings['redirectUri'] = $apiUrl . '/auth/oauth/auth.php';
    unset($oauthSettings['userGroups']);
    unset($oauthSettings['adminGroups']);
    
    /** @disregard undefined type */
    $provider = new GenericProvider($oauthSettings);

    if (isset($_SESSION['accessToken']) && isset($_SESSION['refreshToken']) && isset($_SESSION['expires'])) {
        /** @disregard undefined type */
        $accessToken = new \League\OAuth2\Client\Token\AccessToken([
            'access_token' => $_SESSION['accessToken'],
            'refresh_token' => $_SESSION['refreshToken'],
            'expires' => $_SESSION['expires']
        ]);

        if ($accessToken->hasExpired()) {
            /** @disregard undefined type */
            try {
                $newAccessToken = $provider->getAccessToken('refresh_token', [
                    'refresh_token' => $accessToken->getRefreshToken()
                ]);

                // Update the session with the new access token and expiration time
                $_SESSION['accessToken'] = $newAccessToken->getToken();
                $_SESSION['refreshToken'] = $newAccessToken->getRefreshToken();
                $_SESSION['expires'] = $newAccessToken->getExpires();

                return [
                    'success' => true,
                    'message' => 'Access token refreshed',
                    'accessToken' => $newAccessToken->getToken()
                ];
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                return [
                    'success' => false,
                    'message' => 'Failed to refresh access token: ' . $e->getMessage()
                ];
            }
        } else {
            return [
                'success' => true,
                'message' => 'Access token is still valid',
                'accessToken' => $accessToken->getToken()
            ];
        }
    } else {
        return [
            'success' => false,
            'message' => 'No access token or refresh token available'
        ];
    }
}

?>
