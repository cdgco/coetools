<?php

require_once('db.php');

if($_POST['id'] != '' && $_POST['name'] != '' && $_POST['description'] != '' && $_POST['category'] != '' && $_POST['link'] != '' && $_POST['staffOnly'] != '' && $_POST['display'] != ''&& $_POST['tab'] != '') {
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
$sql1 = "INSERT INTO `tool_dir` (id, name, description, category, link, staffOnly, display, tab) VALUES ('".$id."','".$name."','".$description."','".$category."','".$link."','".$staffOnly."','".$display."','".$tab."');";
if (mysqli_query($con, $sql1)) {
  $r1 = 0;
} else {
  echo "Error updating record: " . mysqli_error($con);
}
print_r($r1);
}
else { echo "3"; }
?>