<?php include('inc/_include.php'); ?>
<?php 
	
	if ($_REQUEST['action'] == "download" && $_REQUEST['booking_id']) 
	{ 

		

		$conn = $db->dbConnect();
			
		$query = "SELECT * FROM `mbs_bookings` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' LIMIT 1";
		$result = mysql_query($query);
					
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
		
			$strFilePath = $row['booking_file_path'] . $row['booking_file_name'];
			$strFileName = $row['booking_file_name'];

			#$strFileName = "Booking " . $row['booking_code'] . " - " . date("Y-m-d-his");

		} // if ($result)


	}


	// Set the time out
	set_time_limit(0);


	$html->downloadFile($strFilePath, $strFileName);

?>