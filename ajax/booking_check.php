<?php
include('../config.php');
require_once('../lib/db.php');

$db = new DB();

$db->dbConnect();
$query = "SELECT COUNT(*) FROM `mbs_bookings` WHERE `booking_code` = '" . trim(mysql_real_escape_string($_REQUEST['frm_booking_code'])) . "'";
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