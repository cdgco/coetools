<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../urls.php');

if ($authMethod !== 'oauth') {
  header("Location: " . $baseUrl . "/#/error?code=400&message=" . urlencode("Invalid Authentication Method") . "&description=" . urlencode("The requested authentication method is not enabled."));
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  header("Content-Type: application/json");
  session_destroy();
  echo json_encode(['success' => true, 'message' => 'Logout successful']);
  exit();
} else {
  session_destroy();

  if (isset($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
    header("Location: " . $redirect);
  } else {
    header("Location: " . $baseUrl);
  }

  exit();
}
?>
