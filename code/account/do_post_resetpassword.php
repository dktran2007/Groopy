<?php
/*********************************
php page starts here by import
the header importer
@author		:	Lam Lu
@date		:	04/19/14
*********************************/
	require_once("../../HeaderImporter.php");
	{
		
		$needShowMessage = false;
		if(isset($_GET['email']) && isset($_POST['password']) && isset($_POST['confirmPassword']))
		{
			$kResetPwEmail = $_GET['email'];
			$kPW = $_POST['password'];
			$kConfirmPW = $_POST['confirmPassword'];
			if($kPW != $kConfirmPW)
				return;
			
			importHeader("dbconnection");
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
								$needShowMessage = true;
							}
						}
					}
				}
			DBConnection::closeConnection();
		}
	}
?>
<!DOCTYPE html>
<html lang="en"><head>
  <head>
    <meta charset="utf-8">
    <title>Groopy | Reset</title>
    <?php
		importHeader("css");
	?>
    <!--------------------------------------------------script starts-------------------------------------------->
    <script type="text/javascript">
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
			
			if(password !== confirmPassword || emptyString.test(confirmPassword))
			{
				document.getElementById("confirm_password_error_row").innerHTML = 
				'<img src="http://localhost:8888/shared/assets/NotOk_Icon.png" />';;
				result = false;
			}
			return result;
		}
		
		/**
		*/
		function checkConfirmPassword()
		{
			var password = document.getElementById("form_password").value;
			var confirmPassword = document.getElementById("form_confirmPassword").value;
			var emptyString = new RegExp("^\\s*$"); /* $ is string empty ; \s* is string containing whitespaces only*/
			if(password !== confirmPassword || emptyString.test(confirmPassword))
			{
				document.getElementById("confirm_password_error_row").innerHTML = 
					'<img src="http://localhost:8888/shared/assets/NotOk_Icon.png" />';;
			}
			else
				document.getElementById("confirm_password_error_row").innerHTML = 
				'<img src="http://localhost:8888/shared/assets/Ok_Icon.png" />';; 
		}
	</script>
    <!--------------------------------------------------script ends-------------------------------------------->
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
                
                <div id="psignup_signupbox">
                    <form id="forgot_password_form" action="" method="post" onsubmit="return formValidate()">
                    
                        <input type="password" name="password" id="form_password"
                        		 class="input_row" placeholder="New password" required />
                           
                        <div class="input_row_wrapper addMargin">     
                        <input type="password" name="confirmPassword" id="form_confirmPassword"
                        	class="k_input_row" placeholder="Confirm new password" onblur= "checkConfirmPassword()"
                            	required />
                          <!---------------------------------------------------------------
                            check confirm password error div. The div is hidden if there is error
                            display error when confirm password does not match
                            ---------------------------------------------------------------->
                            <div class="k_error_row" id="confirm_password_error_row">
                            </div>
                         </div>
                        
                        <input type="submit" value="Reset" class="input_row blue_button" />
                        
                        <!---------------------------------------------------------------
                        hidden message box. display when user submit the email to reset
                        password
                        ---------------------------------------------------------------->
                        <div id="pforgot_messagebox">
                        <span>
                        <?php 
							if($needShowMessage)
							{
								echo "Your password has been reset.You will be redirected to login page in a moment";
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