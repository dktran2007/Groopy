<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();

	if(isset($_POST['taskId'])){ 
		$id = $_POST['taskId'];
		$task = $_POST['edit-task'];
		$assignedTo = $_POST['edit-assignedTo'];
		$status = $_POST['edit-status'];
		$priority = $_POST['edit-priority'];
		$deadline = $_POST['edit-deadline'];
		$projectId = $_POST['projectId'];
		
		$sql = mysqli_query($connection,"SELECT name FROM project WHERE id = $projectId");
		$projectTitle = mysqli_fetch_row($sql);
		
		$result = "UPDATE tasks SET task = '$task', assignedTo = '$assignedTo', status = '$status', priority = '$priority', deadline = '$deadline'
					WHERE id = $id"; // deleted the task
		mysqli_query($connection,$result);
		
	/*** TODO: I wasn't sure if you want a notification to be sent on deleting a task	
		require_once("../account/MailAgent.php");
		$subject = "New Task Has Been Assigned To You";
		session_start();
		$kInviteFirstName = $_SESSION['firstName'];
		$kInviteLastName = $_SESSION['lastName'];
												
		$kTaskMessage = "Hello from Groopy Team.\n".$kInviteFirstName." ".$kInviteLastName." has assigned a new task to you.\n"."Task Description: ".$task."\nTask Deadline: ".$deadline."\nThank You.\nGroopy Team";
		$kHeader = "From: Groopy <noreply@groopy.com>";
		MailAgent::writeEmail($assignedTo,$subject,$kTaskMessage,$kHeader);*/

		header( 'Location: dashboard.php?title=' . $projectTitle[0] . '#tasks' ) ;
		DBConnection::closeConnection();
	}
?>