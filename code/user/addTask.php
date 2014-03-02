<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	
	if(isset($_POST['task'])){ 
		$task = $_POST['task'];
		$assignedTo = $_POST['assignedTo'];
		$deadline = $_POST['deadline'];
		$projectId = $_POST['projectId'];
		$result = "INSERT INTO tasks(task, assignedTo ,deadline, project_id) VALUES ('$task','$assignedTo','$deadline','$projectId')";
		mysqli_query($connection,$result);
	
		$sql = mysqli_query($connection,"SELECT name FROM project WHERE id = $projectId");
		$projectTitle = mysqli_fetch_row($sql);
			
		header( 'Location: dashboard.php?title=' . $projectTitle[0] . '#tasks' ) ;
	}
?>