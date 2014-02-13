<?php
/**
	PHP to check the avaliability of user account
	@author: Lam Lu
	@date: 01/21/2014
*/
	header ("X-Content-Type-Options : nosniff");
	header ("X-Frame-Options: DENY");
	if (isset ($_GET['email']))
	{
		$email = $_GET['email'];
		$availability = "false";
		$error = "";
		$emailString = "/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/";
		if(!preg_match($emailString,$email))
		{
			print $availability;
			return;
		}
		//database connection
		require_once("../../shared/php/DBConnection.php");
		$connection = DBConnection::connectDB();
		if ($connection != null)
		{
			/* create a prepared statement */
			if ($stmt = $connection->prepare("Select email from Users where email = ?")) 
			{
				if (!$stmt->bind_param("s", $email))
				{
					$error=  "Failed to bind param";
				}		
			
				if (!$stmt->execute()) 
				{
			  		$error =  "Failed to execute";
				}
			
				if (!$stmt->bind_result($resultedEmail))
				{
					$error = "Failed to bind result";
				}
				$stmt->store_result(); 
				
				if ($stmt->num_rows >0)
					$availability = "false";
				else $availability= "true";
			}	
			
			DBConnection::closeConnection();
			print $availability;
		}
	}
?>