<?php

ini_set('display_errors', 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../urls.php');

if ($authMethod !== 'ldap') {
    header("Location: " . $baseUrl . "/#/error?code=400&message=" . urlencode("Invalid Authentication Method") . "&description=" . urlencode("The requested authentication method is not enabled."));
    exit();
}

header("Content-Type: application/json");

function ldap_authenticate($username, $password) {
    global $ldapSettings;
    
    if (!isset($ldapSettings['host']) || !isset($ldapSettings['port']) || !isset($ldapSettings['baseDN']) || !isset($ldapSettings['userOU']) || $ldapSettings['host'] === '' || $ldapSettings['port'] === '' || $ldapSettings['baseDN'] === '' || $ldapSettings['userOU'] === '') {
        session_destroy();
        echo json_encode([
            'success' => false,
            'message' => 'LDAP settings are not configured properly.'
        ]);

        exit();
    }

    try {
        $ldapconn = ldap_connect($ldapSettings['host'] . ':' . $ldapSettings['port']);
    } catch (Exception $e) {
        session_destroy();
        echo json_encode([
            'success' => false,
            'message' => 'Could not connect to LDAP server.'
        ]);

        exit();
    }
    if (!$ldapconn) {
        session_destroy();
        echo json_encode([
            'success' => false,
            'message' => 'Could not connect to LDAP server.'
        ]);

        exit();
    }

    $ldapbind = @ldap_bind($ldapconn, 'uid=' . $username . ',' . $ldapSettings['userOU'] . ',' . $ldapSettings['baseDN'], $password);
    if (!$ldapbind) {
        ldap_close($ldapconn);
        session_destroy();
        echo json_encode([
            'success' => false,
            'message' => 'User is not authorized to access this application.'
        ]);

        exit();
    }

    // Search for user's memberships & groups
    $group = ldap_search($ldapconn, $ldapSettings['baseDN'], '(&(cn=*)(memberUid=' . $username . '))');
    $groups = ldap_get_entries($ldapconn, $group);

    // Get real name of user (gecos)
    $userdata = ldap_search($ldapconn, $ldapSettings['baseDN'], 'uid=' . $username);
    $userdata = ldap_get_entries($ldapconn, $userdata);

    $realName = isset($userdata[0]['gecos'][0]) ? $userdata[0]['gecos'][0] : '';

    ldap_close($ldapconn);

    // Store all user groups to array
    $userGroups = [];
    foreach ($groups as $group) {
        if (isset($group['cn'][0])) {
            array_push($userGroups, $group['cn'][0]);
        }
    }

    if ($ldapSettings['userGroups'] && count($ldapSettings['userGroups']) > 0 && (array_intersect($ldapSettings['userGroups'], $userGroups) == [])) {
        session_destroy();
        echo json_encode([
            'success' => false,
            'message' => 'User is not authorized to access this application.'
        ]);

        exit();
    }

    if ($ldapSettings['adminGroups'] && count($ldapSettings['adminGroups']) > 0 && (array_intersect($ldapSettings['adminGroups'], $userGroups) != [])) {
        $isStaff = true;
    } elseif (!isset($ldapSettings['adminGroups']) || count($ldapSettings['adminGroups']) === 0) {
        $isStaff = true;
    }

    return [
        'username' => $username,
        'realName' => $realName,
        'isStaff' => $isStaff,
    ];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if username and password are provided
    if (isset($data['username']) && isset($data['password'])) {
        $username = $data['username'];
        $password = $data['password'];

        // Authenticate using LDAP
        $user = ldap_authenticate($username, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            echo json_encode(['success' => true, 'message' => 'Login successful']);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Username and password are required']);
        exit();
    }
} else {
    header("Location: " . $baseUrl . "/#/error?code=400&message=" . urlencode("Invalid Request Method") . "&description=" . urlencode("The login request was sent using an unsupported method."));
    exit();
}
?>
