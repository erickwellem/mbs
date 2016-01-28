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
$query = "SELECT COUNT(*) FROM `modules` WHERE `module_name` = '" . strtolower(trim($_REQUEST['frm_module_name'])) . "'";
$result = mysql_query($query);

if ($result)
{
	$row = mysql_fetch_row($result);
	
	if ($row[0] > 0)
	{
		echo 'no';
	}
	else 
	{
		echo 'yes';
	}
}
	
?>