<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_POST['msg'])){
		$msg = $_POST['msg'];
		$discussionId = $_POST['discussionId'];
		date_default_timezone_set('America/Los_Angeles');
		$date = date('Y-m-d H:i:s');
		$result = "INSERT INTO post(msg,date,discussion_id) VALUES ('$msg','$date','$discussionId')";
		mysqli_query($connection,$result);
	}
	header( 'Location: dashboard.php?#forum' ) ;
?>