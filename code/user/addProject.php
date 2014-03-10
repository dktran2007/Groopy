<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	
	if(isset($_POST['projName'])){ 
		$projectName = $_POST['projName'];
		$className = $_POST['projClassName'];
		$classNum = $_POST['projClassNum'];
		/*TODO: How to set a forum id in this table???*/
		$result = "INSERT INTO project(name, class_name,class_num) VALUES ('$projectName','$className','$classNum')";
		mysqli_query($connection,$result);
		
		$sql = mysqli_query($connection,"SELECT id from project where name = '$projectName'"); // get the inserted new project ID
		$newProjectId = mysqli_fetch_row($sql);
		$userEmail = $_POST['userEmail']; // who is logged in?
		
		$sql2 = mysqli_query($connection,"SELECT id from users where email = '$userEmail'"); // get the user's ID
		$userId = mysqli_fetch_row($sql2);
		
		$updatePU = "INSERT INTO project_user(project_id,user_id,active) VALUES ($newProjectId[0],$userId[0], 1)"; // inserted into project_user
		mysqli_query($connection,$updatePU);
	}
	
	header( 'Location: home.php' ) ;
?>