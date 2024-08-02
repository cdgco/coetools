<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../urls.php');

if ($authMethod !== 'saml') {
  header("Location: " . $baseUrl . "/#/error?code=400&message=" . urlencode("Invalid Authentication Method") . "&description=" . urlencode("The requested authentication method is not enabled."));
  exit();
}

$settingsInfo = $samlSettings;

$settingsInfo['baseurl'] = $apiUrl . '/auth/saml/';
$settingsInfo['sp']['entityId'] = $apiUrl.'/auth/saml/metadata.php';
$settingsInfo['sp']['assertionConsumerService']['url'] = $apiUrl.'/auth/saml/auth.php';
$settingsInfo['sp']['singleLogoutService']['url'] = $apiUrl.'/auth/saml/logout.php';

?>
