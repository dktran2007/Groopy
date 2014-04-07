<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Untitled Document</title>
</head>

<body>
	<?php
			if(isset($_POST['isUploadToDrive']))
			{
				require_once '../../includes/google-api-php-client/src/Google_Client.php';
				require_once '../../includes/google-api-php-client/src/contrib/Google_DriveService.php';
				require_once '../../includes/google-api-php-client/src/contrib/Google_Oauth2Service.php';

				$fileMIME =  $_FILES['uploadedToDriveFile']['type'];
				$fileName = $_FILES['uploadedToDriveFile']['name'];

echo $fileName."   ".$fileMIME;

				/**
				Temporary load the folder id from text file
				*/				
				$folderID = file_get_contents('folderid.txt');
				echo $folderID;
								
				$client = new Google_Client();
				
				// Get your credentials from the APIs Console
				//$client->setApplicationName('Google+ PHP Starter Application');
				$client = new Google_Client();
				$client->setClientId('509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com');
				$client->setClientSecret('vOXSHACk2rfdVV8d1VMcdds0');
				$client->setRedirectUri('http://localhost:8888');
				$client->setScopes(array('https://www.googleapis.com/auth/drive'));
				
				
				$client->setAccessToken(file_get_contents('conf.json'));
				if ($client->getAccessToken()) {
				//It means that I have the right to manipulate whatever service
				// Choose the service you want to use
					$service = new Google_DriveService($client);
				
					$file = new Google_DriveFile();
					$file->setTitle($fileName);
					$file->setDescription('A test document');
					$file->setMimeType($fileMIME);
					
					$parent = new Google_ParentReference();
					
					$parent->setId($folderID);
					
					$file->setParents(array($parent));
					$data = file_get_contents($_FILES['uploadedToDriveFile']['tmp_name']); //example file
					
					$createdFile = $service->files->insert($file, array(
						'data' => $data,
						'mimeType' => $fileMIME,
					));
				}
			}
	?>
			
 	<form id='uploadToDriveForm' action="" method="post" enctype="multipart/form-data">
        <input type='file' id='uploadedToDriveFile' name='uploadedToDriveFile' />
        <input type="reset" value="clear" />
        <input type="hidden" value="<?php echo $id[0];?>" name="projectId" />
        <input type="hidden" value="<?php session_start(); echo $_SESSION['email'];?>" name="userEmail" />
        <input type="hidden" value="true" name="isUploadToDrive"/>
        <input type="submit" name="submit" value="Submit" />
     </form>
</body>
</html>
