						<form id="frm_report" method="post" action="report.php">
						<fieldset>
							<div>
								<?php if (!$_REQUEST['report_by']) { ?><legend>Reports</legend><?php } ?>
							</div>	

							<div class="row-fluid">
								<div class="span12" style="margin-bottom:20px;">
									<p>Report by&nbsp;&nbsp;&nbsp;
									<label class="radio inline control-label"><input type="radio" id="frm_report_by_store" name="frm_report_by" value="store"<?php if ($_REQUEST['report_by'] == "store" || !$_REQUEST['frm_report_by']) { echo "checked='checked'"; } ?>> Store</label>
									<label class="radio inline control-label"><input type="radio" id="frm_report_by_activity" name="frm_report_by" value="activity"<?php if ($_REQUEST['report_by'] == "activity") { echo "checked='checked'"; } ?>> Activity</label>
									</p>
								</div>
							</div>

							<div class="row-fluid">
								<div id="control-report-by" class="control-group"></div>
							</div>

							<div class="row-fluid">
								<div id="control-store-id" class="control-group span4" style="display:none;">
							      	<!-- Store -->							      						     
							      	<div class="controls">
							      	<?php $arrStores = $db->getStoreData(); ?>								
								  	<select name="frm_store_id" id="frm_store_id" class="input-large">
								  		<option value="">-- Please select Store --</option>
									  	<?php
											if (is_array($arrStores) && count($arrStores) > 0)
											{
												echo "\n<option value=\"all\""; if ($_REQUEST['store_id'] && $_REQUEST['store_id'] == 'all') { echo " selected=\"selected\""; }  echo ">All Stores</option>\n";
												foreach ($arrStores as $intStoreID=>$arrStoresData)
												{
													echo "\n\t<option value=\"" . $intStoreID . "\"";  
													
													if ($_REQUEST['store_id'] && $_REQUEST['store_id'] == $intStoreID)
													{
														echo " selected=\"selected\"";
													}
													
													echo ">" . stripslashes($arrStoresData['store_name']) . "</option>";
												}
											}
									   	?>
								  	</select>

							      </div>
							    </div>

							    <div id="control-activity-id" class="control-group span4" style="display:none;">
							      	<!-- Activity -->							      
							      	<div class="controls">
							      	<?php $arrActivity = $db->getActivityData(); ?>
							      	<?php $intCountInStore = $db->dbGetAggregateData('COUNT', 'mbs_activities', 'activity_store_related', "WHERE `activity_store_related` = 'yes'"); ?>

								  		<select name="frm_activity_id" id="frm_activity_id">
								  			<option value="">-- Please select Activity --</option>
										  	<?php
											if (is_array($arrActivity) && count($arrActivity) > 0)
											{
												echo "\n<option value=\"all\""; if ($_REQUEST['activity_id'] && $_REQUEST['activity_id'] == 'all') { echo " selected=\"selected\""; }  echo ">All Activities</option>\n";
												
												$i = 0;
												foreach ($arrActivity as $intActivityID=>$arrActivityData)
												{
													
													if ($arrActivityData['activity_store_related'] == 'no' && $i == 0) 
													{ 
														echo "\n<optgroup label=\" --- Non In-Store Activities --- \">"; 
													} 
													
													elseif ($arrActivityData['activity_store_related'] == 'yes' && $i == (count($arrActivity)-$intCountInStore)) 
													{ 
														echo "\n<optgroup label=\" --- In-Store Activities --- \">"; 
													}	

													echo "\n\t<option value=\"" . $intActivityID . "\"";  
													
													if ($_REQUEST['activity_id'] && $_REQUEST['activity_id'] == $intActivityID)
													{
														echo " selected=\"selected\"";
													}
													echo ">" . stripslashes($arrActivityData['activity_name']) . "</option>";

													
													if ($arrActivityData['activity_store_related'] == 'no' && $i == (count($arrActivity)-$intCountInStore-1)) { echo "\n</optgroup>"; } 
													elseif ($arrActivityData['activity_store_related'] == 'yes' && $i == (count($arrActivity)-1)) { echo "\n</optgroup>"; }


													$i++;
												}
											}
										   	?>
								  		</select>

							      </div>
							    </div> 
							</div>	

							<div class="row-fluid">
								<label class="control-label" for="frm_report_sort_by">Sort By</label>
								<select id="frm_report_sort_by" name="frm_report_sort_by">
									<option value="">-- Please select --</option>
									<option value="products"<?php if ($_REQUEST['report_sort_by'] && $_REQUEST['report_sort_by'] == 'products') { echo " selected=\"selected\""; } ?>>Products</option>
									<option value="suppliers"<?php if ($_REQUEST['report_sort_by'] && $_REQUEST['report_sort_by'] == 'suppliers') { echo " selected=\"selected\""; } ?>>Suppliers</option>
									<option value="spots"<?php if ($_REQUEST['report_sort_by'] && $_REQUEST['report_sort_by'] == 'spots') { echo " selected=\"selected\""; } ?>>Spots</option>
									<option value="dollars"<?php if ($_REQUEST['report_sort_by'] && $_REQUEST['report_sort_by'] == 'dollars') { echo " selected=\"selected\""; } ?>>Dollars</option>
									<option value="months"<?php if ($_REQUEST['report_sort_by'] && $_REQUEST['report_sort_by'] == 'months') { echo " selected=\"selected\""; } ?>>Month</option>
									<option value="availability-by-month"<?php if ($_REQUEST['report_sort_by'] && $_REQUEST['report_sort_by'] == 'availability-by-month') { echo " selected=\"selected\""; } ?>>Availability by Month</option>
									<option value="availability-by-year"<?php if ($_REQUEST['report_sort_by'] && $_REQUEST['report_sort_by'] == 'availability-by-year') { echo " selected=\"selected\""; } ?>>Availability by Year</option>
								</select>
							</div>

							<div class="row-fluid">
								<div id="control-report-sort-by"></div>	
							</div>	

							
							<div class="row-fluid">
								<div id="control-booking-product-id" class="control-group" style="display:none;">
							      	<!-- Product -->
							      	<label class="control-label" for="frm_product_id">Product</label>	
							      	<div class="controls">
							      	<?php $arrProduct = $db->getProductData(); ?>				
								  	<select name="frm_booking_product_id" id="frm_booking_product_id" class="input-xlarge">
								  		<option value="">-- Please select Product --</option>
								  		
									  	<?php
										if (is_array($arrProduct) && count($arrProduct) > 0)
										{
											echo "\n<option value=\"all\""; if ($_REQUEST['booking_product_id'] && $_REQUEST['booking_product_id'] == 'all') { echo " selected=\"selected\""; }  echo ">All Products</option>\n";

											foreach ($arrProduct as $intProductID=>$arrProductData)
											{
												echo "\n<option value=\"" . $intProductID . "\"";  
												
												if ($_REQUEST['booking_product_id'] && $_REQUEST['booking_product_id'] == $intProductID)
												{
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrProductData['booking_product_name']) . " (Code: " . stripslashes($arrProductData['booking_product_code']) . ")</option>";
											}
										}
									   	?>
								  	</select>

							      </div>
							    </div>
							    
							</div>

							<div class="row-fluid">
								<div id="control-supplier-id" class="control-group" style="display:none;">
							      	<!-- Supplier -->							      
							      	<label class="control-label" for="frm_supplier_id">Supplier</label>	
							      	<div class="controls">
							      	<?php $arrSupplier = $db->getSupplierData(); ?>								
								  	<select name="frm_supplier_id" id="frm_supplier_id" class="input-xlarge">
								  		<option value="">-- Please select Supplier --</option>
								  		
									    <?php
										if (is_array($arrSupplier) && count($arrSupplier) > 0)
										{
											echo "\n<option value=\"all\""; if ($_REQUEST['supplier_id'] && $_REQUEST['supplier_id'] == 'all') { echo " selected=\"selected\""; }  echo ">All Suppliers</option>\n";

											foreach ($arrSupplier as $intSupplierID=>$arrSupplierData)
											{
												echo "\n\t<option value=\"" . $intSupplierID . "\"";  
												
												if ($_REQUEST['supplier_id'] && $_REQUEST['supplier_id'] == $intSupplierID)
												{
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrSupplierData['supplier_name']) . "</option>";
											}
										}
									   ?>
								  	</select>

							      </div>
							    </div>
							</div>	

							<div class="row-fluid">
								<div id="control-spots" class="control-group" style="display:none;">
							      	<!-- Spots -->							      
							      	<label class="control-label" for="frm_spot_id">Spots</label>	
							      	<div class="controls">
							      	<?php $arrSizes = $db->getSizeData(); ?>								
								  	<select name="frm_size_id" id="frm_size_id" class="input-xlarge">
								  		<option value="">-- Please select Spot --</option>
								  		
									  	<?php
										if (is_array($arrSizes) && count($arrSizes) > 0)
										{
											echo "\n<option value=\"all\""; if ($_REQUEST['size_id'] && $_REQUEST['size_id'] == 'all') { echo " selected=\"selected\""; }  echo ">All Spots</option>\n";

											foreach ($arrSizes as $intSizeID=>$arrSizeData)
											{
												echo "\n\t<option value=\"" . $intSizeID . "\"";  
												
												if ($_REQUEST['size_id'] && $_REQUEST['size_id'] == $intSizeID)
												{
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrSizeData['size_name']) . "</option>";
											}
										}
									   	?>
								  </select>
							      </div>
							    </div>
							</div>


							<div class="row-fluid">
								<div id="control-dollars" class="control-group" style="display:none;">
							      	<!-- Dollars -->							      
							      	<label class="control-label" for="frm_dollar_id">Dollars</label>	
							      	<div class="controls">							      
								  	<select name="frm_dollar_id" id="frm_dollar_id" class="input-xlarge">
								  		<option value="">-- Please select --</option>
								  		<option value="all"<?php if ($_REQUEST['dollar_id'] && $_REQUEST['dollar_id'] == 'all') { echo " selected=\"selected\""; } ?>>All Dollars</option>
								  		<option value="1"<?php if ($_REQUEST['dollar_id'] && $_REQUEST['dollar_id'] == '1') { echo " selected=\"selected\""; } ?>>Below $500</option>
								  		<option value="2"<?php if ($_REQUEST['dollar_id'] && $_REQUEST['dollar_id'] == '2') { echo " selected=\"selected\""; } ?>>$500 to $1,000</option>
								  		<option value="3"<?php if ($_REQUEST['dollar_id'] && $_REQUEST['dollar_id'] == '3') { echo " selected=\"selected\""; } ?>>$1,000 to $5,000</option>
								  		<option value="4"<?php if ($_REQUEST['dollar_id'] && $_REQUEST['dollar_id'] == '4') { echo " selected=\"selected\""; } ?>>$5,000 to $10,000</option>
								  		<option value="5"<?php if ($_REQUEST['dollar_id'] && $_REQUEST['dollar_id'] == '5') { echo " selected=\"selected\""; } ?>>Above $10,000</option>
								  	</select>

							      </div>
							    </div>
							</div>

							<div class="control-group">
								<div id="control-months" class="control-group" style="display:none;">
							      <!-- Month -->
							      <label class="control-label" for="frm_month">Month</label>
							      <div class="controls">			      	
							      	<select name="frm_month" id="frm_month" class="input-xlarge">							      		
							      		<option value="">-- Please select Month --</option>
							      		<option value="all"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == 'all') { echo " selected=\"selected\""; } ?>>All Months</option>
							      		<option value="1"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '1') { echo " selected=\"selected\""; } ?>>January</option>
							      		<option value="2"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '2') { echo " selected=\"selected\""; } ?>>February</option>
							      		<option value="3"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '3') { echo " selected=\"selected\""; } ?>>March</option>
							      		<option value="4"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '4') { echo " selected=\"selected\""; } ?>>April</option>
							      		<option value="5"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '5') { echo " selected=\"selected\""; } ?>>May</option>
							      		<option value="6"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '6') { echo " selected=\"selected\""; } ?>>June</option>
							      		<option value="7"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '7') { echo " selected=\"selected\""; } ?>>July</option>
							      		<option value="8"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '8') { echo " selected=\"selected\""; } ?>>August</option>
							      		<option value="9"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '9') { echo " selected=\"selected\""; } ?>>September</option>
							      		<option value="10"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '10') { echo " selected=\"selected\""; } ?>>October</option>
							      		<option value="11"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '11') { echo " selected=\"selected\""; } ?>>November</option>
							      		<option value="12"<?php if ($_REQUEST['month'] && $_REQUEST['month'] == '12') { echo " selected=\"selected\""; } ?>>December</option>
							      		
							      	</select>
							       </div>
							    </div>   
							</div>

							<div class="control-group">
								<div id="control-availability-by-month" class="control-group" style="display:none;">
							      <!-- Availability by Month -->
							      <label class="control-label" for="frm_availability_by_month">Availability by Month</label>
							      <div class="controls">			      	
							      	<select name="frm_availability_by_month" id="frm_availability_by_month" class="input-xlarge">							      		
							      		<option value="">-- Please select Month --</option>
							      		<option value="all"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == 'all') { echo " selected=\"selected\""; } ?>>All Months</option>
							      		<option value="1"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '1') { echo " selected=\"selected\""; } ?>>January</option>
							      		<option value="2"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '2') { echo " selected=\"selected\""; } ?>>February</option>
							      		<option value="3"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '3') { echo " selected=\"selected\""; } ?>>March</option>
							      		<option value="4"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '4') { echo " selected=\"selected\""; } ?>>April</option>
							      		<option value="5"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '5') { echo " selected=\"selected\""; } ?>>May</option>
							      		<option value="6"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '6') { echo " selected=\"selected\""; } ?>>June</option>
							      		<option value="7"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '7') { echo " selected=\"selected\""; } ?>>July</option>
							      		<option value="8"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '8') { echo " selected=\"selected\""; } ?>>August</option>
							      		<option value="9"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '9') { echo " selected=\"selected\""; } ?>>September</option>
							      		<option value="10"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '10') { echo " selected=\"selected\""; } ?>>October</option>
							      		<option value="11"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '11') { echo " selected=\"selected\""; } ?>>November</option>
							      		<option value="12"<?php if ($_REQUEST['availability_by_month'] && $_REQUEST['availability_by_month'] == '12') { echo " selected=\"selected\""; } ?>>December</option>
							      		
							      	</select>
							       </div>
							    </div>   
							</div>

							<div class="control-group">
								<div id="control-availability-by-year" class="control-group" style="display:none;">
							      <!-- Booking Promo Year -->
							      <label class="control-label" for="frm_availability_by_year">Availability by Year</label>
							      <div class="controls">			      	
							      	<select name="frm_availability_by_year" id="frm_availability_by_year" class="input-xlarge">
							      		<option value="">-- Please select Year --</option>
							      		<option value="all">All Years</option>							      		
							      		<?php for ($i = intval(date('Y')-5); $i <= intval(date('Y') + 5); $i++) { ?>
							      		<option value="<?php echo $i; ?>"<?php if ($_REQUEST['availability_by_year'] && $_REQUEST['availability_by_year'] == $i) { echo " selected=\"selected\""; } ?>><?php echo $i; ?></option>
							      		<?php } ?>
							      	</select>  							        
							      </div>
							    </div>  
							</div>	


							<div class="control-group">
			      			<!-- Button -->
			      				<div class="controls" style="text-align:center;margin-top:40px;">
			        				<button class="btn btn-inverse" type="button" name="frm_submit" id="frm_submit">View Report</button>
			      				</div>
			    			</div>


						</fieldset>
						</form>


			<script>
   			$(document).ready(function() {
   				
   				//-- Load default: Store ID
   				<?php if (!$_REQUEST['store_id'] && !$_REQUEST['activity_id']) { ?>
   				$('#control-report-by').html($('#control-store-id').html());
   				<?php } ?>

   				//-- By Store
   				$('#frm_report_by_store').click(function() {
   					
   					if ($(this).is(':checked') && $(this).val() == 'store')
	   				{   	   					
	   					$('#control-report-by').html($('#control-store-id').html());	   								
	   				}
	   			});	
	   			
	   			//-- By Activity	
	   			$('#frm_report_by_activity').click(function() {	
	   				
	   				if ($(this).is(':checked') && $(this).val() == 'activity')
	   				{	
	   					$('#control-report-by').html($('#control-activity-id').html());
	   				}	

   				});

	   			//-- Report to
	   			$('#frm_report_sort_by').change(function() {
	   				
	   				//-- check report by options first
	   				if ($('#frm_store_id option:selected').val() == '' && $('#frm_activity_id option:selected').val() == '')
	   				{
	   					if ($('#frm_report_by_store').is(':checked'))
	   					{
	   						$('#frm_store_id').attr('style', 'border:2px solid #c00;');
	   						$('#frm_store_id').focus();	

	   					}

	   					else if ($('#frm_report_by_activity').is(':checked'))
	   					{
	   						$('#frm_activity_id').attr('style', 'border:2px solid #c00;');
	   						$('#frm_activity_id').focus();	
	   					}
	   				
	   				}

	   				//-- actions
	   				if ($('#frm_report_sort_by option:selected')) 
	   				{	   					
	   					if ($(this).val() == 'products')
	   					{
	   						$('#control-report-sort-by').html($('#control-booking-product-id').html());		
	   					}
	   					else if ($(this).val() == 'suppliers')
	   					{
	   						$('#control-report-sort-by').html($('#control-supplier-id').html());	
	   					}
	   					else if ($(this).val() == 'spots')
	   					{
	   						$('#control-report-sort-by').html($('#control-spots').html());		
	   					}
	   					else if ($(this).val() == 'dollars')
	   					{
	   						$('#control-report-sort-by').html($('#control-dollars').html());		
	   					}
	   					else if ($(this).val() == 'months')
	   					{
	   						$('#control-report-sort-by').html($('#control-months').html());		
	   					}
	   					else if ($(this).val() == 'availability-by-month')
	   					{
	   						$('#control-report-sort-by').html($('#control-availability-by-month').html());		
	   					}
	   					else if ($(this).val() == 'availability-by-year')
	   					{
	   						$('#control-report-sort-by').html($('#control-availability-by-year').html());		
	   					}
	   				}

	   			});



   				//-- Submit
   				$('#frm_submit').click(function() {
   					
   					//-- Check by store or by activity   					
   					if ($('#frm_store_id').val() == '' && $('#frm_activity_id').val() == '')
   					{
   						alert('Please select Report By!');
   						$('#frm_store_id').focus();
   						$('#frm_activity_id').focus();
   						return false;    						
   					}	

   					//-- Check sort by
   					if ($('#frm_report_sort_by').val() == '') 
   					{ 
   						alert('Please select Sort By!'); 
   						$('#frm_report_sort_by').focus();
   						return false;
   					}

   					//-- Check inputs
					if ($('#frm_report_sort_by').val() == 'products' && $('#frm_booking_product_id option:selected').val() == '') 
   					{ 
   						alert('Please select Product!'); 
   						$('#frm_booking_product_id').focus();
   						return false;
   					}   					

   					if ($('#frm_report_sort_by').val() == 'suppliers' && $('#frm_supplier_id option:selected').val() == '') 
   					{ 
   						alert('Please select Supplier!'); 
   						$('#frm_supplier_id').focus();
   						return false;
   					} 

   					if ($('#frm_report_sort_by').val() == 'spots' && $('#frm_size_id option:selected').val() == '') 
   					{ 
   						alert('Please select Spot!'); 
   						$('#frm_size_id').focus();
   						return false;
   					} 

   					if ($('#frm_report_sort_by').val() == 'dollars' && $('#frm_dollar_id option:selected').val() == '') 
   					{ 
   						alert('Please select Dollar range!'); 
   						$('#frm_dollar_id').focus();
   						return false;
   					} 

   					if ($('#frm_report_sort_by').val() == 'months' && $('#frm_month option:selected').val() == '') 
   					{ 
   						alert('Please select Month!'); 
   						$('#frm_month').focus();
   						return false;
   					} 

   					if ($('#frm_report_sort_by').val() == 'availability-by-month' && $('#frm_availability_by_month option:selected').val() == '') 
   					{ 
   						alert('Please select Month!'); 
   						$('#frm_availability_by_month').focus();
   						return false;
   					} 

   					if ($('#frm_report_sort_by').val() == 'availability-by-year' && $('#frm_availability_by_year option:selected').val() == '') 
   					{ 
   						alert('Please select Year!'); 
   						$('#frm_availability_by_year').focus();
   						return false;
   					} 

   					var report_by; if ($('#frm_report_by_store').is(':checked')) { report_by = $('#frm_report_by_store').val(); } else { report_by = $('#frm_report_by_activity').val(); }
   					var store_id = $('#frm_store_id').val();
   					var activity_id = $('#frm_activity_id').val();
   					var report_sort_by = $('#frm_report_sort_by').val();
   					var booking_product_id = $('#frm_booking_product_id').val();
   					var supplier_id = $('#frm_supplier_id').val();
   					var size_id = $('#frm_size_id').val();
   					var dollar_id = $('#frm_dollar_id').val();
   					var month = $('#frm_month').val();
   					var availability_by_month = $('#frm_availability_by_month').val();
   					var availability_by_year = $('#frm_availability_by_year').val();

   					window.location = "<?php echo $STR_URL; ?>reports.php?report_by=" + report_by + "&store_id=" + store_id + "&activity_id=" + activity_id + "&report_sort_by=" + report_sort_by + "&booking_product_id=" + booking_product_id + "&supplier_id=" + supplier_id + "&size_id=" + size_id + "&dollar_id=" + dollar_id + "&month=" + month + "&availability_by_month=" + availability_by_month + "&availability_by_year=" + availability_by_year;

   				});

   			});
   		</script>