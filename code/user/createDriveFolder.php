<?php

require_once '../../includes/google-api-php-client/src/Google_Client.php';
require_once '../../includes/google-api-php-client/src/contrib/Google_DriveService.php';
require_once '../../includes/google-api-php-client/src/contrib/Google_Oauth2Service.php';
$client = new Google_Client();
$client->setClientId('509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com');
$client->setClientSecret('vOXSHACk2rfdVV8d1VMcdds0');
$client->setRedirectUri('http://localhost:8888');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));


// Exchange authorization code for access token
$client->setAccessToken(file_get_contents('conf.json'));
if ($client->getAccessToken()) {
//It means that I have the right to manipulate whatever service
// Choose the service you want to use
    $service = new Google_DriveService($client);

    $file = new Google_DriveFile();
    $file->setTitle('GroopyTestFolder'); //name of the folder
    $file->setDescription('A test folder');
    $file->setMimeType('application/vnd.google-apps.folder');
    $createdFile = $service->files->insert($file, array(
        'mimeType' => 'application/vnd.google-apps.folder',
    ));

    print_r($createdFile['id']);
	
	/***
	This is temporary method. Putting the folder id into a text file. Then load the 
	id when upload files into the drive.
	Possible integrate with our database. First, try to limit one folder per project
	for easy management.
	**/
	file_put_contents('folderid.txt', $createdFile['id']);

}
?>