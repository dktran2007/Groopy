<?php
/*********************************
php page starts here by import
the header importer
@author		:	Lam Lu
@date		:	04/19/14
*********************************/
require_once("../../HeaderImporter.php");
?>
<!DOCTYPE html>
<html lang="en"><head>
  <head>
    <meta charset="utf-8">
    <title>Groopy | Sign Up</title>
    <?php
		importHeader("css");
	?>
   
    <!----------------script starts---------------------->
    <script type="text/javascript">
		/**
		 Ajax to check the email availability of the user account
		*/
		function checkEmailAvailability()
		{
			var email = document.getElementById("form_email").value;
			emailString = new RegExp("^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$");
			
			if(!emailString.test(email))
			{
					document.getElementById("email_avai_row").innerHTML = 
								'<img src="http://localhost:8888/shared/assets/NotOk_Icon.png" />';
			}
			else 
			{
				var ajax;
				if(window.XMLHttpRequest)
				{
					//code for IE7+, Firefox, Chrome, Safari, Opera
					ajax = new XMLHttpRequest();
				}
				else
				{
					//code for IE6, IE5
					ajax = new ActiveXObject("Microsoft.XMLHTTP");
				}
				if (!ajax) 
				{
					 alert('Cannot create an XMLHTTP instance');
					 return false;
				}
				
				ajax.open('GET', 'http://localhost:8888/code/account/checkEmailAvailability.php?email='+ email, true);
				ajax.send(null);
				ajax.onreadystatechange = function()
				{
					if(ajax.readyState==4 && ajax.status == 200)
					{
						var availability = "false";
						availability = ajax.responseText;
						if (availability == "false")
						{
							document.getElementById("email_avai_row").innerHTML = 
								'<img src="http://localhost:8888/shared/assets/NotOk_Icon.png" />';
							document.getElementById("availability").value = "false";
						}
						else
						{
							document.getElementById("email_avai_row").innerHTML = 
								'<img src="http://localhost:8888/shared/assets/Ok_Icon.png" />';
							document.getElementById("availability").value = "true";
						}
					}
				}
			}
		}
		
		/**
		check confirm password. password cannot be empty or whitespaces only
		*/
		function checkConfirmPassword()
		{
			var password = document.getElementById("form_password").value;
			var confirmPassword = document.getElementById("form_confirmPassword").value;
			var emptyString = new RegExp("^\\s*$");
			if(password !== confirmPassword || emptyString.test(confirmPassword))
			{
				document.getElementById("confirm_password_error_row").innerHTML = 
					'<img src="http://localhost:8888/shared/assets/NotOk_Icon.png" />';
			}
			else
				document.getElementById("confirm_password_error_row").innerHTML =
					'<img src="http://localhost:8888/shared/assets/Ok_Icon.png" />';
		}
	</script>
    <!----------------script ends------------------------>
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
                    <form id="signup_form" 
                    	action="http://localhost:8888/code/account/do_post_register.php" method="post">
                        
                    	<input type="text" name="firstName" id="form_firstname"
                        	class="input_row" placeholder="First name" required/>
                            
                        <input type="text" name="lastName" id="form_lastname"
                        	class="input_row" placeholder="Last name" required/>
                            
                        <div class="input_row_wrapper">
                            <input type="email" name="email" id="form_email"
                                class="k_input_row" placeholder="Email" onblur="checkEmailAvailability()" required/>
                            <!---------------------------------------------------------------
                            check email error div. The div is hidden if there is error
                            display error when email is not available or wrong formatted
                            ---------------------------------------------------------------->
                            <div class="k_error_row" id="email_avai_row">
                            </div>
                        </div>
                       
                        <input type="password" name="password" id="form_password"
                        		 class="input_row" placeholder="Password" required />
                           
                        <div class="input_row_wrapper addMargin">     
                        <input type="password" name="confirm_password" id="form_confirmPassword"
                        	class="k_input_row" placeholder="Confirm password" onblur= "checkConfirmPassword()"
                            	required />
                          <!---------------------------------------------------------------
                            check confirm password error div. The div is hidden if there is error
                            display error when confirm password does not match
                            ---------------------------------------------------------------->
                            <div class="k_error_row" id="confirm_password_error_row">
                            </div>
                         </div>
                        
                        <input type="submit" value="Sign up" class="input_row blue_button" />
                    </form>
                </div>										<!-- end psignup_signupbox-->
            </div>											<!-- end psignup_right-->
        </div> 												<!-- end wrapper-->
  </body>
</html>