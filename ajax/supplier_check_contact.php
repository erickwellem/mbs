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

$db = new DB();

$db->dbConnect();

$intSupplierId = $_REQUEST['supplier_id'];

$rowSupplierAccount = DB::getSupplierAccount($intSupplierId);
if(empty($rowSupplierAccount['supplier_contact_name']) ||
	empty($rowSupplierAccount['supplier_contact_phone_number']) ||
		empty($rowSupplierAccount['supplier_contact_email'])){
	echo json_encode(array("status"=>0));
}else{
	echo json_encode(array("status"=>1));
}
?>