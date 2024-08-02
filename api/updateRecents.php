<?php
require_once('db.php');

jsonApiEndpoint(
  "UPDATE user_layouts SET recents=':recents' WHERE user_id=':user_id';", 
  ['recents'],
  true // includeUser
);
