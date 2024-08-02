<?php
require_once('db.php');

jsonApiEndpoint(
  "UPDATE user_layouts SET favorites=':favorites' WHERE user_id=':user_id';", 
  ['favorites'],
  true // includeUser
);
