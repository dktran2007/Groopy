<?php
/**
php to reset the password, must have user email in order to reset
@author: Lam Lu
@date : 03/03/2014
*/
	if(isset($_GET['email']) && isset($_POST['password']) && isset($_POST['confirmPassword']))
	{
		$kResetPwEmail = $_GET['email'];
		$kPW = $_POST['password'];
		$kConfirmPW = $_POST['confirmPassword'];
		if($kPW != $kConfirmPW)
			return;
		
		require_once("../../shared/php/DBConnection.php");
		$connection = DBConnection::connectDB();
		if ($connection != null)
			{
				/* create a prepared statement */
				if ($stmt = $connection->prepare("Update Users set password = ? where email =?")) 
				{
					$hash = password_hash($kPW, PASSWORD_DEFAULT);
					if ($stmt->bind_param("ss", $hash, $kResetPwEmail))
					{
						if ($stmt->execute()) 
						{
							//reset password ok
							//echo "reset password ok";
						}
					}
				}
			}
		DBConnection::connectDB();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Groopy | Reset Password</title>
    <meta charset="utf-8">
<!--Page specific css-->    
	<link rel="stylesheet" type="text/css" href="../../shared/css/base.css">
    
<!--jQuery css & js files-->
	<link href="../../includes/jquery/groopy/css/groopy/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<script src="../../includes/jquery/groopy/js/jquery-1.9.1.js"></script>
	<script src="../../includes/jquery/groopy/js/jquery-ui-1.10.3.custom.js"></script>
    <script type='text/javascript'>
		/**
		validate input fields
		*/
		
		function formValidate()
		{
			
			var result = true;
			var password = document.getElementById("form_password").value;
			var confirmPassword = document.getElementById("form_confirmPassword").value;
			var emptyString = new RegExp("^\\s*$"); /* $ is string empty ; \s* is string containing whitespaces only*/
			if(emptyString.test(password))
			{
				result = false;
			}
			
			if(password !== confirmPassowrd)
			{
				document.getElementById("form_confirmPasswordError").innerHTML = "Password does not match";
				result = false;
			}
			return false;
		}
		
		/**
		*/
		function checkConfirmPassword()
		{
			var password = document.getElementById("form_password").value;
			var confirmPassword = document.getElementById("form_confirmPassword").value;
			if(password !== confirmPassword)
			{
				document.getElementById("form_confirmPasswordError").innerHTML = "Password does not match";
			}
			else
				document.getElementById("form_confirmPasswordError").innerHTML = "";
		}
	</script>
    <style>
		#notification{
			display: none;
		}
		h3{
			padding-left: 300px;
		}
		.formInputError
		{
			color:#F00;
		}
    </style>
</head>

<body>
	<div class="rect">
	    <br/>
		<a class="logo" href="../../index.html" title="LOGO">
       	  <img alt="List-icon" src="../../shared/images/logo3.1.png"/> <strong>GROOPY</strong></a>
		<hr/>
        <div id="retrieveDiv">
          <h3>Reset Password</h3>
        
          <form method="post" id="form_body2" action="" onSubmit="return formValidate()" />
                <p>
                    <label for="form_password">New Password: </label>
                    <input type="password" name='password' id="form_password" required autofocus/>
                </p>
                <p>
                    <label for="form_confirm_password">Confirm New Password: </label>
                    <input type="password" name='confirmPassword' id="form_confirmPassword" onblur= "checkConfirmPassword()" required />
                    <label id="form_confirmPasswordError" class = "formInputError"></label>
                </p>
                <p>
                    <input type="submit" value="Submit" id="emailNotifyBtn" >
                </p>
          </form>
        </div>
        <br/>
        
    </div>
    <br/>
</body>
</html>