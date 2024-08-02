<?php

require __DIR__ . '/../../vendor/autoload.php';

use OneLogin\Saml2\Settings;
use OneLogin\Saml2\Error;

require_once(__DIR__ . '/../../urls.php');
require_once(__DIR__ . '/settings.php');

if ($authMethod !== 'saml') {
    header("Location: " . $baseUrl . "/#/error?code=400&message=" . urlencode("Invalid Authentication Method") . "&description=" . urlencode("The requested authentication method is not enabled."));
    exit();
}

try {
    $settings = new Settings($settingsInfo, true);
    $metadata = $settings->getSPMetadata();
    $errors = $settings->validateMetadata($metadata);
    if (empty($errors)) {
        header('Content-Type: text/xml');
        echo $metadata;
    } else {
        throw new Error(
            'Invalid SP metadata: '.implode(', ', $errors),
            Error::METADATA_SP_INVALID
        );
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
