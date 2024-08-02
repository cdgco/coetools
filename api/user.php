<?php

// Include DB credentials for all calls
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('config.php');

if ($authMethod === 'ldap') {
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit();
    }
    // Get user data from session
    $userSession = $_SESSION['user'];
    $currStaff = $userSession['isStaff'] ? 1 : 0;
    $realName = $userSession['realName'];
    $username = $userSession['username'];
    $authProvierData = $_SESSION['user'];
} elseif ($authMethod === 'oauth') {
    if (!isset($_SESSION['oauthUser'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit();
    }

    require_once('auth/oauth/refresh.php');

    $refresh = refreshAccessToken();

    if (!$refresh['success']) {
        echo json_encode($refresh);
        exit();
    }

    // Get user data from session
    $userSession = $_SESSION['oauthUser'];
    $currStaff = $userSession['isStaff'] ? 1 : 0;

    if (isset($userSession['given_name'])) {
        $realName = $userSession['given_name'] . ' ' . $userSession['family_name'];
    } elseif (isset($userSession['realName'])) {
        $realName = $userSession['realName'];
    } else {
        $realName = $userSession['name'];
    }

    if (isset($userSession['username'])) {
        $username = $userSession['username'];
    } elseif (isset($userSession['email'])) {
        $username = $userSession['email'];
    } elseif (isset($userSession['upn'])) {
        $username = $userSession['upn'];
    } elseif (isset($userSession['unique_name'])) {
        $username = $userSession['unique_name'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid user data']);
        exit();
    }

    $authProvierData = $_SESSION['oauthUser'];
} elseif ($authMethod === 'saml') {
    if (!isset($_SESSION['samlUserdata'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit();
    }
    // Get user data from session
    $userSession = $_SESSION['samlUserdata'];
    $currStaff = isset($userSession['isStaff']) && $userSession['isStaff'] ? 1 : 0;
    $realName = "";

    if (isset($userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'][0]) && isset($userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname'][0])) {
        $realName = $userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'][0] . ' ' . $userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname'][0];
    } elseif (isset($userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'][0])) {
        $realName = $userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'][0];
    } elseif (isset($userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/displayname'][0])) {
        $realName = $userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/displayname'][0];
    }

    if (isset($userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'][0])) {
        $username = $userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'][0];
    } elseif (isset($userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/upn'][0])) {
        $username = $userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/upn'][0];
    } elseif (isset($userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/nameidentifier'][0])) {
        $username = $userSession['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/nameidentifier'][0];
    } elseif (isset($_SESSION['samlNameId']) && $_SESSION['samlNameId'] !== '' && isset($_SESSION['samlNameIdFormat']) && $_SESSION['samlNameIdFormat'] === 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress') {
        $username = $_SESSION['samlNameId'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid user data']);
        exit();
    }

    $authProvierData = $_SESSION['samlUserdata'];
} else {
    die("Invalid authentication method.");
}

// If username is an email address, remove domain
if (isset($stripDomain) && $stripDomain === true && strpos($username, '@') !== false) {
    $username = substr($username, 0, strpos($username, '@'));
}

if ($adminPrefix && $adminPrefix !== '' && strpos($username, $adminPrefix) !== false) {
    $currStaff = 1;
} elseif (in_array($username, $adminUsers)) {
    $currStaff = 1;
}
