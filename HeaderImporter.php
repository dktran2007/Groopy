<?php
/**
Common import phps accross folders
@author	: 	Lam Lu
@date	:	04/19/2014
*/

function importHeader($hParam)
{
	switch($hParam)
	{
		case "dbconnection":
		{
			require_once($_SERVER['DOCUMENT_ROOT']."/shared/php/DBConnection.php");
			break;
		}
		
		case "checklogin":
		{
			require_once($_SERVER['DOCUMENT_ROOT']."/shared/php/CheckLogin.php");
			break;
		}
		
		case "mailagent":
		{
			require_once($_SERVER['DOCUMENT_ROOT']."/code/account/MailAgent.php");
			break;
		}
		case "css":
		{
			echo '<link rel="stylesheet" type="text/css" href="http://localhost:8888/shared/css/stylesheet.css" />';
			echo '<link rel="icon"  href="http://localhost:8888/shared/assets/Groopy_Bar_Icon.png" />';
		//	echo '<link type="text/css"
		//	href="http://localhost:8888/includes/jquery/groopy/css/groopy/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" />';
		//	echo '<link rel="stylesheet" 
		//	href="http://localhost:8888/includes/bootstrap/css/bootstrap.min.css" rel="stylesheet" />';
		//	echo '<link href="http://localhost:8888/includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />';
			break;
  		}
		
		case "jQuery":
		{
			echo '<script type="text/javascript"
					 src="http://localhost:8888//includes/jquery/groopy/js/jquery-1.10.2.js"></script>';
  			echo '<script type="text/javascript"
					 src="http://localhost:8888//includes/jquery/groopy/js/jquery-ui-1.10.4.custom.min.js"></script>';
			break;
		}
		
		case "gPlus":
		{
			echo '<script type="text/javascript"
					src="https://apis.google.com/js/client:plusone.js"></script>';
  			echo '<script type="text/javascript"
					src="https://apis.google.com/js/plusone.js"></script>';
			break;
		}
		default: break;
	}
}

function redirectPage($toPage)
{
	switch($toPage)
	{
		case "home":
		{
			$link = "code/user/home.php";
			$link = "<script type='text/javascript'> location.href = '".$link."';</script>";
			echo $link;
			break;
		}
		default: break;
	}
}
?>