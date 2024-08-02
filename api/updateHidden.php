<?php
require_once('db.php');

jsonApiEndpoint(
  "UPDATE user_layouts SET hidden_elements=':hidden_elements' WHERE user_id=':user_id';", 
  ['hidden_elements'],
  true // includeUser
);
