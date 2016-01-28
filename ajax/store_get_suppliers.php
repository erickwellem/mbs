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


if ($_REQUEST['store_id'])
{

	$db->dbConnect();

	$query = "SELECT `booking_id`, `store_id` FROM `mbs_bookings_activities` WHERE `store_id` <> '' OR `store_id` IS NULL ORDER BY `booking_activity_id`";
	$result = mysql_query($query);
	#echo $query . "<br />";

	if ($result)
	{
		$data = array();
		while ($row = mysql_fetch_assoc($result))
		{
			$arrStoreID = explode(',', $row['store_id']);
			//echo count($arrStoreID) . "<br />";
			for ($i = 0; $i < count($arrStoreID); $i++)
			{
				if ($_REQUEST['store_id'] == 'all')
				{
					
					$data[$row['booking_id']] = array('store_id'=>$arrStoreID[$i], 'booking_id'=>$row['booking_id']);

				}
				
				elseif ($_REQUEST['store_id'] !== 'all' && $_REQUEST['store_id'] == $arrStoreID[$i])
				{
					$data[$row['booking_id']] = array('store_id'=>$arrStoreID[$i], 'booking_id'=>$row['booking_id']);
				}

			}

		}
		#print_r($data);

		if (count($data) > 0) 
		{

			?>
			<!-- Product -->
			<label class="control-label" for="frm_product_id">Product</label>	
				<div class="controls">
							      
					<select name="frm_supplier_id" id="frm_supplier_id" class="input-xlarge">
						<option value="">-- Please select Supplier --</option>
						<option value="all">All Suppliers</option>


			<?php

			foreach ($data as $intBookingID=>$values)
			{
				#echo $data[$i]['booking_activity_id'] . "<br />";
				$queryBooking = "SELECT `supplier_id` FROM `mbs_bookings` WHERE `booking_id` = '" . $intBookingID . "' LIMIT 1";
				$resultBooking = mysql_query($queryBooking);
				#echo $queryProduct . "<br />";
				if ($resultBooking)
				{
					$rowBooking = mysql_fetch_assoc($resultBooking);
					
					echo "\n\t<option value=\"" . $rowBooking['supplier_id'] . "\"";  
					echo ">" . stripslashes($db->dbIDToField('mbs_suppliers', 'supplier_id', $rowBooking['supplier_id'], 'supplier_name')) . "</option>";
				}	
				

			}
			?>
					</select>
				</div>
			<?php
		}

	}

} // if ($_REQUEST['store_id'])	
?>