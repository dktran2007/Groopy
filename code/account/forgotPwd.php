 <?php
 /**
 php to send out the renew password link to the email
 @author: Lam Lu
 @date : 03/03/2014
 */
 	if(isset($_POST['email']))
	{
		$kForgotPwEmail = $_POST['email'];
		require_once("MailAgent.php");
		$subject = "Reset Password";
												
		$kMessage ="Hello from Groopy Team.\n"."You have recently requested to reset your password. Please click on the link below complete the process\n";
		$kLink = "http://localhost:8888/code/account/resetPassword.php?email=".urlencode($kForgotPwEmail);
		$kMessage .= $kLink."\nThank You.\nGroopy Team";
		$kHeader = "From: Groopy <noreply@groopy.com>";
		MailAgent::writeEmail($kForgotPwEmail,$subject,$kMessage,$kHeader);	
	}
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Groopy | Forgot Password</title>
    <meta charset="utf-8">
<!--Page specific css-->    
	<link rel="stylesheet" type="text/css" href="../../shared/css/base.css">
    
<!--jQuery css & js files-->
	<link href="../../includes/jquery/groopy/css/groopy/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<script src="../../includes/jquery/groopy/js/jquery-1.9.1.js"></script>
	<script src="../../includes/jquery/groopy/js/jquery-ui-1.10.3.custom.js"></script>
    <script>
		function showNotification(){
			$('#notification').show();
			setTimeout(function() {
				$('#notification').hide();
			}, 10000);
		}
		function callLogin(){
			location.href= "signIn.php";
		}
	</script>
    <style>
		#notification{
			display: none;
		}
		h3{
			padding-left: 300px;
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
          <h3>Retrieve Password</h3>
        
          <form method="post" id="form_body2">
                <p>
                    <label for="form_email">Please enter your Username: </label>
                    <input type="email" name='email' id="form_email" required autofocus/>
                </p>
                <p>
                    <input type="submit" value="Submit" id="emailNotifyBtn" >
                </p>
          </form>
        </div>
        <br/>
        
    </div>
    <br/>
    <!--Following code works perfectly fine! just some css needs to be fixed!!!-->
    <!--div class="ui-widget" id="notification">  
        <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
            <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
          <strong>Hey Username!</strong> An email has been sent to your account. Please try to <input type="submit" value="Login" onClick="callLogin();" id="loginBtn"> again.</p>
      </div>
	</div-->
</body>
</html>
