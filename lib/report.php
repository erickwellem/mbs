<?php
/*************************************************************
 * REPORT Class
 * @Author		: Erick Wellem (me@erickwellem.com)
 * @Date		: May 7, 2013
 * @Description	: class for report html and layout
 ************************************************************/

class REPORT {

	function showReportStoreToBookingProduct($intStoreID, $intProductID)
	{
		
		DB::dbConnect();

		$query = "SELECT * FROM `mbs_stores` ";

		if ($intStoreID !== 'all') { $query .= " WHERE `store_id` = '" . mysql_real_escape_string($intStoreID) . "' "; }

		$query .= " ORDER BY `store_name`";
		$result = mysql_query($query);

		if ($result)
		{
			while($row = mysql_fetch_assoc($result))
			{
				?>

				<div style="text-align:center;clear:both;"><h3 style="font-size:1.3em;">Store: <?php echo stripslashes($row['store_name']); ?></h3></div>
				<table class="table table-bordered table-hover">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:right;width:10%;"><strong>No</strong></th>
					  	<th style="text-align:center;width:20%;"><strong>Product Code</strong></th>
					  	<th style="text-align:center;"><strong>Product Name</strong></th>
					  	<th style="text-align:center;"><strong>Booking Code</strong></th>
					  	<th style="text-align:center;"><strong>Activity</strong></th>
					</tr>			  
					</thead>
					<tbody>
						<?php
							$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
														   `mbs_bookings_activities` t2 
													 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` ";
							
							if ($intProductID !== 'all') { $queryProduct .= " AND t1.`booking_product_id` = '" . mysql_real_escape_string($intProductID) . "' "; }

							$queryProduct .= " ORDER BY t1.`booking_product_name`";
							$resultProduct = mysql_query($queryProduct);

							if ($resultProduct)
							{
								$i = 0;
								while ($rowProduct = mysql_fetch_assoc($resultProduct))
								{
									if (preg_match("/^" . $row['store_id'] . ",/", $rowProduct['store_id']) > 0 || 
										preg_match("/," . $row['store_id'] . ",/", $rowProduct['store_id']) > 0 || 
										preg_match("/," . $row['store_id'] . "$/", $rowProduct['store_id']) > 0 || 
										preg_match("/^" . $row['store_id'] . "$/", $rowProduct['store_id']) > 0) 
									{

									$i++;
									?>
									<tr>
										<td><div style="text-align:right;"><?php echo $i; ?></div></td>	
										<td><div style="text-align:left;"><?php echo $rowProduct['booking_product_code']; ?></div></td>	
										<td><div style="text-align:left;"><?php echo $rowProduct['booking_product_name']; ?></div></td>	
										<td><div style="text-align:center;"><a href="booking_view.php?booking_id=<?php echo $rowProduct['booking_id']; ?>&action=view"><?php echo DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'booking_code'); ?></a></div></td>
										<td><div style="text-align:left;"><a href="booking_view.php?booking_id=<?php echo $rowProduct['booking_id']; ?>&action=view#id<?php echo $rowProduct['booking_activity_id']; ?>"><?php echo DB::dbIDToField('mbs_activities', 'activity_id', $rowProduct['activity_id'], 'activity_name'); ?></a></div></td>	
									</tr>	
									<?php

									} // if (preg_match())

								} // while ($rowProduct = mysql_fetch_assoc($resultProduct))
							
							} // if ($resultProduct)

							
						?>	
					</tbody>
					<tfoot>
					</tfoot>					
				</table>	

				<?php
			} // while($row = mysql_fetch_assoc($result))
		} // if ($result)

		echo "<div class='well' style=''>";
		echo "<strong>Debug:</strong><br />";
		echo $query . "<br />";
		echo $queryProduct . "<br />";
		print_r($_REQUEST);
		echo "</div>";
		
	} // function showReportBoookingProduct($intProductID)


	function showReportStoreToSupplier($intStoreID, $intSupplierID)
	{
		DB::dbConnect();

		$query = "SELECT * FROM `mbs_stores` ";

		if ($intStoreID !== 'all') { $query .= " WHERE `store_id` = '" . mysql_real_escape_string($intStoreID) . "' "; }

		$query .= " ORDER BY `store_name`";
		$result = mysql_query($query);

		if ($result)
		{
			while($row = mysql_fetch_assoc($result))
			{
				?>

				<div style="text-align:center;clear:both;"><h3 style="font-size:1.3em;">Store: <?php echo stripslashes($row['store_name']); ?></h3></div>
				<table class="table table-bordered table-hover">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:right;width:10%;"><strong>No</strong></th>
					  	<th style="text-align:center;width:20%;"><strong>Supplier Name</strong></th>
					  	<th style="text-align:center;"><strong>Contacts Number / Email</strong></th>
					  	<th style="text-align:center;"><strong>Booking Code</strong></th>
					  	<th style="text-align:center;"><strong>Activity</strong></th>
					</tr>			  
					</thead>
					<tbody>
						<?php
							$querySupplier = "SELECT * FROM `mbs_bookings` t1, 
														    `mbs_bookings_activities` t2 
													 WHERE t1.`booking_id` = t2.`booking_id` ";
							
							if ($intSupplierID !== 'all') { $querySupplier .= " AND t1.`supplier_id` = '" . mysql_real_escape_string($intSupplierID) . "' "; }

							$querySupplier .= " GROUP BY t1.`supplier_id` ";
							$querySupplier .= " ORDER BY t1.`booking_id`";
							$resultSupplier = mysql_query($querySupplier);

							if ($resultSupplier)
							{
								$i = 0;
								while ($rowSupplier = mysql_fetch_assoc($resultSupplier))
								{
									
									$i++;
									?>
									<tr>
										<td><div style="text-align:right;"><?php echo $i; ?></div></td>	
										<td><div style="text-align:left;"><?php echo stripslashes(DB::dbIDToField('mbs_suppliers', 'supplier_id', $rowSupplier['supplier_id'], 'supplier_name')); ?></div></td>	
										<td><div style="text-align:left;"><?php echo stripslashes(DB::dbIDToField('mbs_suppliers', 'supplier_id', $rowSupplier['supplier_id'], 'supplier_phone_number'));  ?> / <?php echo stripslashes(DB::dbIDToField('mbs_suppliers', 'supplier_id', $rowSupplier['supplier_id'], 'supplier_email'));  ?></div></td>	
										<td><div style="text-align:center;"><a href="booking_view.php?booking_id=<?php echo $rowSupplier['booking_id']; ?>&action=view"><?php echo DB::dbIDToField('mbs_bookings', 'booking_id', $rowSupplier['booking_id'], 'booking_code'); ?></a></div></td>
										<td><div style="text-align:left;"><a href="booking_view.php?booking_id=<?php echo $rowSupplier['booking_id']; ?>&action=view#id<?php echo $rowSupplier['booking_activity_id']; ?>"><?php echo DB::dbIDToField('mbs_activities', 'activity_id', $rowSupplier['activity_id'], 'activity_name'); ?></a></div></td>	
									</tr>	
									<?php


								} // while ($rowSupplier = mysql_fetch_assoc($resultSupplier))
							
							} // if ($resultSupplier)

							
						?>	
					</tbody>
					<tfoot>
					</tfoot>					
				</table>	

				<?php
			} // while($row = mysql_fetch_assoc($result))
		} // if ($result)

		echo "<div class='well' style=''>";
		echo "<strong>Debug:</strong><br />";
		echo $query . "<br />";
		echo $querySupplier . "<br />";
		print_r($_REQUEST);
		echo "</div>";
	
	} // showReportStoreToSupplier()


	function showReportStoreToSize($intStoreID, $intSizeID)
	{
		DB::dbConnect();

		$query = "SELECT * FROM `mbs_stores` ";

		if ($intStoreID !== 'all') { $query .= " WHERE `store_id` = '" . mysql_real_escape_string($intStoreID) . "' "; }

		$query .= " ORDER BY `store_name`";
		$result = mysql_query($query);

		if ($result)
		{
			while($row = mysql_fetch_assoc($result))
			{
				?>

				<div style="text-align:center;clear:both;"><h3 style="font-size:1.3em;">Store: <?php echo stripslashes($row['store_name']); ?></h3></div>
				<table class="table table-bordered table-hover">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:right;width:10%;"><strong>No</strong></th>
					  	<th style="text-align:center;width:20%;"><strong>Spot</strong></th>
					  	<th style="text-align:center;"><strong>Description</strong></th>
					  	<th style="text-align:center;"><strong>Booking Code</strong></th>
					  	<th style="text-align:center;"><strong>Activity</strong></th>
					</tr>			  
					</thead>
					<tbody>
						<?php
							$querySize = "SELECT * FROM `mbs_bookings` t1, 
														`mbs_bookings_activities` t2 
													 WHERE t1.`booking_id` = t2.`booking_id` 
													 AND t2.`size_id` IS NOT NULL ";
							
							if ($intSizeID !== 'all') { $querySize .= " AND t2.`size_id` = '" . mysql_real_escape_string($intSizeID) . "' "; }

							$querySize .= " GROUP BY t2.`size_id` ";
							$querySize .= " ORDER BY t1.`booking_id`";
							$resultSize = mysql_query($querySize);

							if ($resultSize)
							{
								$i = 0;
								while ($rowSize = mysql_fetch_assoc($resultSize))
								{
									
									$i++;
									?>
									<tr>
										<td><div style="text-align:right;"><?php echo $i; ?></div></td>	
										<td><div style="text-align:left;"><?php echo stripslashes(DB::dbIDToField('mbs_sizes', 'size_id', $rowSize['size_id'], 'size_name')); ?></div></td>	
										<td><div style="text-align:left;"><?php echo stripslashes(DB::dbIDToField('mbs_sizes', 'size_id', $rowSize['size_id'], 'size_description'));  ?></div></td>	
										<td><div style="text-align:center;"><a href="booking_view.php?booking_id=<?php echo $rowSize['booking_id']; ?>&action=view"><?php echo DB::dbIDToField('mbs_bookings', 'booking_id', $rowSize['booking_id'], 'booking_code'); ?></a></div></td>
										<td><div style="text-align:left;"><a href="booking_view.php?booking_id=<?php echo $rowSize['booking_id']; ?>&action=view#id<?php echo $rowSize['booking_activity_id']; ?>"><?php echo DB::dbIDToField('mbs_activities', 'activity_id', $rowSize['activity_id'], 'activity_name'); ?></a></div></td>	
									</tr>	
									<?php


								} // while ($rowSize = mysql_fetch_assoc($resultSize))
							
							} // if ($resultSize)

							
						?>	
					</tbody>
					<tfoot>
					</tfoot>					
				</table>	

				<?php
			} // while($row = mysql_fetch_assoc($result))
		} // if ($result)

		echo "<div class='well' style=''>";
		echo "<strong>Debug:</strong><br />";
		echo $query . "<br />";
		echo $querySize . "<br />";
		print_r($_REQUEST);
		echo "</div>";
	
	} // showReportStoreToSize()


	function showReportStoreToDollar($intStoreID, $intDollarID)
	{
		DB::dbConnect();

		$query = "SELECT * FROM `mbs_stores` ";

		if ($intStoreID !== 'all') { $query .= " WHERE `store_id` = '" . mysql_real_escape_string($intStoreID) . "' "; }

		$query .= " ORDER BY `store_name`";
		$result = mysql_query($query);

		if ($result)
		{
			//-- Dollar range
			/* <option value="all">All Dollars</option>
				<option value="1">Below $500</option>
				<option value="2">$500 to $1,000</option>
				<option value="3">$1,000 to $5,000</option>
				<option value="4">$5,000 to $10,000</option>
				<option value="5">Above $10,000</option>
			*/

			while($row = mysql_fetch_assoc($result))
			{
				?>

				<div style="text-align:center;clear:both;"><h3 style="font-size:1.3em;">Store: <?php echo stripslashes($row['store_name']); ?></h3></div>
				<table class="table table-bordered table-hover">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:right;width:10%;"><strong>No</strong></th>
					  	<th style="text-align:center;width:20%;"><strong>Amount</strong></th>					  	
					  	<th style="text-align:center;"><strong>Booking Code</strong></th>
					  	<!--<th style="text-align:center;"><strong>Activity</strong></th>-->
					</tr>			  
					</thead>
					<tbody>
						<?php
							$queryPrice = "SELECT * FROM `mbs_bookings` t1, 
														 `mbs_bookings_activities` t2 
												   WHERE t1.`booking_id` = t2.`booking_id` ";
							
							if ($intDollarID !== 'all') 
							{ 
								switch ($intDollarID) {
									case '1':
										#$queryPrice .= " AND t2.`booking_activity_price` < 500 ";
										$queryPrice .= " AND t1.`booking_total` < 500 "; 
										break;

									case '2':
										#$queryPrice .= " AND (t2.`booking_activity_price` BETWEEN 1000 AND 5000)"; 
										$queryPrice .= " AND (t1.`booking_total` BETWEEN 1000 AND 5000)"; 
										break;

									case '3':
										#$queryPrice .= " AND (t2.`booking_activity_price` BETWEEN 500 AND 1000)"; 
										$queryPrice .= " AND (t1.`booking_total` BETWEEN 500 AND 1000)"; 
										break;		

									case '4':
										#$queryPrice .= " AND (t2.`booking_activity_price` BETWEEN 1000 AND 5000)"; 
										$queryPrice .= " AND (t1.`booking_total` BETWEEN 1000 AND 5000)"; 
										break;	
									
									case '5':
										#$queryPrice .= " AND (t2.`booking_activity_price` > 10000)"; 
										$queryPrice .= " AND (t1.`booking_total` > 10000)"; 
										break;

									default:
										$queryPrice .= "";
										break;
								}
								
							}
							
							#$queryPrice .= " GROUP BY t2.`booking_activity_price` ";
							$queryPrice .= " GROUP BY t1.`booking_total` ";

							#$queryPrice .= " ORDER BY t2.`booking_activity_price` DESC";
							$queryPrice .= " ORDER BY t1.`booking_total` DESC";
							$resultPrice = mysql_query($queryPrice);

							if ($resultPrice)
							{
								$i = 0;
								while ($rowPrice = mysql_fetch_assoc($resultPrice))
								{
									
									$i++;
									?>
									<tr>
										<td><div style="text-align:right;"><?php echo $i; ?></div></td>	
										<td><div style="text-align:left;">$<?php echo number_format($rowPrice['booking_total'], 2); ?></div></td>											
										<td><div style="text-align:center;"><a href="booking_view.php?booking_id=<?php echo $rowPrice['booking_id']; ?>&action=view"><?php echo DB::dbIDToField('mbs_bookings', 'booking_id', $rowPrice['booking_id'], 'booking_code'); ?></a></div></td>
										<!--<td><div style="text-align:left;"><a href="booking_view.php?booking_id=<?php echo $rowPrice['booking_id']; ?>&action=view#id<?php echo $rowPrice['booking_activity_id']; ?>"><?php echo DB::dbIDToField('mbs_activities', 'activity_id', $rowPrice['activity_id'], 'activity_name'); ?></a></div></td>-->	
									</tr>	
									<?php


								} // while ($rowSize = mysql_fetch_assoc($resultSize))
							
							} // if ($resultSize)

							
						?>	
					</tbody>
					<tfoot>
					</tfoot>					
				</table>	

				<?php
			} // while($row = mysql_fetch_assoc($result))
		} // if ($result)

		echo "<div class='well' style=''>";
		echo "<strong>Debug:</strong><br />";
		echo $query . "<br />";
		echo $queryPrice . "<br />";
		print_r($_REQUEST);
		echo "</div>";

	} // showReportStoreToDollar()


	function showReportStoreToMonth($intStoreID, $strMonth)
	{
		DB::dbConnect();

		$query = "SELECT * FROM `mbs_stores` ";

		if ($intStoreID !== 'all') { $query .= " WHERE `store_id` = '" . mysql_real_escape_string($intStoreID) . "' "; }

		$query .= " ORDER BY `store_name`";
		$result = mysql_query($query);

		if ($result)
		{
			
			while($row = mysql_fetch_assoc($result))
			{
				?>

				<div style="text-align:center;clear:both;"><h3 style="font-size:1.3em;">Store: <?php echo stripslashes($row['store_name']); ?></h3></div>
				<table class="table table-bordered table-hover">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:right;width:10%;"><strong>No</strong></th>
					  	<th style="text-align:center;width:20%;"><strong>Month</strong></th>					  	
					  	<th style="text-align:center;"><strong>Booking Code</strong></th>
					  	<th style="text-align:center;"><strong>Activity</strong></th>
					</tr>			  
					</thead>
					<tbody>
						<?php
							$queryMonth = "SELECT * FROM `mbs_bookings` t1, 
														 `mbs_bookings_activities` t2 
												   WHERE t1.`booking_id` = t2.`booking_id` ";
							
							if ($strMonth !== 'all') { $queryMonth .= " AND t2.`booking_activity_month` = '" . mysql_real_escape_string($strMonth) . "' "; }
							
							$queryMonth .= " ORDER BY t2.`booking_activity_month` ";
							$resultMonth = mysql_query($queryMonth);

							if ($resultMonth)
							{
								$i = 0;
								while ($rowMonth = mysql_fetch_assoc($resultMonth))
								{
									
									$i++;
									?>
									<tr>
										<td><div style="text-align:right;"><?php echo $i; ?></div></td>	
										<td><div style="text-align:left;"><?php echo HTML::getMonthName($rowMonth['booking_activity_month']); ?></div></td>											
										<td><div style="text-align:center;"><a href="booking_view.php?booking_id=<?php echo $rowMonth['booking_id']; ?>&action=view"><?php echo DB::dbIDToField('mbs_bookings', 'booking_id', $rowMonth['booking_id'], 'booking_code'); ?></a></div></td>
										<td><div style="text-align:left;"><a href="booking_view.php?booking_id=<?php echo $rowMonth['booking_id']; ?>&action=view#id<?php echo $rowMonth['booking_activity_id']; ?>"><?php echo DB::dbIDToField('mbs_activities', 'activity_id', $rowMonth['activity_id'], 'activity_name'); ?></a></div></td>	
									</tr>	
									<?php


								} // while ($rowSize = mysql_fetch_assoc($resultSize))
							
							} // if ($resultSize)

							
						?>	
					</tbody>
					<tfoot>
					</tfoot>					
				</table>	

				<?php
			} // while($row = mysql_fetch_assoc($result))
		} // if ($result)

		echo "<div class='well' style=''>";
		echo "<strong>Debug:</strong><br />";
		echo $query . "<br />";
		echo $queryMonth . "<br />";
		print_r($_REQUEST);
		echo "</div>";


	} // showReportStoreToMonth()


	function showReportStoreToAvailabilityByMonth($intStoreID, $strMonth)
	{

		DB::dbConnect();

		$query = "SELECT * FROM `mbs_stores` ";

		if ($intStoreID !== 'all') { $query .= " WHERE `store_id` = '" . mysql_real_escape_string($intStoreID) . "' "; }

		$query .= " ORDER BY `store_name`";
		$result = mysql_query($query);

		if ($result)
		{
			
			while($row = mysql_fetch_assoc($result))
			{
				?>

				<div style="text-align:center;clear:both;"><h3 style="font-size:1.3em;">Store: <?php echo stripslashes($row['store_name']); ?></h3></div>
				<table class="table table-bordered table-hover">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:right;width:10%;"><strong>No</strong></th>
					  	<th style="text-align:center;width:20%;"><strong>Month</strong></th>					  	
					  	<th style="text-align:center;"><strong>Booking Code</strong></th>
					  	<th style="text-align:center;"><strong>Activity</strong></th>
					</tr>			  
					</thead>
					<tbody>
						<?php
							$queryMonth = "SELECT * FROM `mbs_bookings` t1, 
														 `mbs_bookings_activities` t2 
												   WHERE t1.`booking_id` = t2.`booking_id` ";
							
							if ($strMonth !== 'all') { $queryMonth .= " AND t2.`booking_activity_month` = '" . mysql_real_escape_string($strMonth) . "' "; }
							
							$queryMonth .= " ORDER BY t2.`booking_activity_month` ";
							$resultMonth = mysql_query($queryMonth);

							if ($resultMonth)
							{
								$i = 0;
								while ($rowMonth = mysql_fetch_assoc($resultMonth))
								{
									
									$i++;
									?>
									<tr>
										<td><div style="text-align:right;"><?php echo $i; ?></div></td>	
										<td><div style="text-align:left;"><?php echo HTML::getMonthName($rowMonth['booking_activity_month']); ?></div></td>											
										<td><div style="text-align:center;"><a href="booking_view.php?booking_id=<?php echo $rowMonth['booking_id']; ?>&action=view"><?php echo DB::dbIDToField('mbs_bookings', 'booking_id', $rowMonth['booking_id'], 'booking_code'); ?></a></div></td>
										<td><div style="text-align:left;"><a href="booking_view.php?booking_id=<?php echo $rowMonth['booking_id']; ?>&action=view#id<?php echo $rowMonth['booking_activity_id']; ?>"><?php echo DB::dbIDToField('mbs_activities', 'activity_id', $rowMonth['activity_id'], 'activity_name'); ?></a></div></td>	
									</tr>	
									<?php


								} // while ($rowSize = mysql_fetch_assoc($resultSize))
							
							} // if ($resultSize)

							
						?>	
					</tbody>
					<tfoot>
					</tfoot>					
				</table>	

				<?php
			} // while($row = mysql_fetch_assoc($result))
		} // if ($result)

		echo "<div class='well' style=''>";
		echo "<strong>Debug:</strong><br />";
		echo $query . "<br />";
		echo $queryMonth . "<br />";
		print_r($_REQUEST);
		echo "</div>";

	} // showReportStoreToAvailabilityByMonth()


	function showReportStoreToAvailabilityByYear($intStoreID, $strYear)
	{
		DB::dbConnect();

		$query = "SELECT * FROM `mbs_stores` ";

		if ($intStoreID !== 'all') { $query .= " WHERE `store_id` = '" . mysql_real_escape_string($intStoreID) . "' "; }

		$query .= " ORDER BY `store_name`";
		$result = mysql_query($query);

		if ($result)
		{
			
			while($row = mysql_fetch_assoc($result))
			{
				?>

				<div style="text-align:center;clear:both;"><h3 style="font-size:1.3em;">Store: <?php echo stripslashes($row['store_name']); ?></h3></div>
				<table class="table table-bordered table-hover">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:right;width:10%;"><strong>No</strong></th>
					  	<th style="text-align:center;width:20%;"><strong>Month</strong></th>					  	
					  	<th style="text-align:center;"><strong>Booking Code</strong></th>
					  	<th style="text-align:center;"><strong>Activity</strong></th>
					</tr>			  
					</thead>
					<tbody>
						<?php
							$queryMonth = "SELECT * FROM `mbs_bookings` t1, 
														 `mbs_bookings_activities` t2 
												   WHERE t1.`booking_id` = t2.`booking_id` ";
							
							if ($strMonth !== 'all') { $queryMonth .= " AND t2.`booking_activity_month` = '" . mysql_real_escape_string($strMonth) . "' "; }
							
							$queryMonth .= " ORDER BY t2.`booking_activity_month` ";
							$resultMonth = mysql_query($queryMonth);

							if ($resultMonth)
							{
								$i = 0;
								while ($rowMonth = mysql_fetch_assoc($resultMonth))
								{
									
									$i++;
									?>
									<tr>
										<td><div style="text-align:right;"><?php echo $i; ?></div></td>	
										<td><div style="text-align:left;"><?php echo HTML::getMonthName($rowMonth['booking_activity_month']); ?></div></td>											
										<td><div style="text-align:center;"><a href="booking_view.php?booking_id=<?php echo $rowMonth['booking_id']; ?>&action=view"><?php echo DB::dbIDToField('mbs_bookings', 'booking_id', $rowMonth['booking_id'], 'booking_code'); ?></a></div></td>
										<td><div style="text-align:left;"><a href="booking_view.php?booking_id=<?php echo $rowMonth['booking_id']; ?>&action=view#id<?php echo $rowMonth['booking_activity_id']; ?>"><?php echo DB::dbIDToField('mbs_activities', 'activity_id', $rowMonth['activity_id'], 'activity_name'); ?></a></div></td>	
									</tr>	
									<?php


								} // while ($rowSize = mysql_fetch_assoc($resultSize))
							
							} // if ($resultSize)

							
						?>	
					</tbody>
					<tfoot>
					</tfoot>					
				</table>	

				<?php
			} // while($row = mysql_fetch_assoc($result))
		} // if ($result)

		echo "<div class='well' style=''>";
		echo "<strong>Debug:</strong><br />";
		echo $query . "<br />";
		echo $queryMonth . "<br />";
		print_r($_REQUEST);
		echo "</div>";

	} // showReportStoreToAvailabilityByYear()



	function showReportActivityToBookingProduct($intActivityID, $intProductID)
	{
		DB::dbConnect();

		$query = "SELECT * FROM `mbs_activities` ";

		if ($intActivityID !== 'all') { $query .= " WHERE `activity_id` = '" . mysql_real_escape_string($intActivityID) . "' "; }

		$query .= " ORDER BY `activity_name`";
		$result = mysql_query($query);

		if ($result)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				?>

				<div style="text-align:center;clear:both;"><h3 style="font-size:1.3em;"><?php echo stripslashes($row['activity_name']); ?></h3></div>
				<table class="table table-bordered table-hover">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:right;width:10%;"><strong>No</strong></th>
					  	<th style="text-align:center;width:20%;"><strong>Product Code</strong></th>
					  	<th style="text-align:center;"><strong>Product Name</strong></th>
					  	<th style="text-align:center;"><strong>Booking Code</strong></th>
					  	<th style="text-align:center;"><strong>Activity</strong></th>
					</tr>			  
					</thead>
					<tbody>
						<?php
							$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
														   `mbs_bookings_activities` t2 
													 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` ";
							
							if ($intProductID !== 'all') { $queryProduct .= " AND t1.`booking_product_id` = '" . mysql_real_escape_string($intProductID) . "' "; }

							#$queryProduct .= " GROUP BY t2.`activity_id` ";
							$queryProduct .= " ORDER BY t1.`booking_product_name`";
							$resultProduct = mysql_query($queryProduct);

							if ($resultProduct)
							{
								$i = 0;
								while ($rowProduct = mysql_fetch_assoc($resultProduct))
								{
									
									if ($row['activity_id'] == $rowProduct['activity_id'])
									{

									$i++;
									?>
									<tr>
										<td><div style="text-align:right;"><?php echo $i; ?></div></td>	
										<td><div style="text-align:left;"><?php echo $rowProduct['booking_product_code']; ?></div></td>	
										<td><div style="text-align:left;"><?php echo $rowProduct['booking_product_name']; ?></div></td>	
										<td><div style="text-align:center;"><a href="booking_view.php?booking_id=<?php echo $rowProduct['booking_id']; ?>&action=view"><?php echo DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'booking_code'); ?></a></div></td>
										<td><div style="text-align:left;"><a href="booking_view.php?booking_id=<?php echo $rowProduct['booking_id']; ?>&action=view#id<?php echo $rowProduct['booking_activity_id']; ?>"><?php echo DB::dbIDToField('mbs_activities', 'activity_id', $rowProduct['activity_id'], 'activity_name'); ?></a></div></td>	
									</tr>	
									<?php

									}

								} // while ($rowProduct = mysql_fetch_assoc($resultProduct))
							
							} // if ($resultProduct)

							
						?>	
					</tbody>
					<tfoot>
					</tfoot>					
				</table>	

				<?php
			} // while($row = mysql_fetch_assoc($result))
		} // if ($result)

		echo "<div class='well' style=''>";
		echo "<strong>Debug:</strong><br />";
		echo $query . "<br />";
		echo $queryProduct . "<br />";
		print_r($_REQUEST);
		echo "</div>";	
	} // showReportActivityToBookingProduct()


	function showReportActivityToSupplier($intActivityID, $intSupplierID)
	{

	} // showReportActivityToSupplier()


	function showReportActivityToSize($intActivityID, $intSizeID)
	{

	} // showReportActivityToSize()

	function showReportActivityToDollar($intActivityID, $intDollarID)
	{

	} // showReportActivityToDollar()

	function showReportActivityToMonth($intActivityID, $strMonth)
	{

	} // showReportActivityToMonth()

	function showReportActivityToAvailabilityByMonth($intActivityID, $strMonth)
	{

	} // showReportActivityToAvailabilityByMonth()

	function showReportActivityToAvailabilityByYear($intActivityID, $strYear)
	{

	} // showReportActivityToAvailabilityByYear()


	function getPagesTally($intSizeID)
	{
		switch ($intSizeID) {
			case 1:
				return 4;
				break;
			case 2:
				return 4;
				break;	
			case 3:
				return 8;
				break;
			case 4:
				return 16;
				break;
			case 5:
				return 1;
				break;
			case 6:
				return 2;
				break;			
			default:
				return 1;
				break;
		}
	}

	function showReportGeneralCatalogueByActivity($intYearMonth)
	{	

		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " Catalogue";

		$strTheme = $_REQUEST['frm_report_theme'];
		$strQueryTheme = '';
		if($strTheme == 'pharmacy4less'){
			$strQueryTheme = '%Catalogue - Pharmacy4Less%';
		}else if($strTheme == 'royyoung'){
			$strQueryTheme = '%Catalogue - Roy Young%';
		}else if($strTheme == 'in2health'){
			$strQueryTheme = '%Catalogue - IN 2 Health%';
		}else if($strTheme == 'themed-pharmacy4less'){
			$strQueryTheme = '%Themed Catalogue - Pharmacy4Less%';
		}else if($strTheme == 'themed-royyoung'){
			$strQueryTheme = '%Themed Catalogue - Roy Young%';
		}else{
			$strQueryTheme = '%Catalogue - Pharmacy4Less%';
		}
		?>		

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Activity Size</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;"><strong>Activity</strong></th>
					<th style="text-align:center;">Supplier</th>
					<th style="text-align:center;">UPI Code</th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align:left;" colspan="10"><strong>Single Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t2.`size_id` = 5
											 AND t5.`activity_category` = 'catalogue' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . " 
											 AND t5.`activity_name` LIKE '".$strQueryTheme."' 
											 ORDER BY t4.`supplier_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
							
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_code']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)
						
						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}


					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="10"><strong>Double Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t2.`size_id` = 6
											 AND t5.`activity_category` = 'catalogue' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 AND t5.`activity_name` LIKE '".$strQueryTheme."' 
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_code']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // 

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="10"><strong>Hero Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t2.`size_id` = 1
											 AND t5.`activity_category` = 'catalogue' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 AND t5.`activity_name` LIKE '".$strQueryTheme."' 
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_code']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)
						
						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}	

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="10"><strong>Half Page</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t2.`size_id` = 3
											 AND t5.`activity_category` = 'catalogue' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 AND t5.`activity_name` LIKE '".$strQueryTheme."' 
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{

							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_code']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="10"><strong>Full Page</strong></td>					
				</tr>

				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t2.`size_id` = 4
											 AND t5.`activity_category` = 'catalogue' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 AND t5.`activity_name` LIKE '".$strQueryTheme."' 
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_code']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)

						else 
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php			

	} // showReportGeneralCatalogueByActivity($intYearMonth)


	function showReportGeneralCatalogueBySupplier($intYearMonth)
	{	

		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " Catalogue";

		$strTheme = $_REQUEST['frm_report_theme'];
		$strQueryTheme = '';
		if($strTheme == 'pharmacy4less'){
			$strQueryTheme = '%Catalogue - Pharmacy4Less%';
		}else if($strTheme == 'royyoung'){
			$strQueryTheme = '%Catalogue - Roy Young%';
		}else if($strTheme == 'in2health'){
			$strQueryTheme = '%Catalogue - IN 2 Health%';
		}else if($strTheme == 'themed-pharmacy4less'){
			$strQueryTheme = '%Themed Catalogue - Pharmacy4Less%';
		}else if($strTheme == 'themed-royyoung'){
			$strQueryTheme = '%Themed Catalogue - Roy Young%';
		}else{
			$strQueryTheme = '%Catalogue - Pharmacy4Less%';
		}
		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Supplier</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;"><strong>Supplier</strong></th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Activity Size</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t5.`activity_category` = 'catalogue'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 AND t5.`activity_name` LIKE '".$strQueryTheme."' 
											 ORDER BY t4.`supplier_name`, t1.`booking_product_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{

							$i = 0;
							$data = array();
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$data[$i] = $rowProduct['supplier_id'];
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);

								if ($data[$i-1] !== $data[$i-2])
								{
							?>		
								<tr>
									<td style="text-align:left;" colspan="9"><strong><?php echo stripslashes($strSupplierName); ?></strong></td>
								</tr>
							<?php

								}
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>								
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strActivitySize); ?></td>								
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)
						
						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php			

	} // showReportGeneralCatalogueBySupplier($intYearMonth)


	function showReportGeneralCatalogueByProduct($intYearMonth)
	{
		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " Catalogue";

		$strTheme = $_REQUEST['frm_report_theme'];
		$strQueryTheme = '';
		if($strTheme == 'pharmacy4less'){
			$strQueryTheme = '%Catalogue - Pharmacy4Less%';
		}else if($strTheme == 'royyoung'){
			$strQueryTheme = '%Catalogue - Roy Young%';
		}else if($strTheme == 'in2health'){
			$strQueryTheme = '%Catalogue - IN 2 Health%';
		}else if($strTheme == 'themed-pharmacy4less'){
			$strQueryTheme = '%Themed Catalogue - Pharmacy4Less%';
		}else if($strTheme == 'themed-royyoung'){
			$strQueryTheme = '%Themed Catalogue - Roy Young%';
		}else{
			$strQueryTheme = '%Catalogue - Pharmacy4Less%';
		}
		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Product</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;">No</th>
					<th style="text-align:center;"><strong>Product</strong></th>
					<th style="text-align:center;">Supplier</th>
					<th style="text-align:center;">Activity Size</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t5.`activity_category` = 'catalogue'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 AND t5.`activity_name` LIKE '".$strQueryTheme."' 
											 ORDER BY t1.`booking_product_name`, t4.`supplier_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)	
						{

							$i = 0;						
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{													
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>								
									<td style="text-align:left;"><strong><?php echo stripslashes($rowProduct['booking_product_name']); ?></strong></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strActivitySize); ?></td>								
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)	

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	
	} // showReportGeneralCatalogueByProduct($intYearMonth)


	function showReportGeneralInStoreAllStores($intYearMonth)
	{
		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = "In-Store Activities <br /> " . HTML::getMonthName($intMonth) . " " . $intYear . " ";

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">All Stores</h3></div>
		
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th colspan="2" style="text-align:center;width:20%;"><strong>Store</strong></th>
					<?php
						// Get the In-Store activities
						
						$queryStore = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
						$resultStore = mysql_query($queryStore);
						
						$arrayStore = array();
			
						while ($rowStore = mysql_fetch_assoc($resultStore))
						{
							$arrayStore[] = $rowStore;
							?>
                            	<th style="text-align:center;"><strong><?php echo htmlspecialchars($rowStore['store_name']); ?></strong></th>
                            <?php
						}
						
						$countArrayStore = count($arrayStore);
						
					?>
				</tr>
			  
			</thead>

			<tbody>
					
		<?php 		
			
			  $queryActivity = "SELECT * FROM `mbs_activities` WHERE `year`='$intYear' AND `activity_category` = 'in-store' AND `activity_store_related` = 'yes' ORDER BY `activity_name`";
			  $resultActivity = mysql_query($queryActivity);
			  
			  while ($rowActivity = mysql_fetch_assoc($resultActivity)) 
			  {	
		?>
						
			<tr>
				<td rowspan="2"><div style="text-align:left;"><?php echo htmlspecialchars($rowActivity['activity_name']); ?></div></td>
                <td><div style="text-align:center;">Total</div></td>
				
				<?php 
					
					for ($i = 0; $i < $countArrayStore; $i++) 
					{ 
						$rowStore = $arrayStore[$i];
						$arrInStoreResult = DB::checkActivityInStoreByDateTime($rowActivity['activity_id'], $rowStore['store_id'], $intYear, $intMonth);
						
						$strBookingCode = DB::dbIDToField('mbs_bookings', 'booking_id', $arrInStoreResult['booking_id'], 'booking_code');
						$strBookingName = DB::dbIDToField('mbs_bookings', 'booking_id', $arrInStoreResult['booking_id'], 'booking_name');
				?> 
					<td><div style="text-align:right;"><?php echo $arrInStoreResult;?><?php //if (count($arrInStoreResult) > 1) { echo count($arrInStoreResult); } else { echo 0; } ?></div></td>
					
				<?php 
					} // for () 
				?>
			</tr>
            <tr>
            	<td><div style="text-align:center;">Avail.</div></td>
                <?php 
					for ($i = 0; $i < $countArrayStore; $i++) 
					{ 
						$rowStore = $arrayStore[$i];
						$arrInStoreResult = DB::checkActivityInStoreByDateTime($rowActivity['activity_id'], $rowStore['store_id'], $intYear, $intMonth);
						
						$strBookingCode = DB::dbIDToField('mbs_bookings', 'booking_id', $arrInStoreResult['booking_id'], 'booking_code');
						$strBookingName = DB::dbIDToField('mbs_bookings', 'booking_id', $arrInStoreResult['booking_id'], 'booking_name');
				?> 
					<td><div style="text-align:right;"><?php echo (3-$arrInStoreResult);?><?php //if (count($arrInStoreResult) > 1) { echo 3-1; } else { echo 3; } ?></div></td>
				
				<?php 
					} // for () 
				?>
            </tr>	
		
		<?php							
			}	// while ($rowStore = mysql_fetch_assoc($resultStore))
		?>
					
			</tbody>
			<tfoot>
				
			</tfoot>					
		</table>


		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;"><strong>In-Store Activities</strong></th>
					<th style="text-align:center;">Products</th>
					<th style="text-align:center;">Suppliers</th>
					<th style="text-align:center;">Spots</th>
					<th style="text-align:center;">Dollars</th>
					<th style="text-align:center;">Month</th>
					<th style="text-align:center;">Availability by Month</th>
					<th style="text-align:center;">Availability by Year</th>					
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t2.`activity_id` = t5.`activity_id`
											 AND t5.`activity_category` = 'in-store'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t5.`activity_name`, t1.`booking_product_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)
						{
							$i = 0;
							$data = array();						
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$data[$i] = $rowProduct['activity_name'];
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								
								if ($data[$i-1] !== $data[$i-2])
								{
							?>
									<tr>
										<td style="text-align:left;" colspan="9"><strong><?php echo stripslashes($rowProduct['activity_name']); ?></strong></td>
									</tr>
							<?php 		

								}
							?>
								<tr>
									<td style="text-align:left;"></td>								
									<td style="text-align:left;"> <?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>								
									<td style="text-align:right;"><?php echo stripslashes($strActivitySize); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_activity_price']); ?></td>
									<td style="text-align:right;"><?php echo HTML::getMonthName($intMonth) . " " . $intYear; ?></td>
									<td style="text-align:right;"></td>
									<td style="text-align:right;"></td>								
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows())	
						
						else 
						{
							?>
							<tr>
								<td colspan="8"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}	
						
					} // if ($resultProduct)
				?>

				

			</tbody>
			<tfoot>
			</tfoot>
		</table>

		<?php	

		// The Log	
		$strLog = "View In-Store Activities for All Stores Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);

	} // function showReportGeneralInStoreAllStores($intYearMonth)


	function showReportGeneralInStoreSingleStores($intStoreID, $intYearMonth)
	{
		global $STR_URL;		
		
		$strStoreName = DB::dbIDToField('mbs_stores', 'store_id', intval($intStoreID), 'store_name');
		$strReportTitle .= "In-Store Activities <br />" . $strStoreName . " Store ";

		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle .= HTML::getMonthName($intMonth) . " " . $intYear;

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;">No</th>					
					<th style="text-align:center;">Supplier</th>
					<th style="text-align:center;">Product</th>					
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 AND t5.`activity_category` = 'in-store'
											 AND (`store_id` REGEXP '^" . intval($intStoreID) . ",' 
											      OR `store_id` REGEXP '," . intval($intStoreID) . ",' 
											      OR `store_id` REGEXP '," . intval($intStoreID) . "$' 
											      OR `store_id` REGEXP '^" . intval($intStoreID) . "$') 	
											 ORDER BY t5.`activity_name`, t4.`supplier_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
							
						if (mysql_num_rows($resultProduct) > 0)
						{

							$i = 0;	
							$data = array();					
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{													
								$data[$i] = $rowProduct['activity_name'];
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								
								if ($data[$i-1] !== $data[$i-2])
								{
							?>
									<tr>
										<td style="text-align:left;" colspan="9"><strong><?php echo stripslashes($rowProduct['activity_name']); ?></strong></td>
									</tr>
							<?php		
								}
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>																
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>																
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="3"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View In-Store by Month for Single Store Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);

	} // showReportGeneralInStoreSingleStores($intStoreID, $intYearMonth)


	function showReportGeneralInStoreByYear($intStoreID, $intYear)
	{
		global $STR_URL;

		$strStoreName = DB::dbIDToField('mbs_stores', 'store_id', intval($intStoreID), 'store_name');
		$strReportTitle .= "In-Store Activities <br />" . $strStoreName . " Store " . $intYear;

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;"></h3></div>

		<!-- In-Store -->		
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th rowspan="2" style="text-align:center;width:10%;"><strong>Year <?php echo $intYear; ?></strong></th>
					<?php
						// Get the In-Store activities
						$queryInStore = "SELECT * FROM `mbs_activities` WHERE `year`='$intYear' AND `activity_category` = 'in-store' AND `activity_store_related` = 'yes' ORDER BY `activity_name`";
						$resultInStore = mysql_query($queryInStore);

						$intCountIntStore = mysql_num_rows($resultInStore);

						$arrDataInStoreAct = array();
						while ($rowInStore = mysql_fetch_assoc($resultInStore)) 
						{
							$arrDataInStoreAct[] = $rowInStore;	
							?>
								<th style="text-align:center;" colspan="2"><strong><?php echo htmlspecialchars($rowInStore['activity_name']); ?></strong></th>
								
							<?php
						}
					
					?>
				</tr>

				<tr>
					
					<?php for ($i = 0; $i < count($arrDataInStoreAct); $i++) { ?> 
					<td><div style="text-align:center;">Total</div></td>
					<td><div style="text-align:center;">Avail.</div></td>
					<?php } ?>
				</tr>
							  
			</thead>

			<tbody>
					
		<?php 
		
			$queryStore = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
			$resultStore = mysql_query($queryStore);

			for ($j = 1; $j <= 12; $j++)
			{
				
		?>
						
			<tr>
				<td><div style="text-align:left;"><?php echo HTML::getMonthName($j); ?></div></td>	
				
				<?php 
					for ($i = 0; $i < $intCountIntStore; $i++) 
					{
						$arrInStoreResult = DB::checkActivityInStoreByDateTime($arrDataInStoreAct[$i]['activity_id'], $intStoreID, $intYear, $j);
				?> 
					<td><div style="text-align:right;"><?php echo $arrInStoreResult;//if (count($arrInStoreResult) > 1) { echo 1; } else { echo 0; } ?></div></td>
					<td><div style="text-align:right;"><?php echo (3-$arrInStoreResult);//if (count($arrInStoreResult) > 1) { echo 3-1; } else { echo 3; } ?></div></td>
				
				<?php 
					} // for () 
				?>
			</tr>	
		
		<?php							
			}	// for ($j = 1; $j <= 12; $j++)
		?>
					
			</tbody>
			<tfoot>
				
			</tfoot>					
		</table>

		<?php

		// The Log	
		$strLog = "View In-Store Activities by Year of " .  $intYear . " Report for \"" . $strStoreName . "\" store";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);

	} // showReportGeneralInStoreByYear($intStoreID, $intYear)


	function showReportGeneralNewspaperByActivity($intYearMonth)
	{	

		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " Newspaper";

		?>		

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Activity Size</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;"><strong>Activity</strong></th>
					<th style="text-align:center;">Supplier</th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align:left;" colspan="9"><strong>Single Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 5 
											 AND t5.`activity_category` = 'newspaper' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
							
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)
						
						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}


					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="9"><strong>Double Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 6 
											 AND t5.`activity_category` = 'newspaper' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // 

						else
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="9"><strong>Hero Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 1 
											 AND t5.`activity_category` = 'newspaper' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)
						
						else
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}	

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="9"><strong>Half Page</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 3 
											 AND t5.`activity_category` = 'newspaper' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{

							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="9"><strong>Full Page</strong></td>					
				</tr>

				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 4 
											 AND t5.`activity_category` = 'newspaper' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)

						else 
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View Newspaper by Month by Activity Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);		

	} // showReportGeneralNewspaperByActivity($intYearMonth)


	function showReportGeneralNewspaperByProduct($intYearMonth)
	{
		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " Newspaper";

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Product</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;">No</th>
					<th style="text-align:center;"><strong>Product</strong></th>
					<th style="text-align:center;">Supplier</th>
					<th style="text-align:center;">Activity Size</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t5.`activity_category` = 'newspaper'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t1.`booking_product_name`, t4.`supplier_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)	
						{

							$i = 0;						
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{													
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>								
									<td style="text-align:left;"><strong><?php echo stripslashes($rowProduct['booking_product_name']); ?></strong></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strActivitySize); ?></td>								
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)	

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View Newspaper by Month by Product Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);

	} // showReportGeneralNewspaperByProduct($intYearMonth)

	function showReportGeneralNewspaperBySupplier($intYearMonth)
	{	

		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " Newspaper";

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Supplier</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;"><strong>Supplier</strong></th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Activity Size</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t5.`activity_category` = 'newspaper'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`, t1.`booking_product_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{

							$i = 0;
							$data = array();
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$data[$i] = $rowProduct['supplier_id'];
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);

								if ($data[$i-1] !== $data[$i-2])
								{
							?>		
								<tr>
									<td style="text-align:left;" colspan="9"><strong><?php echo stripslashes($strSupplierName); ?></strong></td>
								</tr>
							<?php

								}
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>								
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strActivitySize); ?></td>								
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)
						
						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View Newspaper by Month by Supplier Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);		

	} // showReportGeneralNewspaperBySupplier($intYearMonth)


	function showReportGeneralEmailByActivity($intYearMonth)
	{	

		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " 4YOU Email";

		?>		

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Activity Size</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;"><strong>Activity</strong></th>
					<th style="text-align:center;">Supplier</th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align:left;" colspan="9"><strong>Single Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 5 
											 AND t5.`activity_category` = 'email' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
							
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)
						
						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}


					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="9"><strong>Double Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 6 
											 AND t5.`activity_category` = 'email' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // 

						else
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="9"><strong>Hero Spot</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 1 
											 AND t5.`activity_category` = 'email' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)
						
						else
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}	

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="9"><strong>Half Page</strong></td>
				</tr>
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 3 
											 AND t5.`activity_category` = 'email' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{

							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				<tr>
					<td style="text-align:left;" colspan="9"><strong>Full Page</strong></td>					
				</tr>

				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id`
											 AND t5.`activity_id` = t2.`activity_id`
											 AND t2.`size_id` = 4 
											 AND t5.`activity_category` = 'email' 
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`";

					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{


							$i = 0;
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)

						else 
						{
							?>
							<tr>
								<td colspan="9"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php

		// The Log	
		$strLog = "View 4YOU Email by Month by Activity Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);			

	} // showReportGeneralEmailByActivity($intYearMonth)


	function showReportGeneralEmailByProduct($intYearMonth)
	{
		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " 4YOU Email";

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Product</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;">No</th>
					<th style="text-align:center;"><strong>Product</strong></th>
					<th style="text-align:center;">Supplier</th>
					<th style="text-align:center;">Activity Size</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t5.`activity_category` = 'email'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t1.`booking_product_name`, t4.`supplier_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)	
						{

							$i = 0;						
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{													
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>								
									<td style="text-align:left;"><strong><?php echo stripslashes($rowProduct['booking_product_name']); ?></strong></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strActivitySize); ?></td>								
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)	

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View 4YOU Email by Month by Product Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);

	} // showReportGeneralEmailByProduct($intYearMonth)


	function showReportGeneralEmailBySupplier($intYearMonth)
	{	

		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " 4YOU Email";

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Supplier</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;"><strong>Supplier</strong></th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Activity Size</th>
					<th style="text-align:center;">Normal Retail</th>
					<th style="text-align:center;">Promo Price</th>
					<th style="text-align:center;">Cost Price</th>
					<th style="text-align:center;">RRP</th>
					<th style="text-align:center;">SAVE</th>
					<th style="text-align:center;">Pages Tally</th>
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t5.`activity_category` = 'email'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`, t1.`booking_product_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{

							$i = 0;
							$data = array();
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$data[$i] = $rowProduct['supplier_id'];
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);

								if ($data[$i-1] !== $data[$i-2])
								{
							?>		
								<tr>
									<td style="text-align:left;" colspan="9"><strong><?php echo stripslashes($strSupplierName); ?></strong></td>
								</tr>
							<?php

								}
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>								
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes($strActivitySize); ?></td>								
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_normal_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_promo_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_cost_price']); ?></td>
									<td style="text-align:right;">$<?php echo stripslashes($rowProduct['booking_product_recommended_retail_price']); ?></td>
									<td style="text-align:right;">$<?php echo (floatval($rowProduct['booking_product_recommended_retail_price']) - floatval($rowProduct['booking_product_promo_price'])); ?></td>
									<td style="text-align:right;"><?php echo $intPagesTally; ?></td>
								</tr>

							<?php

							} // while($rowProduct)
						
						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="10"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View 4YOU Email by Month by Supplier Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);		

	} // showReportGeneralEmailBySupplier($intYearMonth)


	function showReportGeneralPREPSchoolByProduct($intYearMonth)
	{
		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " PREP School";

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Product</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;">No</th>
					<th style="text-align:center;"><strong>Product</strong></th>
					<th style="text-align:center;">Supplier</th>
					<th style="text-align:center;">Date</th>
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t5.`activity_name` LIKE '%PREP School%'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t1.`booking_product_name`, t4.`supplier_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{
						
						if (mysql_num_rows($resultProduct) > 0)	
						{

							$i = 0;						
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{													
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>								
									<td style="text-align:left;"><strong><?php echo stripslashes($rowProduct['booking_product_name']); ?></strong></td>
									<td style="text-align:left;"><?php echo stripslashes($strSupplierName); ?></td>
									<td style="text-align:left;"><?php echo stripslashes(HTML::convertDate($rowProduct['booking_date'])); ?></td>
								</tr>

							<?php

							} // while($rowProduct)

						} // if (mysql_num_rows($resultProduct) > 0)	

						else
						{
							?>
							<tr>
								<td colspan="4"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

				

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View PREP School by Month by Product Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);

	} // showReportGeneralPREPSchoolByProduct($intYearMonth)


	function showReportGeneralPREPSchoolBySupplier($intYearMonth)
	{	

		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear . " PREP School";

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">Report by Supplier</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;"><strong>Supplier</strong></th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Date</th>					
				</tr>
			</thead>
			<tbody>				
				<?php 
					$queryProduct = "SELECT * FROM `mbs_bookings_products` t1, 
												   `mbs_bookings_activities` t2,  
												   `mbs_bookings` t3,
												   `mbs_suppliers` t4,
												   `mbs_activities` t5
											 WHERE t1.`booking_activity_id` = t2.`booking_activity_id` 
											 AND t1.`booking_id` = t3.`booking_id`
											 AND t3.`supplier_id` = t4.`supplier_id` 
											 AND t5.`activity_id` = t2.`activity_id` 
											 AND t5.`activity_name` LIKE '%PREP School%'
											 AND t2.`booking_activity_year` = " . $intYear . " 
											 AND t2.`booking_activity_month` = " . $intMonth . "
											 ORDER BY t4.`supplier_name`, t1.`booking_product_name`";
					//echo $queryProduct;						 
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct)
					{

						if (mysql_num_rows($resultProduct) > 0)
						{

							$i = 0;
							$data = array();
							while ($rowProduct = mysql_fetch_assoc($resultProduct)) 
							{						
								$data[$i] = $rowProduct['supplier_id'];
								$i++;

								$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $rowProduct['booking_id'], 'supplier_id');
								$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name');
								$strActivitySize = DB::dbIDToField('mbs_sizes', 'size_id', $rowProduct['size_id'], 'size_name');
								$intPagesTally = REPORT::getPagesTally($rowProduct['size_id']);

								if ($data[$i-1] !== $data[$i-2])
								{
							?>		
								<tr>
									<td style="text-align:left;" colspan="4"><strong><?php echo stripslashes($strSupplierName); ?></strong></td>
								</tr>
							<?php

								}
							?>
								<tr>
									<td style="text-align:right;"><?php echo $i; ?></td>								
									<td style="text-align:left;"><?php echo stripslashes($rowProduct['booking_product_name']); ?></td>
									<td style="text-align:left;"><?php echo stripslashes(HTML::convertDate($rowProduct['booking_date'])); ?></td>								
									
								</tr>

							<?php

							} // while($rowProduct)
						
						} // if (mysql_num_rows($resultProduct) > 0)

						else
						{
							?>
							<tr>
								<td colspan="4"><div style="text-align:center;"><em>No data was found</em></div></td>	
							</tr>
							<?php
						}

					} // if ($resultProduct)
				?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View PREP School by Month by Supplier Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);		

	} // showReportGeneralPREPSchoolBySupplier($intYearMonth)


	function showReportGeneralMonthly($intMonth, $intYear)
	{

		global $STR_URL;

		// vars
		$intMinBookingTime = 3600*24*7; // 3600 seconds * 24 hours * 14 days

		DB::dbConnect();
		
		?>
		<script type="text/javascript" src="<?php echo $STR_URL; ?>js/jqplot/jquery.jqplot.min.js"></script>
		<script type="text/javascript" src="<?php echo $STR_URL; ?>js/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
		
		
		<div align="center" style="margin-top:20px;">
		<form id="frm_report_date" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">									
							
				<select id="month" name="month" class="input-medium">
					<?php for ($i = 1; $i <= 12; $i++) { ?>	
					<option value="<?php echo $i; ?>"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == $i) { ?> selected="selected"<?php } elseif (!$_REQUEST['month'] && date('n') == $i) { ?> selected="selected"<?php } ?>><?php echo HTML::getMonthName($i); ?></option>
					<?php } ?>
				</select>
			
				<select id="year" name="year" class="input-small">
					<?php for ($i = date('Y')-1; $i < date('Y')+4; $i++) { ?>	
					<option value="<?php echo $i; ?>"<?php if ($_REQUEST['year'] && $_REQUEST['year'] == $i) { ?> selected="selected"<?php } elseif (!$_REQUEST['year'] && date('Y') == $i) { ?> selected="selected"<?php } ?>><?php echo $i; ?></option>
					<?php } ?>
				</select><br />

				<input class="btn" type="submit" value="Submit" />				
						
		</form>
		</div>

		<div style="text-align:center;clear:both;"><h2>General Report (<?php echo HTML::getMonthName($intMonth) . " " . $intYear; ?>)</h2></div>

		<!-- In-Store -->
		<div style="clear:both;"><h3 style="font-size:1.3em;">In-Store</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;width:20%;"><strong>Store</strong></th>
					<?php
						// Get the In-Store activities
						$queryInStore = "SELECT * FROM `mbs_activities` WHERE `activity_category` = 'in-store' AND `activity_store_related` = 'yes' ORDER BY `activity_name`";
						$resultInStore = mysql_query($queryInStore);

						$intCountIntStore = mysql_num_rows($resultInStore);

						$arrDataInStoreAct = array();
						while ($rowInStore = mysql_fetch_assoc($resultInStore)) 
						{
							$arrDataInStoreAct[] = $rowInStore;	
							?>
								<th style="text-align:center;"><strong><?php echo htmlspecialchars($rowInStore['activity_name']); ?></strong></th>
							<?php
						}
					
					?>
				</tr>			  
			</thead>

			<tbody>
					
		<?php 
		
			$queryStore = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
			$resultStore = mysql_query($queryStore);

			while ($rowStore = mysql_fetch_assoc($resultStore))
			{
				
		?>
						
			<tr>
				<td><div style="text-align:left;"><?php echo htmlspecialchars($rowStore['store_name']); ?></div></td>	
				
				<?php 
					for ($i = 0; $i < $intCountIntStore; $i++) 
					{ 

						$arrInStoreResult = DB::checkActivityInStoreByDateTime($arrDataInStoreAct[$i]['activity_id'], $rowStore['store_id'], $intYear, $intMonth);
						$strBookingCode = DB::dbIDToField('mbs_bookings', 'booking_id', $arrInStoreResult['booking_id'], 'booking_code');
						$strBookingName = DB::dbIDToField('mbs_bookings', 'booking_id', $arrInStoreResult['booking_id'], 'booking_name');
				?> 
				
				<td style="background-color:<?php if (count($arrInStoreResult) > 1) { ?>#f2dede<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>#ddd<?php } else { ?>#dff0d8<?php } ?>;">
					<div style="text-align:center;font-size:0.8em;">
						<?php if (count($arrInStoreResult) > 1) { ?>
							<a href="booking_view.php?booking_id=<?php echo $arrInStoreResult['booking_id']; ?>&action=view#id<?php echo $arrInStoreResult['booking_activity_id']; ?>" title="<?php echo htmlspecialchars($strBookingCode); ?> / <?php echo htmlspecialchars($strBookingName); ?>"><em><?php echo htmlspecialchars($strBookingCode); ?></em></a>	
						<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>
						N/A	
						<?php } else { ?>
							<a href="booking.php?action=add&frm_activity_id=<?php echo $arrDataInStoreAct[$i]['activity_id']; ?>&frm_store_id=<?php echo $rowStore['store_id']; ?>" style="color:#468847;">Available</a>
						<?php } ?>
					</div>
				</td>
				
				<?php 
					} 
				?>
			</tr>	
		
		<?php							
			}	// while ($rowStore = mysql_fetch_assoc($resultStore))
		?>
					
			</tbody>
			<tfoot>
				<!--Sub Total-->
				<tr>
					<td><div style="text-align:right;"><strong>Sub-Total</strong></div></td>
					<?php 
					for ($i = 0; $i < $intCountIntStore; $i++) 
					{ 
						$arrInStoreSubTotal = DB::getActivityInStoreTotalByDateTime($arrDataInStoreAct[$i]['activity_id'], $intYear, $intMonth);
						$strInStoreSubTotal = $arrInStoreSubTotal['booking_activity_price_total'];
						$strInStoreSubTotal = $strInStoreSubTotal;
						$strInStoreTotal += $strInStoreSubTotal;

					?>
					<td><div style="text-align:right;">$<?php echo htmlspecialchars(number_format($strInStoreSubTotal, 2)); ?></div></td>
					<?php
					}
					?>
				</tr>
				<!-- Total -->
				<tr>					
					<td colspan="<?php echo ($intCountIntStore); ?>"><div style="text-align:right;"><strong>Total</strong></div></td>
					<td style="background-color:#ddd;"><div style="text-align:right;"><strong>$<?php echo htmlspecialchars(number_format($strInStoreTotal, 2)); ?></strong></div></td>
				</tr>
			</tfoot>					
		</table>


		<!-- Catalogue -->
		<div style="clear:both;"><h3 style="font-size:1.3em;">Catalogue</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;width:20%;"><strong>Size/Spot</strong></th>
					<th style="text-align:center;"><strong>Booking</strong></th>
					<th style="text-align:center;width:20%;"><strong>Total</strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
			$queryCatalogue = "SELECT * FROM `mbs_activities` WHERE `activity_category` = 'catalogue' ORDER BY `activity_name`";
			$resultCatalogue = mysql_query($queryCatalogue);

			while ($rowCatalogue = mysql_fetch_assoc($resultCatalogue)) 
			{

				$arrCatalogue = DB::getActivityByDateTime($rowCatalogue['activity_id'], $intYear, $intMonth);
				$strBookingCode = DB::dbIDToField('mbs_bookings', 'booking_id', $arrCatalogue['booking_id'], 'booking_code');
				$strBookingName = DB::dbIDToField('mbs_bookings', 'booking_id', $arrCatalogue['booking_id'], 'booking_name');
				$strCataloguePrice = $arrCatalogue['booking_activity_price_total'];
				$strCataloguePriceTotal += $strCataloguePrice;
		?>
				<tr>
					<td><div style="text-align:left;"><?php echo htmlspecialchars($rowCatalogue['activity_name']); ?></div></td>
					<td style="background-color:<?php if (count($arrCatalogue) > 1) { ?>#f2dede<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time()  + $intMinBookingTime) { ?>#ddd<?php } else { ?>#dff0d8<?php } ?>;">
					<div style="text-align:center;">
						<?php if (count($arrCatalogue) > 1) { ?>
							<a href="booking_view.php?booking_id=<?php echo $arrCatalogue['booking_id']; ?>&action=view#id<?php echo $arrCatalogue['booking_activity_id']; ?>" title="<?php echo htmlspecialchars($strBookingCode); ?> / <?php echo htmlspecialchars($strBookingName); ?>"><em><?php echo htmlspecialchars($strBookingCode); ?></em></a>	
						<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>
						N/A	
						<?php } else { ?>
							<a href="booking.php?action=add&frm_activity_id=<?php echo $rowCatalogue['activity_id']; ?>" style="color:#468847;">Available</a>
						<?php } ?>
					</div>
				</td>
					<td><div style="text-align:right;">$<?php echo htmlspecialchars(number_format($strCataloguePrice)); ?></div></td>	
				</tr>
		<?php
			}
		?>		
			</tbody>			
			<tfoot>
				<td colspan="2"><div style="text-align:right;"><strong>Total</strong></div></td>
				<td style="background-color:#ddd;"><div style="text-align:right;"><strong>$<?php echo htmlspecialchars(number_format($strCataloguePriceTotal, 2)); ?></strong></div></td>
			</tfoot>
		</table>


		<!-- Newspaper -->
		<div style="clear:both;"><h3 style="font-size:1.3em;">Newspaper</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;width:20%;"><strong>Size/Spot</strong></th>
					<th style="text-align:center;"><strong>Booking</strong></th>
					<th style="text-align:center;width:20%;"><strong>Total</strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
			$queryNewspaper = "SELECT * FROM `mbs_activities` WHERE `activity_category` = 'newspaper' ORDER BY `activity_name`";
			$resultNewspaper = mysql_query($queryNewspaper);

			while ($rowNewspaper = mysql_fetch_assoc($resultNewspaper)) 
			{
				$arrNewspaper = DB::getActivityByDateTime($rowNewspaper['activity_id'], $intYear, $intMonth);
				$strBookingCode = DB::dbIDToField('mbs_bookings', 'booking_id', $arrNewspaper['booking_id'], 'booking_code');
				$strBookingName = DB::dbIDToField('mbs_bookings', 'booking_id', $arrNewspaper['booking_id'], 'booking_name');
				$strNewspaperPrice = $arrNewspaper['booking_activity_price_total'];
				$strNewspaperPriceTotal += $strNewspaperPrice;
		?>
				<tr>
					<td><div style="text-align:left;"><?php echo htmlspecialchars($rowNewspaper['activity_name']); ?></div></td>
					<td style="background-color:<?php if (count($arrNewspaper) > 1) { ?>#f2dede<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>#ddd<?php } else { ?>#dff0d8<?php } ?>;">
					<div style="text-align:center;">
						<?php if (count($arrNewspaper) > 1) { ?>
							<a href="booking_view.php?booking_id=<?php echo $arrNewspaper['booking_id']; ?>&action=view#id<?php echo $arrNewspaper['booking_activity_id']; ?>" title="<?php echo htmlspecialchars($strBookingCode); ?> / <?php echo htmlspecialchars($strBookingName); ?>"><em><?php echo htmlspecialchars($strBookingCode); ?></em></a>	
						<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>
						N/A	
						<?php } else { ?>
							<a href="booking.php?action=add&frm_activity_id=<?php echo $rowNewspaper['activity_id']; ?>" style="color:#468847;">Available</a>
						<?php } ?>
					</div>
				</td>
					<td><div style="text-align:right;">$<?php echo htmlspecialchars(number_format($strNewspaperPrice)); ?></div></td>	
				</tr>
		<?php
			}
		?>		
			</tbody>			
			<tfoot>
				<td colspan="2"><div style="text-align:right;"><strong>Total</strong></div></td>
				<td style="background-color:#ddd;"><div style="text-align:right;"><strong>$<?php echo htmlspecialchars(number_format($strNewspaperPriceTotal, 2)); ?></strong></div></td>
			</tfoot>
		</table>


		<!-- Email -->
		<div style="clear:both;"><h3 style="font-size:1.3em;">4 YOU Email</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;width:20%;"><strong>Size/Spot</strong></th>
					<th style="text-align:center;"><strong>Booking</strong></th>
					<th style="text-align:center;width:20%;"><strong>Total</strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
			$queryEmail = "SELECT * FROM `mbs_activities` WHERE `activity_category` = 'email' ORDER BY `activity_name`";
			$resultEmail = mysql_query($queryEmail);

			while ($rowEmail = mysql_fetch_assoc($resultEmail)) 
			{
				$arrEmail = DB::getActivityByDateTime($rowEmail['activity_id'], $intYear, $intMonth);
				$strBookingCode = DB::dbIDToField('mbs_bookings', 'booking_id', $arrEmail['booking_id'], 'booking_code');
				$strBookingName = DB::dbIDToField('mbs_bookings', 'booking_id', $arrEmail['booking_id'], 'booking_name');
				$strEmailPrice = $arrEmail['booking_activity_price_total'];
				$strEmailPriceTotal += $strEmailPrice;
		?>
				<tr>
					<td><div style="text-align:left;"><?php echo htmlspecialchars($rowEmail['activity_name']); ?></div></td>
					<td style="background-color:<?php if (count($arrEmail) > 1) { ?>#f2dede<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>#ddd<?php } else { ?>#dff0d8<?php } ?>;">
					<div style="text-align:center;">
						<?php if (count($arrEmail) > 1) { ?>
							<a href="booking_view.php?booking_id=<?php echo $arrEmail['booking_id']; ?>&action=view#id<?php echo $arrEmail['booking_activity_id']; ?>" title="<?php echo htmlspecialchars($strBookingCode); ?> / <?php echo htmlspecialchars($strBookingName); ?>"><em><?php echo htmlspecialchars($strBookingCode); ?></em></a>	
						<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>
						N/A	
						<?php } else { ?>
							<a href="booking.php?action=add&frm_activity_id=<?php echo $rowEmail['activity_id']; ?>" style="color:#468847;">Available</a>
						<?php } ?>
					</div>
				</td>
					<td><div style="text-align:right;">$<?php echo htmlspecialchars(number_format($strEmailPrice)); ?></div></td>	
				</tr>
		<?php
			}
		?>		
			</tbody>			
			<tfoot>
				<td colspan="2"><div style="text-align:right;"><strong>Total</strong></div></td>
				<td style="background-color:#ddd;"><div style="text-align:right;"><strong>$<?php echo htmlspecialchars(number_format($strEmailPriceTotal, 2)); ?></strong></div></td>
			</tfoot>
		</table>


		<!-- Other -->
		<div style="clear:both;"><h3 style="font-size:1.3em;">Other</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;width:20%;"><strong>Activity</strong></th>
					<th style="text-align:center;"><strong>Booking</strong></th>
					<th style="text-align:center;width:20%;"><strong>Total</strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
			$queryOther = "SELECT * FROM `mbs_activities` WHERE `activity_category` = 'other' ORDER BY `activity_name`";
			$resultOther = mysql_query($queryOther);

			while ($rowOther = mysql_fetch_assoc($resultOther)) 
			{
				$arrOther = DB::getActivityByDateTime($rowOther['activity_id'], $intYear, $intMonth);
				$strBookingCode = DB::dbIDToField('mbs_bookings', 'booking_id', $arrOther['booking_id'], 'booking_code');
				$strBookingName = DB::dbIDToField('mbs_bookings', 'booking_id', $arrOther['booking_id'], 'booking_name');
				$strOtherPrice = $arrOther['booking_activity_price_total'];
				$strOtherPriceTotal += $strOtherPrice;
		?>
				<tr>
					<td><div style="text-align:left;"><?php echo htmlspecialchars($rowOther['activity_name']); ?></div></td>
					<td style="background-color:<?php if (count($arrOther) > 1) { ?>#f2dede<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>#ddd<?php } else { ?>#dff0d8<?php } ?>;">
					<div style="text-align:center;">
						<?php if (count($arrOther) > 1) { ?>
							<a href="booking_view.php?booking_id=<?php echo $arrOther['booking_id']; ?>&action=view#id<?php echo $arrOther['booking_activity_id']; ?>" title="<?php echo htmlspecialchars($strBookingCode); ?> / <?php echo htmlspecialchars($strBookingName); ?>"><em><?php echo htmlspecialchars($strBookingCode); ?></em></a>	
						<?php } elseif (mktime(0,0,0,$intMonth,1,$intYear) < time() + $intMinBookingTime) { ?>
						N/A	
						<?php } else { ?>
							<a href="booking.php?action=add&frm_activity_id=<?php echo $rowOther['activity_id']; ?>" style="color:#468847;">Available</a>
						<?php } ?>
					</div>
				</td>
					<td><div style="text-align:right;">$<?php echo htmlspecialchars(number_format($strOtherPrice)); ?></div></td>	
				</tr>
		<?php
			}
		?>		
			</tbody>			
			<tfoot>
				<td colspan="2"><div style="text-align:right;"><strong>Total</strong></div></td>
				<td style="background-color:#ddd;"><div style="text-align:right;"><strong>$<?php echo htmlspecialchars(number_format($strOtherPriceTotal, 2)); ?></strong></div></td>
			</tfoot>
		</table>



		<div style="clear:both;"><h3 style="font-size:1.3em;">Summary</h3></div>
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;width:20%;"><strong>Summary</strong></th>
					<th style="text-align:center;width:20%;"><strong>Data</strong></th>
					<th style="text-align:center;"><strong>Chart</strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
			$strTotal = $strInStoreTotal + $strCataloguePriceTotal + $strNewspaperPriceTotal + $strEmailPriceTotal + $strOtherPriceTotal;
		?>
				
				<tr>
					<td><div style="text-align:left;">In-Store</div></td>
					<td><div style="text-align:right;"><strong>$<?php echo number_format($strInStoreTotal); ?></strong></div></td>
					<td rowspan="6"><div id="chart"></div></td>
				</tr>
				<tr>
					<td><div style="text-align:left;">Catalogue</div></td>
					<td><div style="text-align:right;"><strong>$<?php echo number_format($strCataloguePriceTotal); ?></strong></div></td>					
				</tr>
				<tr>
					<td><div style="text-align:left;">Newspaper</div></td>
					<td><div style="text-align:right;"><strong>$<?php echo number_format($strNewspaperPriceTotal); ?></strong></div></td>					
				</tr>
				<tr>
					<td><div style="text-align:left;">4 YOU Email</div></td>
					<td><div style="text-align:right;"><strong>$<?php echo number_format($strEmailPriceTotal); ?></strong></div></td>					
				</tr>
				<tr>
					<td><div style="text-align:left;">Other</div></td>
					<td><div style="text-align:right;"><strong>$<?php echo number_format($strOtherPriceTotal); ?></strong></div></td>					
				</tr>
				<tr>
					<td><div style="text-align:left;"><strong>Total Amount</strong></div></td>
					<td style="background-color:#ccc;">
					<div style="text-align:right;"><strong>$<?php echo number_format($strTotal); ?></strong></div>
					</td>
					
				</tr>
		
			</tbody>			
			<tfoot>				
			</tfoot>
		</table>

		

		<script>
			$(document).ready(function(){
			  var data = [
			    ['In-Store', <?php echo $strInStoreTotal; ?>],['Catalogue', <?php echo $strCataloguePriceTotal; ?>], ['Newspaper', <?php echo $strNewspaperPriceTotal; ?>], 
			    ['4 You Email', <?php echo $strEmailPriceTotal; ?>],['Other', <?php echo $strOtherPriceTotal; ?>]
			  ];
			  var plot = jQuery.jqplot ('chart', [data], 
			    { 
			      seriesDefaults: {
			        // Make this a pie chart.
			        renderer: jQuery.jqplot.PieRenderer, 
			        rendererOptions: {
			          // Put data labels on the pie slices.
			          // By default, labels show the percentage of the slice.
			          showDataLabels: true
			        }
			        
			      },
			      title: 'Revenue <?php echo HTML::getMonthName($intMonth) . " " . $intYear; ?>',
			      grid: {
            			drawBorder: false, 
            			drawGridlines: false,
            			background: '#ffffff',
            			shadow:false
        		  }, 
        		  seriesColors:['#f00', '#0f0', '#00f', '#fa0', '#b51be0'],
			      legend: { show:true, location: 'e' }
			    }
			  );
			});		
		</script>

		<?php

		// The Log	
		$strLog = "View monthly General Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);

		#echo "<div class='well' style=''>";
		#echo "<strong>Debug:</strong><br />";
		#print_r($_REQUEST);
		#echo "</div>";

	} // showReportGeneralCurrentMonth()


	function showReportGeneralYearly($intYear)
	{

		?>
			<div style="text-align:center;clear:both;"><h2>General Report <?php echo $intYear; ?></h2></div>
			<br /><br />
										
			<script type="text/javascript" src="<?php echo $STR_URL; ?>js/jqplot/jquery.jqplot.min.js"></script>
			<script type="text/javascript" src="<?php echo $STR_URL; ?>js/jqplot/plugins/jqplot.barRenderer.min.js"></script>	
			<script type="text/javascript" src="<?php echo $STR_URL; ?>js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>	
			<script type="text/javascript" src="<?php echo $STR_URL; ?>js/jqplot/plugins/jqplot.pointLabels.min.js"></script>

			<div id="chart"></div>
			<div id="chart2"></div>
		

			<?php $arrDataPrice = DB::countActivityPriceInAYear(date('Y')); ?>
			<?php $arrDataActivity = DB::countActivityInAYear(date('Y')); ?>

			<?php $arrDataPriceInStore = DB::countActivityPriceInAYear(date('Y'), 'in-store'); ?>
			<?php $arrDataPriceCatalogue = DB::countActivityPriceInAYear(date('Y'), 'catalogue'); ?>
			<?php $arrDataPriceNewspaper = DB::countActivityPriceInAYear(date('Y'), 'newspaper'); ?>
			<?php $arrDataPriceEmail = DB::countActivityPriceInAYear(date('Y'), 'email'); ?>
			<?php $arrDataPriceOther = DB::countActivityPriceInAYear(date('Y'), 'other'); ?>


			<script>
				$(document).ready(function() {
			        var s01 = [<?php echo $arrDataPrice[1]; ?>, <?php echo $arrDataPrice[2]; ?>, <?php echo $arrDataPrice[3]; ?>, <?php echo $arrDataPrice[4]; ?>, <?php echo $arrDataPrice[5]; ?>, <?php echo $arrDataPrice[6]; ?>, <?php echo $arrDataPrice[7]; ?>, <?php echo $arrDataPrice[8]; ?>, <?php echo $arrDataPrice[9]; ?>, <?php echo $arrDataPrice[10]; ?>, <?php echo $arrDataPrice[11]; ?>, <?php echo $arrDataPrice[12]; ?>];
			        var s02 = [<?php echo $arrDataPriceInStore[1]; ?>, <?php echo $arrDataPriceInStore[2]; ?>, <?php echo $arrDataPriceInStore[3]; ?>, <?php echo $arrDataPriceInStore[4]; ?>, <?php echo $arrDataPriceInStore[5]; ?>, <?php echo $arrDataPriceInStore[6]; ?>, <?php echo $arrDataPriceInStore[7]; ?>, <?php echo $arrDataPriceInStore[8]; ?>, <?php echo $arrDataPriceInStore[9]; ?>, <?php echo $arrDataPriceInStore[10]; ?>, <?php echo $arrDataPriceInStore[11]; ?>, <?php echo $arrDataPriceInStore[12]; ?>];
			        var s03 = [<?php echo $arrDataPriceCatalogue[1]; ?>, <?php echo $arrDataPriceCatalogue[2]; ?>, <?php echo $arrDataPriceCatalogue[3]; ?>, <?php echo $arrDataPriceCatalogue[4]; ?>, <?php echo $arrDataPriceCatalogue[5]; ?>, <?php echo $arrDataPriceCatalogue[6]; ?>, <?php echo $arrDataPriceCatalogue[7]; ?>, <?php echo $arrDataPriceCatalogue[8]; ?>, <?php echo $arrDataPriceCatalogue[9]; ?>, <?php echo $arrDataPriceCatalogue[10]; ?>, <?php echo $arrDataPriceCatalogue[11]; ?>, <?php echo $arrDataPriceCatalogue[12]; ?>];
			        
			        var s2 = [<?php echo $arrDataActivity[1]; ?>, <?php echo $arrDataActivity[2]; ?>, <?php echo $arrDataActivity[3]; ?>, <?php echo $arrDataActivity[4]; ?>, <?php echo $arrDataActivity[5]; ?>, <?php echo $arrDataActivity[6]; ?>, <?php echo $arrDataActivity[7]; ?>, <?php echo $arrDataActivity[8]; ?>, <?php echo $arrDataActivity[9]; ?>, <?php echo $arrDataActivity[10]; ?>, <?php echo $arrDataActivity[11]; ?>, <?php echo $arrDataActivity[12]; ?>];
			        var ticks = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
			         
			        plot = $.jqplot('chart', [s01], {
			            seriesDefaults: {
			                renderer:$.jqplot.BarRenderer,
			                pointLabels: { show: true }
			            },
			            axes: {
			                xaxis: {
			                    renderer: $.jqplot.CategoryAxisRenderer,
			                    ticks: ticks
			                }
			            },
			            title: 'Revenue <?php echo date('Y'); ?> (Total: $<?php echo number_format(array_sum($arrDataPrice), 2); ?>)',
			            seriesColors:['#c00', '#00c', '#0c0'],
			            grid: {
							drawBorder: true, 
							drawGridlines: true,
							background: '#ffffff',
							shadow:true
							}
			        });	


			        plot2 = $.jqplot('chart2', [s2], {
			            seriesDefaults: {
			                renderer:$.jqplot.BarRenderer,
			                pointLabels: { show: true }
			            },
			            axes: {
			                xaxis: {
			                    renderer: $.jqplot.CategoryAxisRenderer,
			                    ticks: ticks
			                }
			            },
			            title: 'Activities <?php echo date('Y'); ?> (Total: <?php echo array_sum($arrDataActivity); ?>)',
			            seriesColors:['#333'],
			            grid: {
							drawBorder: true, 
							drawGridlines: true,
							background: '#ffffff',
							shadow:true
							}
			        });										     
			        
			    });
			</script>

	<?php									

	} // showReportGeneralYearly($intYear)

	function showReportGeneralDepartments($intYearMonth)
	{
		global $STR_URL;		
		
		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle = "Catalogue Booked Activity by Department <br /> " . HTML::getMonthName($intMonth) . " " . $intYear . " ";

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		<div style="clear:both;"><h3 style="font-size:1.3em;">All Departments</h3></div>
        
        <!-- In-Store ------------------------------------------------------------------------>		
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;width:50%;"><strong>Departments</strong></th>
                    <th style="text-align:center;"><strong>Total</strong></th>


                   



				</tr>
			  
			</thead>

			<tbody>
					
		<?php 		
			
			  $queryInStore = "SELECT * FROM `mbs_departments` ORDER BY `department_name`";
			  $resultInStore = mysql_query($queryInStore);

			  while ($rowInStore = mysql_fetch_assoc($resultInStore)) 
			  {	
		?>
						
			<tr>
				<td><div style="text-align:left;"><?php echo htmlspecialchars($rowInStore['department_name']); ?></div></td>
           			<?php
           				$department_id=$rowInStore['department_id'];
						$result_department_id = mysql_query("SELECT COUNT(*) FROM mbs_bookings_products WHERE mbs_bookings_products.booking_department_id = $department_id and DATE_FORMAT(booking_product_created_date,'%c')=$intMonth and DATE_FORMAT(booking_product_created_date,'%Y')=$intYear");
						$count_department_id = mysql_result($result_department_id, 0); 

					?>

					<td style="width:40%;"><div style="text-align:center;"><?php echo $count_department_id; ?></div></td>
					

			</tr>
           	
		
		<?php							
			}	// while ($rowStore = mysql_fetch_assoc($resultStore))
		?>
					
			</tbody>
			<tfoot>
				
			</tfoot>					
		</table>


		

		<?php	

		// The Log	
		$strLog = "View In-Store Activities for All Stores Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);

	} // function showReportGeneralDepartments($intYearMonth)


	// function showReportBookingBySupplier($intSupplierID, $intYearMonth)
	function showReportBookingBySupplier($intSupplierID, $intYearMonth)
	{


		global $STR_URL;		
		
		$strSupplierName = DB::dbIDToField('mbs_suppliers', 'supplier_id', intval($intSupplierID), 'supplier_name');
		$strReportTitle .= "Bookings Activities By Supplier <br />" . $strSupplierName . " Supplier ";

		$intMonth = intval(substr($intYearMonth, -2, 2));
		$intYear = intval(substr($intYearMonth, 0, 4));
		$strReportTitle .= HTML::getMonthName($intMonth) . " " . $intYear;

		$booking_id=1;

		?>

		<div style="clear:both;text-align:center;"><h2><?php echo $strReportTitle; ?></h3></div>
		
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="width:20%;" style="text-align:center;">Month/Year</th>					
					<th style="width:60%;" style="text-align:center;">Booking Activity Description</th>
					<th style="width:20%;" style="text-align:center;">Price</th>
				</tr>
			</thead>
			<tbody>				
				<?php 
						$booking = array();
						$querybookingid="SELECT * FROM mbs_bookings WHERE supplier_id='$intSupplierID'";
						//echo $querybookingid;
						$resultInquerybookingid = mysql_query($querybookingid);
						while ($rowBookingId = mysql_fetch_assoc($resultInquerybookingid)) 
							{						
								$booking[] = $rowBookingId['booking_id'];
								$i++;
							}

						$bookingid=implode(",", $booking);
					  	$queryInSupplier = "SELECT * FROM mbs_bookings_activities WHERE booking_activity_year=$intYear and booking_activity_month=$intMonth and booking_id in ($bookingid)";
					  	//echo $queryInSupplier;
					  	$resultInSupplier = mysql_query($queryInSupplier);
					  
					  	if ($resultInSupplier)
						{
							if (mysql_num_rows($resultInSupplier) > 0)
							{
							  	while ($rowInSupplier = mysql_fetch_assoc($resultInSupplier)) 
							  	{	
									?>
											
								<tr>
									<td><?php echo HTML::getMonthName($rowInSupplier['booking_activity_month']); ?> <?php echo stripslashes($rowInSupplier['booking_activity_year']); ?></td>
									<td><?php echo stripslashes($rowInSupplier['booking_activity_description']); ?></td>
									<td><div style="width:10%;" style="text-align:left;"><?php echo htmlspecialchars($rowInSupplier['booking_activity_price']); ?></div></td>
								</tr>
								<?php							
									}	// while ($rowStore = mysql_fetch_assoc($resultStore))
								
							}

							else
							{
								?>
								<tr>
									<td colspan="3"><div style="text-align:center;"><em>No data was found</em></div></td>	
								</tr>
								<?php
							}

						}?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>	

		<?php	

		// The Log	
		$strLog = "View In-Store by Month for Single Store Report for \"" . HTML::getMonthName($intMonth) . " " . $intYear . "\"";
			
		$queryLog = "INSERT INTO `logs` (`log_id`, 
										 `log_user`, 
										 `log_action`, 
										 `log_time`, 
										 `log_from`, 
										 `log_logout`)

					VALUES (NULL, 
							'" . $_SESSION['user']['login_name'] . "',
							'" . mysql_real_escape_string($strLog) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . $_SESSION['user']['ip_address'] . "', 
							NULL)";			
			
		$resultLog = mysql_query($queryLog);


		
	} // function showReportBySupplier($intProductID)

}	
?>