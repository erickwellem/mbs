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
session_start();

$db = new DB();
$admin = new ADMIN();
$html = new HTML();


function insertLog($strLog)
{
	global $db;

	$db->dbConnect();

	$query = "INSERT INTO `logs` (`log_id`, 
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
								
	$result = mysql_query($query);

	if ($result)
	{
		return 1;
	}

}

//print_r($_REQUEST);
//--> Email
if ($_REQUEST['action'] == 'email' && $_REQUEST['report']) 
{
	// get site config
	$arrSiteConfig = $db->getSiteConfig();
		
	// get current user's email
	$strEmailFrom = $db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_email');

	// get the other variables
	if ($_REQUEST['frm_year_month'] && strlen($_REQUEST['frm_year_month']) > 0) 
	{ 
		$intYearMonth = intval($_REQUEST['frm_year_month']); 
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
	}   			

	$strReport = htmlspecialchars($_REQUEST['report']);

	if ($_REQUEST['frm_store_id'] && strlen($_REQUEST['frm_store_id']) > 0)
	{
		$intStoreID = intval($_REQUEST['frm_store_id']);
		$strStoreName = $db->dbIDToField('mbs_stores', 'store_id', intval($intStoreID), 'store_name'); 
	}
	
	if ($_REQUEST['frm_year'] && strlen($_REQUEST['frm_year']) > 0) 
	{ 
		$intYearVal = intval($_REQUEST['frm_year']); 
	} 
	

	// message
	$strMessage = "";

	if ($_REQUEST['frm_message']) 
	{
		$strMessage .= "<p><em>\"" . stripslashes(htmlspecialchars($_REQUEST['frm_message'])) . "\"</em></p><br />\n\n";
	}
	

	// get report title
	$strReportTitle = "";	

	switch ($_REQUEST['report']) 
	{
	 	case 'reports_general_in_store_all.php':
	 		$strReportTitle .= "In-Store Activities ";
	 		$strReportTitle .= $html->getMonthName($intMonth) . " " . $intYear;

	 		$strMessage .= file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&report=' . $_REQUEST['report']);
	 		break;
	 	
	 	case 'reports_general_in_store_single_by_year.php':
	 		$strReportTitle .= "In-Store Activities ";
	 		if ($intStoreID) { $strReportTitle .= $strStoreName . " "; }
	 		$strReportTitle .= $intYearVal;	 		

	 		$strMessage .= file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year=' . $intYearVal . '&frm_store_id=' . $intStoreID . '&report=' . $_REQUEST['report']);
	 		break;	

	 	case 'reports_general_in_store_single.php':
	 		$strReportTitle .= "In-Store Activities ";
	 		if ($intStoreID) { $strReportTitle .= $strStoreName . " "; }
	 		$strReportTitle .= $html->getMonthName($intMonth) . " " . $intYear;

	 		$strMessage .= file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&frm_store_id=' . $intStoreID . '&report=' . $_REQUEST['report']);
	 		break;		

		case 'reports_general_catalogue.php':
	 		$strReportTitle .= $html->getMonthName($intMonth) . " " . $intYear;
	 		$strReportTitle .= " Catalogue";	 	

	 		$strMessage .= file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&report=' . $_REQUEST['report']);		 		
	 		break;

	 	case 'reports_general_newspaper.php':
	 		$strReportTitle .= $html->getMonthName($intMonth) . " " . $intYear;
	 		$strReportTitle .= " Newspaper";	 			 		
	 		
	 		$strMessage .= file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&report=' . $_REQUEST['report']);		 		
	 		break;	

	 	case 'reports_general_email.php':
	 		$strReportTitle .= $html->getMonthName($intMonth) . " " . $intYear;
	 		$strReportTitle .= " 4YOU Email";	 			 		

	 		$strMessage .= file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&report=' . $_REQUEST['report']);		 		
	 		break;		

	 	case 'reports_general_prep_school.php':
	 		$strReportTitle .= $html->getMonthName($intMonth) . " " . $intYear;
	 		$strReportTitle .= " PREP School";	 			 		

	 		$strMessage .= file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&report=' . $_REQUEST['report']);		 		
	 		break;		

	 	default:
	 		$strReportTitle = "";
	 		break;
	 } 

	// subject
	$strSubject = "Report " . $strReportTitle . " | " . $arrSiteConfig['site_name'];

	
	

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

		if ($_REQUEST['frm_send_copy'] == "yes") 
		{
			echo " A copy also sent to " . strtolower($strEmailFrom) . "";
		}


		$strLog = 'Report "' . $strReportTitle . '" is successfully sent to "' . strtolower($_REQUEST['frm_email_to']) . '"';
		
		if ($_REQUEST['frm_send_copy'] == "yes") 
		{
			$strLog .= ' and a copy also sent to "' . strtolower($strEmailFrom) . '"';
		}

		insertLog($strLog);

	}

	else
	{
		echo "Failed to send email!";
	}

}
//--> Email
	
else 
{
	echo "<p>The form was not submitted correctly!</p>";
}	

?>