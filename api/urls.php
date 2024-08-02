<?php

require_once('config.php');

// Default to production
$buildEnv = 'production';

// Attempt to load buildenv.php
if (file_exists(__DIR__ . '/buildenv.php')) {
    require_once(__DIR__ . '/buildenv.php');
}

if ($buildEnv === 'development') {
    $baseUrl = $basePathDev;

    if (isset($apiUrlDev) && !empty($apiUrlDev)) {
        $apiUrl = $apiUrlDev;
    } else {
        $apiUrl = $baseUrl . '/api';
    }
} else {
    $baseUrl = $basePathProd;
    
    if (isset($apiUrlProd) && !empty($apiUrlProd)) {
        $apiUrl = $apiUrlProd;
    } else {
        $apiUrl = $baseUrl . '/api';
    }
}

// Strip trailing slashes
$baseUrl = rtrim($baseUrl, '/');
$apiUrl = rtrim($apiUrl, '/');
