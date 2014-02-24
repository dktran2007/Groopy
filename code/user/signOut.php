<?php
/**
php to log out the user
*/
	session_start();
	if(isset($_SESSION['email']))
		unset($_SESSION['email']);
	if(isset($_SESSION['firstName']))
		unset($_SESSION['firstName']);
	if(isset($_SESSION['lastName']))
		unset($_SESSION['lastName']);
	session_destroy();
	echo "you will be directed to Log In in 3 seconds...";
	echo "
	 <script type='text/javascript'>
		setTimeout(function(){
			gapi.auth.signOut();
			location.href = '../account/signIn.php';
		},3000);
	</script>
	";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Logging Out...</title>
	<script src="../../../includes/jquery/groopy/js/jquery-1.9.1.js"></script>
	<script src="../../../includes/jquery/groopy/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="https://apis.google.com/js/client:plusone.js"></script>
    <script src="https://apis.google.com/js/plusone.js"></script>
</head>

<body>
	

</body>
</html>