<?php
require_once('db.php');

jsonApiEndpoint(
  "INSERT INTO tool_dir (id, tool_name, tool_description, category, link, staff_only, display, tab) VALUES (':id',':tool_name',':tool_description',':category',':link',':staff_only',':display',':tab');", 
  ['id', 'tool_name', 'tool_description', 'category', 'link', 'staff_only', 'display', 'tab'],
  false // includeUser
);
