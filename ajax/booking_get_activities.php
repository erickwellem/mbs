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

if ($_REQUEST['booking_id'] && intval($_REQUEST['booking_id']) > 0)
{
	$query = "SELECT * 
		 	  FROM `mbs_bookings_activities` 
		 	  WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' 
		 	  ORDER BY DATE(CONCAT(`booking_activity_year`,'-', `booking_activity_month`,'-','01'))";

	$result = mysql_query($query);
	#echo $query . "<br /><br />";
	if ($result)
	{
		$i = 0;	
		while ($row = mysql_fetch_assoc($result))
		{
			foreach ($row as $key => $value) {
				$data[$i][$key] = $value;
			}
			$i++;
		}
		
		#print_r($data);
		print json_encode($data);
	}
}

else
{
	echo "No results found";
}

?>