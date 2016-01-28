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


//print_r($_REQUEST);
//--> Add
if ($_REQUEST['action'] == "add" && $_REQUEST['frm_store_name'])
{
	

	// filter input
	if (!$_REQUEST['frm_store_active']) { $_REQUEST['frm_store_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "INSERT INTO `mbs_stores` (`store_id`, 
										`store_name`, 
										`store_api_acc`, 
										`store_address`,
										`store_phone`, 
										`store_fax`, 
										`store_email`, 
										`store_contact`,  
										`store_active`, 
										`store_created_date`, 
										`store_created_by`, 
										`store_modified_date`, 
										`store_modified_by`) 

				VALUES (NULL, 
						'" . mysql_real_escape_string($_REQUEST['frm_store_name']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_store_api_acc']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_store_address']) . "', 	
						'" . mysql_real_escape_string($_REQUEST['frm_store_phone']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_store_fax']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_store_email']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_store_contact']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_store_active']) . "', 
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "',
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "')";

	$result = mysql_query($query);
	$intID = mysql_insert_id();


	if ($result)
	{
		$strAlert = '<p>Store named "' . stripslashes($_REQUEST['frm_store_name']) . '" is successfuly added!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('stores', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store_view.php?store_id=". $intID . "&action=view\" title=\"View Store\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('stores', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store.php?store_id=" . $intID . "&action=edit\" title=\"Edit Store\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('stores', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store.php?store_id=" . $intID . "&action=delete\" title=\"Delete Store\" onclick=\"return confirmDeleteStore(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('stores', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store.php?action=add\" title=\"Add Store\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('stores', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store_list.php\" title=\"Store List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Store named "' . stripslashes($_REQUEST['frm_store_name']) . '" is successfully added.';
					
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
elseif ($_REQUEST['action'] == "edit" && $_REQUEST['store_id']) 
{
		

	// filter input
	if (!$_REQUEST['frm_store_active']) { $_REQUEST['frm_store_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "UPDATE `mbs_stores` SET `store_name` = '" . mysql_real_escape_string($_REQUEST['frm_store_name']) . "', 
									  `store_api_acc` = '" . mysql_real_escape_string($_REQUEST['frm_store_api_acc']) . "', 
									  `store_address` = '" . mysql_real_escape_string($_REQUEST['frm_store_address']) . "', 
									  `store_phone` = '" . mysql_real_escape_string($_REQUEST['frm_store_phone']) . "', 
									  `store_fax` = '" . mysql_real_escape_string($_REQUEST['frm_store_fax']) . "', 
									  `store_email` = '" . mysql_real_escape_string($_REQUEST['frm_store_email']) . "', 
									  `store_contact` = '" . mysql_real_escape_string($_REQUEST['frm_store_contact']) . "', 
									  `store_active` = '" . mysql_real_escape_string($_REQUEST['frm_store_active']) . "', 
									  `store_modified_date` = '" . date('Y-m-d H:i:s') . "', 
									  `store_modified_by` = '" . $_SESSION['user']['login_name'] . "' 
								WHERE `store_id` = '" . mysql_real_escape_string($_REQUEST['store_id']) . "' 
								LIMIT 1";

	$result = mysql_query($query);
	$intID = $_REQUEST['store_id'];


	if ($result)
	{
		$strAlert = '<p>Store named "' . stripslashes($_REQUEST['frm_store_name']) . '" is successfuly updated!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('stores', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store_view.php?store_id=". $intID . "&action=view\" title=\"View Store\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('stores', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store.php?store_id=" . $intID . "&action=edit\" title=\"Edit Store\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('stores', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store.php?store_id=" . $intID . "&action=delete\" title=\"Delete Store\" onclick=\"return confirmDeleteStore(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('stores', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store.php?action=add\" title=\"Add Store\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('stores', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"store_list.php\" title=\"Store List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Store named "' . stripslashes($_REQUEST['frm_store_name']) . '" is successfully updated.';
					
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

elseif ($_REQUEST['action'] == "delete" && $_REQUEST['store_id'])
{
	
	$strStoreName = $db->dbIDToField('mbs_stores', 'store_id', $_REQUEST['store_id'], 'store_name');

	// delete	
	$query = "DELETE FROM `mbs_stores` WHERE `store_id` = '" . mysql_real_escape_string($_REQUEST['store_id']) .  "' LIMIT 1";
	$result = mysql_query($query);
	
	$strAlert = 'Store named "' . $strStoreName . '" is successfully deleted!';

	$strLog = 'Store named "' . $strStoreName . '" is successfully deleted.';
						
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
	$strSubject = "Store List | " . $arrSiteConfig['site_name'];

	// message
	$strMessage = "";

	if ($_REQUEST['frm_message']) 
	{
		$strMessage .= "<p><em>\"" . stripslashes($_REQUEST['frm_message']) . "\"</em></p><br />\n\n";
	}	

	$strMessage .= file_get_contents($STR_URL . 'store_list_print.php?action=print');
	

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

		$strLog = 'Store List is successfully sent to "' . strtolower($_REQUEST['frm_email_to']) . '"';
		
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