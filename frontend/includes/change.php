<?php

require_once('db.php');

if($_POST['id'] != '' && $_POST['name'] != '' && $_POST['description'] != '' && $_POST['category'] != '' && $_POST['link'] != '' && $_POST['staffOnly'] != '' && $_POST['display'] != '' && $_POST['tab'] != '') {
$r1 = 2;
$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
$id = mysqli_real_escape_string($con, $_POST['id']);
$name = mysqli_real_escape_string($con, $_POST['name']);
$description = mysqli_real_escape_string($con, $_POST['description']);
$category = mysqli_real_escape_string($con, $_POST['category']);
$link = mysqli_real_escape_string($con, $_POST['link']);
$staffOnly = mysqli_real_escape_string($con, $_POST['staffOnly']);
$display = mysqli_real_escape_string($con, $_POST['display']);
$tab = mysqli_real_escape_string($con, $_POST['tab']);
$sql1 = "UPDATE `tool_dir` SET name='".$name."', description='".$description."', category='".$category."', link='".$link."', staffOnly='".$staffOnly."', `display`='".$display."', `tab`='".$tab."' WHERE id='".$id."';";
if (mysqli_query($con, $sql1)) { $r1 = 0; } else { $r1 = 1; }
print_r($r1);
}
