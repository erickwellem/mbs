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


if ($_REQUEST['activity_id'] && $_REQUEST['year'] && $_REQUEST['month'])
{

	$db->dbConnect();
	$query = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
	$result = mysql_query($query);

	$dataStore = array();
	while ($row = mysql_fetch_assoc($result))
	{
		$query = "SELECT * FROM `mbs_bookings_activities` WHERE 
						(
							store_id REGEXP '^".$row['store_id'].",' OR
							store_id REGEXP ',".$row['store_id'].",' OR
							store_id REGEXP ',".$row['store_id']."$' OR
							store_id REGEXP '^".$row['store_id']."$'  
						
						)AND activity_id = " .$_REQUEST['activity_id'] . " AND booking_activity_year = " .$_REQUEST['year'] . " AND booking_activity_month = " .$_REQUEST['month'] ;
		$resultActivities = mysql_query($query);
		$countResult = mysql_num_rows($resultActivities);
		$countResult = ($countResult == 3 ? 'no':'yes');
		$dataStore[$row['store_id']] = array('store_name'=>$row['store_name'], 'count'=>$countResult);
	}
	
	$fieldNameActivity = $db->dbIDToField("mbs_activities", "activity_id",$_REQUEST['activity_id'] , "activity_name");
	if(strpos($fieldNameActivity,'Gondola') !== false && strpos($fieldNameActivity,'1 Store for 12 Months') !== false){
		
		$query = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
		$result = mysql_query($query);
	
		$dataStore = array();
		while ($row = mysql_fetch_assoc($result))
		{
			$query = "SELECT * FROM `mbs_bookings_activities` WHERE 
						(
							store_id REGEXP '^".$row['store_id'].",' OR
							store_id REGEXP ',".$row['store_id'].",' OR
							store_id REGEXP ',".$row['store_id']."$' OR
							store_id REGEXP '^".$row['store_id']."$' 
						
						)AND activity_id = " .$_REQUEST['activity_id'] . " AND booking_activity_year = " .$_REQUEST['year'] . " AND booking_activity_month = " .$_REQUEST['month'] ;
			$resultActivities = mysql_query($query);
			$countResult = mysql_num_rows($resultActivities);
			$countResult = ($countResult == 1 ? 'no':'yes');
			$dataStore[$row['store_id']] = array('store_name'=>$row['store_name'], 'count'=>$countResult);
		}
	}
	
}


?>
								<!-- Store -->								
								<label class="control-label" for="frm_store_id">Store</label>
								<div class="controls">
								<?php if ($dataStore) { $arrStores = $dataStore; } else { $arrStores = $db->getStoreData(); }?>								
								<?php
									
									if (is_array($arrStores) && count($arrStores) > 0)
									{
										
										foreach ($arrStores as $intStoreID=>$arrStoresData)
										{
											
											echo "<div style=\"border-bottom:1px solid #eee;width:40%;\">";
											echo "\n\t<input ".($arrStoresData['count']=="no"?"disabled":"")." type=\"checkbox\" name=\"frm_store_id_" . $intStoreID . "\" id=\"frm_store_id_" . $intStoreID . "\" value=\"" . $intStoreID . "\"";  
														
											if ($_REQUEST['frm_store_id'] && $_REQUEST['frm_store_id'] == $intStoreID)
											{
												echo " checked=\"checked\"";
											}

											elseif (!$_REQUEST['frm_store_id'] && $rowActivity['store_id'] == $intStoreID) 
											{
												echo " checked=\"checked\"";
											}
														
											echo " /> " . stripslashes($arrStoresData['store_name']) ." &nbsp; &nbsp; &nbsp; &nbsp;". ($arrStoresData['count']=='no'?"<span class=\"label label-important\">Not Available":"<span class=\"label label-success\">Available"). "</span></div>\n";
											
										}
									}
								?>
								<br /><input type="checkbox" id="frm_check_all" value="check-all" /> All stores	
								<br /><br /><div style="float:left;">Store(s) selected: </div> <div id="store_count"></div>
								<!--<br /><small>Store is not in the list? Please <a class="ajax callbacks cboxElement" href="store.php?action=add&pop=yes">insert a new one</a>.</small>-->

								</div>

								<script>
									$(document).ready(function () {
										//-- Check all stores functionality
										store_count = 0;
										
										$('#frm_check_all').click(function() { 
											//-- Check all and update the store count
											if ($('#frm_check_all').is(':checked')) {
												
												<?php if ($dataStore) { $arrStores = $dataStore; } else { $arrStores = $db->getStoreData(); }?>
												<?php if (count($arrStores) > 0) { ?>
								    			<?php foreach ($arrStores as $intStoreID=>$strStoreName) { ?>
								    				<?php if($strStoreName['count']!='no'):?>
									    			$('#frm_store_id_<?php echo $intStoreID; ?>').prop('checked', true);
									    			store_count = <?php echo count($arrStores); ?>;
									    			$('#store_count').html('<strong><em>' + store_count +'</strong></em>');
									    			$('#total_price').html('<strong><em>Total $ ' + parseFloat(store_count*$('#frm_booking_activity_price').val()).toFixed(2) +'</strong></em>');
								    				<?php endif;?>
								    			<?php } ?>		    			
								    			<?php } ?>

											}
											//-- Uncheck all and update the store count back to 0
											else
											{
												
												<?php if ($dataStore) { $arrStores = $dataStore; } else { $arrStores = $db->getStoreData(); }?>
												<?php if (count($arrStores) > 0) { ?>
								    			<?php foreach ($arrStores as $intStoreID=>$strStoreName) { ?>	
								    			$('#frm_store_id_<?php echo $intStoreID; ?>').prop('checked', false);
								    			store_count = 0;
								    			$('#store_count').html('<strong><em>' + store_count +'</strong></em>');
								    			$('#total_price').html('<strong><em>Total $ ' + parseFloat(store_count*$('#frm_booking_activity_price').val()).toFixed(2) +'</strong></em>');
								    			<?php } ?>		    			
								    			<?php } ?>

											}

										});

										//-- Get the check all checkbox checked or unchecked if the items were checked manually
										<?php if ($dataStore) { $arrStores = $dataStore; } else { $arrStores = $db->getStoreData(); }?>
										<?php if (count($arrStores) > 0) { ?>
								    	<?php foreach ($arrStores as $intStoreID=>$strStoreName) { ?>
								    		$('#frm_store_id_<?php echo $intStoreID; ?>').click(function() {
								    			store_count = $('#store_count').text();
								    			if ($(this).is(':checked')) { store_count++; } else { store_count--; } 
								    			if ($('#frm_check_all').is(':checked') && (store_count < <?php echo count($arrStores); ?>)) { $('#frm_check_all').prop('checked', false); } 
								    			else if (store_count == <?php echo count($arrStores); ?>) { $('#frm_check_all').prop('checked', true); store_count = <?php echo count($arrStores); ?>; }
								    			$('#store_count').html('<strong><em>' + store_count +'</strong></em>');
								    			$('#total_price').html('<strong><em>Total $ ' + parseFloat(store_count*$('#frm_booking_activity_price').val()).toFixed(2) +'</strong></em>');
								    		});
								    		<?php } ?>

								    	<?php } ?>

										$('#store_count').text(store_count);
										//-- end of Check all stores functionality	
									});
								</script>
	
								