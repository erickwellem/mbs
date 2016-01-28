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
$strTextOption = $_REQUEST['text'];
$strYear = $_REQUEST['year'];
$strMonth = $_REQUEST['month'];

$status = DB::checkSupplierCatalogueStock($intSupplierId, $strTextOption, $strYear, $strMonth);

if($status){
	echo json_encode(array("status"=>1));
}else{
	echo json_encode(array("status"=>0));
}
?>