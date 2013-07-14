<?php

session_start();
include('../config.php');
require_once('../lib/db.php');
require_once('../lib/admin.php');
require_once('../lib/html.php');

$db = new DB();
$admin = new ADMIN();
$html = new HTML();


if ($_REQUEST['booking_id'])
{

	$db->dbConnect();

	// Get the booking activity
	$queryBookingActivity = "SELECT * FROM `mbs_bookings_activities` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' ORDER BY DATE(CONCAT(`booking_activity_year`,'-', `booking_activity_month`,'-','01'))";
	$resultBookingActivity = mysql_query($queryBookingActivity);

	$arrBookingActivityData = array();
	while ($rowBookingActivity = mysql_fetch_assoc($resultBookingActivity))
	{
		$arrBookingActivityData[] = $rowBookingActivity;
	}

	// Get the booking activity amount
	$queryBookingActivityAmount = "SELECT COUNT(*) FROM `mbs_bookings_activities` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "'";
	$resultBookingActivityAmount = mysql_query($queryBookingActivityAmount);
	$rowBookingActivityAmount = mysql_fetch_row($resultBookingActivityAmount);				
	$intBookingActivityAmount = $rowBookingActivityAmount[0];

?>


					    		<table class="table table-bordered table-hover">			  		  
									<thead class="well">
									<tr>
										<th style="text-align:center;"><strong>Month/Year</strong></th>
									  	<th style="text-align:center;"><strong>Promotional Agreement</strong></th>
									  	<th style="text-align:center;"><strong>Price</strong></th>
									  	<th style="text-align:center;"><strong>Action</strong></th>					  	
									</tr>			  
									</thead>

									<tbody>
									<?php if ($intBookingActivityAmount > 0) { ?>
									<?php for ($i = 0; $i < count($arrBookingActivityData); $i++) { ?>
									<?php if ($arrBookingActivityData[$i]['store_id']) { $arrStoreID = explode(',', $arrBookingActivityData[$i]['store_id']); $intStoreCount = count($arrStoreID); } ?>
									<?php if ($arrBookingActivityData[$i]['store_id']) { $strPrice = $arrBookingActivityData[$i]['booking_activity_price']*$intStoreCount; } else { $strPrice = $arrBookingActivityData[$i]['booking_activity_price']; } ?>
									<tr>
									  	<td><?php echo $html->getMonthName($arrBookingActivityData[$i]['booking_activity_month']); ?> <?php echo stripslashes($arrBookingActivityData[$i]['booking_activity_year']); ?></td>
									  	<td><?php echo stripslashes($arrBookingActivityData[$i]['booking_activity_description']); ?></td>
									  	<td style="width:10%;"><div style="text-align:right;">$<?php echo number_format($strPrice, 2); ?></div></td>
									  	<td style="width:20%;"><div style="text-align:center;"><button type="button" class="btn" id="frm_activity_edit_<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</button> 
									  		&nbsp;&nbsp;&nbsp; 
									  		<button type="button" class="btn" id="frm_activity_delete_<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Remove</a>
									  	    </div></td>
									</tr>
									<?php $intTotalAmount += $strPrice; ?>
									<?php } ?>	
									<?php } else { ?>
									<tr>
										<td colspan="4"><div align="center">No Promo Activity yet</div></td>
									</tr>	
									<?php } ?>
									<tr>
										<td colspan="2"><div style="text-align:right;"><strong>Total</strong></div></td>
										<td><div style="text-align:right;"><strong>$<?php echo number_format($intTotalAmount, 2); ?></strong></div></td>
										<td></td>
									</tr>	

									</tbody>
								</table>

								<script>
								$(document).ready(function() {
									//-- Rules for editing or removing the activities from the preview table
									<?php $arrActivities = $db->getActivitiesByBookingID($_REQUEST['booking_id']); ?>
									<?php if (count($arrActivities) > 0) { for ($i = 0; $i < count($arrActivities); $i++) { ?>
										
										//-- Edit button
										$('#frm_activity_edit_<?php echo $arrActivities[$i]['booking_activity_id']; ?>').click(function() { 
											
											$('#second-tab').show();
											$('#formtab-nav a[href="#tab2"]').tab('show');
											
											$('#frm_booking_activity_year').val('<?php echo $arrActivities[$i]['booking_activity_year']; ?>');
		    								$('#frm_booking_activity_month').val('<?php echo $arrActivities[$i]['booking_activity_month']; ?>');
		    								$('#frm_activity_id').val('<?php echo $arrActivities[$i]['activity_id']; ?>');		    			
		    								$('#frm_booking_activity_id_alt').val('<?php echo $arrActivities[$i]['booking_activity_id']; ?>');
		    								$('#frm_booking_product_id_alt').val('<?php echo $db->dbFieldToID('mbs_bookings_products', 'booking_activity_id', $arrActivities[$i]['booking_activity_id'], 'booking_product_id'); ?>');
		    								$('#frm_booking_id_alt').val('<?php echo $_REQUEST['booking_id']; ?>');
							    			$('#child_action').val('edit-activity');

							    			//-- Clear things up							    			
							    			<?php $arrStores = $db->getStoreData(); ?>
							    			<?php if (count($arrStores) > 0) { ?>
									    	<?php foreach ($arrStores as $intStoreID=>$strStoreName) { ?>
									    	$('#frm_store_id_<?php echo $intStoreID; ?>').prop('checked', false);
									    	<?php } ?>	
									    	<?php } ?>
									    	$('#total_price').html('');	

									    	//-- Populate Store ID
							    			<?php if ($arrActivities[$i]['store_id']) { ?>
							    			<?php $arrStoreID = explode(',', $arrActivities[$i]['store_id']); ?>
							    			<?php if (count($arrStoreID) > 0 && $arrStoreID[0]) 
												  { 
											?>
											store_count = <?php echo count($arrStoreID); ?>;
											$('#control-store-id').show();
											$('#store_count').html('<strong><em>' + store_count +'</strong></em>');
		    								$('#total_price').html('<strong><em>Total $ ' + parseFloat(store_count*<?php echo $arrActivities[$i]['booking_activity_price']; ?>).toFixed(2) +'</strong></em>');	
											<?php		
													for ($j = 0; $j < count($arrStoreID); $j++) 
													{
														if (strlen($arrStoreID[$j]) > 0 && $arrStoreID[$j] !== '0')
														{
														?>	
														$('#frm_store_id_<?php echo $arrStoreID[$j]; ?>').prop('checked', true);	
														<?php	
														}
													}

												  }

												  else
												  {
											?>
												  	$('#control-store-id').hide();	
											<?php
												  }
											?>
											<?php } ?>


											//-- Size ID
											<?php if ($arrActivities[$i]['size_id']) { ?> 
							    			$('#control-size-id').show();
							    			$('#frm_size_id').val('<?php echo $arrActivities[$i]['size_id']; ?>');
		    								<?php } else { ?>
		    								$('#control-size-id').hide();
		    								<?php } ?>	

		    								//-- Activity price
		    								$('#control-booking-activity-price').show();
		    								$('#frm_booking_activity_price').val('<?php echo $arrActivities[$i]['booking_activity_price']; ?>');

		    								//-- Products
		    								<?php $arrProducts = $db->getActivityProductByID($arrActivities[$i]['booking_activity_id']); ?>
		    								<?php if (count($arrProducts) > 0) {?>
		    								<?php for ($k = 0; $k < count($arrProducts); $k++) {?> 
		    									$('#frm_booking_product_code').val('<?php echo $arrProducts[$k]['booking_product_code']; ?>');
		    									$('#frm_booking_product_name').val('<?php echo $arrProducts[$k]['booking_product_name']; ?>');
		    									$('#frm_booking_product_normal_retail_price').val('<?php echo $arrProducts[$k]['booking_product_normal_retail_price']; ?>');
		    									$('#frm_booking_product_promo_price').val('<?php echo $arrProducts[$k]['booking_product_promo_price']; ?>');
		    									$('#frm_booking_product_cost_price').val('<?php echo $arrProducts[$k]['booking_product_cost_price']; ?>');
		    									$('#frm_booking_product_recommended_retail_price').val('<?php echo $arrProducts[$k]['booking_product_recommended_retail_price']; ?>');
		      									$('#frm_booking_product_special_offer_details').val('<?php echo $arrProducts[$k]['booking_product_special_offer_details']; ?>');
		      									
		    								<?php } ?>
		    								<?php } ?>
		    								
										
										});
										
										//-- Delete button
										$('#frm_activity_delete_<?php echo $arrActivities[$i]['booking_activity_id']; ?>').click(function() { 
											if (confirmDeleteBookingActivity())
											{
												$(this).closest('tr').remove();	

												var dataString = 'action=delete&booking_id=<?php echo $arrActivities[$i]['booking_id']; ?>&booking_activity_id=<?php echo $arrActivities[$i]['booking_activity_id']; ?>';	
						      				   
												var request = $.ajax({							    
													url: 'ajax/booking_proc.php',
													type: 'post', 
													data: dataString,
													success: function(msg) {
														
														$.gritter.add({				
															title: 'Info',				
															text: '<p>' + msg + '</p>',				
															image: '<?php echo $STR_URL; ?>img/accepted.png',				
															sticky: false,				
															time: '3000'
														});

														$('#frm_preview').load('ajax/booking_activity_preview.php?booking_id=<?php echo $arrActivities[$i]['booking_id']; ?>');

													}
														    
												});		

											}	
											return false;
										});

									<?php } } ?>	
								});
								</script>

<?php } ?>								