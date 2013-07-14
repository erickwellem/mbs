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
if ($_REQUEST['action'] == "add" && 
	$_REQUEST['frm_supplier_name'] && 
	$_REQUEST['frm_supplier_email'] && 
	$_REQUEST['frm_supplier_phone_number'] && 
	$_REQUEST['frm_supplier_postal_address'] && 
	$_REQUEST['frm_supplier_contact_name'] && 
	$_REQUEST['frm_supplier_contact_name'])
{
	
	// filter input
	if (!$_REQUEST['frm_supplier_active']) { $_REQUEST['frm_supplier_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "INSERT INTO `mbs_suppliers` (`supplier_id`, 
										   `supplier_name`, 
										   `supplier_email`, 
										   `supplier_phone_number`, 
										   `supplier_postal_address`, 
										   `supplier_last_year_purchase`, 
										   `supplier_target`, 
										   `supplier_growth_incentives`, 
										   `supplier_budget`, 
										   `supplier_po_ref_number`, 
										   `supplier_active`, 
										   `supplier_created_date`, 
										   `supplier_created_by`, 
										   `supplier_modified_date`, 
										   `supplier_modified_by`) 

				VALUES (NULL, 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_name']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_email']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_phone_number']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_postal_address']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_last_year_purchase']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_target']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_growth_incentives']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_budget']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_po_ref_number']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_active']) . "', 
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "',
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "')";

	$result = mysql_query($query);
	$intID = mysql_insert_id();


	if ($result)
	{

		// Insert the Marketing Contact
		if ($intID && $_REQUEST['frm_supplier_contact_name'])
		{
			$queryContact = "INSERT INTO `mbs_suppliers_marketing_contacts` (`supplier_contact_id`, 
																			  `supplier_id`, 
																			  `supplier_contact_name`, 
																			  `supplier_contact_position`, 
																			  `supplier_contact_email`, 
																			  `supplier_contact_phone_number`, 
																			  `supplier_contact_mobile_number`, 
																			  `supplier_contact_postal_address`, 
																			  `supplier_contact_active`, 
																			  `supplier_contact_created_date`, 
																			  `supplier_contact_created_by`, 
																			  `supplier_contact_modified_date`, 
																			  `supplier_contact_modified_by`) 

																	VALUES (NULL, 
																			'" . $intID . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_name']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_position']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_email']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_phone_number']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_mobile_number']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_postal_address']) . "', 									
																			'yes', 
																			'" . date('Y-m-d H:i:s') . "', 
																			'" . $_SESSION['user']['login_name'] . "',
																			'" . date('Y-m-d H:i:s') . "', 
																			'" . $_SESSION['user']['login_name'] . "')";

			$resultContact = mysql_query($queryContact);
			
		} // if ($intID && $_REQUEST['frm_supplier_contact_name'])

		// Insert the Account Contact
		if ($intID && $_REQUEST['frm_supplier_account_name'])
		{
			$queryAccount = "INSERT INTO `mbs_suppliers_account_contacts` (`supplier_account_id`, 
																		   `supplier_id`, 
																		   `supplier_account_name`, 
																		   `supplier_account_email`, 
																		   `supplier_account_phone_number`, 
																		   `supplier_account_postal_address`, 
																		   `supplier_account_active`, 
																		   `supplier_account_created_date`, 
																		   `supplier_account_created_by`, 
																		   `supplier_account_modified_date`, 
																		   `supplier_account_modified_by`) 

																	VALUES (NULL, 
																			'" . $intID . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_account_name']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_account_email']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_account_phone_number']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_account_postal_address']) . "', 									
																			'yes', 
																			'" . date('Y-m-d H:i:s') . "', 
																			'" . $_SESSION['user']['login_name'] . "',
																			'" . date('Y-m-d H:i:s') . "', 
																			'" . $_SESSION['user']['login_name'] . "')";

			$resultAccount = mysql_query($queryAccount);
			
		} // if ($intID && $_REQUEST['frm_supplier_account_name'])

		// Insert the Territory Contacts
		if ($intID && ($_REQUEST['frm_supplier_territory_name_1'] || $_REQUEST['frm_supplier_territory_name_2'] || $_REQUEST['frm_supplier_territory_name_3'] || $_REQUEST['frm_supplier_territory_name_4'] || $_REQUEST['frm_supplier_territory_name_5'] || $_REQUEST['frm_supplier_territory_name_6']))
		{
			
			for ($i = 1; $i <= 6; $i++)
			{
				$queryTerritory = "INSERT INTO `mbs_suppliers_territory_contacts` (`supplier_territory_id`, 
																				   `supplier_id`, 
																				   `territory_id`, 
																				   `territory_name`, 
																				   `supplier_territory_name`, 
																				   `supplier_territory_phone_number`, 
																				   `supplier_territory_active`, 
																				   `supplier_territory_created_date`, 
																				   `supplier_territory_created_by`, 
																				   `supplier_territory_modified_date`, 
																				   `supplier_territory_modified_by`) 

																			VALUES (NULL, 
																					'" . $intID . "', 
																					'" . mysql_real_escape_string($_REQUEST['frm_territory_id_' . $i]) . "', 
																					'" . mysql_real_escape_string($_REQUEST['frm_territory_name_' . $i]) . "', 
																					'" . mysql_real_escape_string($_REQUEST['frm_supplier_territory_name_' . $i]) . "', 																					
																					'" . mysql_real_escape_string($_REQUEST['frm_supplier_territory_phone_number_' . $i]) . "', 																					
																					'yes', 
																					'" . date('Y-m-d H:i:s') . "', 
																					'" . $_SESSION['user']['login_name'] . "',
																					'" . date('Y-m-d H:i:s') . "', 
																					'" . $_SESSION['user']['login_name'] . "')";

				$resultTerritory = mysql_query($queryTerritory);
				
			} // for ($i = 1; $i <= 6; $i++)
		} // if ($intID && ($_REQUEST['frm_supplier_territory_name_1'] || ...

		$strAlert = '<p>Supplier named "' . stripslashes($_REQUEST['frm_supplier_name']) . '" is successfuly added!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('suppliers', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier_view.php?supplier_id=". $intID . "&action=view\" title=\"View Supplier\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('suppliers', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier.php?supplier_id=" . $intID . "&action=edit\" title=\"Edit Supplier\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('suppliers', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier.php?supplier_id=" . $intID . "&action=delete\" title=\"Delete Supplier\" onclick=\"return confirmDeleteSupplier(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('suppliers', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier.php?action=add\" title=\"Add Supplier\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('suppliers', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier_list.php\" title=\"Supplier List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Supplier named "' . stripslashes($_REQUEST['frm_supplier_name']) . '" is successfully added.';
					
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

	} // if ($result)

	else
	{
		echo "<p>Failed to insert to the database!</p>";
	} // else

} //--> Add

//--> Edit
elseif ($_REQUEST['action'] == "edit" && $_REQUEST['supplier_id']) 
{
		

	// filter input
	if (!$_REQUEST['frm_supplier_active']) { $_REQUEST['frm_supplier_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "UPDATE `mbs_suppliers` SET `supplier_name` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_name']) . "', 
										 `supplier_email` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_email']) . "', 
										 `supplier_phone_number` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_phone_number']) . "', 
										 `supplier_postal_address` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_postal_address']) . "', 
										 `supplier_last_year_purchase` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_last_year_purchase']) . "', 
										 `supplier_target` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_target']) . "', 
										 `supplier_growth_incentives` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_growth_incentives']) . "', 
										 `supplier_budget` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_budget']) . "', 
										 `supplier_po_ref_number` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_po_ref_number']) . "', 
										 `supplier_active` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_active']) . "', 
										 `supplier_modified_date` = '" . date('Y-m-d H:i:s') . "', 
										 `supplier_modified_by` = '" . $_SESSION['user']['login_name'] . "' 
								WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' 
								LIMIT 1";

	$result = mysql_query($query);
	$intID = $_REQUEST['supplier_id'];


	if ($result)
	{
		// Check for existing Marketing Contact 
		$intCount = intval($db->dbGetAggregateData("count", "mbs_suppliers_marketing_contacts", "supplier_id", "WHERE `supplier_id` = '" . $intID . "'" ));
		if ($intCount == 0) 
		{
			$queryContact = "INSERT INTO `mbs_suppliers_marketing_contacts` (`supplier_contact_id`, 
																			  `supplier_id`, 
																			  `supplier_contact_name`, 
																			  `supplier_contact_position`, 
																			  `supplier_contact_email`, 
																			  `supplier_contact_phone_number`, 
																			  `supplier_contact_mobile_number`, 
																			  `supplier_contact_postal_address`, 
																			  `supplier_contact_active`, 
																			  `supplier_contact_created_date`, 
																			  `supplier_contact_created_by`, 
																			  `supplier_contact_modified_date`, 
																			  `supplier_contact_modified_by`) 

																	VALUES (NULL, 
																			'" . $intID . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_name']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_position']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_email']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_phone_number']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_mobile_number']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_postal_address']) . "', 									
																			'yes', 
																			'" . date('Y-m-d H:i:s') . "', 
																			'" . $_SESSION['user']['login_name'] . "',
																			'" . date('Y-m-d H:i:s') . "', 
																			'" . $_SESSION['user']['login_name'] . "')";

			$resultContact = mysql_query($queryContact);

		} // if ($intCount == 0) 

		// Update the Marketing Contact
		elseif ($intID && $_REQUEST['frm_supplier_contact_name'])
		{
			$queryContact = "UPDATE `mbs_suppliers_marketing_contacts` SET `supplier_contact_name` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_name']) . "', 
																		   `supplier_contact_position` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_position']) . "', 	
																		   `supplier_contact_email` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_email']) . "', 
																		   `supplier_contact_phone_number` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_phone_number']) . "', 
																		   `supplier_contact_mobile_number` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_mobile_number']) . "', 
																		   `supplier_contact_postal_address` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_contact_postal_address']) . "', 
																		   `supplier_contact_active` = 'yes', 
																		   `supplier_contact_modified_date` = '" . date('Y-m-d H:i:s') . "', 
																		   `supplier_contact_modified_by` = '" . $_SESSION['user']['login_name'] . "' 
																	WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' 
																	AND `supplier_contact_id` = '" . mysql_real_escape_string($_REQUEST['supplier_contact_id']) . "' 
																	LIMIT 1";

			$resultContact = mysql_query($queryContact);

		} // elseif ($intID && $_REQUEST['frm_supplier_contact_name'])


		// Check for existing Account Contact 
		$intCount = intval($db->dbGetAggregateData("count", "mbs_suppliers_account_contacts", "supplier_id", "WHERE `supplier_id` = '" . $intID . "'" ));
		if ($intCount == 0) 
		{
			$queryAccount = "INSERT INTO `mbs_suppliers_account_contacts` (`supplier_account_id`, 
																		   `supplier_id`, 
																		   `supplier_account_name`, 
																		   `supplier_account_email`, 
																		   `supplier_account_phone_number`, 
																		   `supplier_account_postal_address`, 
																		   `supplier_account_active`, 
																		   `supplier_account_created_date`, 
																		   `supplier_account_created_by`, 
																		   `supplier_account_modified_date`, 
																		   `supplier_account_modified_by`) 

																	VALUES (NULL, 
																			'" . $intID . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_account_name']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_account_email']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_account_phone_number']) . "', 
																			'" . mysql_real_escape_string($_REQUEST['frm_supplier_account_postal_address']) . "', 									
																			'yes', 
																			'" . date('Y-m-d H:i:s') . "', 
																			'" . $_SESSION['user']['login_name'] . "',
																			'" . date('Y-m-d H:i:s') . "', 
																			'" . $_SESSION['user']['login_name'] . "')";

			$resultAccount = mysql_query($queryAccount);
			
		} // if ($intCount == 0) 
		
		// Update the Account Contact
		if ($intID && $_REQUEST['frm_supplier_account_name'])
		{
			$queryAccount = "UPDATE `mbs_suppliers_account_contacts` SET `supplier_account_name` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_account_name']) . "', 
																		 `supplier_account_email` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_account_email']) . "', 
																		 `supplier_account_phone_number` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_account_phone_number']) . "', 
																		 `supplier_account_postal_address` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_account_postal_address']) . "', 
																		 `supplier_account_active` = 'yes', 
																		 `supplier_account_modified_date` = '" . date('Y-m-d H:i:s') . "', 
																		 `supplier_account_modified_by` = '" . $_SESSION['user']['login_name'] . "' 
																	WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' 
																	AND `supplier_account_id` = '" . mysql_real_escape_string($_REQUEST['supplier_account_id']) . "' 
																	LIMIT 1";

			$resultAccount = mysql_query($queryAccount);

		} // if ($intID && $_REQUEST['frm_supplier_account_name'])

		// Update the Territory Contact
		if ($intID && ($_REQUEST['frm_supplier_territory_name_1'] || $_REQUEST['frm_supplier_territory_name_2'] || $_REQUEST['frm_supplier_territory_name_3'] || $_REQUEST['frm_supplier_territory_name_4'] || $_REQUEST['frm_supplier_territory_name_5'] || $_REQUEST['frm_supplier_territory_name_6']))
		{
			
			for ($i = 1; $i <= 6; $i++)
			{
				
				if ($_REQUEST['frm_supplier_territory_id_' . $i])
				{
					$queryTerritory = "UPDATE `mbs_suppliers_territory_contacts` SET `territory_id` = '" . mysql_real_escape_string($_REQUEST['frm_territory_id_' . $i]) . "', 
																					 `territory_name` = '" . mysql_real_escape_string($_REQUEST['frm_territory_name_' . $i]) . "', 
																					 `supplier_territory_name` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_territory_name_' . $i]) . "', 
																					 `supplier_territory_phone_number` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_territory_phone_number_' . $i]) . "', 
																					 `supplier_territory_active` = 'yes', 
																					 `supplier_territory_modified_date` = '" . date('Y-m-d H:i:s') . "', 
																					 `supplier_territory_modified_by` =  '" . $_SESSION['user']['login_name'] . "' 

																				WHERE `supplier_territory_id` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_territory_id_' . $i]) . "' 
																				AND `supplier_id` = '" . $intID . "' LIMIT 1";

					$resultTerritory = mysql_query($queryTerritory);	
				}

				else
				{
					$queryTerritory = "INSERT INTO `mbs_suppliers_territory_contacts` (`supplier_territory_id`, 
																					   `supplier_id`, 
																					   `territory_id`, 
																					   `territory_name`, 
																					   `supplier_territory_name`, 
																					   `supplier_territory_phone_number`, 
																					   `supplier_territory_active`, 
																					   `supplier_territory_created_date`, 
																					   `supplier_territory_created_by`, 
																					   `supplier_territory_modified_date`, 
																					   `supplier_territory_modified_by`) 

																				VALUES (NULL, 
																						'" . $intID . "', 
																						'" . mysql_real_escape_string($_REQUEST['frm_territory_id_' . $i]) . "', 
																						'" . mysql_real_escape_string($_REQUEST['frm_territory_name_' . $i]) . "', 
																						'" . mysql_real_escape_string($_REQUEST['frm_supplier_territory_name_' . $i]) . "', 																					
																						'" . mysql_real_escape_string($_REQUEST['frm_supplier_territory_phone_number_' . $i]) . "', 																					
																						'yes', 
																						'" . date('Y-m-d H:i:s') . "', 
																						'" . $_SESSION['user']['login_name'] . "',
																						'" . date('Y-m-d H:i:s') . "', 
																						'" . $_SESSION['user']['login_name'] . "')";

					$resultTerritory = mysql_query($queryTerritory);

				} // else

			}  // for ($i = 1; $i <= 6; $i++)

		} // if ($intID && ($_REQUEST['frm_supplier_territory_name_1'] || ...


		$strAlert = '<p>Supplier named "' . stripslashes($_REQUEST['frm_supplier_name']) . '" is successfuly updated!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('suppliers', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier_view.php?supplier_id=". $intID . "&action=view\" title=\"View Supplier\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('suppliers', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier.php?supplier_id=" . $intID . "&action=edit\" title=\"Edit Supplier\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('suppliers', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier.php?supplier_id=" . $intID . "&action=delete\" title=\"Delete Supplier\" onclick=\"return confirmDeleteSupplier(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('suppliers', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier.php?action=add\" title=\"Add Supplier\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('suppliers', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"supplier_list.php\" title=\"Supplier List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Supplier named "' . stripslashes($_REQUEST['frm_supplier_name']) . '" is successfully updated.';
					
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

	} // if ($result)

	else
	{
		echo "<p>Failed to insert to the database!</p>";
	} // else

}
//--> Edit

//--> Delete
elseif ($_REQUEST['action'] == "delete" && $_REQUEST['supplier_id'])
{
	
	$strSupplierName = $db->dbIDToField('mbs_suppliers', 'supplier_id', $_REQUEST['supplier_id'], 'supplier_name');

	// delete supplier	
	$query = "DELETE FROM `mbs_suppliers` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) .  "' LIMIT 1";
	$result = mysql_query($query);

	// delete marketing contact
	$queryContact = "DELETE FROM `mbs_suppliers_marketing_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) .  "'";
	$resultContact = mysql_query($queryContact);

	// delete account contact
	$queryAccount = "DELETE FROM `mbs_suppliers_account_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) .  "'";
	$resultAccount = mysql_query($queryAccount);

	// delete territory contact
	$queryTerritory = "DELETE FROM `mbs_suppliers_territory_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) .  "'";
	$resultTerritory = mysql_query($queryTerritory);
	
	$strAlert = 'Supplier named "' . $strSupplierName . '" is successfully deleted!';

	$strLog = 'Supplier named "' . $strSupplierName . '" is successfully deleted.';
						
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
//--> Delete

//--> Email
elseif ($_REQUEST['action'] == "email" && $_REQUEST['supplier_id']) 
{
	// get site config
	$arrSiteConfig = $db->getSiteConfig();
	
	// get supplier name
	$strSupplierName = $db->dbIDToField('mbs_suppliers', 'supplier_id', $_REQUEST['supplier_id'], 'supplier_name');

	// get current user's email
	$strEmailFrom = $db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_email');

	// subject
	$strSubject = "Supplier Data | " . stripslashes($strSupplierName) . " | " . $arrSiteConfig['site_name'];

	// message
	$strMessage = "";

	if ($_REQUEST['frm_message']) 
	{
		$strMessage .= "<p><em>\"" . stripslashes($_REQUEST['frm_message']) . "\"</em></p><br />\n\n";
	}
	
	$strMessage .= file_get_contents($STR_URL . 'supplier_view_print.php?action=print&supplier_id=' . $_REQUEST['supplier_id']);
	

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

		$strLog = 'Supplier named "' . $strSupplierName . '" is successfully sent to "' . strtolower($_REQUEST['frm_email_to']) . '"';
		
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
//--> Email

//--> Email List
elseif ($_REQUEST['action'] == "email_list")
{
	// get site config
	$arrSiteConfig = $db->getSiteConfig();
		
	// get current user's email
	$strEmailFrom = $db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_email');

	// subject
	$strSubject = "Supplier List | " . $arrSiteConfig['site_name'];

	// message
	$strMessage = "";

	if ($_REQUEST['frm_message']) 
	{
		$strMessage .= "<p><em>\"" . stripslashes($_REQUEST['frm_message']) . "\"</em></p><br />\n\n";
	}	

	$strMessage .= file_get_contents($STR_URL . 'supplier_list_print.php?action=print');
	

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

		$strLog = 'Supplier List is successfully sent to "' . strtolower($_REQUEST['frm_email_to']) . '"';
		
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