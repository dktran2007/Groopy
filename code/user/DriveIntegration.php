<?php
	/**
	This is the google drive api integration
	@author: Lam Lu
	*/
	
	//===================================================
	// check login first
	// redirect to home page if not logged in
	//===================================================
	if (!isset($_SESSION))
	{
		session_start();
	}
	$email = $_SESSION['email'];
	if ($email == NULL)
	{
		//no login, redirect to login page
		header('Location: '.filter_var('../account/signIn.php'));
	}
	
	//===================================================
	// set up Google API client and 
	// check token access for the client
	//===================================================
	require_once '../../includes/google-api-php-client/src/Google_Client.php';
	require_once '../../includes/google-api-php-client/src/contrib/Google_DriveService.php';
	require_once '../../includes/google-api-php-client/src/contrib/Google_Oauth2Service.php';
	$client = new Google_Client();
	$client->setClientId('509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com');
	$client->setClientSecret('vOXSHACk2rfdVV8d1VMcdds0');
	$client->setRedirectUri('http://localhost:8888');
	$client->setScopes(array(
				'https://www.googleapis.com/auth/drive',
				'https://www.googleapis.com/auth/drive.file',
				'https://www.googleapis.com/auth/drive.readonly',
				'https://www.googleapis.com/auth/drive.readonly.metadata',
				'https://www.googleapis.com/auth/drive.appdata'));
	
	$refreshToken = NULL;
	//database connection
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if ($connection != null)
	{
		/* create a prepared statement */
		if ($stmt = $connection->prepare("Select driveAPI_Token from users where email = ?")) 
		{
			if ($stmt->bind_param("s", $email))
			{
				if ($stmt->execute()) 
				{
					if ($stmt->bind_result($driveAccessToken))
					{
						if($stmt->fetch())
						{
							//decode the string into json
							$jsonDriveAccessToken = json_decode($driveAccessToken);
							//get the refreshToken
							$refreshToken = $jsonDriveAccessToken->refresh_token;
							$client->setAccessToken($driveAccessToken);
						}
					}
				}
			}
		}	

		DBConnection::closeConnection();
	}
	
	//===================================================
	//create the Drive Root Folder
	//Possibly leave the subfolder creation to 
	//Google API Picker?
	//===================================================
	if(isset($_POST['Create_Folder']))
	{
		//check if there is a root folder for the project yet?
		//if not, create
		//if there is already, open warning?
		$projectID = $_SESSION['projectID'];
		$connection = DBConnection::connectDB();
		if ($connection != null)
		{
			/* create a prepared statement */
			if ($stmt = $connection->prepare("Select drive_folder_id from project where id =?")) 
			{
				if ($stmt->bind_param("s", $projectID))
				{
					if ($stmt->execute()) 
					{
						if ($stmt->bind_result($driveFolderID))
						{
							if($stmt->fetch())
							{
								//drive folder exists, possible warn the user to continue
								
								if (!empty($driveFolderID) && $driveFolderID != NULL )
									echo "there is a folder";
							
								else
								{
									if($client->isAccessTokenExpired()) 
									{
										$client->refreshToken($refreshToken);
									}
									
									//drive folder is not exist, create one
									//access ok
									if ($client->getAccessToken()) 
									{
							
										$service = new Google_DriveService($client);
									
										$file = new Google_DriveFile();
										$file->setTitle('GroopyTestFolder'); //name of the folder
										$file->setDescription('A test folder');
										$file->setMimeType('application/vnd.google-apps.folder');
										$createdFile = $service->files->insert($file, array(
											'mimeType' => 'application/vnd.google-apps.folder',
										));
										$stmt = NULL;
										$stmt = $connection->prepare("update project set drive_folder_id = ? where id = ?");
										$stmt->bind_param("ss",$createdFile['id'] ,$projectID);										
										$stmt->execute();
										echo "create Folder OK";				
									}
								}
							}	
						}
					}
				}
			}
		}
		DBConnection::closeConnection();
	}
	
	
	//===================================================
	// Upload file to google drive
	//===================================================
	if(isset($_POST['Upload_To_Drive']))
	{
		$fileMIME =  $_FILES['uploadedToDriveFile']['type'];
		$fileName = $_FILES['uploadedToDriveFile']['name'];
		
		$connection = DBConnection::connectDB();
			$projectID = $_SESSION['projectID'];
			$stmt = NULL;
			$stmt = $connection->prepare("Select drive_folder_id from project where id =?");
			$stmt->bind_param("s", $projectID);
			$stmt->execute();
			$stmt->bind_result($driveFolderID);
			$stmt->fetch();
		DBConnection::closeConnection();
		echo ($driveFolderID);
		
		if($client->isAccessTokenExpired()) 
		{
			$client->refreshToken($refreshToken);
		}
		if ($client->getAccessToken()) 
		{
				
			$service = new Google_DriveService($client);
				
			$file = new Google_DriveFile();
			$file->setTitle($fileName);
			$file->setDescription('A test document');
			$file->setMimeType($fileMIME);
					
			$parent = new Google_ParentReference();
					
			$parent->setId($driveFolderID);
					
			$file->setParents(array($parent));
			$data = file_get_contents($_FILES['uploadedToDriveFile']['tmp_name']); //example file
					
			$createdFile = $service->files->insert($file, array(
				'data' => $data,
				'mimeType' => $fileMIME,
			));
		}
	}
	
	/**
	 * Retrieve a list of File resources.
	 *
	 * @param Google_DriveService $service Drive API service instance.
	 * @return Array List of Google_DriveFile resources.
	 */
	if(isset($_POST['List_Drive_Files']))
	{
		if($client->isAccessTokenExpired()) 
		{
			$client->refreshToken($refreshToken);
		}
		
		if ($client->getAccessToken()) 
		{/*
			  $service = new Google_DriveService($client);
			  $pageToken = NULL;
			  $folderId = "0B66j_3FAnVMUbExKVXgtc2RzSlU";
			  do {
				try {
				  $parameters = array();
				  if ($pageToken) {
					$parameters['pageToken'] = $pageToken;
				  }
				  $children = $service->children->listChildren($folderId, $parameters);
print "<pre>";
print_r($children);
print "</pre>";	
echo'<iframe src="https://drive.google.com/embeddedfolderview?id=0B66j_3FAnVMUbExKVXgtc2RzSlU#list" width="800" height="200" frameborder="0"></iframe>';			  
					foreach ($children['items'] as $child) 
					{ 
					print_r ($child['id']);
				  }
				  $pageToken = $children->getNextPageToken();
				} catch (Exception $e) {
				  print "An error occurred: " . $e->getMessage();
				  $pageToken = NULL;
				}
			  } while ($pageToken);*/
			  
			  echo '<iframe src="https://drive.google.com/embeddedfolderview?id=0B66j_3FAnVMUbExKVXgtc2RzSlU#list" width="800" height="500" frameborder="0"></iframe>';
		}
	}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Drive Integration</title>
</head>

<body>
	<form id='driveIntegration' action="" method="post" enctype="multipart/form-data">
    	<input type="submit" name="Create_Folder" value="needCreateFolder" />
        <br />
        <input type='file' id='uploadedToDriveFile' name='uploadedToDriveFile' />
        <input type="submit" name="Upload_To_Drive" value="uploadFileToDrive" />
         <br />
        <input type="submit" name="List_Drive_Files" value="List_Drive_Files" />
     </form>
  

</body>
</html>