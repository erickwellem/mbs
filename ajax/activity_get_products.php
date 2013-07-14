<?php
include('../config.php');
require_once('../lib/db.php');

$db = new DB();


if ($_REQUEST['activity_id'])
{

	$db->dbConnect();

	$query = "SELECT t1.`booking_activity_id`, 
					 t2.`booking_product_id`, 
					 t2.`booking_product_code`, 
					 t2.`booking_product_name` 
			  FROM `mbs_bookings_activities` t1, `mbs_bookings_products` t2
			  WHERE t1.`booking_activity_id` = t2.`booking_activity_id` ";
	
	if ($_REQUEST['activity_id'] !== 'all')
	{
		$query .= " AND t1.`activity_id` = '" . $_REQUEST['activity_id'] . "' ";
	}
	
	$query .= "  ORDER BY `booking_activity_id`";
	
	$result = mysql_query($query);
	echo $query . "<br />";

	if ($result)
	{

		?>
			<!-- Product -->
			<label class="control-label" for="frm_product_id">Product</label>	
				<div class="controls">
							      
					<select name="frm_booking_product_id" id="frm_booking_product_id" class="input-xlarge">
						<option value="">-- Please select Product --</option>
						<option value="all">All Products</option>


						<?php
		
						while ($row = mysql_fetch_assoc($result))
						{
							echo "\n\t<option value=\"" . $row['booking_product_id'] . "\"";  
							echo ">" . stripslashes($row['booking_product_name']) . " (Code: " . stripslashes($row['booking_product_code']) . ")</option>";
						}
		?>	
					</select>
				</div>
		<?php
		
	}

} // if ($_REQUEST['store_id'])	
?>