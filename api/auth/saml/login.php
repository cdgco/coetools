<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';

use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Utils;

require_once(__DIR__ . '/../../urls.php');
require_once(__DIR__ . '/settings.php');

if ($authMethod !== 'saml') {
    header("Location: " . $baseUrl . "/#/error?code=400&message=" . urlencode("Invalid Authentication Method") . "&description=" . urlencode("The requested authentication method is not enabled."));
    exit();
}

$auth = new Auth($settingsInfo);

// ForceAuthn is set to true to force re-authentication, this prevents auth loop on logout
if (isset($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
    $auth->login($redirect, array(), true);
    exit();
} else {
    $auth->login($baseUrl, array(), true);
    exit();
}

?>
