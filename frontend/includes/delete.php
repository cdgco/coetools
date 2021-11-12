<?php

require_once('db.php');

$r1 = 2;
$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
$currentid = mysqli_real_escape_string($con, $_POST['id']);
$sql1 = "DELETE FROM `tool_dir` WHERE `id` = '" . $currentid . "';";
if (mysqli_query($con, $sql1)) { $r1 = 0; } else { $r1 = 1; }
print_r($r1);
?>