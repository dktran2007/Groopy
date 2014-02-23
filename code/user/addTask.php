<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	
	if(isset($_POST['task'])){ 
		$task = $_POST['task'];
		$assignedTo = $_POST['assignedTo'];
		$deadline = $_POST['deadline'];
		$result = "INSERT INTO tasks(task, assignedTo ,deadline) VALUES ('$task','$assignedTo','$deadline')";
		mysqli_query($connection,$result);
	}
	
	header( 'Location: dashboard.php?#tasks' ) ;
?>