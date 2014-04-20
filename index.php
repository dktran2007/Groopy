<?php
/**
Login php
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
require_once("HeaderImporter.php");

if(isset($_POST['email']) && isset($_POST['password']))
{
	$email = $_POST['email'];
	$password = $_POST['password'];
	$error = 0;
	
	//import DBConnection
	
	importHeader("dbconnection");
	
	$connection = DBConnection::connectDB();
	
	if ($connection != null)
	{
		/* create a prepared statement */
		if ($stmt = $connection->prepare("Select email,first_name,last_name, password,active from Users where email = ?")) 
		{
			if ($stmt->bind_param("s", $email))
			{
				if ($stmt->execute()) 
				{
					if ($stmt->bind_result($rEmail,$firstName,$lastName,$rPassword,$rActive))
					{
						if($stmt->fetch())
						{
							if(strcasecmp($email,$rEmail)==0 && password_verify($password,$rPassword))
							{
								if($rActive == 0)//acccount not yet activated
								{
									$message = "Account has not been activated.";
								}
								else //login ok
								{
									DBConnection::closeConnection();
									session_start();
									
									$_SESSION['email'] = $rEmail;
									$_SESSION['firstName'] = $firstName;
									$_SESSION['lastName'] = $lastName;
									
									redirectPage("home");
								}
							}
							else $message = "The email or password is incorrect.";//email and password does not match	
						}
						else $message = "Account was not found.";//email not found on database
					}
				}
			}
		}	

		DBConnection::closeConnection();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Groopy</title>
        <meta name="google-signin-clientid" 
                content="509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com" />
        <meta name="google-signin-scope" content="https://www.googleapis.com/auth/plus.profile.emails.read" />
        <meta name="google-signin-requestvisibleactions" content="http://schemas.google.com/AddActivity" />
        <meta name="google-signin-cookiepolicy" content="single_host_origin" />
		<?php
			importHeader("css");
			importHeader("jQuery");
			importHeader("gPlus");
		?>
        <script type="application/javascript">
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
		

		/** google sign in button click*/
		function gSignInClicked()
		{
			// Additional params including the callback, the rest of the params will
		   // come from the page-level configuration.
		   var additionalParams = {
			 'callback': signinCallback
		   };
		   gapi.auth.signIn(additionalParams);
		}
		 
		 function signinCallback(authResult)
		 {
			 console.log("calling google sign in call back");
		  if (authResult['status']['signed_in'])
		  {
			// Hide the sign-in button now that the user is authorized, for example:
			//document.getElementById('googleSigninButton').setAttribute('style', 'display: none');
			gapi.client.load('plus', 'v1', function() 
			{
  				gapi.client.plus.people.get( {'userId' : 'me'} ).execute(function(resp) 
				{
   				 // Shows profile information
   				 // console.log(resp.name.familyName);
				 //console.log(resp.name.givenName);
				 //console.log(resp.emails[0].value);
				  $.ajax({
					 type:'POST',
					 url:"code/account/startGPlusSession.php",
					 data:
					 {
						 email:resp.emails[0].value,
						 firstName:resp.name.givenName,
						 lastName:resp.name.familyName
					 },
					 success: function()
					 {
						 console.log("here");
						 //do something here in case call back in success
						 location.href = 'code/user/home.php';
					 }
				 });
				 
  				})
			});
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
  </head>

  <body>
  		<div id="wrapper">            
        	<div id="pindex_left" class="center_image_tag">
               	 <img src="shared/assets/Groopy_Puzzle.png" />
            </div> 											
            
            <div id="pindex_right">
            	<div id="pindex_logocontainer" class="center_image_tag">
                	<img src="shared/assets/Groopy_Logo.png " />
                </div>										
                
                <div id="pindex_logoDescription">
                	<span>Simplified Project Management</span>
                </div>									
                
                <div id="pindex_loginbox">
                    <form id="login_form" action="" method="post">
                    	<input type="email" name="email" class="input_row" placeholder="Email" 
                        	value="<?php echo $_POST['email'];?>" required/>
                        <input type="password" name="password" class="input_row" placeholder="Password" required />
                        
                        <!---------------------------------------------------------------
                        Login error div. The div is hidden if there is no login error
                        or the form is not posted yet
                        ---------------------------------------------------------------->
                        <div class="hidden_message_row">
                        	<span class="error_message">
                            	<?php
									echo $message;
								?>
                            </span>
                        </div>
                        
                        <input type="submit" value="Sign in" class="input_row blue_button" />
                        <span class="input_row centerOr">or</span>
                        <input type="button" value="Sign in with Google+" class="input_row blue_button"
                        	onClick="gSignInClicked()" />
                        
                        <div class="input_row">
                        	<span class="k_graytext">Don't have an account? 
                            		<a href="http://localhost:8888/code/account/signup.php" class="k_link">
                                    Sign up here
                                    </a>
                             </span>
                                    
                            <span class="k_graytext">Forgot password? 
                            		<a href="http://localhost:8888/code/account/forgotPassword.php" class="k_link">
                                    Reset here
                                    </a>
                            </span>
                        </div>
                    </form>
                </div>										<!-- end pindex_loginbox-->
            </div>											<!-- end pindex_right-->
        </div> 												<!-- end wrapper-->
  </body>
</html>
