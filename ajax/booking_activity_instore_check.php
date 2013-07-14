<?php
include('../config.php');
require_once('../lib/db.php');

$db = new DB();


if ($_REQUEST['activity_id'] && $_REQUEST['year'] && $_REQUEST['month'])
{

	$db->dbConnect();
	$query = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
	$result = mysql_query($query);

	$data = array();
	while ($row = mysql_fetch_assoc($result))
	{
		$arrCheckResult = $db->checkActivityInStoreByDateTime(intval($_REQUEST['activity_id']), $row['store_id'], intval($_REQUEST['year']), intval($_REQUEST['month'])); 
		if (count($arrCheckResult) > 1)
		{
			$data[] = $arrCheckResult;
		}

	}

	#print_r($data);
	#echo $data[0]['store_id'];
	#echo count($data);
	if ($data[0]['store_id'])
	{
		//-- Get other stores
		$queryStore = "SELECT * FROM `mbs_stores` WHERE `store_id` NOT IN (" . $data[0]['store_id'] . ") ORDER BY `store_name`";
		$resultStore = mysql_query($queryStore);
		
		if ($resultStore)
		{
			$dataStore = array();
			while ($rowStore = mysql_fetch_assoc($resultStore)) 
			{
				$dataStore[$rowStore['store_id']] = array('store_name'=>$rowStore['store_name']);
			}
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
											echo "\n\t<input type=\"checkbox\" name=\"frm_store_id_" . $intStoreID . "\" id=\"frm_store_id_" . $intStoreID . "\" value=\"" . $intStoreID . "\"";  
														
											if ($_REQUEST['frm_store_id'] && $_REQUEST['frm_store_id'] == $intStoreID)
											{
												echo " checked=\"checked\"";
											}

											elseif (!$_REQUEST['frm_store_id'] && $rowActivity['store_id'] == $intStoreID) 
											{
												echo " checked=\"checked\"";
											}
														
											echo " /> " . stripslashes($arrStoresData['store_name']) . "</div>\n";
											
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
								    			$('#frm_store_id_<?php echo $intStoreID; ?>').prop('checked', true);
								    			store_count = <?php echo count($arrStores); ?>;
								    			$('#store_count').html('<strong><em>' + store_count +'</strong></em>');
								    			$('#total_price').html('<strong><em>Total $ ' + parseFloat(store_count*$('#frm_booking_activity_price').val()).toFixed(2) +'</strong></em>');
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
	
								