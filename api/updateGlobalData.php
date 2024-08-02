<?php
require_once('db.php');

jsonApiEndpoint(
  "REPLACE INTO global_data (extension_name, extension_data) VALUES (':extension_name', ':extension_data');",
  ['extension_name', 'extension_data'],
  true, // includeUser
  "INSERT INTO global_data (extension_name, extension_data) VALUES (':extension_name', ':extension_data') ON CONFLICT (extension_name) DO UPDATE SET extension_data = EXCLUDED.extension_data;"
);
