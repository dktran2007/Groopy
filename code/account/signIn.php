<!DOCTYPE html>
<html>
<head>
	<title>Groopy</title>
    <meta charset="utf-8">
    <meta name="google-signin-clientid" content="509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com" />
    <meta name="google-signin-scope" content="https://www.googleapis.com/auth/plus.profile.emails.read" />
    <meta name="google-signin-requestvisibleactions" content="http://schemas.google.com/AddActivity" />
    <meta name="google-signin-cookiepolicy" content="single_host_origin" />
   
<!--Page specific css-->    
    <link rel="stylesheet" type="text/css" href="../../shared/css/base.css">
    <link href="../../../includes/jquery/groopy/css/groopy/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
	<script src="../../../includes/jquery/groopy/js/jquery-1.9.1.js"></script>
	<script src="../../../includes/jquery/groopy/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="https://apis.google.com/js/client:plusone.js"></script>
    <script src="https://apis.google.com/js/plusone.js"></script>

	<script type="text/javascript">
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
		
		/**
		Goolge sign in code
		*/
		(function()
		{
			 var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			 po.src = 'https://apis.google.com/js/client:plusone.js';
			 var s = document.getElementsByTagName('script')[0]; 
			 s.parentNode.insertBefore(po, s);
		})();
		

		 /* Executed when the APIs finish loading 
		 	not used since does not initiate this function
		 */
		 /*
		function render() 
		{

		   // Additional params including the callback, the rest of the params will
		   // come from the page-level configuration.
		   var additionalParams = {
			 'callback': signinCallback
		   };

		   // Attach a click listener to a button to trigger the flow.
		   var gSigninButton = document.getElementById("googleSigninButton");
		   gSigninButton.addEventListener('click', function() {
			 gapi.auth.signIn(additionalParams); // Will use page level configuration
			 console.log('here');
		   });
		   
		}*/
		
		/** google sign in button click*/
		function gSignInClicked()
		{
			// Additional params including the callback, the rest of the params will
		   // come from the page-level configuration.
		   var additionalParams = {
			 'callback': signinCallback
		   };
		   gapi.auth.signIn(additionalParams);
		   console.log('here');
		}
		 
		 function signinCallback(authResult)
		 {
		  if (authResult['status']['signed_in'])
		  {
			// Hide the sign-in button now that the user is authorized, for example:
			//document.getElementById('googleSigninButton').setAttribute('style', 'display: none');
			gapi.client.load('plus', 'v1', function() 
			{
  				gapi.client.plus.people.get( {'userId' : 'me'} ).execute(function(resp) 
				{
   				 // Shows profile information
   				 console.log(resp.name);
				 console.log(resp.emails[0]);
  				})
			});
			location.href = '../user/home.php';
		  } 
		  else 
		  {
			// Update the app to reflect a signed out user
			// Possible error values:
			//   "user_signed_out" - User is signed-out
			//   "access_denied" - User denied access to your app
			//   "immediate_failed" - Could not automatically log in the user
			//console.log('Sign-in state: ' + authResult['error']);
		  }
	
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
		$connection = DBConnection::connectDB();
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
										DBConnection::closeConnection();
										echo "<script type='text/javascript'> location.href = '../user/home.php';</script>";
									}
								}
								else $message = "* The email or password is incorrect";//email and password does not match	
							}
							else $message = "* Account was not found, please register";//email not found on database
						}
					}
				}
			}	

			DBConnection::closeConnection();
		}
	}
	?>
</head>

<body>
<!-- ui-dialog -->
	<div class="rect">
        <br/>
        <a class="logo" href="../../index.html" title="LOGO">
       	  <img alt="List-icon" src="../../shared/images/logo3.1.png"/> <strong>GROOPY</strong></a>
          <hr/>
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
                    &nbsp;
                   
                 
                </p>
            </form>
            <!-- google sign in button -->
			<button id="googleSigninButton" onClick="gSignInClicked()">Sign in with Google</button>
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