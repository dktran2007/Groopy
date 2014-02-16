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
	}
	
	header( 'Location: home.php' ) ;
?>