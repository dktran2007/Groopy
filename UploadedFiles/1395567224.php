<?php
/**
php to integrate G+ account into the database
*/

if(isset($_POST['email']) && isset($_POST['firstName']) && isset($_POST['lastName']))
{
	$email = $_POST['email'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if($statement = $connection->prepare("insert into Users(email,first_name,last_name,active) values (?,?,?,?)"))
	{
		$active = 1;
		if($statement->bind_param("sssd",$email,$firstName,$lastName,$active))
		{
			if(!$statement->execute())
			{
				//sucessful inserting g+ account into db
			}
			else
			{
				//account is already in db
			}
		}
	}
	DBConnection::closeConnection();
	session_start();
	$_SESSION['email'] = $email;
	$_SESSION['firstName'] = $firstName;
	$_SESSION['lastName'] = $lastName;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>