<?php
/**
php to register user
@author: Lam Lu
*/
		if (isset($_POST['email']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['password']))
		{
			$emptyString = "/^\\s*$/";
			$charOnly = "/^(?!\\s*$)[A-Za-z]*$/";
			
			$error = false;
		
			$firstname = $_POST['firstName'];
			if(preg_match($emptyString,$firstname))
				$error = true;
	
			$lastname = $_POST['lastName'];
			if(preg_match($emptyString,$lastname))
				$error = true;
				
			$email = $_POST['email'];
			$emailString = "/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/";
			if(preg_match($emptyString,$email)|| !preg_match($emailString, $email))
				$error = true;
			
			$password = $_POST['password'];
			if(preg_match($emptyString,$password))
				$error = true;
			
			//validation ok, no error
			if ($error == false)
			{
				
				require_once("../../shared/php/DBConnection.php");
				$connection = DBConnection::connectDB();
				$error = null;
				if ($connection != null)
				{
					if ($stmt = $connection->prepare("Insert into Users(first_name, last_name, email, password, activation_key,active) values (?,?,?,?,?,?)")) 
					{
						$hash = password_hash($password, PASSWORD_DEFAULT);
						$activation_key = md5(uniqid(rand(), true));
						$active = 0;
						if (!$stmt->bind_param("sssssd",$firstname,$lastname,$email,$hash,$activation_key,$active))
						{
							$error=  "Failed to bind param";
							
						}		
						if (!$stmt->execute()) 
						{
							$error =  "Failed to execute";
						}
					}
					DBConnection::closeConnection();
					if ($error == null)
					{
						session_start();
						//$_SESSION['username'] = $username;
	
						require_once("MailAgent.php");
						$subject = "Account Activation";
						$message = 
						"Hi ".$firstname." ".$lastname."\n".
						"Thank you for registering as a Groopy User.\n"."Your Groopy ID is: ".$email.
						". Please click the link below to activate your account:\n".
						"http://localhost:8888/code/account/activateAccount.php?email=".urlencode($email)."&key=".$activation_key.
						" \nBest Regards.\nGroopy Team.";
						$header = "From: Groopy <noreply@groopy.com>";
						MailAgent::writeEmail($email,$subject,$message,$header);
						?>
						<!DOCTYPE html>
						<html>
						<head>
						    <title>Registered</title>
							<link rel="stylesheet" type="text/css" href="../../shared/css/base.css">
						</head>
						<body>
							<div class='rect'>
								<br/>
								<h3 style="padding-top: 40px; text-align:center;">Registration Completed!</h3>
								<h3 style="padding-top: 30px; text-align:center;">Thank you for registering. Please check your email to activate your account.</h3>
							</div>
						</body>
						</html>
					<?php }
					else 
						print "Database errors. Could not register";
				}
			}
		}
?>
		