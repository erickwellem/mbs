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

	$query = "SELECT `booking_activity_id`, `store_id` FROM `mbs_bookings_activities` WHERE `store_id` <> '' OR `store_id` IS NULL ORDER BY `booking_activity_id`";
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
					
					$data[$row['booking_activity_id']] = array('store_id'=>$arrStoreID[$i], 'booking_activity_id'=>$row['booking_activity_id']);

				}
				
				elseif ($_REQUEST['store_id'] !== 'all' && $_REQUEST['store_id'] == $arrStoreID[$i])
				{
					$data[$row['booking_activity_id']] = array('store_id'=>$arrStoreID[$i], 'booking_activity_id'=>$row['booking_activity_id']);
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
							      
					<select name="frm_booking_product_id" id="frm_booking_product_id" class="input-xlarge">
						<option value="">-- Please select Product --</option>
						<option value="all">All Products</option>


			<?php

			foreach ($data as $intBookingActivityID=>$values)
			{
				#echo $data[$i]['booking_activity_id'] . "<br />";
				$queryProduct = "SELECT `booking_product_id`, `booking_product_code`, `booking_product_name` FROM `mbs_bookings_products` WHERE `booking_activity_id` = '" . $intBookingActivityID . "' LIMIT 1";
				$resultProduct = mysql_query($queryProduct);
				#echo $queryProduct . "<br />";
				if ($resultProduct)
				{
					$rowProduct = mysql_fetch_assoc($resultProduct);
					
					echo "\n\t<option value=\"" . $rowProduct['booking_product_id'] . "\"";  
					echo ">" . stripslashes($rowProduct['booking_product_name']) . " (Code: " . stripslashes($rowProduct['booking_product_code']) . ")</option>";
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