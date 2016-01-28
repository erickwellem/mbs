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
if ($_REQUEST['action'] == "add" && $_REQUEST['frm_size_name'])
{
	

	// filter input
	if (!$_REQUEST['frm_size_active']) { $_REQUEST['frm_size_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "INSERT INTO `mbs_sizes` (`size_id`, 
											`size_name`, 
											`size_description`, 
											`size_active`, 
											`size_created_date`, 
											`size_created_by`, 
											`size_modified_date`, 
											`size_modified_by`) 

				VALUES (NULL, 
						'" . mysql_real_escape_string($_REQUEST['frm_size_name']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_size_description']) . "', 	
						'" . mysql_real_escape_string($_REQUEST['frm_size_active']) . "', 
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "',
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "')";

	$result = mysql_query($query);
	$intID = mysql_insert_id();


	if ($result)
	{
		$strAlert = '<p>Size named "' . stripslashes(htmlspecialchars($_REQUEST['frm_size_name'])) . '" is successfuly added!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('sizes', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size_view.php?size_id=". $intID . "&action=view\" title=\"View Size\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('sizes', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size.php?size_id=" . $intID . "&action=edit\" title=\"Edit Size\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('sizes', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size.php?size_id=" . $intID . "&action=delete\" title=\"Delete Size\" onclick=\"return confirmDeleteSize(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('sizes', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size.php?action=add\" title=\"Add Size\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('sizes', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size_list.php\" title=\"Size List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Size named "' . stripslashes(htmlspecialchars($_REQUEST['frm_size_name'])) . '" is successfully added.';
					
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
elseif ($_REQUEST['action'] == "edit" && $_REQUEST['size_id']) 
{
		

	// filter input
	if (!$_REQUEST['frm_size_active']) { $_REQUEST['frm_size_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "UPDATE `mbs_sizes` SET `size_name` = '" . mysql_real_escape_string($_REQUEST['frm_size_name']) . "', 
										  `size_description` = '" . mysql_real_escape_string($_REQUEST['frm_size_description']) . "', 
										  `size_active` = '" . mysql_real_escape_string($_REQUEST['frm_size_active']) . "', 
										  `size_modified_date` = '" . date('Y-m-d H:i:s') . "', 
										  `size_modified_by` = '" . $_SESSION['user']['login_name'] . "' 
								WHERE `size_id` = '" . mysql_real_escape_string($_REQUEST['size_id']) . "' 
								LIMIT 1";

	$result = mysql_query($query);
	$intID = $_REQUEST['size_id'];


	if ($result)
	{
		$strAlert = '<p>Size named "' . stripslashes(htmlspecialchars($_REQUEST['frm_size_name'])) . '" is successfuly updated!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('sizes', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size_view.php?size_id=". $intID . "&action=view\" title=\"View Size\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('sizes', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size.php?size_id=" . $intID . "&action=edit\" title=\"Edit Size\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('sizes', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size.php?size_id=" . $intID . "&action=delete\" title=\"Delete Size\" onclick=\"return confirmDeleteSize(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('sizes', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size.php?action=add\" title=\"Add Size\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('sizes', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"size_list.php\" title=\"Size List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Size named "' . stripslashes(htmlspecialchars($_REQUEST['frm_size_name'])) . '" is successfully updated.';
					
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

elseif ($_REQUEST['action'] == "delete" && $_REQUEST['size_id'])
{
	
	$strSizeName = $db->dbIDToField('mbs_sizes', 'size_id', $_REQUEST['size_id'], 'size_name');

	// delete	
	$query = "DELETE FROM `mbs_sizes` WHERE `size_id` = '" . mysql_real_escape_string($_REQUEST['size_id']) .  "' LIMIT 1";
	$result = mysql_query($query);
	
	$strAlert = 'Size named "' . $strSizeName . '" is successfully deleted!';

	$strLog = 'Size named "' . $strSizeName . '" is successfully deleted.';
						
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
	$strSubject = "Size List | " . $arrSiteConfig['site_name'];

	// message
	$strMessage = "";

	if ($_REQUEST['frm_message']) 
	{
		$strMessage .= "<p><em>\"" . stripslashes(htmlspecialchars($_REQUEST['frm_message'])) . "\"</em></p><br />\n\n";
	}	

	$strMessage .= file_get_contents($STR_URL . 'size_list_print.php?action=print');
	

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