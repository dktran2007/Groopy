<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	
	if(isset($_POST['task'])){ 
		$task = $_POST['task'];
		$assignedTo = $_POST['assignedTo'];
		$status = $_POST['status'];
		$priority = $_POST['priority'];
		$deadline = $_POST['deadline'];
		$projectId = $_POST['projectId'];
		
		$result = "INSERT INTO tasks(task, assignedTo, status, priority, deadline, project_id) VALUES ('$task','$assignedTo', '$status','$priority','$deadline','$projectId')";
		mysqli_query($connection,$result);
	
		$sql = mysqli_query($connection,"SELECT name FROM project WHERE id = $projectId");
		$projectTitle = mysqli_fetch_row($sql);
		
		require_once("../account/MailAgent.php");
		$subject = "New Task Has Been Assigned To You";
		session_start();
		$kInviteFirstName = $_SESSION['firstName'];
		$kInviteLastName = $_SESSION['lastName'];
												
		$kTaskMessage = "Hello from Groopy Team.\n".$kInviteFirstName." ".$kInviteLastName." has assigned a new task to you.\n"."Task Description: ".$task."\nTask Deadline: ".$deadline."\nThank You.\nGroopy Team";
		$kHeader = "From: Groopy <noreply@groopy.com>";
		MailAgent::writeEmail($assignedTo,$subject,$kTaskMessage,$kHeader);	
		DBConnection::closeConnection();
		header( 'Location: dashboard.php?title=' . $projectTitle[0] . '#tasks' ) ;
	}
?>