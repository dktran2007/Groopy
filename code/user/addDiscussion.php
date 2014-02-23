<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_POST['topic'])){
		$topic = $_POST['topic'];
		$result = "INSERT INTO discussion(topic) VALUES ('$topic')";
		mysqli_query($connection,$result);
	}
	header( 'Location: dashboard.php' ) ;
?>