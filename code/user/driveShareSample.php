<?php
	/**
	 * Insert a new permission.
	 *
	 * @param Google_DriveService $service Drive API service instance.
	 * @param String $fileId ID of the file to insert permission for.
	 * @param String $value User or group e-mail address, domain name or NULL for
						   "default" type.
	 * @param String $type The value "user", "group", "domain" or "default".
	 * @param String $role The value "owner", "writer" or "reader".
	 * @return Google_Permission The inserted permission. NULL is returned if an API
								 error occurred.
	 */
	function insertPermission($service, $fileId, $value, $type, $role) {
	  $newPermission = new Google_Permission();
	  $newPermission->setValue($value);
	  $newPermission->setType($type);
	  $newPermission->setRole($role);
	  try {
		return $service->permissions->insert($fileId, $newPermission);
	  } catch (Exception $e) {
		print "An error occurred: " . $e->getMessage();
	  }
	  return NULL;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Goolge drive share</title>
    <script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
	<script type="text/javascript">
		init = function() 
		{
			s = new gapi.drive.share.ShareClient('509349210477');
			s.setItemIds(["0B66j_3FAnVMUMGE0MHRObkltR3M"]);
		}
		window.onload = function() {
			gapi.load('drive-share', init);
		}
	</script>
</head>

<body>
    <?php
				require_once '../../includes/google-api-php-client/src/Google_Client.php';
				require_once '../../includes/google-api-php-client/src/contrib/Google_DriveService.php';
				require_once '../../includes/google-api-php-client/src/contrib/Google_Oauth2Service.php';
				$client = new Google_Client();
				
				// Get your credentials from the APIs Console
				//$client->setApplicationName('Google+ PHP Starter Application');
				$client = new Google_Client();
				$client->setClientId('509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com');
				$client->setClientSecret('vOXSHACk2rfdVV8d1VMcdds0');
				$client->setRedirectUri('http://localhost:8888');
				$client->setScopes(array('https://www.googleapis.com/auth/drive'));
				
				
				$client->setAccessToken(file_get_contents('conf.json'));
				if ($client->getAccessToken()) 
				{
				//It means that I have the right to manipulate whatever service
				// Choose the service you want to use
					$service = new Google_DriveService($client);
					insertPermission($service,"0B66j_3FAnVMUMGE0MHRObkltR3M","ll195btest@gmail.com","user","writer");
				}
								
	?>
    <form id='uploadToDriveForm' action="" method="post" enctype="multipart/form-data">
        <input type="submit" name="submit" value="Submit" />
     </form>
</body>
</html>
