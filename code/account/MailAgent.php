<?php
/**
 * This is a static class to create the mail agent
 * @author Lam Lu
 */

class MailAgent
{
	//function to check spam emails
	static function spamcheck ($email)
	{
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
			return TRUE;
		else return FALSE;
						
	}
		
	//function to close the connection
	/*
	function to write email
	@param to: destination email
	@param subject: subject of the email
	@param message: message content of the mail
	@param header: header of the email
	*/
	static function writeEmail($toEmail, $subject, $message, $header)
	{
		if(!MailAgent::spamcheck($toEmail))
			return FALSE;
		mail($toEmail,$subject,$message,$header);		
	}
}


?>