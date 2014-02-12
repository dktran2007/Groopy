<?php
/**
php to verify the account after registration. User needs to login their email
and click on activation link to activate account
*/
if(isset($_GET['email']) && isset($_GET['key']))
{
	$email = $_GET['email'];
	$key = $_GET['key'];
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB("localhost", "Groopy_Schema", "groopyuser", "groopyuser");
	$error = true;
	if ($connection != null)
	{
		if($statement=$connection->prepare("Select email,activation_key, active from Users where email = ?"))
		{
			if($statement->bind_param("s",$email))
			{
				if($statement->execute())
				{
					if($statement->bind_result($rEmail,$rKey, $rActive))
					{
						if($statement->fetch())
						{
							$error = false; 
						}
					}
				}
			}
		}
		$statement->close();
		if($error == false) //no error with select statement
		{
			if ($rActive === 1)//account had been activated before
			{
				echo '<div> Your account had been activated before You may <a href="../../">log in</a> now</div>';
			}
			
			else if(strcasecmp($rEmail,$email) == 0 && strcmp($rKey,$key) === 0)//account has not been activated yet
			{
				if($statement=$connection->prepare("Update Users set activation_key = ?, active = ? where email = ? "))
				{
					$active = 1;
					$key = "";
					if($statement->bind_param("sds",$key,$active,$email))
					{
						if($statement->execute())
						{
							//validate ok
							echo '<div>Your account is now active. You may <a href="../../">log in</a> now</div>'; 
						}
					}
				}
			}
			else // there is an error
				$error = true;
		}
		
		else //there is error with select statement
		{
			//fail to verify
			echo '<div>Oops !Your account could not be activated. Please recheck the link or contact the system administrator.</div>';
		}
		$statement-close();
		
	}
	DBConnection::closeConnection($connection);
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Account Activation</title>
</head>

<body>
</body>
</html>