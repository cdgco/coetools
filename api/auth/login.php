<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../urls.php');

header("Content-Type: application/json");

if ($authMethod === 'ldap') {
    require_once('ldap/login.php');
} elseif ($authMethod === 'oauth') {
    require_once('oauth/login.php');
} elseif ($authMethod === 'saml') {
    require_once('saml/login.php');
} else {
    header("Location: " . $baseUrl . "/#/error?message=" . urlencode("Invalid authentication method."));
    exit();
}
?>
