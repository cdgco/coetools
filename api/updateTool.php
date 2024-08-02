<?php
require_once('db.php');

jsonApiEndpoint(
  "UPDATE tool_dir SET tool_name=':tool_name', tool_description=':tool_description', category=':category', link=':link', staff_only=':staff_only', display=':display', tab=':tab' WHERE id=':id';", 
  ['id', 'tool_name', 'tool_description', 'category', 'link', 'staff_only', 'display', 'tab']
);
