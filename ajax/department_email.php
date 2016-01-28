<?php
include('../config.php');
require_once('../lib/db.php');
require_once('../lib/admin.php');
require_once('../lib/html.php');
session_start();

$db = new DB();
$admin = new ADMIN();
$html = new HTML();


	$intMonth = $_REQUEST['month'];
	$intYear = $_REQUEST['year'];


//--> Email List
if ($_REQUEST['action'] == "email_list")
{
	// get site config
	$arrSiteConfig = $db->getSiteConfig();
		
	// get current user's email
	$strEmailFrom = $db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_email');

	// subject
	$strSubject = "Reports by department for catalogue booked activity | " . $arrSiteConfig['site_name'];

	// message
	$strMessage = "";

	if ($_REQUEST['frm_message']) 
	{
		$strMessage .= "<p><em>\"" . stripslashes(htmlspecialchars($_REQUEST['frm_message'])) . "\"</em></p><br />\n\n";
	}	

	$strMessage .= file_get_contents($STR_URL . 'report_department_print.php?action=print&print=false&month='.$intMonth.'&year='.$intYear);
	

	// From
	$arrFrom = 	array('from'=>array($strEmailFrom));

	// To
	if ($_REQUEST['frm_send_copy'] == "yes")
	{
		$arrTo = array('to'=>array(strtolower($_REQUEST['frm_email_to'])), 'cc'=>array(strtolower($strEmailFrom)));	
	}

	else
	{
		$arrTo = array('to'=>array(strtolower($_REQUEST['frm_email_to'])));
	}
	
	
	$isSucceed = $html->sendEmail($arrFrom, $arrTo, $strSubject, $strMessage, 'html', 'normal');

	if ($isSucceed > 0)
	{
		echo "Email is successfully sent to " . strtolower($_REQUEST['frm_email_to']);

		if ($_REQUEST['frm_send_copy'] == "yes") {
			echo " A copy also sent to " . strtolower($strEmailFrom) . "";
		}

		$strLog = 'Size List is successfully sent to "' . strtolower($_REQUEST['frm_email_to']) . '"';
		
		if ($_REQUEST['frm_send_copy'] == "yes") 
		{
			$strLog .= ' and a copy also sent to "' . strtolower($strEmailFrom) . '"';
		}

		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

								VALUES (NULL, 
										'" . $_SESSION['user']['login_name'] . "',
										'" . addslashes($strLog) . "',
										'" . date('Y-m-d H:i:s') . "',
										'" . $_SESSION['user']['ip_address'] . "', 
										NULL)";
							
		$resultLog = mysql_query($queryLog);

	}

	else
	{
		echo "Failed to send email!";
	}

}
//--> Email List
else
{
	echo "<p>The form was not submitted correctly!</p>";
}


?>