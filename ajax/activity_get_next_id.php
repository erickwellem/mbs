<?php
include('../config.php');
require_once('../lib/db.php');

$db = new DB();

$db->dbConnect();

if ($_REQUEST['action'] == 'add-activity')
{
	echo $db->getNextAutoIncrement('mbs_bookings_activities');
}

else
{
	echo "No results found";
}

?>