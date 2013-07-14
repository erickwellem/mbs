<?php
/**************************************************************************************************
 * EW Web Apps Process File
 * @Author		: Erick Wellem (me@erickwellem.com)
 * 				  October 2009
 *				  This version: February 2013
 * 		
 * @Desc: Process file using Ajax
 **************************************************************************************************/
session_start();
include('../config.php');
require_once('../lib/db.php');
require_once('../lib/admin.php');
require_once('../lib/html.php');

$db = new DB();
$admin = new ADMIN();
$html = new HTML();


//print_r($_REQUEST);
//--> Add
if ($_REQUEST['action'] == "add" && $_REQUEST['frm_activity_name'])
{
	

	// filter input
	if (!$_REQUEST['frm_activity_store_related']) { $_REQUEST['frm_activity_store_related'] = "no"; }
	if (!$_REQUEST['frm_activity_active']) { $_REQUEST['frm_activity_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "INSERT INTO `mbs_activities` (`activity_id`, 
											`activity_name`, 
											`activity_category`, 
											`activity_description`, 
											`activity_price`, 
											`activity_store_related`, 
											`activity_active`, 
											`size_id`, 
											`activity_created_date`, 
											`activity_created_by`, 
											`activity_modified_date`, 
											`activity_modified_by`) 

				VALUES (NULL, 
						'" . mysql_real_escape_string($_REQUEST['frm_activity_name']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_activity_category']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_activity_description']) . "', 	
						'" . mysql_real_escape_string($_REQUEST['frm_activity_price']) . "', 	
						'" . mysql_real_escape_string($_REQUEST['frm_activity_store_related']) . "', 	
						'" . mysql_real_escape_string($_REQUEST['frm_activity_active']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_size_id']) . "', 
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "',
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "')";

	$result = mysql_query($query);
	$intID = mysql_insert_id();


	if ($result)
	{
		$strAlert = '<p>Activity named "' . stripslashes($_REQUEST['frm_activity_name']) . '" is successfuly added!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('activities', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity_view.php?activity_id=". $intID . "&action=view\" title=\"View Activity\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('activities', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity.php?activity_id=" . $intID . "&action=edit\" title=\"Edit Activity\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('activities', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity.php?activity_id=" . $intID . "&action=delete\" title=\"Delete Activity\" onclick=\"return confirmDeleteActivity(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('activities', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity.php?action=add\" title=\"Add Activity\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('activities', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity_list.php\" title=\"Activity List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Activity named "' . stripslashes($_REQUEST['frm_activity_name']) . '" is successfully added.';
					
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

		echo $strAlert;				

	}

	else
	{
		echo "<p>Failed to insert to the database!</p>";
	}

} //--> Add

//--> Edit
elseif ($_REQUEST['action'] == "edit" && $_REQUEST['activity_id']) 
{
		

	// filter input
	if (!$_REQUEST['frm_activity_store_related']) { $_REQUEST['frm_activity_store_related'] = "no"; }
	if (!$_REQUEST['frm_activity_active']) { $_REQUEST['frm_activity_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "UPDATE `mbs_activities` SET `activity_name` = '" . mysql_real_escape_string($_REQUEST['frm_activity_name']) . "', 
										  `activity_category` = '" . mysql_real_escape_string($_REQUEST['frm_activity_category']) . "', 
										  `activity_description` = '" . mysql_real_escape_string($_REQUEST['frm_activity_description']) . "', 
										  `activity_price` = '" . mysql_real_escape_string($_REQUEST['frm_activity_price']) . "', 
										  `activity_store_related` = '" . mysql_real_escape_string($_REQUEST['frm_activity_store_related']) . "', 
										  `activity_active` = '" . mysql_real_escape_string($_REQUEST['frm_activity_active']) . "', 
										  `size_id` = '" . mysql_real_escape_string($_REQUEST['frm_size_id']) . "', 
										  `activity_modified_date` = '" . date('Y-m-d H:i:s') . "', 
										  `activity_modified_by` = '" . $_SESSION['user']['login_name'] . "' 
								WHERE `activity_id` = '" . mysql_real_escape_string($_REQUEST['activity_id']) . "' 
								LIMIT 1";

	$result = mysql_query($query);
	$intID = $_REQUEST['activity_id'];
	

	if ($result)
	{
		$strAlert = '<p>Activity named "' . stripslashes($_REQUEST['frm_activity_name']) . '" is successfuly updated!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('activities', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity_view.php?activity_id=". $intID . "&action=view\" title=\"View Activity\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('activities', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity.php?activity_id=" . $intID . "&action=edit\" title=\"Edit Activity\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('activities', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity.php?activity_id=" . $intID . "&action=delete\" title=\"Delete Activity\" onclick=\"return confirmDeleteActivity(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('activities', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity.php?action=add\" title=\"Add Activity\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('activities', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"activity_list.php\" title=\"Activity List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Activity named "' . stripslashes($_REQUEST['frm_activity_name']) . '" is successfully updated.';
					
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

		echo $strAlert;				

	}

	else
	{
		echo "<p>Failed to insert to the database!</p>";
	}

}

elseif ($_REQUEST['action'] == "delete" && $_REQUEST['activity_id'])
{
	
	$strActivityName = $db->dbIDToField('mbs_activities', 'activity_id', $_REQUEST['activity_id'], 'activity_name');

	// delete	
	$query = "DELETE FROM `mbs_activities` WHERE `activity_id` = '" . mysql_real_escape_string($_REQUEST['activity_id']) .  "' LIMIT 1";
	$result = mysql_query($query);
	
	$strAlert = 'Activity named "' . $strActivityName . '" is successfully deleted!';	

	$strLog = 'Activity named "' . $strActivityName . '" is successfully deleted.';
						
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

	
	echo $strAlert;
			
}

//--> Email List
elseif ($_REQUEST['action'] == "email_list")
{
	// get site config
	$arrSiteConfig = $db->getSiteConfig();
		
	// get current user's email
	$strEmailFrom = $db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_email');

	// subject
	$strSubject = "Activity Price List | " . $arrSiteConfig['site_name'];

	// message
	$strMessage = "";

	if ($_REQUEST['frm_message']) 
	{
		$strMessage .= "<p><em>\"" . stripslashes($_REQUEST['frm_message']) . "\"</em></p><br />\n\n";
	}	

	$strMessage .= file_get_contents($STR_URL . 'activity_list_print.php?action=print');
	

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

		$strLog = 'Activity Price List is successfully sent to "' . strtolower($_REQUEST['frm_email_to']) . '"';
		
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