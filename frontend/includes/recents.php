<?php

require_once('db.php');

if($_POST['user'] != '' && $_POST['recents'] != '') {
	$r1 = 2;
	$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
	$user = mysqli_real_escape_string($con, $_POST['user']);
	$recents12 = mysqli_real_escape_string($con, $_POST['recents']);
	$sql1 = 'REPLACE INTO `user_recentbackup` (user, recents) VALUES ("'.$user.'","'.$recents12.'");';
	$result = mysqli_query($con, $sql1);
	if ($result) {
	  $r1 = 0;
	} else {
	  echo "Error updating record: " . mysqli_error($con);
	}
	print_r($r1);
}
else { echo "3"; }
?>