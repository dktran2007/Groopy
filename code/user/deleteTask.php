<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	echo $_POST['taskId'];
	if(isset($_POST['taskId'])){ 
		$task = $_POST['taskId'];
		
		$sql = mysqli_query($connection,"SELECT project_id FROM tasks WHERE task = '$task'"); // retrieved the project_id
		$projectID = mysqli_fetch_row($sql);
		
		$sql2 = mysqli_query($connection,"SELECT name FROM project WHERE id = $projectID[0]"); // got the project name
		$projectTitle = mysqli_fetch_row($sql2);
		
		$result = "DELETE FROM tasks where task = '$task'"; // deleted the task
		mysqli_query($connection,$result);
	
	/*** TODO: I wasn't sure if you want a notification to be sent on deleting a task	
		require_once("../account/MailAgent.php");
		$subject = "New Task Has Been Assigned To You";
		session_start();
		$kInviteFirstName = $_SESSION['firstName'];
		$kInviteLastName = $_SESSION['lastName'];
												
		$kTaskMessage = "Hello from Groopy Team.\n".$kInviteFirstName." ".$kInviteLastName." has assigned a new task to you.\n"."Task Description: ".$task."\nTask Deadline: ".$deadline."\nThank You.\nGroopy Team";
		$kHeader = "From: Groopy <noreply@groopy.com>";
		MailAgent::writeEmail($assignedTo,$subject,$kTaskMessage,$kHeader);	*/

		header( 'Location: dashboard.php?title=' . $projectTitle[0] . '#tasks' ) ;
		DBConnection::closeConnection();
	}
?>