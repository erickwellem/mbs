<?php
/**************************************************************************************************
 * EW Web Apps Process File
 * @Author		: Erick Wellem (me@erickwellem.com)
 * 				  October 2009
 *				  This version: February 2013
 * 		
 * @Desc: Process file using Ajax
 **************************************************************************************************/
include('../config.php');
require_once('../lib/db.php');
require_once('../lib/admin.php');
require_once('../lib/html.php');

$db = new DB();
$admin = new ADMIN();
$html = new HTML();	

$strCode = htmlentities($_REQUEST['frm_user_password_reset_code']);
$strPassword = htmlentities($_REQUEST['frm_user_password']);
$strPasswordConfirm = htmlentities($_REQUEST['frm_user_password_confirm']);

if (($strCode && $strPassword && $strPasswordConfirm) && !$_SERVER['QUERY_STRING'])
{
	
	$intUserID = $admin->getUserIDByPasswordResetCode($strCode);

	if ($intUserID)
	{
		if ($admin->resetUserPasswordByCode($intUserID, $strPassword) > 0)
		{
			sendEmailPasswordResetSuccess($intUserID);	
		}

		else
		{
			echo "Failed to reset the password. There might be a database problem!";
		}

		
	}

	else
	{
		echo "Failed to reset the password. The password reset code is invalid or has been expired.";
	}	

}

else
{
	echo "Form submission error!";
}


function sendEmailPasswordResetSuccess($intUserID)
{
	global $STR_URL;
	global $db;
	global $admin;
	global $html;

	$arrSiteConfig = $db->getSiteConfig();
	
	$strIPAddress = $_SERVER['REMOTE_ADDR'] . " (" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")";

	
	$strSubject =  "Password Reset Successful - " . stripslashes($arrSiteConfig['site_name']);

	$strEmail = $db->getUserEmailByID($intUserID);
	$strEmail = strtolower($strEmail);
	
	$strMsg = "<p>Hi,</p>\n\n"; 

	$strMsg .= "<p>You or someone else has successfully reset your password from <em><strong>" . $strIPAddress . "</strong></em>. You can login with your new password through the URL below:</p>\n\n";		
	
	$strMsg .= "<p>URL : " . $STR_URL . "</p><br />\n\n";
	$strMsg .= "<p>Regards,</p><br /><br />\n\n\n";
	$strMsg .= "<p>Admin</p>\n";

	$message = $strMsg;
	
	// header for plain text email
	$headers = "From: " . strtolower($arrSiteConfig['site_admin_email']) . "\r\n";
	$headers = "Cc: me@erickwellem.com\r\n";		
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";

	// mail the URL
	$strMail = @mail ($strEmail, $strSubject, stripslashes($message), $headers);	

	if ($strMail)
	{
		echo "Congratulations! Password is successfully reset. Please login with your new password <a href='" . $STR_URL . "'>here</a>!";
	}

}

?>