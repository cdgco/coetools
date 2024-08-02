<?php
require_once('db.php');

jsonApiEndpoint(
  "DELETE FROM tool_dir WHERE id=':id';", 
  ['id'],
);
