<?php
require_once('db.php');

jsonApiEndpoint(
  "UPDATE user_layouts SET direct_links=':direct_links' WHERE user_id=':user_id';", 
  ['direct_links'],
  true // includeUser
);
