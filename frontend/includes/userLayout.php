<?php

require_once('db.php');

if($_POST['user'] != '' && $_POST['dark'] != '') {
	
$r1 = 2;
$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
$user = mysqli_real_escape_string($con, $_POST['user']);
$order = mysqli_real_escape_string($con, $_POST['order']);
$nightmode = mysqli_real_escape_string($con, $_POST['dark']);
$hidden = mysqli_real_escape_string($con, $_POST['hidden']);
$favorites = mysqli_real_escape_string($con, $_POST['favorites']);
$sql1 = "REPLACE INTO `user_layouts` (user, layout, nightmode, hidden, favorites) VALUES ('".$user."','".$order."','".$nightmode."','".$hidden."','".$favorites."');";
if (mysqli_query($con, $sql1)) {
  $r1 = 0;
} else {
  echo "Error updating record: " . mysqli_error($con);
}
print_r($r1);
}
else { echo "3"; }
?>