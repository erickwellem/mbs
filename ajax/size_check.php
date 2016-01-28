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
$query = "SELECT COUNT(*) FROM `mbs_sizes` WHERE `size_name` = '" . trim($_REQUEST['frm_size_name']) . "'";
$result = mysql_query($query);

if ($result)
{
	$row = mysql_fetch_row($result);
	
	if ($row[0] > 0)
	{
		echo "no";
	}

	else 
	{
		echo "yes";
	}
}
	
?>