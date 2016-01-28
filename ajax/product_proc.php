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
session_start();

$db = new DB();
$admin = new ADMIN();


//print_r($_REQUEST);
//--> Add
if ($_REQUEST['action'] == "add" && $_REQUEST['frm_product_name'])
{
	

	// filter input	
	if (!$_REQUEST['frm_product_active']) { $_REQUEST['frm_product_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "INSERT INTO `mbs_products` (`product_id`, 
										  `product_code`, 
										  `product_name`, 
										  `product_size`, 
										  `product_normal_retail_price`, 
										  `product_promo_price`, 
										  `product_special_offer_details`, 
										  `product_description`, 
										  `product_active`, 
										  `product_created_date`, 
										  `product_created_by`, 
										  `product_modified_date`, 
										  `product_modified_by`) 

				VALUES (NULL, 
						'" . mysql_real_escape_string($_REQUEST['frm_product_code']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_product_name']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_size_id']) . "', 
						'" . mysql_real_escape_string($_REQUEST['frm_product_normal_retail_price']) . "', 	
						'" . mysql_real_escape_string($_REQUEST['frm_product_promo_price']) . "', 	
						'" . mysql_real_escape_string($_REQUEST['frm_product_special_offer_details']) . "',
						'" . mysql_real_escape_string($_REQUEST['frm_product_name']) . "', 	
						'" . mysql_real_escape_string($_REQUEST['frm_product_active']) . "', 						
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "',
						'" . date('Y-m-d H:i:s') . "', 
						'" . $_SESSION['user']['login_name'] . "')";

	$result = mysql_query($query);
	$intID = mysql_insert_id();


	if ($result)
	{
		$strAlert = '<p>Product named "' . stripslashes($_REQUEST['frm_product_name']); 

		if ($_REQUEST['frm_product_code']) { $strAlert .= ' (Code: ' . stripslashes($_REQUEST['frm_product_code']) . ')'; }

		$strAlert .= '" is successfuly added!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('products', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product_view.php?product_id=". $intID . "&action=view\" title=\"View Product\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('products', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product.php?product_id=" . $intID . "&action=edit\" title=\"Edit Product\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('products', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product.php?product_id=" . $intID . "&action=delete\" title=\"Delete Product\" onclick=\"return confirmDeleteProduct(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('products', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product.php?action=add\" title=\"Add Product\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('products', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product_list.php\" title=\"Product List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Product named "' . stripslashes($_REQUEST['frm_product_name']); 

		if ($_REQUEST['frm_product_code']) { $strLog .= ' (Code: ' . stripslashes($_REQUEST['frm_product_code']) . ')'; }

		$strLog .= '" is successfully added.';
					
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
elseif ($_REQUEST['action'] == "edit" && $_REQUEST['product_id']) 
{
		

	// filter input	
	if (!$_REQUEST['frm_product_active']) { $_REQUEST['frm_product_active'] = "no"; }

	// the query
	$db->dbConnect();
	$query = "UPDATE `mbs_products` SET `product_code` = '" . mysql_real_escape_string($_REQUEST['frm_product_code']) . "', 
										`product_name` = '" . mysql_real_escape_string($_REQUEST['frm_product_name']) . "', 
										`product_size` = '" . mysql_real_escape_string($_REQUEST['frm_size_id']) . "', 
										`product_normal_retail_price` = '" . mysql_real_escape_string($_REQUEST['frm_product_normal_retail_price']) . "', 
										`product_promo_price` = '" . mysql_real_escape_string($_REQUEST['frm_product_promo_price']) . "', 
										`product_special_offer_details` = '" . mysql_real_escape_string($_REQUEST['frm_product_special_offer_details']) . "',   
										`product_description` = '" . mysql_real_escape_string($_REQUEST['frm_product_name']) . "', 
										`product_active` = '" . mysql_real_escape_string($_REQUEST['frm_product_active']) . "', 
										`product_modified_date` = '" . date('Y-m-d H:i:s') . "', 
										`product_modified_by` = '" . $_SESSION['user']['login_name'] . "' 
								WHERE `product_id` = '" . mysql_real_escape_string($_REQUEST['product_id']) . "' 
								LIMIT 1";

	$result = mysql_query($query);
	$intID = $_REQUEST['product_id'];
	

	if ($result)
	{
		$strAlert = '<p>Product named "' . stripslashes($_REQUEST['frm_product_name']);

		if ($_REQUEST['frm_product_code']) { $strAlert .= ' (Code: ' . stripslashes($_REQUEST['frm_product_code']) . ')'; }

		$strAlert .= '" is successfuly updated!</p>';

		$strAlert .= "<br />\n";	

		if ($admin->getModulePrivilege('products', 'view') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product_view.php?product_id=". $intID . "&action=view\" title=\"View Product\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('products', 'edit') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product.php?product_id=" . $intID . "&action=edit\" title=\"Edit Product\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('products', 'delete') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product.php?product_id=" . $intID . "&action=delete\" title=\"Delete Product\" onclick=\"return confirmDeleteProduct(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
					
		$strAlert .= "<br /><br />\n";
					
		if ($admin->getModulePrivilege('products', 'add') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product.php?action=add\" title=\"Add Product\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
		if ($admin->getModulePrivilege('products', 'list') > 0) { $strAlert .= "<a class=\"link_proc\" href=\"product_list.php\" title=\"Product List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
		
		$strLog = 'Product named "' . stripslashes($_REQUEST['frm_product_name']);

		if ($_REQUEST['frm_product_code']) { $strLog .= ' (Code: ' . stripslashes($_REQUEST['frm_product_code']) . ')'; }

		$strLog .= '" is successfully updated.';
					
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

elseif ($_REQUEST['action'] == "delete" && $_REQUEST['product_id'])
{
	
	$strProductName = $db->dbIDToField('mbs_products', 'product_id', $_REQUEST['product_id'], 'product_name');
	$strProductCode = $db->dbIDToField('mbs_products', 'product_id', $_REQUEST['product_id'], 'product_code');

	// delete	
	$query = "DELETE FROM `mbs_products` WHERE `product_id` = '" . mysql_real_escape_string($_REQUEST['product_id']) .  "' LIMIT 1";
	$result = mysql_query($query);
	
	$strAlert = 'Product named "' . $strProductName . ' (Code: ' . $strProductCode . ')" is successfully deleted!';
	$strAlert .= '<br /><br /><a class="btn" href="product_list.php">Back</a>';

	$strLog = 'Product named "' . $strProductName . ' (Code: ' . $strProductCode . ')" is successfully deleted.';
						
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
	echo "<p>The form was not submitted correctly!</p>";
}
	
?>