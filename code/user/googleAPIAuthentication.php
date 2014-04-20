<?php
	if (!isset($_SESSION))
	{
		session_start();
	}
	require_once '../../includes/google-api-php-client/src/Google_Client.php';
	require_once '../../includes/google-api-php-client/src/contrib/Google_DriveService.php';

	$client = new Google_Client();
	$client->setClientId('509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com');
	$client->setClientSecret('vOXSHACk2rfdVV8d1VMcdds0');
	$client->setRedirectUri('http://localhost:8888/code/user/googleAPIAuthentication.php');
	$client->setScopes(array('https://www.googleapis.com/auth/drive'));
	$authUrl = $client->createAuthUrl();
	if(isset($_GET['code']))
	{
		//already have access token, do something here
		$accessToken = $_GET['code'];
		$accessToken = $client->authenticate($_GET['code']);
//		print_r ($accessToken);
		$email = $_SESSION['email'];
		if ($email == NULL)
		{
			//no login, redirect to login page
			header('Location: '.filter_var('../account/signIn.php'));
		}
		
		//store access token into the database
		require_once("../../shared/php/DBConnection.php");
		$connection = DBConnection::connectDB();
		if ($connection != null)
			{
				/* create a prepared statement */
				if ($stmt = $connection->prepare("Update Users set driveAPI_Token = ? where email =?")) 
				{
					$accessToken = json_encode($accessToken);
					if ($stmt->bind_param("ss", $accessToken, $email))
					{
						if ($stmt->execute()) 
						{
							//store token ok
							echo "You can use Google Drive API now";
						}
					}
				}
			}
		DBConnection::connectDB();
	}
	else
	{
		//if there is no access token yet, authenticate here
		echo '<script type="text/javascript">

				window.open("'.$authUrl.'",\'_blank\');'.'
				</script>';
	}
	
?>