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

if ($_REQUEST['action'] == 'add-activity')
{
	echo $db->getNextAutoIncrement('mbs_bookings_activities');
}

else
{
	echo "No results found";
}

?>