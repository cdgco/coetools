<?php

require_once(__DIR__ . '/../config.php');

header("Content-Type: application/json");

if ($authMethod === 'ldap') {
  echo json_encode(['success' => true, 'provider' => 'ldap', 'type' => 'internal']);
} elseif ($authMethod === 'oauth') {
  echo json_encode(['success' => true, 'provider' => 'oauth', 'type' => 'external']);
} elseif ($authMethod === 'saml') {
  echo json_encode(['success' => true, 'provider' => 'saml', 'type' => 'external']);
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid authentication method.']);
}

?>
