<?php
require_once '../../includes/google-api-php-client/src/Google_Client.php';
require_once '../../includes/google-api-php-client/src/contrib/Google_DriveService.php';

session_start();
$client = new Google_Client();
$client->setClientId('509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com');
$client->setClientSecret('vOXSHACk2rfdVV8d1VMcdds0');
$client->setRedirectUri('http://localhost:8888/code/user/googleAPIAuthentication.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

////I will address building permit
$authUrl = $client->createAuthUrl();
print "Connect Me!";
echo $authUrl;
//Return of the address with the code that I can autenticarme
if (isset($_GET['code'])) {
  $accessToken = $client->authenticate($_GET['code']);
  
  file_put_contents('conf.json', $accessToken);
  $client->setAccessToken($accessToken);
  $redirect = 'http://localhost:8888/code/user/googleAPIAuthentication.php';
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}
?>