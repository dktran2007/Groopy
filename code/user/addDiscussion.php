<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_POST['topic'])){
		$topic = $_POST['topic'];
		$projectId = $_POST['projectId'];
		$result = "INSERT INTO discussion(topic,project_id) VALUES ('$topic','$projectId')";
		mysqli_query($connection,$result);
		
		$sql = mysqli_query($connection,"SELECT name FROM project WHERE id = $projectId");
		$projectTitle = mysqli_fetch_row($sql);
		
		header( 'Location: dashboard.php?title=' . $projectTitle[0] . '#forum' ) ;
	}
?>