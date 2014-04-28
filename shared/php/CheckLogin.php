<?php
/****************************************************
php to check login. 
return true if user is loggged in
	   false if user is not logged in.
case user is not logged in. page is redirected to 
login page to prevent direct access to database
@author		:		Lam Lu
@date		:		04/20/2014
****************************************************/

//check session first
if (!isset($_SESSION)) 
{
	session_start();
}

function checklogin()
{
	$link 			= $_SERVER['DOCUMENT_ROOT']."/HeaderImporter.php";
	require_once($link);
	importHeader("dbconnection");
	$connection = DBConnection::connectDB();
	$result = true;
	$email = NULL;
	$email = $_SESSION['email'];

	//if no email stored in session
	if($email == NULL || $email == "")
		$result = false;
	else //check login if user is in db
	{
		$statement = $connection->prepare("Select email from Users where email = ?");
		$statement->bind_param("s", $email);
		$statement->execute();
		$statement->bind_result($rEmail);
		$statement->store_result();
		if($statement->num_rows <= 0)
			$result = false;
	}
	DBConnection::closeConnection();
	
	
	if($result == false)//not login, redirect to login page
		header('Location: http://localhost:8888' );
}


?>