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

if (isset($_SESSION) && isset($_SESSION['AuthNRequestID'])) {
    $requestID = $_SESSION['AuthNRequestID'];
} else {
    $requestID = null;
}

$auth->processResponse($requestID);

$errors = $auth->getErrors();

if (!empty($errors)) {
    session_destroy();
    header("Location: " . $baseUrl . "/#/error?code=500&description=" . urlencode("Failed to process SAML response: " . implode(', ', $errors)));
    exit();
}

if (!$auth->isAuthenticated()) {
    session_destroy();
    header("Location: " . $baseUrl . "/#/error?code=500&description=" . urlencode("Failed to authenticate after SAML response"));
    exit();
}

$_SESSION['samlUserdata'] = $auth->getAttributes();
$_SESSION['samlNameId'] = $auth->getNameId();
$_SESSION['samlNameIdFormat'] = $auth->getNameIdFormat();
$_SESSION['samlNameIdNameQualifier'] = $auth->getNameIdNameQualifier();
$_SESSION['samlNameIdSPNameQualifier'] = $auth->getNameIdSPNameQualifier();
$_SESSION['samlSessionIndex'] = $auth->getSessionIndex();

unset($_SESSION['AuthNRequestID']);
if (isset($_POST['RelayState']) && Utils::getSelfURL() != $_POST['RelayState']) {
    // To avoid 'Open Redirect' attacks, before execute the
    // redirection confirm the value of $_POST['RelayState']
    // is a trusted URL.
    $auth->redirectTo($_POST['RelayState']);
}
