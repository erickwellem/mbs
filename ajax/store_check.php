<?php
include('../config.php');
require_once('../lib/db.php');

$db = new DB();

$db->dbConnect();
$query = "SELECT COUNT(*) FROM `mbs_stores` WHERE `store_name` = '" . trim($_REQUEST['frm_store_name']) . "'";
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