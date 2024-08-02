<?php
require_once('db.php');

jsonApiEndpoint(
  "UPDATE user_layouts SET night_mode=':night_mode' WHERE user_id=':user_id';", 
  ['night_mode'],
  true // includeUser
);
