<?php
/*********************************
php page starts here by import
the header importer
@author		:	Lam Lu
@date		:	04/19/14
*********************************/
require_once("../../HeaderImporter.php");
	{
		
		$needShowMessage =false;
		if(isset($_POST['email']))
		{
			$kForgotPwEmail = $_POST['email'];
			importHeader("mailagent");
			$subject = "Reset Password";
													
			$kMessage ="Hello from Groopy Team.\n"."You have recently requested to reset your password. Please click on the link below complete the process\n";
			$kLink = "http://localhost:8888/code/account/do_post_resetpassword.php?email=".urlencode($kForgotPwEmail);
			$kMessage .= $kLink."\nThank You.\nGroopy Team";
			$kHeader = "From: Groopy <noreply@groopy.com>";
			MailAgent::writeEmail($kForgotPwEmail,$subject,$kMessage,$kHeader);	
			$needShowMessage = true;
		}
	}
?>
<!DOCTYPE html>
<html lang="en"><head>
  <head>
    <meta charset="utf-8">
    <title>Groopy | Forgot</title>
    <?php
		importHeader("css");
	?>
  </head>

  <body>
  		<div id="wrapper">            
        	<div id="pindex_left" class="center_image_tag">
               	 <img src="http://localhost:8888/shared/assets/Groopy_Puzzle.png" />
            </div> 											
            
            <div id="pindex_right">
            	<div id="psignup_logocontainer" class="center_image_tag">
                	<img src="http://localhost:8888/shared/assets/Groopy_Logo.png " />
                </div>										
                
                <div id="pindex_logoDescription">
                	<span>Simplified Project Management</span>
                </div>									
                
                <div id="pindex_loginbox">
                    <form id="forgot_password_form" action="" method="post">
                        <input type="email" name="email" class="input_row" placeholder="Email" required/>
                        <input type="submit" value="Submit" class="input_row blue_button" />
                        
                        <!---------------------------------------------------------------
                        hidden message box. display when user submit the email to reset
                        password
                        ---------------------------------------------------------------->
                        <div id="pforgot_messagebox">
                            <span>
                            <?php 
                                if ($needShowMessage == true)
                                {
                                    echo "Check your email to reset password.\nYou will be redirected to home page in a moment";
                                    echo '
                                        <script type="text/javascript">
                                            setTimeout(function()
                                            {
                                                window.location.replace("../../index.php");
                                            }, 3000);
                                        </script>
                                    ';
                                }
                            ?>
                            </span>
                        </div>
                    </form>
                </div>										<!-- end pindex_loginbox-->
            </div>											<!-- end pindex_right-->
        </div> 												<!-- end wrapper-->
  </body>
</html>