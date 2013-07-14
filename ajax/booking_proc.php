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


//-- FUNCTIONS
function insertBooking()
{
	global $db, $strSupplierName;
	
	$db->dbConnect();

	$strBookingTotal = $db->getBookingTotal($_REQUEST['booking_id']);
	if (!$strBookingTotal) { $strBookingTotal = "0.00"; }

	$query = "INSERT INTO `mbs_bookings` (`booking_id`, 
										  `supplier_id`, 
										  `booking_code`, 
										  `booking_name`, 
										  `booking_date`, 
										  `booking_supplier_name`, 
										  `booking_supplier_po_ref_number`, 
										  `booking_total`, 
										  `booking_description`, 
										  `booking_file_name`, 
										  `booking_file_path`, 
										  `booking_active`, 
										  `booking_created_date`, 
										  `booking_created_by`, 
										  `booking_modified_date`, 
										  `booking_modified_by`) 

				VALUES (NULL, 
						'" . mysql_real_escape_string($_REQUEST['frm_supplier_id']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_booking_code']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_booking_name']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_booking_date']) . "', 
						'" . mysql_real_escape_string($strSupplierName) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_booking_supplier_po_ref_number']) . "', 
						'" . mysql_real_escape_string($strBookingTotal) . "', 
						'Booking for " . mysql_real_escape_string($strSupplierName) . "', 
						'', 
						'', 
						'" . mysql_real_escape_string($_REQUEST['frm_booking_active']) . "', 
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "',
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "')";

	$result = mysql_query($query);
	$intID = mysql_insert_id();
		
	return $intID;	

} // insertBooking()


function insertBookingActivity($intBookingID)
{
	global $db, $strSizeID, $strStoreID, $strActivityDescription, $strActivityPrice, $strActivityPriceTotal;

	$db->dbConnect();

	$query = "INSERT INTO `mbs_bookings_activities` (`booking_activity_id`, 
													`booking_id`, 
													`activity_id`, 
													`size_id`, 
													`store_id`, 																			
													`booking_activity_year`, 
													`booking_activity_month`, 
													`booking_activity_description`, 
													`booking_activity_price`, 
													`booking_activity_price_total`, 
													`booking_activity_due_date`, 
													`booking_activity_created_date`, 
													`booking_activity_created_by`, 
													`booking_activity_modified_date`, 
													`booking_activity_modified_by`) 
											
											VALUES (NULL,
													'" . $intBookingID . "', 
													'" . mysql_real_escape_string($_REQUEST['frm_activity_id']) . "', 
													" . $strSizeID . ", 
													'" . mysql_real_escape_string($strStoreID) . "', 																			
													'" . mysql_real_escape_string($_REQUEST['frm_booking_activity_year']) . "', 
													'" . mysql_real_escape_string(intval($_REQUEST['frm_booking_activity_month'])) . "', 
													'" . mysql_real_escape_string($strActivityDescription) . "',
													'" . mysql_real_escape_string($strActivityPrice) . "', 
													'" . mysql_real_escape_string($strActivityPriceTotal) . "', 	
													LAST_DAY('" . mysql_real_escape_string($_REQUEST['frm_booking_activity_year']) . "-" . mysql_real_escape_string($_REQUEST['frm_booking_activity_month']) . "-01'), 
													'" . date('Y-m-d H:i:s') . "', 
													'" . $_SESSION['user']['login_name'] . "',
													'" . date('Y-m-d H:i:s') . "', 
													'" . $_SESSION['user']['login_name'] . "');";

	$result = mysql_query($query);
	$intID = mysql_insert_id();
	#echo $query ."<br /><br />";	
	return $intID;	
	
} // insertBookingActivity()


function insertBookingProduct($intBookingID, $intBookingActivityID)
{
	global $db;

	$db->dbConnect();

	$query = "INSERT INTO `mbs_bookings_products` (`booking_product_id`, 
												  `booking_id`, 
												  `booking_activity_id`, 
												  `booking_product_code`, 
												  `booking_product_name`, 																	
												  `booking_product_normal_retail_price`, 
												  `booking_product_promo_price`, 
												  `booking_product_cost_price`, 
												  `booking_product_recommended_retail_price`, 
												  `booking_product_special_offer_details`, 
												  `booking_product_description`, 
												  `booking_product_created_date`, 
												  `booking_product_created_by`, 
												  `booking_product_modified_date`, 
												  `booking_product_modified_by`) 
							
										   VALUES (NULL, 
										   		   '" . $intBookingID . "', 
										   		   '" . $intBookingActivityID . "', 
										   		   '" . mysql_real_escape_string($_REQUEST['frm_booking_product_code']) . "', 
										   		   '" . mysql_real_escape_string($_REQUEST['frm_booking_product_name']) . "', 
										   		   '" . mysql_real_escape_string($_REQUEST['frm_booking_product_normal_retail_price']) . "', 
										   		   '" . mysql_real_escape_string($_REQUEST['frm_booking_product_promo_price']) . "', 
										   		   '" . mysql_real_escape_string($_REQUEST['frm_booking_product_cost_price']) . "', 
										   		   '" . mysql_real_escape_string($_REQUEST['frm_booking_product_recommended_retail_price']) . "', 
										   		   '" . mysql_real_escape_string($_REQUEST['frm_booking_product_special_offer_details']) . "', 
										   		   '" . mysql_real_escape_string($_REQUEST['frm_booking_product_code'] . " " . $_REQUEST['frm_booking_product_name']) . "',
										   		   '" . date('Y-m-d H:i:s') . "', 
												   '" . $_SESSION['user']['login_name'] . "',
												   '" . date('Y-m-d H:i:s') . "', 
												   '" . $_SESSION['user']['login_name'] . "')";	

	$result = mysql_query($query);
	$intID = mysql_insert_id();
		
	return $intID;

} // insertBookingProduct($intBookingID, $intBookingActivityID)


function updateBooking($intBookingID)
{
	global $db, $strSupplierName, $strBookingTotal;

	$db->dbConnect();

	$strBookingTotal = $db->getBookingTotal($intBookingID);

	$query = "UPDATE `mbs_bookings` SET `supplier_id` = '" . mysql_real_escape_string($_REQUEST['frm_supplier_id']) . "', 
										`booking_code` = '" . mysql_real_escape_string($_REQUEST['frm_booking_code']) . "', 
										`booking_name` = '" . mysql_real_escape_string($_REQUEST['frm_booking_name']) . "', 
										`booking_date` = '" . mysql_real_escape_string($_REQUEST['frm_booking_date']) . "', 
										`booking_supplier_name` = '" . mysql_real_escape_string($strSupplierName) . "', 
										`booking_supplier_po_ref_number` = '" . mysql_real_escape_string($_REQUEST['frm_booking_supplier_po_ref_number']) . "', 
										`booking_total` = '" . mysql_real_escape_string($strBookingTotal) . "', 
										`booking_description` = 'Booking for " . mysql_real_escape_string($strSupplierName) . "', 
										`booking_modified_date` = '" . date('Y-m-d H:i:s') . "', 
										`booking_modified_by` = '" . $_SESSION['user']['login_name'] . "' 
								WHERE `booking_id` = '" . mysql_real_escape_string($intBookingID) . "' 
								LIMIT 1";

	$result = mysql_query($query);
	
	if ($result)
	{
		return 1;
	}							

} // updateBooking($intBookingID)


function updateBookingActivity($intBookingActivityID)
{
	global $db, $strSizeID, $strStoreID, $strActivityDescription, $strActivityPrice, $strActivityPriceTotal;

	$db->dbConnect();

	$query = "UPDATE `mbs_bookings_activities` SET `activity_id` = '" . mysql_real_escape_string($_REQUEST['frm_activity_id']) . "', 
												  `size_id` = " . $strSizeID . ", 
												  `store_id` = '" . mysql_real_escape_string($strStoreID) . "', 
												  `booking_activity_year` = '" . mysql_real_escape_string($_REQUEST['frm_booking_activity_year']) . "',  
												  `booking_activity_month` = '" . mysql_real_escape_string(intval($_REQUEST['frm_booking_activity_month'])) . "', 
												  `booking_activity_description` = '" . mysql_real_escape_string($strActivityDescription) . "', 
												  `booking_activity_price` = '" . mysql_real_escape_string($strActivityPrice) . "', 
												  `booking_activity_price_total` = '" . $strActivityPriceTotal . "', 
												  `booking_activity_due_date` = LAST_DAY('" . mysql_real_escape_string($_REQUEST['frm_booking_activity_year']) . "-" . mysql_real_escape_string($_REQUEST['frm_booking_activity_month']) . "-01'), 
												  `booking_activity_modified_date` = '" . date('Y-m-d H:i:s') . "', 
												  `booking_activity_modified_by` = '" . $_SESSION['user']['login_name'] . "'  
										
										    WHERE `booking_activity_id` = '" . mysql_real_escape_string($intBookingActivityID) . "' LIMIT 1";

	$result = mysql_query($query);

	if ($result)
	{
		return 1;
	}


} // updateBookingActivity($intBookingActivityID)


function updateBookingProduct($intBookingProductID)
{
	global $db;

	$db->dbConnect();

	$query = "UPDATE `mbs_bookings_products` SET `booking_product_code` = '" . mysql_real_escape_string($_REQUEST['frm_booking_product_code']) . "', 
												`booking_product_name` = '" . mysql_real_escape_string($_REQUEST['frm_booking_product_name']) . "', 
												`booking_product_normal_retail_price` = '" . mysql_real_escape_string($_REQUEST['frm_booking_product_normal_retail_price']) . "', 
												`booking_product_promo_price` = '" . mysql_real_escape_string($_REQUEST['frm_booking_product_promo_price']) . "', 
												`booking_product_cost_price` = '" . mysql_real_escape_string($_REQUEST['frm_booking_product_cost_price']) . "', 
												`booking_product_recommended_retail_price` = '" . mysql_real_escape_string($_REQUEST['frm_booking_product_recommended_retail_price']) . "', 
												`booking_product_special_offer_details` = '" . mysql_real_escape_string($_REQUEST['frm_booking_product_special_offer_details']) . "', 
												`booking_product_description` = '" . mysql_real_escape_string($_REQUEST['frm_booking_product_code'] . " " . $_REQUEST['frm_booking_product_name']) . "', 
												`booking_product_modified_date` = '" . date('Y-m-d H:i:s') . "', 
												`booking_product_modified_by` = '" . $_SESSION['user']['login_name'] . "' 

											WHERE `booking_product_id` = '" . mysql_real_escape_string($intBookingProductID) . "' LIMIT 1";
	$result = mysql_query($query);

	if ($result)
	{
		return 1;
	}

} // updateBookingProduct($intBookingProductID)


function verifyInput()
{
	if (($_REQUEST['action'] == 'add' || $_REQUEST['action'] == 'edit') && 
		($_REQUEST['child_action'] == 'submit' || $_REQUEST['child_action'] == 'add-activity' || $_REQUEST['child_action'] == 'add-product' || $_REQUEST['child_action'] == 'edit-activity' || $_REQUEST['child_action'] == 'edit-product') && 		
		($_REQUEST['frm_booking_code'] && $_REQUEST['frm_booking_name'] && $_REQUEST['frm_supplier_id'] && $_REQUEST['frm_booking_date'])
	)
	{
		return 1;
	}

	elseif (($_REQUEST['action'] == 'delete' || $_REQUEST['action'] == 'email' || $_REQUEST['action'] == 'upload') && $_REQUEST['booking_id']) 
	{
		return 1;
	}

	elseif ($_REQUEST['action'] == 'email_list')
	{
		return 1;
	}

	else
	{
		return 0;
	}

} // verifyInput()


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


function updateBookingTotal($intBookingID)
{
	global $db;

	$db->dbConnect();

	$strBookingTotal = $db->getBookingTotal($intBookingID);

	$query = "UPDATE `mbs_bookings` SET `booking_total` = '" . $strBookingTotal . "' WHERE `booking_id` = '" . $intBookingID . "' LIMIT 1";
	$result = mysql_query($query);

	if ($result)
	{
		return 1;
	}

} // updateBookingTotal($intBookingID)


function updateBookingActivityDescription($intBookingActivityID)
{
	global $db, $strProductResult;

	$db->dbConnect();

	$strBookingActivityDesc = $db->dbIDToField('mbs_bookings_activities', 'booking_activity_id', $intBookingActivityID, 'booking_activity_description');
	$strBookingActivityDesc .= $strProductResult;

	$query = "UPDATE `mbs_bookings_activities` SET `booking_activity_description` = '" . $strBookingActivityDesc . "' WHERE `booking_activity_id` = '" . $intBookingActivityID . "' LIMIT 1";
	$result = mysql_query($query);	

	if ($result)
	{
		return 1;
	}

} // updateBookingActivityDescription($intBookingActivityID)


function updateActivityGondola()
{
	global $db;

	$db->dbConnect();

	//-- For ID 89 Gondola End (1 Store for 12 months)
	$query = "UPDATE `mbs_bookings_activities` SET `booking_activity_due_date` = LAST_DAY(DATE_ADD(CONCAT(`booking_activity_year`, '-', `booking_activity_month`, '-', '01'), INTERVAL 12 MONTH)) 
						 					   WHERE `activity_id` = 89 ";
	$result = mysql_query($query);	

	if ($result)
	{
		return 1;
	}

} // updateActivityGondola()


function showAlert($strAlertContent, $intBookingID, $intBookingActivityID, $intBookingProductID)
{
	global $admin;

	//-- send the intID back to the form
	$strAlert = '<div id="intID" style="display:none;">' . $intBookingID . ',' . $intBookingActivityID . ',' . $intBookingProductID .'</div>';

	$strAlert .= $strAlertContent;
		
	$strAlert .= "<br />\n";	

	if ($admin->getModulePrivilege('bookings', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"booking_view.php?booking_id=". $intBookingID . "&action=view\" title=\"View Booking\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
	if ($admin->getModulePrivilege('bookings', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"booking.php?booking_id=" . $intBookingID . "&action=edit\" title=\"Edit Booking\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
	if ($admin->getModulePrivilege('bookings', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"booking.php?booking_id=" . $intBookingID . "&action=delete\" title=\"Delete Booking\" onclick=\"return confirmDeleteBooking(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
				
	$strAlert .= "<br /><br />\n";
				
	if ($admin->getModulePrivilege('bookings', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"booking.php?action=add\" title=\"Add Booking\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
	if ($admin->getModulePrivilege('bookings', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"booking_list.php\" title=\"Booking List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
	
	echo $strAlert;

	#echo "<br /><br />CA = " . $_REQUEST['child_action'] . ", AB = " . $_REQUEST['frm_booking_action_btn'] . ", PS = " . $_REQUEST['frm_product_seq'] . ", BID = " . $_REQUEST['frm_booking_id_alt'] . ", BAID = " . $_REQUEST['frm_booking_activity_id_alt'] . ", BPID = " . $_REQUEST['frm_booking_product_id_alt'] . "<br />";
	
}

//-- INPUT
/***************************************************************************************************/
	// filter input
	if ($_REQUEST['frm_booking_date'])
	{
		// convert to yyyy-mm-dd from dd-mm-yyyy
		$arrBookingDate = explode('-', $_REQUEST['frm_booking_date']);
		$_REQUEST['frm_booking_date'] = $arrBookingDate[2] . "-" . $arrBookingDate[1] . "-" . $arrBookingDate[0];

	}

	if (!$_REQUEST['frm_booking_active']) { $_REQUEST['frm_booking_active'] = "yes"; }
	if ($_REQUEST['frm_size_id'] == '0' || $_REQUEST['frm_size_id'] == '') { $strSizeID = "NULL"; } else { $strSizeID = "'" . mysql_real_escape_string($_REQUEST['frm_size_id']) . "'"; }
	$strSupplierName = $db->dbIDToField('mbs_suppliers', 'supplier_id', $_REQUEST['frm_supplier_id'], 'supplier_name');

	// get activity description/name
	$strActivityDescription = "<strong>" . DB::dbIDToField('mbs_activities', 'activity_id', $_REQUEST['frm_activity_id'], 'activity_name') . "</strong>";
	$strActivityPrice = floatval($_REQUEST['frm_booking_activity_price']);
	
	$strActivityStatus = $strActivityDescription;

	if ($strActivityPrice) { $strActivityDescription .= " @ $" . number_format($strActivityPrice, 2) . " "; }


	//-- filter the store id, make sure the input is clean n,n,n format
	$arrStoreID = explode(',', $_REQUEST['frm_store_id']);
			
	if (is_array($arrStoreID) && count($arrStoreID) > 0) 
	{
		$strStoreID = "";	
		for ($i = 0; $i < count($arrStoreID); $i++)
		{
			if ($arrStoreID[$i] && (strlen($arrStoreID[$i]) > 0) && $arrStoreID[$i] !== '0' && $arrStoreID[$i] !== 'undefined')
			{
				$strStoreID .= $arrStoreID[$i];

				if ($i == count($arrStoreID)-1) { $strStoreID .= ""; } else { $strStoreID .= ","; }
			}
		}

		if (substr($strStoreID, -1) == ",") { $strStoreID = substr_replace($strStoreID, '', -1); }
	}

	//-- set activity description related to store ID's
	if ($strStoreID) 
	{ 
		$strStoreIDConv = explode(',', $strStoreID); 

		$strStoreResult = "";
		if (count($strStoreIDConv) > 0)
		{
			$strStoreResult .= " for ";  
			if (count($strStoreIDConv) > 1) { $strStoreResult .= count($strStoreIDConv) . " stores: "; } else { $strStoreResult .= " store: "; }
			for ($i = 0; $i < count($strStoreIDConv); $i++)
			{
				$strStoreResult .= $db->dbIDToField('mbs_stores', 'store_id', $strStoreIDConv[$i], 'store_name');
				
				if ($i == (count($strStoreIDConv)-2)) { $strStoreResult.= " and "; } elseif ($i == (count($strStoreIDConv)-1)) { $strStoreResult.= ""; } else { $strStoreResult.= ", "; }
			}
		}
		
	}

	//-- Activity description for Store results
	if ($strStoreResult) { $strActivityDescription .= $strStoreResult . ". "; }


	//-- Set total price
	if ($strStoreID && count($strStoreID) > 0) 
	{  
		$strActivityPriceTotal = count($strStoreIDConv) * $strActivityPrice;
	} 

	else 
	{ 
		$strActivityPriceTotal = $strActivityPrice; 
	}

	//-- Set activity description related to product
	$strProductResult = "";
	if ($_REQUEST['frm_booking_product_code'] || $_REQUEST['frm_booking_product_name'])
	{
		$strProductResult .= "<br /><br />\n\n<em><u>Product Detail:</u></em>";
		if ($_REQUEST['frm_booking_product_code']) { $strProductResult .= "<br />\nUPI Code: " . $_REQUEST['frm_booking_product_code'] . ", "; }		
		if ($_REQUEST['frm_booking_product_name']) { $strProductResult .= "<br />\nName: " . $_REQUEST['frm_booking_product_name'] . ", "; }
		if ($_REQUEST['frm_booking_product_normal_retail_price']) { $strProductResult .= "<br />\nNormal Retail Price: $" . $_REQUEST['frm_booking_product_normal_retail_price'] . ", "; }
		if ($_REQUEST['frm_booking_product_promo_price']) { $strProductResult .= "<br />\nPromo Price: $" . $_REQUEST['frm_booking_product_promo_price'] . ", "; }
		if ($_REQUEST['frm_booking_product_cost_price']) { $strProductResult .= "<br />\nCost Price: $" . $_REQUEST['frm_booking_product_cost_price'] . ", "; }
		if ($_REQUEST['frm_booking_product_recommended_retail_price']) { $strProductResult .= "<br />\nRRP: $" . $_REQUEST['frm_booking_product_recommended_retail_price'] . ", "; }
		if ($_REQUEST['frm_booking_product_special_offer_details']) { $strProductResult .= "<br />\nSpecial Offer: " . $_REQUEST['frm_booking_product_special_offer_details'] . ""; }
	}

	//-- Activity description for Product results
	if ($strProductResult) { $strActivityDescription .= $strProductResult; }


	//-- Product Name / Code
	$strBookingProduct = stripslashes($_REQUEST['frm_booking_product_name']) . " (" . $_REQUEST['frm_booking_product_code'] . ")";

	//-- Activity Name
	$strBookingActivity = strip_tags($strActivityStatus);

	//-- Booking Code
	$strBookingCode = stripslashes($_REQUEST['frm_booking_code']);

/***************************************************************************************************/
//-- INPUT


//-- ACTIONS
/***************************************************************************************************/
if (verifyInput() > 0)
{
	//--> Add
	if ($_REQUEST['action'] == 'add')
	{
		if ($_REQUEST['child_action'] == 'add-product')
		{
			if (intval($_REQUEST['frm_product_seq']) == 1)
			{
				$intBookingID = insertBooking();

				if (!$intBookingID || $intBookingID == 0) { $intBookingID = $db->dbFieldToID('mbs_bookings', 'booking_code', $strBookingCode, 'booking_id'); }

				$intBookingActivityID = insertBookingActivity($intBookingID);				
				$intBookingProductID = insertBookingProduct($intBookingID, $intBookingActivityID);
				
			} // if ($_REQUEST['frm_product_seq'] == 1)

			else
			{
				
				if (intval($_REQUEST['frm_booking_id_alt']) > 0) { $intBookingID = intval($_REQUEST['frm_booking_id_alt']); } else { $intBookingID = $db->dbFieldToID('mbs_bookings', 'booking_code', $strBookingCode, 'booking_id'); }
				if (intval($_REQUEST['frm_booking_activity_id_alt']) > 0) { $intBookingActivityID = intval($_REQUEST['frm_booking_activity_id_alt']); } else { $intBookingActivityID = insertBookingActivity($intBookingID); } 
				$intBookingProductID = insertBookingProduct($intBookingID, $intBookingActivityID);				
				updateBookingActivityDescription($intBookingActivityID);
			
			}

			$strAlertContent = 'Product <strong>"' . $strBookingProduct . '"</strong> is successfully added to Activity <strong>"' . $strBookingActivity . '"</strong> in Booking <strong>"' . $strBookingCode . '"</strong>!';
			$strLog = 'Product "' . $strBookingProduct . '" is successfully added to Activity "' . $strBookingActivity . '" in Booking "' . $strBookingCode . '"';
			
		} // if ($_REQUEST['child_action'] == 'add-product')

		elseif ($_REQUEST['child_action'] == 'add-activity')
		{
			if (intval($_REQUEST['frm_booking_id_alt']) > 0) { $intBookingID = intval($_REQUEST['frm_booking_id_alt']); } else { $intBookingID = $db->dbFieldToID('mbs_bookings', 'booking_code', $strBookingCode, 'booking_id'); }
			$intBookingActivityID = insertBookingActivity($intBookingID);
			$intBookingProductID = insertBookingProduct($intBookingID, $intBookingActivityID);
			
			$strAlertContent = 'Activity <strong>"' . $strBookingActivity . '"</strong> is successfully added to Booking <strong>"' . $strBookingCode . '"</strong>!';
			$strLog = 'Activity "' . $strBookingActivity . '" is successfully add in Booking "' . $strBookingCode . '"';

		} // elseif ($_REQUEST['child_action'] == 'add-activity')

		else
		{
			$intBookingID = insertBooking();

			if (!$intBookingID || $intBookingID == 0) { $intBookingID = $db->dbFieldToID('mbs_bookings', 'booking_code', $strBookingCode, 'booking_id'); }

			$intBookingActivityID = insertBookingActivity($intBookingID);				
			$intBookingProductID = insertBookingProduct($intBookingID, $intBookingActivityID);	

			$strAlertContent = 'Booking <strong>"' . $strBookingCode . '"</strong> for <strong>"' . $strSupplierName . '"</strong> is successfully added!';
			$strLog = 'Booking "' . $strBookingCode . '" for "' . $strSupplierName . '" is successfully added';
			
		}

		updateBookingTotal($intBookingID);
		updateActivityGondola();
		insertLog($strLog);
		showAlert($strAlertContent, $intBookingID, $intBookingActivityID, $intBookingProductID);

	} //-- Add, if ($_REQUEST['action'] == 'add')

	//-- Edit
	elseif ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id'])
	{

		if ($_REQUEST['child_action'] == 'add-product')
		{
			if (intval($_REQUEST['frm_product_seq']) == 1)
			{
				$intBookingID = $_REQUEST['booking_id'];

				if (!$intBookingID || $intBookingID == 0) { $intBookingID = $db->dbFieldToID('mbs_bookings', 'booking_code', $strBookingCode, 'booking_id'); }

				updateBookingActivity($intBookingID);
				
				$intBookingActivityID = $_REQUEST['booking_activity_id'];
				$intBookingProductID = insertBookingProduct($intBookingID, $intBookingActivityID);
				
			} // if ($_REQUEST['frm_product_seq'] == 1)

			else
			{
				
				if (intval($_REQUEST['booking_id']) > 0) { $intBookingID = intval($_REQUEST['booking_id']); } else { $intBookingID = $db->dbFieldToID('mbs_bookings', 'booking_code', $strBookingCode, 'booking_id'); }
				if (intval($_REQUEST['booking_activity_id']) > 0) { $intBookingActivityID = intval($_REQUEST['booking_activity_id']); } else { $intBookingActivityID = insertBookingActivity($intBookingID); } 
				$intBookingProductID = insertBookingProduct($intBookingID, $intBookingActivityID);				
				updateBookingActivityDescription($intBookingActivityID);				
			
			}

		}	// if ($_REQUEST['child_action'] == 'add-product')	

		elseif ($_REQUEST['child_action'] == 'add-activity') 
		{
			# code...

		} // elseif ($_REQUEST['child_action'] == 'add-activity') 
		
		elseif ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id'])
		{
			$intBookingID = intval($_REQUEST['booking_id']);
			$intBookingActivityID = intval($_REQUEST['booking_activity_id']);
			$intBookingProductID = intval($_REQUEST['booking_product_id']);

			updateBooking($intBookingID);
			updateBookingActivity($intBookingActivityID);
			updateBookingProduct($intBookingProductID);

			updateBookingActivityDescription($intBookingActivityID);

			$strAlertContent = 'Booking <strong>"' . $strBookingCode . '"</strong> for <strong>"' . $strSupplierName . '"</strong> is successfully updated!';
			$strLog = 'Booking "' . $strBookingCode . '" for "' . $strSupplierName . '" is successfully updated';

		} // elseif ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id'])
				
		
		updateBookingTotal($intBookingID);
		updateActivityGondola();
		insertLog($strLog);
		showAlert($strAlertContent, $intBookingID, $intBookingActivityID, $intBookingProductID);
		

	} //--> Edit

	//--> Delete
	elseif ($_REQUEST['action'] == 'delete' && $_REQUEST['booking_id'])
	{
		
		$strBookingCode = $db->dbIDToField('mbs_bookings', 'booking_id', $_REQUEST['booking_id'], 'booking_code');

		if ($_REQUEST['booking_activity_id'])
		{
			// delete booking activities
			$queryActivities = "DELETE FROM `mbs_bookings_activities` WHERE `booking_activity_id` = '" . mysql_real_escape_string($_REQUEST['booking_activity_id']) .  "' LIMIT 1";
			$resultActivities = mysql_query($queryActivities);

			// delete booking activity products
			$queryProduct = "DELETE FROM `mbs_bookings_products` WHERE `booking_activity_id` = '" . mysql_real_escape_string($_REQUEST['booking_activity_id']) .  "'";
			$resultProduct = mysql_query($queryProduct);
			
			$strAlert = 'Booking activity for "' . $strBookingCode . '" is successfully deleted!';
			#$strAlert .= '<br /><br /><a class="btn" href="booking_list.php">Back</a>';

			$strLog = 'Booking activity for "' . $strBookingCode . '" is successfully deleted.';

		}

		else
		{

			// delete booking	
			$query = "DELETE FROM `mbs_bookings` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) .  "' LIMIT 1";
			$result = mysql_query($query);

			// delete booking activities
			$queryActivities = "DELETE FROM `mbs_bookings_activities` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) .  "'";
			$resultActivities = mysql_query($queryActivities);

			// delete booking activity products
			$queryAccount = "DELETE FROM `mbs_bookings_products` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) .  "'";
			$resultAccount = mysql_query($queryAccount);
			
			$strAlert = 'Booking "' . $strBookingCode . '" is successfully deleted!';
			#$strAlert .= '<br /><br /><a class="btn" href="booking_list.php">Back</a>';

			$strLog = 'Booking "' . $strBookingCode . '" is successfully deleted.';

		}


		insertLog($strLog);

		
		echo $strAlert;
				
	}
	//--> Delete

	//--> Email
	elseif ($_REQUEST['action'] == 'email' && $_REQUEST['booking_id']) 
	{
		// get site config
		$arrSiteConfig = $db->getSiteConfig();
		
		// get supplier ID
		$intSupplierID = $db->dbIDToField('mbs_bookings', 'booking_id', $_REQUEST['booking_id'], 'supplier_id');

		// get supplier name
		$strSupplierName = $db->dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');

		// get booking code
		$strBookingCode = $db->dbIDToField('mbs_bookings', 'booking_id', $_REQUEST['booking_id'], 'booking_code');

		// get current user's email
		$strEmailFrom = $db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_email');

		// subject
		$strSubject = "Booking " . $strBookingCode . " | " . stripslashes($strSupplierName) . " | " . $arrSiteConfig['site_name'];

		// message
		$strMessage = "";

		if ($_REQUEST['frm_message']) 
		{
			$strMessage .= "<p><em>\"" . stripslashes(htmlspecialchars($_REQUEST['frm_message'])) . "\"</em></p><br />\n\n";
		}

		$strMessage .= file_get_contents($STR_URL . 'booking_view_print.php?action=print&booking_id=' . $_REQUEST['booking_id']);
		

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


			$strLog = 'Booking "' . $strBookingCode . '" is successfully sent to "' . strtolower($_REQUEST['frm_email_to']) . '"';
			
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

	//--> Email List
	elseif ($_REQUEST['action'] == 'email_list')
	{
		// get site config
		$arrSiteConfig = $db->getSiteConfig();
			
		// get current user's email
		$strEmailFrom = $db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_email');

		// subject
		$strSubject = "Booking List | " . $arrSiteConfig['site_name'];

		// message
		$strMessage = "";

		if ($_REQUEST['frm_message']) 
		{
			$strMessage .= "<p><em>\"" . stripslashes(htmlspecialchars($_REQUEST['frm_message'])) . "\"</em></p><br />\n\n";
		}

		$strMessage .= file_get_contents($STR_URL . 'booking_list_print.php?action=print');
		

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

			$strLog = 'Booking List is successfully sent to "' . strtolower($_REQUEST['frm_email_to']) . '"';
			
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
	//--> Email List

	//--> Upload
	elseif ($_REQUEST['action'] == 'upload' && $_REQUEST['booking_id'])
	{
		
		// get supplier ID
		$intSupplierID = $db->dbIDToField('mbs_bookings', 'booking_id', intval($_REQUEST['booking_id']), 'supplier_id');

		// get supplier name
		$strSupplierName = $db->dbIDToField('mbs_suppliers', 'supplier_id', intval($intSupplierID), 'supplier_name');

		// get booking code
		$strBookingCode = $db->dbIDToField('mbs_bookings', 'booking_id', intval($_REQUEST['booking_id']), 'booking_code');

		// get file path
		$strFilePath = "uploads/" . date('Y') . "/" . date('m') . "/";

		// get file name
		//$strFileName = $db->dbIDToField('mbs_bookings', 'booking_id', $_REQUEST['booking_id'], 'booking_file_name');
		$strFileName = 	str_replace("C:\\fakepath\\", "", $_REQUEST['frm_file']);

		$query = "UPDATE `mbs_bookings` SET `booking_file_path` = '" . $strFilePath . "', `booking_file_name` = '" . $strFileName . "' WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' LIMIT 1";	
		$result = mysql_query($query);

		if ($result)
		{

			echo 'File "' . $strFileName . '" is successfully uploaded as attachment for Booking "' . $strBookingCode . '"';

			$strLog = 'Upload file for Booking "' . $strBookingCode . '" is successful.';			
			
			insertLog($strLog);
		}

	}
	//--> Upload

	

} // if (verifyInput() > 0)

else 
{
	echo "<p>The form was not submitted correctly!</p>";
}	

?>