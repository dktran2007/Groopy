<?php
	/**
	* login php
	@author: Lam Lu
	return error: 
	0: login succeeds
	1: login fails, account is not yet activated
	2: login fails, email and password do not match
	3: login fails, email not found on database
	*/
	header ("X-Content-Type-Options : nosniff");
	header ("X-Frame-Options: DENY");
	if(isset($_POST['email']) && isset($_POST['password']))
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		$error = 0;
		//database connection
		require_once("DBConnection.php");
		$connection = DBConnection::connectDB("localhost", "Groopy_Schema", "groopyuser", "groopyuser");
		if ($connection != null)
		{
			/* create a prepared statement */
			if ($stmt = $connection->prepare("Select email,password,active from Users where email = ?")) 
			{
				if ($stmt->bind_param("s", $email))
				{
					if ($stmt->execute()) 
					{
						if ($stmt->bind_result($rEmail,$rPassword,$rActive))
						{
							if($stmt->fetch())
							{
								if(strcasecmp($email,$rEmail)==0 && password_verify($password,$rPassword))
								{
									if($rActive == 0)//acccount not yet activated
									{
										$error = 1;
									}	
								}
								else $error = 2;//email and password does not match	
							}
							else $error = 3;//email not found on database
						}
					}
				}
			}	
			
			DBConnection::closeConnection($connection);
			print $error;
		}
	}
?>