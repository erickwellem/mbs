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
	

	$strUsername = htmlentities($_REQUEST['frm_username']);
	$strEmail = htmlentities(strtolower($_REQUEST['frm_email']));	

	if (($strEmail || $strUsername) && !$_SERVER['QUERY_STRING'])
	{
		#echo $strUsername . " " . $strEmail;

		if ($strEmail && !$strUsername)
		{
			$intUserID = $db->dbFieldToID('users', 'user_email', $strEmail, 'user_id');

			if ($intUserID)
			{
				sendEmailPasswordReset($intUserID);

				echo "The email was sent to <em>" . $strEmail . "</em>";
			}

			else
			{
				echo "No account is using <em>" . $strEmail . "</em>!";
			}

		}

		elseif (!$strEmail && $strUsername)
		{
			$intUserID = $db->dbFieldToID('users', 'user_login_name', $strUsername, 'user_id');

			if ($intUserID)
			{
				sendEmailPasswordReset($intUserID);

			}

			else
			{
				echo "<em>" . $strUsername . "</em> does not exist!";
			}

		}

	}


	function sendEmailPasswordReset($intUserID)
	{

		global $STR_URL;
		global $db;
		global $admin;
		global $html;

		$arrSiteConfig = $db->getSiteConfig();
		$strCode = $admin->createPasswordResetCode($intUserID);
		$strExpiryDate = $db->dbIDToField('users', 'user_id', $intUserID, 'user_password_reset_date_expiry');
		$strExpiryDate = $html->convertDateTime($strExpiryDate); 
		$strIPAddress = $_SERVER['REMOTE_ADDR'] . " (" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")";

		
		$strSubject =  "Password Reset request - " . stripslashes($arrSiteConfig['site_name']);

		$strEmail = $db->getUserEmailByID($intUserID);
		$strEmail = strtolower($strEmail);
		
		$strMsg = "<p>Hi,</p>\n\n"; 

		$strMsg .= "<p>You or someone else has requested to reset your password from <em><strong>" . $strIPAddress . "</strong></em>. Please enter the Code to reset your password or simply click the URL below:</p><br />\n\n";
		
		$strMsg .= "<p>Code : <strong>" . $strCode . "</strong></p>\n";
		$strMsg .= "<p>URL : " . $STR_URL . "password_reset.php?c=" . $strCode . "</p><br />\n";
		$strMsg .= "<p>Expiry Date : " . $strExpiryDate . "</p><br />\n\n";

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
			echo "An email with instructions to reset your password has been sent to your email. Please check and follow the instructions!";
		}
	
	}

?>