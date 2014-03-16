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
	$connection = DBConnection::connectDB();
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
			{ ?>
				<!DOCTYPE html>
				<html>
				<head>
					<title>Account Activation</title>
					<link rel="stylesheet" type="text/css" href="../../shared/css/base.css">
				</head>
				<body>
					<div class='rect'>
						<br/>
						<h3 style="padding-top: 100px; text-align:center;">Your account had been activated before. You may <a href="../../">Login</a> now</h3>
					</div>
				</body>
				</html>
			<?php }
			
			else if(strcasecmp($rEmail,$email) == 0 && strcmp($rKey,$key) === 0)//account has not been activated yet
			{	
				if($statement=$connection->prepare("Update Users set activation_key = ?, active = ? where email = ? "))
				{
					$active = 1;
					$key = NULL;
					if($statement->bind_param("sds",$key,$active,$email))
					{
						
						if($statement->execute())
						{
							//echo 'okay, validated!';
							//validate ok ?>
							<!DOCTYPE html>
							<html>
							<head>
								<title>Account Activation</title>
								<link rel="stylesheet" type="text/css" href="../../shared/css/base.css">
							</head>
							<body>
								<div class='rect'>
									<br/>
									<h3 style="padding-top: 100px; text-align:center;">Your account is now active. You may <a href="../../">Login</a> now</h3>
								</div>
							</body>
							</html>
						<?php }
						//else echo mysqli_stmt_error ( $statement );
						else{
							echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
						}
					}
					
				}
			}
			else // there is an error
				$error = true;
		}
		
		else //there is error with select statement
		{
			//fail to verify ?>
			<!DOCTYPE html>
			<html>
			<head>
				<title>Account Activation</title>
				<link rel="stylesheet" type="text/css" href="../../shared/css/base.css">
			</head>
			<body>
				<div class='rect'>
					<br/>
					<h3 style="padding-top: 100px; text-align:center;">Oops! Your account could not be activated. Please recheck the link or contact the system administrator.</h3>
				</div>
			</body>
			</html>
		<?php }
		$statement->close();
	}
	DBConnection::closeConnection();
	
}
?>