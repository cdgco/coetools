<?php
require_once('db.php');

jsonApiEndpoint(
  "UPDATE user_layouts SET real_name=':name', status_cards=':status_cards', extension_data=':extension_data' WHERE user_id=':user_id';", 
  ['name', 'status_cards', 'extension_data'], 
  true // includeUser
);
