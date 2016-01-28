<?php
/**************************************************************************************************
 * EW Web Apps Process File
 * @Author		: Hanif Nur Amrullah (hanif@brandxindo.com)
 * 				  Apr 2014
 **************************************************************************************************/
include('config.php');
require_once('lib/db.php');
require_once('lib/html.php');
$html = new Html();

ini_set('max_execution_time', 3000);

$db = new DB();
$db->dbConnect();

$intMonth = date("m");
$intYear = date("Y");

$strMonth = date("F");

$strThirdWednesday = date("d-m-Y", strtotime("third monday of {$strMonth} {$intYear}"));

$now = date("d-m-Y");


if($strThirdWednesday == $now){
	$next = new DateTime(date("Y-m-d",strtotime("+2 month", strtotime($now))));

	$nextMonth = $next->format('m');
	$nextYear = $next->format('Y');
	$strNextMonth = $next->format('F');


	$intYearMonth = $nextYear.$nextMonth;

	$queryStore = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
	$resultStore = mysql_query($queryStore);

	while ($rowStore = mysql_fetch_assoc($resultStore))
	{
		$intStoreID = $rowStore['store_id'];
		$strStoreName = $db->dbIDToField('mbs_stores', 'store_id', intval($intStoreID), 'store_name');
		
		$strReportTitle = "In-Store Activities : 6 Week Before Activities Start -";
		$strReportTitle .= $strStoreName . " ";
		
		$strReportTitle .= $strNextMonth . " " . $intYear;

		$strMessage = file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&frm_store_id=' . $intStoreID . '&report=reports_general_in_store_single.php&print=no');
		
		$emails = $db->getListEmail($intStoreID);
		
		foreach($emails as $value){
			sendMailToEmails($db, $html,$strReportTitle, $value, $strMessage);
			//sendMailToEmails($db, $html,$strReportTitle, 'muhajirin.imam@yahoo.com', $strMessage);
		}

		$strStoreEmail = $db->dbIDToField('mbs_stores', 'store_id', intval($intStoreID), 'store_email');
		sendMailToEmails($db, $html, $strReportTitle, $strStoreEmail, $strMessage);
		//sendMailToEmails($db, $html, $strReportTitle, 'muhajirin.imam@gmail.com', $strMessage);
	}	

	$queryStore = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
	$resultStore = mysql_query($queryStore);

	while ($rowStore = mysql_fetch_assoc($resultStore))
	{
		$intStoreID = $rowStore['store_id'];
		$strStoreName = $db->dbIDToField('mbs_stores', 'store_id', intval($intStoreID), 'store_name');
		
		$strReportTitle = "In-Store Activities All ";
		$strReportTitle .= $strStoreName . " ";
		
		$strReportTitle .= $strNextMonth . " " . $intYear;

		$strMessage = file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&frm_store_id=' . $intStoreID . '&report=reports_general_in_store_all.php&print=no');
		
		$emails = $db->getListEmail($intStoreID);
		
		foreach($emails as $value){
			sendMailToEmails($db, $html,$strReportTitle, $value, $strMessage);
			//sendMailToEmails($db, $html,$strReportTitle, 'muhajirin.imam@yahoo.com', $strMessage);
		}

		$strStoreEmail = $db->dbIDToField('users', 'user_id', 2, 'user_email');
		
		sendMailToEmails($db, $html, $strReportTitle, $strStoreEmail, $strMessage);
		//sendMailToEmails($db, $html, $strReportTitle, 'muhajirin.imam@outlook.com', $strMessage);
	}
}else{

	echo "Nothing Sent !!";
}

$strThirdWednesday = date("d-m-Y", strtotime("third monday of {$strMonth} {$intYear}"));

$now = date("d-m-Y");


if($strThirdWednesday == $now){
	$next = new DateTime(date("Y-m-d",strtotime("+1 month", strtotime($now))));

	$nextMonth = $next->format('m');
	$nextYear = $next->format('Y');
	$strNextMonth = $next->format('F');


	$intYearMonth = $nextYear.$nextMonth;

	$queryStore = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
	$resultStore = mysql_query($queryStore);

	while ($rowStore = mysql_fetch_assoc($resultStore))
	{
		$intStoreID = $rowStore['store_id'];
		$strStoreName = $db->dbIDToField('mbs_stores', 'store_id', intval($intStoreID), 'store_name');
		
		$strReportTitle = "In-Store Activities : 4 Week Before Activities Start - ";
		$strReportTitle .= $strStoreName . " ";
		
		$strReportTitle .= $strNextMonth . " " . $intYear;

		$strMessage = file_get_contents($STR_URL . 'reports_print.php?action=print&frm_year_month=' . $intYearMonth . '&frm_store_id=' . $intStoreID . '&report=reports_general_in_store_single.php&print=no');
		
		$emails = $db->getListEmail($intStoreID);
		
		foreach($emails as $value){
			sendMailToEmails($db, $html,$strReportTitle, $value, $strMessage);
			//sendMailToEmails($db, $html,$strReportTitle, 'muhajirin.imam@yahoo.com', $strMessage);
		}

		$strStoreEmail = $db->dbIDToField('mbs_stores', 'store_id', intval($intStoreID), 'store_email');
		sendMailToEmails($db, $html, $strReportTitle, $strStoreEmail, $strMessage);
		//sendMailToEmails($db, $html, $strReportTitle, 'muhajirin.imam@gmail.com', $strMessage);
	}
}

else{

	echo "Nothing Sent !!";
}

echo "Send Notification";

function sendMailToEmails($db, $html,$strReportTitle, $strEmailTo, $strMessage){

	
	$arrSiteConfig = $db->getSiteConfig();
	$strEmailFrom = "admin@pharmacy4less.com.au";

	$strSubject = "Report " . $strReportTitle . " | " . $arrSiteConfig['site_name'];

	// From
	$arrFrom = 	array('from'=>array($strEmailFrom));

	$arrTo = array('to'=>array($strEmailTo),'bcc'=>array("amrews@hotmail.com","muhajirin.imam@gmail.com"));
		
	$isSucceed = $html->sendEmail($arrFrom, $arrTo, $strSubject, $strMessage, 'html', 'normal');

	if ($isSucceed > 0)
	{
		$strLog = "Email has been sent to : {$strEmailTo}";
				
		$queryLog = "INSERT INTO `logs` (`log_id`, 
									     `log_user`, 
									     `log_action`, 
									     `log_time`, 
									     `log_from`, 
									     `log_logout`)

					VALUES (NULL, 
							'CRON',
						    '" . mysql_real_escape_string($strLog) . "',
							NOW( ),
							'CRON', 
							NULL)";			
		
		$resultLog = mysql_query($queryLog);
	}

	else
	{
		echo "Failed to send email!";
	}
}
?>