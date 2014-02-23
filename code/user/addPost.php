<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_POST['msg'])){
		$msg = $_POST['msg'];
		date_default_timezone_set('America/Los_Angeles');
		$date = date('Y-m-d H:i:s');
		$result = "INSERT INTO post(msg,date) VALUES ('$msg','$date')";
		mysqli_query($connection,$result);
	}
	header( 'Location: dashboard.php' ) ;
?>