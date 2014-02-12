<!DOCTYPE html>
<html>
<head>
	<title>Groopy</title>
    <meta charset="utf-8">
<!--Page specific css-->    
    <link rel="stylesheet" type="text/css" href="../../shared/css/base.css">
    

	<script>
		function callRegister(){
			window.location.href="register.html";
		}
	
		/**
		validate input fields
		*/
		function validate()
		{
			var result = true;
	
			var email = document.getElementById("form_userName").value;
			var password = document.getElementById("form_password").value;
			var emptyString = new RegExp("^\\s*$"); /* $ is string empty ; \s* is string containing whitespaces only*/
				
			var emailString = new RegExp("^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$");
			if(!emailString.test(email))
			{
				result =  false;
					
			}
			if(emptyString.test(password))
			{
			
				result = false;
			}
			return result;
		}	
		
	</script>
    
    
  	
    <?php
	/**
	* login php
	@author: Lam Lu
	return error: 
	0: login succeeds
	1: login fails, account is not yet activated
	2: login fails, email and password do not match
	3: login fails, email not found on database
	*/
	header ("X-Content-Type-Options : nosniff");
	header ("X-Frame-Options: DENY");
	$message = ""; // moved it to global b/c of "variable undefined error!"
	if(isset($_POST['email']) && isset($_POST['password']))
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		$error = 0;
		//database connection
		require_once("../../shared/php/DBConnection.php");
		$connection = DBConnection::connectDB("localhost", "Groopy_Schema", "groopyuser", "groopyuser");
		if ($connection != null)
		{
			/* create a prepared statement */
			if ($stmt = $connection->prepare("Select email,password,active from Users where email = ?")) 
			{
				if ($stmt->bind_param("s", $email))
				{
					if ($stmt->execute()) 
					{
						if ($stmt->bind_result($rEmail,$rPassword,$rActive))
						{
							if($stmt->fetch())
							{
								if(strcasecmp($email,$rEmail)==0 && password_verify($password,$rPassword))
								{
									if($rActive == 0)//acccount not yet activated
									{
										$message = "* Account has not been activated";
									}
									else //login ok
									{
										DBConnection::closeConnection($connection);
										echo "<script type='text/javascript'> location.href = '../user/home.php';</script>";
									}
								}
								else $message = "* The email or password you entered is incorrect";//email and password does not match	
							}
							else $message = "* Account was not found, please register";//email not found on database
						}
					}
				}
			}	
			
			DBConnection::closeConnection($connection);
		}
	}
	?>
</head>
<body>
<!-- ui-dialog -->
	<div class="rect">
        <br/>
        <img src="../../shared/images/logo_2D.png" id="logo">
        <br/><br/>
        <div id="leftDiv">
            <h3>Sign In</h3>
            <form method="post" action="../../code/account/signIn.php" onSubmit="return validate()">
                <p> 
                    <input type="email" id="form_userName"  placeholder=" example@yahoo.com" name="email" autofocus required value="<?php if (isset($_POST['email'])) echo $_POST['email'];?>"/>
                </p>
                <p>
                    <input type="password" id="form_password" name="password" placeholder=" password" required/>
                </p>
                    <label id="form_error" class = "formInputError"><?php echo $message;?></label>
                <p>
                    <input type="submit" id="signInBtn" value="Sign In"/> 
                    &nbsp; &nbsp; <a href="../../code/account/forgotPwd.html">Forgot password</a> 
                </p>
            </form>
            <br/>
        </div>
        
        <img src="../../shared/images/or.png" id="or"/>
        
        <div id="rightDiv">
            <h3>Sign Up</h3>
            <p>If you don't have an account yet</p>
            <input type="submit" id="signUpBtn" value="Sign Up" onClick="callRegister()"/>
            <br/> 
        </div>
        <br/><br/>
	</div>
</body>
</html>