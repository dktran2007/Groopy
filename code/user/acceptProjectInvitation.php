<?php
/**
php to accept the project invitation
@author: Lam
@date: 03/03/2014
*/
	if(isset($_GET['key']) && isset($_GET['accept']))
	{
		$kActivationKey = $_GET['key'];
		require_once("../../shared/php/DBConnection.php");
		$connection = DBConnection::connectDB();
		/* create a prepared statement */
		if ($stmt = $connection->prepare("update project_user set active = ?, activation_key =? where activation_key = ?")) 
		{
			$kActive = 1;
			$kKey = NULL;
			if ($stmt->bind_param("dss",$kActive, $kKey, $kActivationKey))
			{
				if ($stmt->execute()) 
				{
					//update ok
					echo "Accepted Invitation. You will be redirected to login page in 3 seconds";
					echo '
						<script type="text/javascript">
							setTimeout(function()
							{
								window.location.replace("../account/signIn.php");
							}, 3000);
						</script>
					';
				}
				//else update not ok, maybe display an error message
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Accept Project Invitation</title>
</head>

<body>
</body>
</html>