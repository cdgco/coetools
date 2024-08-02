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
unset($oauthSettings['userGroups']);
unset($oauthSettings['adminGroups']);

/** @disregard undefined type */
$provider = new GenericProvider($oauthSettings);

$authorizationUrl = $provider->getAuthorizationUrl([
  'scope' => $oauthSettings['scope'],
  'prompt' => $oauthSettings['prompt'],
]);

$_SESSION['oauth2state'] = $provider->getState();

header('Location: ' . $authorizationUrl);
exit;

?>
