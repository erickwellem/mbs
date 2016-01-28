<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<?php 
	// Page Config
	$strPageName = "Booking";
	$strPageIDName = "booking_id";
	$strDBTableName = "mbs_bookings";
	$strDBFieldName = "booking_name";
	$strDBFieldCode = "booking_code";

	// determine the state
	if (!$_REQUEST['action']) { $_REQUEST['action'] = "add"; } 
	
	$strState = "";
	
	if ($_REQUEST['action'] == "add") { $strState = "Insert New"; } elseif ($_REQUEST['action'] == "edit") { $strState = "Update"; } else { $strState = "Insert New"; }

	if ($_REQUEST[$strPageIDName])
	{
		$strPageItemName = DB::dbIDtoField($strDBTableName, $strPageIDName, $_REQUEST[$strPageIDName], $strDBFieldName);
		$strPageItemCode = DB::dbIDtoField($strDBTableName, $strPageIDName, $_REQUEST[$strPageIDName], $strDBFieldCode);

		$strQuery = "SELECT * FROM `" . $strDBTableName . "` WHERE `" . $strPageIDName . "` = '" . mysql_real_escape_string($_REQUEST[$strPageIDName]) .  "' LIMIT 1";
		$result = mysql_query($strQuery);

		if ($result)
		{
			$row = mysql_fetch_assoc($result);
		}	

		// for delete
		if ($_REQUEST['action'] == "delete" && $strPageItemName)
		{
			$strQueryDelete = "DELETE FROM `" . $strDBTableName . "` WHERE `" . $strPageIDName . "` = '" . mysql_real_escape_string($_REQUEST[$strPageIDName]) .  "' LIMIT 1";
			$resultQueryDelete = mysql_query($strQueryDelete);

			$strLog = 'Booking named "' . $strPageItemName . ' (Code: ' . $strPageItemCode . ')" is successfully deleted.';
					
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										 	 `log_user`, 
										 	 `log_action`, 
										 	 `log_time`, 
										 	 `log_from`, 
										 	 `log_logout`)

								VALUES (NULL, 
										'" . $_SESSION['user']['login_name'] . "',
										'" . addslashes($strLog) . "',
										'" . date('Y-m-d H:i:s') . "',
										'" . $_SESSION['user']['ip_address'] . "', 
										NULL)";
					
			$resultLog = mysql_query($queryLog);
			
		}
	
	}	
	
?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
    <title><?php echo $strState; ?> <?php echo $strPageName; ?> | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	
    <meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
    
    <!-- Select2 jQuery Plugin -->
    <link href="<?php echo $STR_URL; ?>css/select2-3.4.3/select2.css" rel="stylesheet"/>
    <script src="<?php echo $STR_URL; ?>css/select2-3.4.3/select2.js"></script>
    <script>
		$(function(){
			$("#frm_booking_product_code").select2({tags:[]});
		})
    </script>
    <!-- Select2 jQuery Plugin -->

	
	<?php if ($_REQUEST['action'] == "add") { ?>
	<!-- JS for Page -->
	<script>

        pic1 = new Image(16, 16); 
		pic1.src = "<?php echo $STR_URL; ?>img/loading.gif";
				
		$(document).ready(function() {
				
			$("#frm_booking_code").blur(function() { 
				
				var strcheck = $("#frm_booking_code").val();
					
				if (strcheck.length >= 3)
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/loading.gif" /> Checking <?php echo $strPageName; ?>...');
					
						$.ajax({ 
								type: "post", 
								url: "ajax/booking_check.php", 
								data: "frm_booking_code="+strcheck, 
								success: function(msg) { 
									
									if (msg == "yes") 
									{
										$("#frm_booking_code").removeClass("status_not_ok"); 
										$("#frm_booking_code").addClass("status_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/accepted.png" /> <span class="greentext"><?php echo $strPageName; ?> Code is available!</span>');		
									}

									else if (msg == "no") 
									{ 
										$("#frm_booking_code").removeClass("status_ok"); 
										$("#frm_booking_code").addClass("status_not_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <span class="redtext"><?php echo $strPageName; ?> Code is already exist in the database! Please try something else!</span>'); 
									}
									
								}
							
						});
						
				}
							
				else						
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <?php echo $strPageName; ?> Code must contain of 3 characters minimum!');
					$("#frm_booking_code").removeClass('status_ok'); // if necessary
					$("#frm_booking_code").addClass("status_not_ok");
							
				}
			});
				
		});	

		//-->
	
	</script>
    
    <?php } elseif ($_REQUEST['action'] == "delete") { ?>
		<!-- For Delete -->
		<?php 
			if ($resultQueryDelete && $strPageItemName) 
			{ 
				$strMessage = $strPageName . " named \"" . $strPageItemName . " (Code: " . $strPageItemCode . ")\" is successfully deleted!"; 
				$strImageInfo = "accepted.png";
			} 

			else 
			{ 
				$strMessage = "Failed to delete " . $strPageName . " (Code: " . $strPageItemCode . ") or there is no record found!"; 
				$strImageInfo = "unaccepted.png";
			}  
		?>
		
		<script>
			$(document).ready(function() {
				$.gritter.add({				
					title: 'Info',				
					text: '<p><?php echo $strMessage; ?></p>',				
					image: '<?php echo $STR_URL; ?>img/<?php echo $strImageInfo; ?>',				
					sticky: false,				
					time: '3000'
				});

				//--clearForm();
			});
		</script>

	<?php } ?>				
	

</head> 

<body>

	<?php if (!$_REQUEST['pop']) { include('inc/header.php'); } ?>

<div id="wrapper" class="container-fluid">

	<?php if ($admin->isLoggedIn() > 0 && !$_REQUEST['pop']) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

    		<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>			  
			  <li><a href="booking_list.php">Bookings</a> <span class="divider">/</span></li>
			  <li class="active"><?php if ($_REQUEST['action'] == "add") { ?>New<?php } elseif ($_REQUEST['action'] == "edit") { ?>Update<?php } ?> <?php echo $strPageName; ?></li>
			</ul>	

			<h2 class="booking-code-title"><?php echo $strState; ?> <?php echo $strPageName; ?> <?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { echo " &raquo; " . $strPageItemName; } ?></h2>

			<!-- Menu -->
			<div class="pull-right">
				<?php if ($admin->getModulePrivilege('bookings', 'add') > 0 && $_REQUEST['action'] == "edit") { ?>
				<a class="btn btn-popover" href="booking.php?action=add" rel="popover" data-content="Insert new Booking to the database" data-original-title="New <?php echo $strPageName; ?>" title="New <?php echo $strPageName; ?>"><i class="icon-plus"></i> New</a>
				<?php } ?>
				<?php if ($admin->getModulePrivilege('bookings', 'view') > 0 && $_REQUEST['action'] == "edit") { ?>
				<a class="btn btn-popover" href="booking_view.php?booking_id=<?php echo $_REQUEST['booking_id']; ?>&action=view" rel="popover" data-content="View this Booking details" data-original-title="View <?php echo $strPageName; ?>" title="View <?php echo $strPageName; ?>"><i class="icon-info-sign"></i> View</a>
				<?php } ?>
				<?php if ($admin->getModulePrivilege('bookings', 'delete') > 0 && $_REQUEST['action'] == "edit") { ?>
				<a class="btn btn-popover" href="booking_list.php?booking_id=<?php echo $_REQUEST['booking_id']; ?>&action=delete" onclick="return confirmDeleteBooking(this.form)" rel="popover" data-content="Delete this Booking from the database" data-original-title="Delete <?php echo $strPageName; ?>" title="Delete <?php echo $strPageName; ?>"><i class="icon-remove"></i> Delete</a>
				<?php } ?>
				<?php if ($admin->getModulePrivilege('bookings', 'list') > 0) { ?>
				<a class="btn btn-popover" href="booking_list.php" rel="popover" data-content="Show Booking table from the database" data-original-title="<?php echo $strPageName; ?> List" title="<?php echo $strPageName; ?> List"><i class="icon-list-alt"></i> List</a>			
				<?php } ?>
				<a class="btn btn-popover" href="documentation_list.php#bookings" rel="popover" data-content="Look up for the Documentation about Booking module" data-original-title="Help" title="Help"><i class="icon-info-sign"></i> Help</a>
			</div>

			<?php
				// generate booking code
				$strBookingCode = $html->generateBookingCode();

				// convert booking date format to dd-mm-yyyy
				$arrBookingDate = explode('-', $row['booking_date']);
				$row['booking_date'] = $arrBookingDate[2] . "-" . $arrBookingDate[1] . "-" . $arrBookingDate[0];

				// get supplier reference number
				if ($_REQUEST['action'] == 'edit' || ($_REQUEST['action'] == 'add' && $_REQUEST['booking_id']))
				{
					$strSupplierRefNo = $row['booking_supplier_po_ref_number'];
				}

				else
				{
					$strSupplierRefNo = $db->dbIDtoField('mbs_suppliers', 'supplier_id', $row['supplier_id'], 'frm_supplier_po_ref_number');
				}

				// if $_REQUEST['booking_activity_id'] exists
				if ($_REQUEST['booking_activity_id'])
				{
					$queryActivity = "SELECT * FROM `mbs_bookings_activities` WHERE `booking_activity_id` = '" . $_REQUEST['booking_activity_id'] . "' LIMIT 1";
					$resultActivity = mysql_query($queryActivity);

					if ($resultActivity) { $rowActivity = mysql_fetch_assoc($resultActivity); }

					$queryProduct = "SELECT * FROM `mbs_bookings_products` WHERE `booking_activity_id` = '" . $_REQUEST['booking_activity_id'] . "' LIMIT 1";
					$resultProduct = mysql_query($queryProduct);

					if ($resultProduct) { $rowProduct = mysql_fetch_assoc($resultProduct); }

					// populate store id's					
					if ($rowActivity['store_id'])
					{
						?>
						<script>
						$(document).ready(function(){ 
						
						<?php $arrStoreID = explode(',', $rowActivity['store_id']); ?>
						
						<?php
						if (count($arrStoreID) > 0)
						{
							for ($i = 0; $i < count($arrStoreID); $i++)
							{
								?>
								$('#frm_store_id_<?php echo $arrStoreID[$i]; ?>').prop('checked', true);
								<?php
							}	

						}
						?>

						});
						</script>
						<?php
					} // if ($rowActivity['store_id'])

				} // if ($_REQUEST['booking_activity_id'])

			?>

			<div style="clear:both;margin-bottom:20px;"></div>

			<a href="#" id="top"></a>

			<!-- Form -->
			<form id="frm_booking" class="form-horizontal" action="" method="post">
				<?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { ?><input type="hidden" name="<?php echo $strPageIDName; ?>" id="<?php echo $strPageIDName; ?>" value="<?php echo $_REQUEST[$strPageIDName]; ?>" /><?php } ?>
				<input type="hidden" name="action" id="action" value="<?php if ($_REQUEST['action'] == "edit") { echo "edit"; } else { echo "add"; } ?>" />
				
				<?php if ($_REQUEST['action'] == 'edit') { ?>
				<div class="alert">
  					<button type="button" class="close" data-dismiss="alert">&times;</button>
  					<strong>Note:</strong> 
  					<ul style="margin-left:20px;">	
  						<li>Please click the <strong>Preview</strong> tab to edit the promotional activities and products of this Booking!</li> 
  						<li>Click the Update Booking button to update the Supplier &amp; Date only.</li>
  						<li>Please allow a few seconds before the table content update itself without reloading the page every time the Preview tab is clicked.</li>
  					</ul>
				</div>	
				<?php } elseif ($_REQUEST['action'] == 'add') { ?>
				<div class="alert">
  					<button type="button" class="close" data-dismiss="alert">&times;</button>
  					<strong>Note:</strong> 
  					<ul style="margin-left:20px;">	
  						<li>Please fill Booking data in all tabs: Supplier &amp; Date, Activity and Product and then click Submit button at the last tab</li> 
  						<li>Click the Preview tab that will appear after inserting the data to view, add another Promotional Activity or finish</li>
  						<li>Please allow a few seconds before the table content update itself without reloading the page every time the Preview tab is clicked.</li>
  					</ul>
				</div>
				<?php } ?>

				<div class="tabbable">
					<ul class="nav nav-tabs" id="formtab-nav">
						<li class="active" id="first-tab"><a href="#tab1" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the basic Booking data e.g Code (will be generated automatically by the application), Ref No (will be automatically filled if available when a Supplier is selected) and Booking Date (in dd-mm-yyyy format which is selected through the popped up calendar). Please fill all required fields (*)" data-original-title="Supplier & Tab"><i class="icon-align-justify"></i> Supplier &amp; Date</a></li>
						<li id="second-tab"><a href="#tab2" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the Booking Promotional Activities. Please fill all required fields (*)" data-original-title="Booking Promotional Activity Tab"><i class="icon-align-justify"></i> Activity</a></li>
						<li id="third-tab"><a href="#tab3" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the Promotional Activity Product. Please fill all required fields (*)" data-original-title="Promotional Activity Product Tab"><i class="icon-align-justify"></i> Product</a></li>
						<li id="preview-tab" style="display:none;"><a href="#tab4" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the Promotional Activities list for this Booking. Click to view the list and edit the content" data-original-title="Preview Tab"><i class="icon-eye-open"></i> Preview</a></li>
					</ul>

					<div class="tab-content">

						<div class="tab-pane active" id="tab1">
							<!-- BOOKING -->
							<fieldset>
							    <div id="legend">
							      <legend class="">Supplier &amp; Date</legend>
							    </div>
							    
							    <div class="control-group">
							      <!-- Booking Code -->
							      <label class="control-label" for="frm_booking_code">Code</label>
							      <div class="controls">
							        <input type="text" id="frm_booking_code" name="frm_booking_code" placeholder="Type Booking Code" class="input-xlarge" value="<?php if ($_REQUEST['frm_booking_code']) { echo stripslashes($_REQUEST['frm_booking_code']); } elseif (!$_REQUEST['frm_booking_code'] && $row['booking_code']) { echo stripslashes($row['booking_code']); } elseif (!$_REQUEST['frm_booking_code'] && !$row['booking_code']) { echo $strBookingCode; } ?>" data-validation="required" readonly /> 
							        <p class="help-block"></p>
							        <div id="statusbox"></div>
							      </div>
							    </div>	


							    <div class="control-group">
							      <!-- Supplier Ref. No -->
							      <label class="control-label" for="frm_booking_supplier_po_ref_number">Ref. No</label>
							      <div class="controls">
							        <input type="text" id="frm_booking_supplier_po_ref_number" name="frm_booking_supplier_po_ref_number" placeholder="Type Supplier's Ref. No" class="input-xlarge" value="<?php if ($_REQUEST['frm_booking_supplier_po_ref_number']) { echo stripslashes($_REQUEST['frm_booking_supplier_po_ref_number']); } elseif (!$_REQUEST['frm_booking_supplier_po_ref_number'] && $strSupplierRefNo) { echo stripslashes($strSupplierRefNo); } ?>" data-validation="" /> 
							        <p class="help-block">Type the Ref. No to select the Supplier automatically</p>
							      </div>
							    </div>


							    <div class="control-group">
							    <!-- Booking Supplier -->
							      <label class="control-label" for="frm_supplier_id">Supplier</label>
							      <div class="controls">
							      <?php $arrSuppliers = $db->getSupplierData(); ?>								
								  	<select name="frm_supplier_id" id="frm_supplier_id" data-validation="required">
								  		<option value="">-- Please select --</option>
								  <?php
									if (is_array($arrSuppliers) && count($arrSuppliers) > 0)
									{
										foreach ($arrSuppliers as $intSupplierID=>$arrSupplierData)
										{
											echo "\n\t<option value=\"" . $intSupplierID . "\"";  
											
											if ($_REQUEST['frm_supplier_id'] && $_REQUEST['frm_supplier_id'] == $intSupplierID)
											{
												echo " selected=\"selected\"";
											}

											elseif (!$_REQUEST['frm_supplier_id'] && $row['supplier_id'] == $intSupplierID) {
												echo " selected=\"selected\"";
											}
											
											echo ">" . stripslashes($arrSupplierData['supplier_name']) . "</option>";
										}
									}
								   ?>
								  	</select> *
								  <!--<br /><small>Supplier is not in the list? Please <a class="ajax callbacks cboxElement" href="supplier.php?action=add&pop=yes">insert a new one</a>.</small>-->

							      </div>
							    </div> 

							    <div class="control-group">
							      <!-- Booking Date -->
							      <label class="control-label" for="frm_booking_date">Date</label>
							      <div class="controls">			      	
							        <input type="text" id="frm_booking_date" name="frm_booking_date" placeholder="Type the date in yyyy-mm-dd format" class="input-small" value="<?php if ($_REQUEST['frm_booking_date']) { echo stripslashes($_REQUEST['frm_booking_date']); } elseif (!$_REQUEST['frm_booking_date'] && $row['booking_date']) { echo stripslashes($row['booking_date']); } else { echo date('d-m-Y'); } ?>" data-validation="required" /> *	
							        <p class="help-block">The booking date in dd-mm-yyyy format</p>			        
							      </div>
							    </div>

							    <div class="control-group">
							      <!-- Button -->
							      <div class="controls">
							        <?php if ($_REQUEST['action'] == "edit" && $_REQUEST['booking_id']) { ?><button class="btn btn-submit" type="button" name="frm_submit" id="frm_submit"><?php echo $strState; ?> Booking</button><?php } ?> &nbsp;&nbsp;&nbsp; <button class="btn" type="button" name="frm_next_1" id="frm_next_1">Next <i class="icon-forward"></i></button>
							      </div>
							    </div>

							</fieldset>
						</div> <!--#tab1 -->   


						<div class="tab-pane" id="tab2">
						    <!-- ACTIVITY-->
							<fieldset>
							<div>
								<legend>Activity</legend> 								
							</div>


							<?php if ($_REQUEST['action'] == "edit" && $_REQUEST['booking_id']) { ?>
							<div class="row-fluid" style="height:80px;">
								<button class="btn" type="button" name="frm_get_activity_prev" id="frm_get_activity_prev" style="float:left;"><i class="icon-arrow-left"></i></button>
								&nbsp;<div id="frm_activity_nav" style="float:left;margin:0 10px;"></div>&nbsp;
								<button class="btn" type="button" name="frm_get_activity_next" id="frm_get_activity_next" style="float:left;"><i class="icon-arrow-right"></i></button>
								<div id="JSONActivities"></div>
								<input type="hidden" id="frm_activity_offset" name="frm_activity_offset" value="0" />
								<input type="hidden" id="frm_booking_activity_id" name="frm_booking_activity_id" value="" />
							</div>
							<?php } ?>
							
							
							
						    				
						    <div class="control-group">
							<!-- Booking Activity Year -->
								<label class="control-label" for="frm_booking_activity_year">Year</label>
								<div class="controls">			      	
									<select name="frm_booking_activity_year" id="frm_booking_activity_year" class="input-small" data-validation="<?php if ($_REQUEST['action'] == "add" && !$_REQUEST['booking_id']) { ?>required<?php } ?>">
										      		
									<?php for ($i = intval(date('Y')); $i <= intval(date('Y') + 5); $i++) { ?>
										<option value="<?php echo $i; ?>"<?php if ($rowActivity['booking_activity_year'] == $i) { echo " selected='selected'"; } elseif (date('n') <= 10 && $i == date("Y")) { echo " selected='selected'"; } elseif (date('n') > 10 && $i == date("Y")+1) { echo " selected='selected'"; } ?>><?php echo $i; ?></option>
									<?php } ?>
									</select> * 							        
								</div>
							</div>		

							<div class="control-group">
							<!-- Booking Activity Month -->
								<label class="control-label" for="frm_booking_activity_month">Month</label>
								<div class="controls">			      	
									<select name="frm_booking_activity_month" id="frm_booking_activity_month" class="input-medium" data-validation="<?php if ($_REQUEST['action'] == "add" && !$_REQUEST['booking_id']) { ?>required<?php } ?>">							      		
										<option value="1"<?php if ($rowActivity['booking_activity_month'] == '1') { echo " selected='selected'"; } elseif (date('n')-11 == '1') { echo " selected='selected'"; } ?>>January</option>
										<option value="2"<?php if ($rowActivity['booking_activity_month'] == '2') { echo " selected='selected'"; } elseif (date('n')-1 == '2') { echo " selected='selected'"; } ?>>February</option>
										<option value="3"<?php if ($rowActivity['booking_activity_month'] == '3') { echo " selected='selected'"; } elseif (date('n')+1 == '3') { echo " selected='selected'"; } ?>>March</option>
										<option value="4"<?php if ($rowActivity['booking_activity_month'] == '4') { echo " selected='selected'"; } elseif (date('n')+1 == '4') { echo " selected='selected'"; } ?>>April</option>
										<option value="5"<?php if ($rowActivity['booking_activity_month'] == '5') { echo " selected='selected'"; } elseif (date('n')+1 == '5') { echo " selected='selected'"; } ?>>May</option>
										<option value="6"<?php if ($rowActivity['booking_activity_month'] == '6') { echo " selected='selected'"; } elseif (date('n')+1 == '6') { echo " selected='selected'"; } ?>>June</option>
										<option value="7"<?php if ($rowActivity['booking_activity_month'] == '7') { echo " selected='selected'"; } elseif (date('n')+1 == '7') { echo " selected='selected'"; } ?>>July</option>
										<option value="8"<?php if ($rowActivity['booking_activity_month'] == '8') { echo " selected='selected'"; } elseif (date('n')+1 == '8') { echo " selected='selected'"; } ?>>August</option>
										<option value="9"<?php if ($rowActivity['booking_activity_month'] == '9') { echo " selected='selected'"; } elseif (date('n')+1 == '9') { echo " selected='selected'"; } ?>>September</option>
										<option value="10"<?php if ($rowActivity['booking_activity_month'] == '10') { echo " selected='selected'"; } elseif (date('n')+1 == '10') { echo " selected='selected'"; } ?>>October</option>
										<option value="11"<?php if ($rowActivity['booking_activity_month'] == '11') { echo " selected='selected'"; } elseif (date('n')+1 == '11') { echo " selected='selected'"; } ?>>November</option>
										<option value="12"<?php if ($rowActivity['booking_activity_month'] == '12') { echo " selected='selected'"; } elseif (date('n')+1 == '12') { echo " selected='selected'"; } ?>>December</option>
									</select> * 		
									<p class="help-block"></p>					        
									<div id="datetime-check"></div>
								</div>

							</div>

							<div class="control-group">
							<!-- Booking Activity -->
								<label class="control-label" for="frm_activity_id">Activity</label>
								<div class="controls">
								<?php $arrActivity = $db->getActivityData(); ?>
								<?php $intCountInStore = $db->dbGetAggregateData('COUNT', 'mbs_activities', 'activity_store_related', "WHERE `year`= ".date("Y")." AND `activity_store_related` = 'yes'"); ?>

									<select name="frm_activity_id" id="frm_activity_id" class="input-xlarge" data-validation="<?php if ($_REQUEST['action'] == "add" && !$_REQUEST['booking_id']) { ?>required<?php } ?>">
										<option value="">-- Please select --</option>
								<?php
										if (is_array($arrActivity) && count($arrActivity) > 0)
										{
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
														
												if ($_REQUEST['frm_activity_id'] && $_REQUEST['frm_activity_id'] == $intActivityID)
												{
													echo " selected=\"selected\"";
												}

												elseif (!$_REQUEST['frm_activity_id'] && $rowActivity['activity_id'] == $intActivityID) 
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
								</select> *
								<!--<br /><small>Activity is not in the list? Please <a class="ajax callbacks cboxElement" href="activity.php?action=add&pop=yes">insert a new one</a>.</small>-->

								</div>
							</div> 


							<div id="control-size-id" class="control-group" style="display:<?php if ($rowActivity['size_id']) { ?>block<?php } else { ?>none<?php } ?>;">
							
							<!-- Activity Size -->
								<label class="control-label" for="frm_size_id">Size</label>
								<div class="controls">
								<?php $arrSizes = $db->getSizeData(); ?>								
									<select name="frm_size_id" id="frm_size_id" readonly="readonly" data-validation="">
										<option value="">-- Not specified --</option>
								<?php
									
									if (is_array($arrSizes) && count($arrSizes) > 0)
									{
										foreach ($arrSizes as $intSizeID=>$arrSizesData)
										{
											echo "\n\t<option value=\"" . $intSizeID . "\"";  
														
											if ($_REQUEST['frm_size_id'] && $_REQUEST['frm_size_id'] == $intSizeID)
											{
												echo " selected=\"selected\"";
											}

											elseif (!$_REQUEST['frm_size_id'] && $rowActivity['size_id'] == $intSizeID) 
											{
												echo " selected=\"selected\"";
											}
														
											echo ">" . stripslashes($arrSizesData['size_name']) . "</option>";
											
										}
									}

								?>
								</select>
								<!--<br /><small>Size is not in the list? Please <a class="ajax callbacks cboxElement" href="size.php?action=add&pop=yes">insert a new one</a>.</small>-->

								</div>
							</div> 	


							<div id="control-store-id" class="control-group" style="display:<?php if ($rowActivity['store_id']) { ?>block<?php } else { ?>none<?php } ?>;">
							<!-- Store -->
								<label class="control-label" for="frm_store_id">Store</label>
								<div class="controls">
								<?php $arrStores = $db->getStoreData(); ?>								
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
												
												<?php $arrStores = $db->getStoreData(); ?>
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
												
												<?php $arrStores = $db->getStoreData(); ?>
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
										<?php $arrStores = $db->getStoreData(); ?>
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
							</div>

							<div id="control-booking-activity-price" class="control-group" style="display:<?php if ($rowActivity['booking_activity_price'] || $_REQUEST['action'] == 'edit') { ?>block<?php } else { ?>none<?php } ?>;">
							<!-- Booking Activity Price -->
								<label class="control-label" for="frm_booking_activity_price">Price $</label>
								<div class="controls">			      	
									<input type="text" id="frm_booking_activity_price" name="frm_booking_activity_price" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_booking_activity_price']) { echo stripslashes($_REQUEST['frm_booking_activity_price']); } elseif (!$_REQUEST['frm_booking_activity_price'] && $rowActivity['booking_activity_price']) { echo stripslashes($rowActivity['booking_activity_price']); } ?>" /> 
									<p class="help-block"></p>
								</div>
								
								<div class="controls">
									<div id="total_price"></div>
								</div>	
							</div> 


							<div class="control-group">
							    <!-- Button -->
							    <div class="controls">
							        <button class="btn" type="button" name="frm_prev_1" id="frm_prev_1"><i class="icon-backward"></i> Previous</button>&nbsp;&nbsp;&nbsp; <!--<button class="btn btn-submit" type="button" name="frm_submit_2" id="frm_submit_2"><?php echo $strState; ?> Activity</button>--> &nbsp;&nbsp;&nbsp;<button class="btn" type="button" name="frm_next_2" id="frm_next_2">Next <i class="icon-forward"></i></button>
							    </div>
							</div>

						    </fieldset>		

						</div> <!--#tab2 -->	

					    
						<div class="tab-pane" id="tab3">

					    	<!-- PRODUCT -->
					    	<fieldset>
					    	<div>
					    		<legend>Product</legend>
					    	</div>	


					    	<?php if ($_REQUEST['action'] == "edit" && $_REQUEST['booking_id']) { ?>
							<div class="row-fluid" style="height:80px;">
								<button class="btn" type="button" name="frm_get_product_prev" id="frm_get_product_prev" style="float:left;"><i class="icon-arrow-left"></i></button>
								&nbsp;<div id="frm_product_nav" style="float:left;margin:0 10px;"></div>&nbsp;
								<button class="btn" type="button" name="frm_get_product_next" id="frm_get_product_next" style="float:left;"><i class="icon-arrow-right"></i></button>
								<div id="JSONProducts"></div>
								<input type="hidden" id="frm_product_amount" name="frm_product_amount" value="" />
								<input type="hidden" id="frm_product_offset" name="frm_product_offset" value="0" />
								<input type="hidden" id="frm_booking_product_id" name="frm_booking_product_id" value="" />
							</div>
							<?php } ?>

					    	<input name="frm_product_seq" id="frm_product_seq" type="hidden" value="1" />					    	

					    	<div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Code -->
										<label for="frm_booking_product_code">UPI Code</label>
										<input type="hidden" id="frm_booking_product_code" name="frm_booking_product_code" placeholder="Type product code" class="input-xxlarge" value="<?php if ($_REQUEST['frm_booking_product_code']) { echo stripslashes($_REQUEST['frm_booking_product_code']); } elseif (!$_REQUEST['frm_booking_product_code'] && $rowProduct['booking_product_code']) { echo stripslashes($rowProduct['booking_product_code']); } ?>" data-validation="required" /> *
										<p class="help-block">Type then enter. </p>
									</div>
                                </div>
							</div>	    

							<div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Name -->
										<label for="frm_booking_product_name">Name</label>										      		      	
										<input type="text" id="frm_booking_product_name" name="frm_booking_product_name" placeholder="Type product name" class="input-xxlarge" value="<?php if ($_REQUEST['frm_booking_product_name']) { echo stripslashes($_REQUEST['frm_booking_product_name']); } elseif (!$_REQUEST['frm_booking_product_name'] && $rowProduct['booking_product_name']) { echo stripslashes($rowProduct['booking_product_name']); } ?>" data-validation="<?php if ($_REQUEST['action'] == "add" && !$_REQUEST['booking_id']) { ?>required<?php } ?>" /> *										        										      
									</div>
								</div>
							</div>
                            
                            <div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Name -->
										<label for="frm_booking_department_id">Department</label>	
                                        <?php $arrDepartments = $db->getDepartmentData(); ?>								
                                            <select name="frm_booking_department_id" id="frm_booking_department_id" class="input-xxlarge" data-validation="required">
                                                <option value="">-- Please select --</option>
                                          <?php
                                            if (is_array($arrDepartments) && count($arrDepartments) > 0)
                                            {
                                                foreach ($arrDepartments as $intDepartmentID=>$arrDepartmentData)
                                                {
                                                    echo "\n\t<option value=\"" . $intDepartmentID . "\"";  
                                                    
                                                    if ($_REQUEST['frm_booking_department_id'] && $_REQUEST['frm_booking_department_id'] == $intDepartmentID)
                                                    {
                                                        echo " selected=\"selected\"";
                                                    }
        
                                                    elseif (!$_REQUEST['frm_booking_department_id'] && $row['booking_department_id'] == $intDepartmentID) {
                                                        echo " selected=\"selected\"";
                                                    }
                                                    
                                                    echo ">" . stripslashes($arrDepartmentData['department_name']) . "</option>";
                                                }
                                            }
                                           ?>
                                           </select> *									      		      	
									</div>
								</div>
							</div>	    

							<div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Normal Retail Price -->
										<label for="frm_booking_product_normal_retail_price">Normal Retail Price $</label>										      
										<input type="text" id="frm_booking_product_normal_retail_price" name="frm_booking_product_normal_retail_price" placeholder="0.00" class="input-xlarge" value="<?php if ($_REQUEST['frm_booking_product_normal_retail_price']) { echo stripslashes($_REQUEST['frm_booking_product_normal_retail_price']); } elseif (!$_REQUEST['frm_booking_product_normal_retail_price'] && $rowProduct['booking_product_normal_retail_price']) { echo stripslashes($rowProduct['booking_product_normal_retail_price']); } ?>" data-validation="required" /> *
									</div>
								</div>
							</div>
                            
                            <div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Promo Price -->
										<label for="frm_booking_product_promo_price">Promo Price $</label>
										<input type="text" id="frm_booking_product_promo_price" name="frm_booking_product_promo_price" placeholder="0.00" class="input-xlarge" value="<?php if ($_REQUEST['frm_booking_product_promo_price']) { echo stripslashes($_REQUEST['frm_booking_product_promo_price']); } elseif (!$_REQUEST['frm_booking_product_promo_price'] && $rowProduct['booking_product_promo_price']) { echo stripslashes($rowProduct['booking_product_promo_price']); } ?>" data-validation="required" /> *
									</div>
								</div>
							</div>

							<div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Cost Price -->
										<label for="frm_booking_product_cost_price">Cost Price $</label>										      
										<input type="text" id="frm_booking_product_cost_price" name="frm_booking_product_cost_price" placeholder="0.00" class="input-xlarge" value="<?php if ($_REQUEST['frm_booking_product_cost_price']) { echo stripslashes($_REQUEST['frm_booking_product_cost_price']); } elseif (!$_REQUEST['frm_booking_product_cost_price'] && $rowProduct['booking_product_cost_price']) { echo stripslashes($rowProduct['booking_product_cost_price']); } ?>" data-validation="required" /> *
									</div>
								</div>
							</div>
                            
                            <div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Recommended Retail Price -->
										<label for="frm_booking_product_recommended_retail_price">RRP $</label>
										<input type="text" id="frm_booking_product_recommended_retail_price" name="frm_booking_product_recommended_retail_price" placeholder="0.00" class="input-xlarge" value="<?php if ($_REQUEST['frm_booking_product_recommended_retail_price']) { echo stripslashes($_REQUEST['frm_booking_product_recommended_retail_price']); } elseif (!$_REQUEST['frm_booking_product_recommended_retail_price'] && $rowProduct['booking_product_recommended_retail_price']) { echo stripslashes($rowProduct['booking_product_recommended_retail_price']); } ?>" data-validation="required" /> *
									</div>
								</div>
							</div>
                            
                            <div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Recommended Retail Price -->
										<label for="frm_booking_product_discount">Discount %</label>
										<input type="text" id="frm_booking_product_discount" name="frm_booking_product_discount" placeholder="0.00" class="input-xlarge" value="<?php if ($_REQUEST['frm_booking_product_discount']) { echo stripslashes($_REQUEST['frm_booking_product_discount']); } elseif (!$_REQUEST['frm_booking_product_discount'] && $rowProduct['booking_product_discount']) { echo stripslashes($rowProduct['booking_product_discount']); } ?>" />
									</div>
								</div>
							</div>

							<div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">
										<!-- Booking Activity Product Special Offer Details -->
										<label for="frm_booking_product_special_offer_details">Special Offer Details</label>
										<textarea id="frm_booking_product_special_offer_details" name="frm_booking_product_special_offer_details" placeholder="Type special offer details" class="input-xxlarge" rows="3"><?php if ($_REQUEST['frm_booking_product_special_offer_details']) { echo stripslashes($_REQUEST['frm_booking_product_special_offer_details']); } elseif (!$_REQUEST['frm_booking_product_special_offer_details'] && $rowProduct['booking_product_special_offer_details']) { echo stripslashes($rowProduct['booking_product_special_offer_details']); } ?></textarea>
									</div>
							    					
								</div>
							</div>

							<div style="margin-left:20px;">
								<button class="btn" type="button" name="frm_prev_2" id="frm_prev_2"><i class="icon-backward"></i> Previous</button>
								&nbsp;&nbsp;&nbsp; 
								<button class="btn btn-submit btn-popover" type="button" name="frm_add_product" id="frm_add_product" rel="popover" data-content="Click this to add more Product for the Activity selected on the previous tab. You can add as many as you want until the Submit button is clicked." data-original-title="Add more Product button"><i class="icon-plus"></i> Add more Product</button>
								&nbsp;&nbsp;&nbsp;
								<button class="btn btn-submit btn-inverse" type="button" name="frm_submit_2" id="frm_submit_2">Submit</button>
								<input type="hidden" id="frm_booking_action_btn" value="<?php if ($_REQUEST['frm_booking_action_btn']) { echo $_REQUEST['frm_booking_action_btn']; } ?>" />
								<input type="hidden" id="frm_booking_id_alt" value="<?php if ($_REQUEST['booking_id']) { echo $_REQUEST['booking_id']; } else { echo $db->getNextAutoIncrement('mbs_bookings'); } ?>" />
								<input type="hidden" id="frm_booking_activity_id_alt" value="<?php if ($_REQUEST['booking_activity_id']) { echo $_REQUEST['booking_activity_id']; } else { echo $db->getNextAutoIncrement('mbs_bookings_activities'); } ?>" />
								<input type="hidden" id="frm_booking_product_id_alt" value="<?php if ($_REQUEST['booking_product_id']) { echo $_REQUEST['booking_product_id']; } else { echo $db->getNextAutoIncrement('mbs_bookings_products'); } ?>" />
								<input type="hidden" id="child_action" value="<?php if ($_REQUEST['booking_activity_id']) { echo 'edit-activity'; } else { echo 'submit'; } ?>" />
							</div>

							<div class="container-fluid" style="margin-bottom:20px;">
					    		<div class="row-fluid">
							    	<div class="span12">										
									</div>							    					
								</div>
							</div>

					    	</fieldset>		

					    </div> <!--#tab3 -->
					    	
					    <!--#tab4 -->
					    <div class="tab-pane" id="tab4">
					    	<fieldset>
					    		<div>
					    			<legend>Preview</legend>
					    		</div>

					    		<div id="frm_preview"></div>
								<div style="text-align:center;"><button class="btn btn-popover" type="button" name="frm_add_activity" id="frm_add_activity" rel="popover" data-content="Click this to add more Promotional Activities for this Booking. You will be automatically taken to the Activity Tab." data-original-title="Add more Activity button"><i class="icon-plus"></i> Add more Promo Activity</button> &nbsp;&nbsp;&nbsp; <a id="button-finish" class="btn btn-inverse" href="#">Finish</a></div>

					    	</fieldset>	
					    </div> 	<!--#tab4 -->

					</div> <!--.tab-content-->	  
				</div>	<!--.tabbbable-->

			</form>
			<p class="pull-left"><a class="btn" href="#top"><i class="icon-arrow-up"></i> Back to top</a></p>
			
			
			<!-- JS Validator -->
			<script src="<?php echo $STR_URL; ?>js/jquery.formvalidator.min.js"></script>
			<script>$('#frm_booking').validateOnBlur();</script>

			<!-- JS Form action -->
			<script>

			//-- Load the first tab as default
			<?php if (!$_REQUEST['booking_activity_id']) { ?>
			$(window).load(function() {
    			$('#formtab-nav a:first').tab('show');
			});
			<?php } ?>

			$(document).ready(function() {

				//-- Popover
				$('.btn-popover').popover({ 
					trigger: 'hover',
					placement: 'top'
				});
				
				var pricefrist='';
				var pricelast='';
				$('#frm_booking_activity_price').focus(function(){
				  pricefrist = $('#frm_booking_activity_price').val();
				
				});
				$('#frm_booking_activity_price').blur(function(){
				  pricelast = $('#frm_booking_activity_price').val();
				 if(pricelast!=pricefrist)
				 {
				 
				  var stat = confirm('Are you sure want to change the price '+pricefrist+' to '+pricelast+' ?');
				  if(stat==true)
				  {
				   $('#frm_booking_activity_price').val(pricelast);
				  }
				  else
				  {
				   $('#frm_booking_activity_price').val(pricefrist);
				  }
				 }
				});

				

				//-- Edit mode
				<?php if ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id']) { ?>
						
						$('#preview-tab').show(); //-- show preview if edit mode
						$('#frm_booking_id_alt').val('<?php echo $_REQUEST['booking_id']; ?>');
						<?php if ($_REQUEST['booking_activity_id']) { ?>
						var store_count = <?php echo count($arrStoreID); ?>;
						$('#store_count').html('<strong><em>' + store_count +'</strong></em>');		
						<?php } ?>	
						
						<?php if ($_REQUEST['child_action'] == 'edit-activity' && $_REQUEST['booking_activity_id']) { ?>
						$('#first-tab').hide();
						$('#second-tab').show(); //-- show the second tab
						$('#third-tab').show(); //-- show the third tab	
						$('#formtab-nav a[href="#tab2"]').tab('show');
						<?php } else { ?>	
						$('#second-tab').hide(); //-- show the second tab
						$('#third-tab').hide(); //-- show the third tab
						<?php } ?>

						$('#frm_preview').load('ajax/booking_activity_preview.php?booking_id=<?php echo $_REQUEST['booking_id']; ?>&action=edit'); //-- Load the preview table
						$('#button-finish').attr('href', '<?php echo $STR_URL; ?>booking_view.php?booking_id=<?php echo $_REQUEST['booking_id']; ?>&action=view'); //-- Update the url for the finish button
						
						$('#frm_add_activity').click(function() { //-- Take the user to the second tab if he/she wants to add more activities
							clearActivity();
							$('#second-tab').show();
							$('#formtab-nav a[href="#tab2"]').tab('show');
							$('#child_action').val('add-activity');
							$('#action').val('add');
							//--clearForm();
						});

						

						//-- Process the Activities						
						var booking_id = '<?php echo $_REQUEST['booking_id']; ?>';

						$.getJSON('ajax/booking_get_activities.php?booking_id=' + booking_id, function(data) {
							
							var str_activities_json = JSON.stringify(data, null, 2);	
							var arr_activities = JSON.parse(str_activities_json);
							//--$('#JSONActivities').html(str_activities_json);
							//--alert(arr_activities[0]['booking_id'] + ' ' + arr_activities[0]['booking_activity_description']);								
							//--alert(arr_activities.length);

							$('#frm_booking_activity_year').val(arr_activities[0]['booking_activity_year']);
							$('#frm_booking_activity_month').val(arr_activities[0]['booking_activity_month']);
							$('#frm_activity_id').val(arr_activities[0]['activity_id']).trigger('change');
							$('#frm_booking_activity_price').val(arr_activities[0]['booking_activity_price']);
							$('#frm_booking_activity_id').val(arr_activities[0]['booking_activity_id']);
							$('#frm_activity_nav').html('<strong>1 of ' + arr_activities.length + '</strong>');
							$('#frm_get_activity_prev').attr('disabled', 'disabled'); 
							if (parseInt(arr_activities.length) == 1) { $('#frm_get_activity_next').attr('disabled', 'disabled'); }
							//-- alert($('#frm_booking_activity_id').val());
							
							//-- Process the Products
							var booking_activity_id = $('#frm_booking_activity_id').val();
							//-- alert(booking_activity_id);

							$.getJSON('ajax/booking_activity_get_products.php?booking_activity_id=' + booking_activity_id, function(data) { 
								
								var str_products_json = JSON.stringify(data, null, 2);
								var arr_products = JSON.parse(str_products_json);
								//--$('#JSONActivities').html(str_products_json);

								$('#frm_product_amount').val(parseInt(arr_products.length));
								$('#frm_booking_product_id').val(arr_products[0]['booking_product_id']);
								$('#frm_booking_department_id').val(arr_products[0]['booking_department_id']);
								//$('#frm_booking_product_code').val(arr_products[0]['booking_product_code']);
								$('#frm_booking_product_code').select2("val", arr_products[0]['booking_product_code'].split(","));
								$('#frm_booking_product_name').val(arr_products[0]['booking_product_name']);
								$('#frm_booking_product_normal_retail_price').val(arr_products[0]['booking_product_normal_retail_price']);
								$('#frm_booking_product_promo_price').val(arr_products[0]['booking_product_promo_price']);
								$('#frm_booking_product_cost_price').val(arr_products[0]['booking_product_cost_price']);
								$('#frm_booking_product_discount').val(arr_products[0]['booking_product_discount']);
								$('#frm_booking_product_recommended_retail_price').val(arr_products[0]['booking_product_recommended_retail_price']);
								$('#frm_booking_product_special_offer_details').val(arr_products[0]['booking_product_special_offer_details']);
								$('#frm_product_nav').html('<strong>1 of ' + arr_products.length + '</strong>');
								$('#frm_get_product_prev').attr('disabled', 'disabled'); 
								if (parseInt(arr_products.length) == 1) { $('#frm_get_product_next').attr('disabled', 'disabled'); }
								$('#frm_product_offset').val(0);
								$('#frm_product_seq').val(1);
								//-- alert($('#frm_booking_product_id').val());

							});

						});							
							
							

						//-- Activity Nav Button: Prev
						$('#frm_get_activity_prev').click(function() {
							
							var booking_id = '<?php echo $_REQUEST['booking_id']; ?>';

							$.getJSON('ajax/booking_get_activities.php?booking_id=' + booking_id, function(data) {
							
								var str_activities_json = JSON.stringify(data, null, 2);	
								var arr_activities = JSON.parse(str_activities_json);
								
								var int_activities_offset = $('#frm_activity_offset').val();
								int_activities_offset = parseInt(int_activities_offset) - 1;

								//--if (parseInt(int_activities_offset)-1 == 0) { $('#frm_get_activity_prev').attr('disabled', 'disabled'); }								
								
								$('#frm_booking_activity_year').val(arr_activities[int_activities_offset]['booking_activity_year']);
								$('#frm_booking_activity_month').val(arr_activities[int_activities_offset]['booking_activity_month']);
								$('#frm_activity_id').val(arr_activities[int_activities_offset]['activity_id']).trigger('change');
								$('#frm_booking_activity_price').val(arr_activities[int_activities_offset]['booking_activity_price']);
								$('#frm_booking_activity_id').val(arr_activities[int_activities_offset]['booking_activity_id']);
								$('#frm_activity_nav').html('<strong>' + (parseInt(int_activities_offset)+1) + ' of ' + arr_activities.length + '</strong>');								
								
								if (parseInt(int_activities_offset) < (parseInt(arr_activities.length))) { $('#frm_get_activity_next').removeAttr('disabled'); }
								if (parseInt(int_activities_offset) == 0) { $('#frm_get_activity_prev').attr('disabled', 'disabled'); }

								$('#frm_activity_offset').val(parseInt(int_activities_offset));
								//--alert($('#frm_activity_offset').val());
								//--alert($('#frm_booking_activity_id').val());

								//-- Process the Products
								var booking_activity_id = $('#frm_booking_activity_id').val();
								//-- alert(booking_activity_id);

								$.getJSON('ajax/booking_activity_get_products.php?booking_activity_id=' + booking_activity_id, function(data) { 
									
									var str_products_json = JSON.stringify(data, null, 2);
									var arr_products = JSON.parse(str_products_json);
									//--$('#JSONActivities').html(str_products_json);

									$('#frm_product_amount').val(parseInt(arr_products.length));
									$('#frm_product_offset').val(0);
									$('#frm_product_seq').val(1);
									var int_product_offset = $('#frm_product_offset').val();

									$('#frm_booking_product_id').val(arr_products[0]['booking_product_id']);
									$('#frm_booking_department_id').val(arr_products[0]['booking_department_id']);
									//$('#frm_booking_product_code').val(arr_products[0]['booking_product_code']);
									$('#frm_booking_product_code').select2("val", arr_products[0]['booking_product_code'].split(","));
									$('#frm_booking_product_name').val(arr_products[0]['booking_product_name']);
									$('#frm_booking_product_normal_retail_price').val(arr_products[0]['booking_product_normal_retail_price']);
									$('#frm_booking_product_promo_price').val(arr_products[0]['booking_product_promo_price']);
									$('#frm_booking_product_cost_price').val(arr_products[0]['booking_product_cost_price']);
									$('#frm_booking_product_discount').val(arr_products[0]['booking_product_discount']);
									$('#frm_booking_product_recommended_retail_price').val(arr_products[0]['booking_product_recommended_retail_price']);
									$('#frm_booking_product_special_offer_details').val(arr_products[0]['booking_product_special_offer_details']);
									
									$('#frm_product_nav').html('<strong>1 of ' + arr_products.length + '</strong>');									
									if (parseInt(int_product_offset) == 0) { $('#frm_get_product_prev').attr('disabled', 'disabled'); }
									if (parseInt(arr_products.length) == 1) { $('#frm_get_product_next').attr('disabled', 'disabled'); }
									if (parseInt(int_product_offset) > 0) { $('#frm_get_product_prev').removeAttr('disabled'); }
									if (parseInt(int_product_offset) < parseInt(arr_products.length)-1) { $('#frm_get_product_next').removeAttr('disabled'); }
									
									
									//-- alert($('#frm_booking_product_id').val());

								}); //-- $.getJSON()

							});	//-- $.getJSON()							
							
						}); //-- $('#frm_get_activity_prev').click()


						//-- Activity Nav Button: Next
						$('#frm_get_activity_next').click(function() {
							
							var booking_id = '<?php echo $_REQUEST['booking_id']; ?>';

							$.getJSON('ajax/booking_get_activities.php?booking_id=' + booking_id, function(data) {
							
								var str_activities_json = JSON.stringify(data, null, 2);	
								var arr_activities = JSON.parse(str_activities_json);

								var int_activities_offset = $('#frm_activity_offset').val();
								int_activities_offset = parseInt(int_activities_offset) + 1;
								
								$('#frm_booking_activity_year').val(arr_activities[int_activities_offset]['booking_activity_year']);
								$('#frm_booking_activity_month').val(arr_activities[int_activities_offset]['booking_activity_month']);
								$('#frm_activity_id').val(arr_activities[int_activities_offset]['activity_id']).trigger('change');
								$('#frm_booking_activity_price').val(arr_activities[int_activities_offset]['booking_activity_price']);
								$('#frm_booking_activity_id').val(arr_activities[int_activities_offset]['booking_activity_id']);
								$('#frm_activity_nav').html('<strong>' + (parseInt(int_activities_offset)+1) + ' of ' + arr_activities.length + '</strong>');

								if (parseInt(int_activities_offset) == (parseInt(arr_activities.length)-1)) { $('#frm_get_activity_next').attr('disabled', 'disabled'); }
								if (parseInt(int_activities_offset) > 0) { $('#frm_get_activity_prev').removeAttr('disabled'); }

								$('#frm_activity_offset').val(parseInt(int_activities_offset));
								//--alert($('#frm_activity_offset').val());
								//--alert($('#frm_booking_activity_id').val());

								//-- Process the Products
								var booking_activity_id = $('#frm_booking_activity_id').val();
								//-- alert(booking_activity_id);

								$.getJSON('ajax/booking_activity_get_products.php?booking_activity_id=' + booking_activity_id, function(data) { 
									
									var str_products_json = JSON.stringify(data, null, 2);
									var arr_products = JSON.parse(str_products_json);
									//--$('#JSONActivities').html(str_products_json);

									$('#frm_product_amount').val(parseInt(arr_products.length));
									$('#frm_product_offset').val(0);
									$('#frm_product_seq').val(1);
									var int_product_offset = $('#frm_product_offset').val();

									$('#frm_booking_product_id').val(arr_products[0]['booking_product_id']);
									$('#frm_booking_department_id').val(arr_products[0]['booking_department_id']);
									//$('#frm_booking_product_code').val(arr_products[0]['booking_product_code']);
									$('#frm_booking_product_code').select2("val", arr_products[0]['booking_product_code'].split(","));
									$('#frm_booking_product_name').val(arr_products[0]['booking_product_name']);
									$('#frm_booking_product_normal_retail_price').val(arr_products[0]['booking_product_normal_retail_price']);
									$('#frm_booking_product_promo_price').val(arr_products[0]['booking_product_promo_price']);
									$('#frm_booking_product_cost_price').val(arr_products[0]['booking_product_cost_price']);
									$('#frm_booking_product_discount').val(arr_products[0]['booking_product_discount']);
									$('#frm_booking_product_recommended_retail_price').val(arr_products[0]['booking_product_recommended_retail_price']);
									$('#frm_booking_product_special_offer_details').val(arr_products[0]['booking_product_special_offer_details']);
									
									$('#frm_product_nav').html('<strong>1 of ' + arr_products.length + '</strong>');
									if (parseInt(int_product_offset) == 0) { $('#frm_get_product_prev').attr('disabled', 'disabled'); }
									if (parseInt(arr_products.length) == 1) { $('#frm_get_product_next').attr('disabled', 'disabled'); }
									if (parseInt(int_product_offset) > 0) { $('#frm_get_product_prev').removeAttr('disabled'); }
									if (parseInt(int_product_offset) < parseInt(arr_products.length)-1) { $('#frm_get_product_next').removeAttr('disabled'); }
									
									//-- alert($('#frm_booking_product_id').val());

								}); //-- $.getJSON()

							});	//-- $.getJSON()							

						});	//-- $('#frm_get_activity_next').click()



						//-- Product Nav Button: Prev
						$('#frm_get_product_prev').click(function() {

							//-- Process the Products
							var booking_activity_id = $('#frm_booking_activity_id').val();
							//-- alert(booking_activity_id);

							$.getJSON('ajax/booking_activity_get_products.php?booking_activity_id=' + booking_activity_id, function(data) { 
								
								var str_products_json = JSON.stringify(data, null, 2);
								var arr_products = JSON.parse(str_products_json);
								//--$('#JSONActivities').html(str_products_json);

								var int_product_offset = $('#frm_product_offset').val();
								int_product_offset = parseInt(int_product_offset) - 1;

								$('#frm_product_seq').val(parseInt(int_product_offset)+1);
								$('#frm_booking_product_id').val(arr_products[int_product_offset]['booking_product_id']);
								$('#frm_booking_department_id').val(arr_products[int_product_offset]['booking_department_id']);
								//$('#frm_booking_product_code').val(arr_products[int_product_offset]['booking_product_code']);
								$('#frm_booking_product_code').select2("val", arr_products[int_product_offset]['booking_product_code'].split(","));
								$('#frm_booking_product_name').val(arr_products[int_product_offset]['booking_product_name']);
								$('#frm_booking_product_normal_retail_price').val(arr_products[int_product_offset]['booking_product_normal_retail_price']);
								$('#frm_booking_product_promo_price').val(arr_products[int_product_offset]['booking_product_promo_price']);
								$('#frm_booking_product_cost_price').val(arr_products[int_product_offset]['booking_product_cost_price']);
								$('#frm_booking_product_discount').val(arr_products[int_product_offset]['booking_product_discount']);
								$('#frm_booking_product_recommended_retail_price').val(arr_products[int_product_offset]['booking_product_recommended_retail_price']);
								$('#frm_booking_product_special_offer_details').val(arr_products[int_product_offset]['booking_product_special_offer_details']);
								
								$('#frm_product_nav').html('<strong>' + (parseInt(int_product_offset)+1) + ' of ' + arr_products.length + '</strong>');

								if (parseInt(int_product_offset) == 0) { $('#frm_get_product_prev').attr('disabled', 'disabled'); }
								if (parseInt(arr_products.length) == 1) { $('#frm_get_product_next').attr('disabled', 'disabled'); }
								if (parseInt(int_product_offset) > 0) { $('#frm_get_product_prev').removeAttr('disabled'); }
								if (parseInt(int_product_offset) < parseInt(arr_products.length)-1) { $('#frm_get_product_next').removeAttr('disabled'); }															

								$('#frm_product_offset').val(parseInt(int_product_offset));								
								//-- alert($('#frm_booking_product_id').val());
								//-- $('#JSONProducts').html($('#frm_product_seq').val());

							}); //-- $.getJSON()

						}); //-- $('#frm_get_product_prev').click()	


						//-- Product Nav Button: Next
						$('#frm_get_product_next').click(function() {

							//-- Process the Products
							var booking_activity_id = $('#frm_booking_activity_id').val();
							//-- alert(booking_activity_id);

							$.getJSON('ajax/booking_activity_get_products.php?booking_activity_id=' + booking_activity_id, function(data) { 
								
								var str_products_json = JSON.stringify(data, null, 2);
								var arr_products = JSON.parse(str_products_json);
								//--$('#JSONActivities').html(str_products_json);

								var int_product_offset = $('#frm_product_offset').val();
								int_product_offset = parseInt(int_product_offset) + 1;

								$('#frm_product_seq').val(parseInt(int_product_offset)+1);
								$('#frm_booking_product_id').val(arr_products[int_product_offset]['booking_product_id']);
								$('#frm_booking_department_id').val(arr_products[int_product_offset]['booking_department_id']);
								//$('#frm_booking_product_code').val(arr_products[int_product_offset]['booking_product_code']);
								$('#frm_booking_product_code').select2("val", arr_products[int_product_offset]['booking_product_code'].split(","));
								$('#frm_booking_product_name').val(arr_products[int_product_offset]['booking_product_name']);
								$('#frm_booking_product_normal_retail_price').val(arr_products[int_product_offset]['booking_product_normal_retail_price']);
								$('#frm_booking_product_promo_price').val(arr_products[int_product_offset]['booking_product_promo_price']);
								$('#frm_booking_product_cost_price').val(arr_products[int_product_offset]['booking_product_cost_price']);
								$('#frm_booking_product_discount').val(arr_products[int_product_offset]['booking_product_discount']);
								$('#frm_booking_product_recommended_retail_price').val(arr_products[int_product_offset]['booking_product_recommended_retail_price']);
								$('#frm_booking_product_special_offer_details').val(arr_products[int_product_offset]['booking_product_special_offer_details']);
								
								$('#frm_product_nav').html('<strong>' + (parseInt(int_product_offset)+1) + ' of ' + arr_products.length + '</strong>');

								if (parseInt(int_product_offset) == 0) { $('#frm_get_product_prev').attr('disabled', 'disabled'); }
								if (parseInt(arr_products.length) == 1) { $('#frm_get_product_next').attr('disabled', 'disabled'); }
								if (parseInt(int_product_offset) == parseInt(arr_products.length)-1) { $('#frm_get_product_next').attr('disabled', 'disabled'); }
								if (parseInt(int_product_offset) > 0) { $('#frm_get_product_prev').removeAttr('disabled'); }
								if (parseInt(int_product_offset) < parseInt(arr_products.length)-1) { $('#frm_get_product_next').removeAttr('disabled'); }

								$('#frm_product_offset').val(parseInt(int_product_offset));								
								//-- alert($('#frm_booking_product_id').val());
								//-- $('#JSONProducts').html($('#frm_product_seq').val());

							}); //-- $.getJSON()

						}); //-- $('#frm_get_product_next').click()					

				<?php } ?>


				//-- Booking date				
	    		$("#frm_booking_date").datepicker({
	      			changeMonth: true,
	      			changeYear: true,
	      			dateFormat: "dd-mm-yy"	      			
	    		}).val(getTodaysDate(0));
				
				

	    		function getTodaysDate(val) 
	    		{
				    var t = new Date, day, month, year = t.getFullYear();
				    if (t.getDate() < 10) {
				        day = "0" + t.getDate();
				    }
				    else {
				        day = t.getDate();
				    }
				    if ((t.getMonth() + 1) < 10) {
				        month = "0" + (t.getMonth() + 1 - val);
				    }
				    else {
				        month = t.getMonth() + 1 - val;
				    }

				    return (day + '-' + month + '-' + year);
			   	}
	  		
				//-- Next Button 1
				$('#frm_next_1').click(function() {
					$('#second-tab').show();
					$('#formtab-nav a[href="#tab2"]').tab('show');
				});

				//-- Next Button 2
				$('#frm_next_2').click(function() {
					$('#third-tab').show();
					$('#formtab-nav a[href="#tab3"]').tab('show');
				});				

				//-- Previous Button 1
				$('#frm_prev_1').click(function() {
					$('#first-tab').show();
					$('#formtab-nav a[href="#tab1"]').tab('show');
				});

				//-- Previous Button 2
				$('#frm_prev_2').click(function() {
					$('#second-tab').show();
					$('#formtab-nav a[href="#tab2"]').tab('show');
				});

				//-- Autocomplete for Ref. No, get the number and then automatically select the supplier ID 
				$('#frm_booking_supplier_po_ref_number').autocomplete({
					source: 'ajax/supplier_po_ref_number_check.php',
				    minLength: 2,
				    select: function(event, ui) {
				    	if (ui.item.label) {
				    		this.value = ui.item.label;	
				    	}
				    	    
				    	$('#frm_supplier_id').val(ui.item.id);    					
				    }
				});


				//-- Supplier, get the Ref No and fill up the field if the field was left blank
				$('#frm_supplier_id').change(function() {
					if ($('#frm_booking_supplier_po_ref_number').val() == '')
					{
						<?php $arrSupplierRefNo = $db->getSupplierRefNo(); ?>
						<?php if (count($arrSupplierRefNo) > 0) { ?>
							<?php foreach ($arrSupplierRefNo as $intSupplierID => $strSupplierRefNo) { ?>
							if ($('#frm_supplier_id option:selected').val() == <?php echo $intSupplierID; ?>) { $('#frm_booking_supplier_po_ref_number').val('<?php echo $strSupplierRefNo; ?>'); }
						<?php } ?>
						<?php } ?>	
					}
				});

				//-- Booking Year
				$('#frm_booking_activity_year').change(function () {
					var strmonth = $('#frm_booking_activity_month option:selected').val();
					var stryear = $('#frm_booking_activity_year option:selected').val();
					$('#datetime-check').load('ajax/booking_datetime_check.php?year=' + stryear + '&month=' + strmonth);
				});

				//-- Booking Month
				$('#frm_booking_activity_month').change(function () {
					var strmonth = $('#frm_booking_activity_month option:selected').val();
					var stryear = $('#frm_booking_activity_year option:selected').val();
					$('#datetime-check').load('ajax/booking_datetime_check.php?year=' + stryear + '&month=' + strmonth);
				});

				//-- Get the store related activities to populate either the store and size
				$('#frm_activity_id').change(function() {
					
					var activity_id = $('#frm_activity_id option:selected').val();					
					var strmonth = $('#frm_booking_activity_month option:selected').val();
					var stryear = $('#frm_booking_activity_year option:selected').val();
					
					//-- make sure the store and checked all is not checked yet
					<?php $arrStores = $db->getStoreData(); ?>
					<?php if (count($arrStores) > 0) { ?>
		    		<?php foreach ($arrStores as $intStoreID=>$strStoreName) { ?>
		    		$('#frm_store_id_<?php echo $intStoreID; ?>').prop('checked', false);
		    		<?php } ?>	
		    		<?php } ?>
		    		$('#frm_check_all').prop('checked', false);

		    		//-- make sure the store count is set to 0		    		
					var store_count = 0;
		    		$('#store_count').html('<strong><em>' + store_count +'</strong></em>');

		    		//-- make sure the total price has not been counted yet
					$('#total_price').html(''); 	
					
					
					//-- Store related, if the activity selected was store related then show the stores
					<?php $arrStoreRel = $db->getActivityStoreRelated(); ?>
					<?php if (count($arrStoreRel)) { ?>
					if (
					<?php for ($i = 0; $i < count($arrStoreRel); $i++) { ?>
						activity_id == '<?php echo $arrStoreRel[$i]; ?>'
						<?php if ($i == count($arrStoreRel) - 1) { echo ''; } else { echo ' || '; } ?>
					<?php } ?>	
					) 
					{ 
						//$('#control-store-id').load('ajax/booking_activity_instore_check.php?activity_id=' + activity_id + '&year=' + stryear + '&month=' + strmonth); 
						$('#control-store-id').load('ajax/booking_check_gondola.php?activity_id=' + activity_id + '&year=' + stryear + '&month=' + strmonth); 
						$('#control-store-id').show(); 
					} 

					else 
					{ 
						$('#control-store-id').hide(); 
					}
					<?php } ?>	
					
					//-- Size ID, if the activity selected has size then populate it, then automatically select the size
					<?php $arrSizeRel = $db->getActivitySizeID(); ?>
					<?php if (count($arrSizeRel) > 0) { ?>
					if (
					<?php for ($i = 0; $i < count($arrSizeRel); $i++) { ?>
						activity_id == '<?php echo $arrSizeRel[$i]; ?>'
						<?php if ($i == count($arrSizeRel) - 1) { echo ''; } else { echo ' || '; } ?>
					<?php } ?>	
					)
					{
						$('#control-size-id').show();
						<?php $arrSizeRelData = $db->getActivitySizeIDData(); ?>
						<?php for ($j = 0; $j < count($arrSizeRelData); $j++) { ?>
							if ($('#frm_activity_id option:selected').val() == '<?php echo $arrSizeRelData[$j]['activity_id']; ?>') { $('#frm_size_id').val('<?php echo $arrSizeRelData[$j]['size_id']; ?>'); }		
						<?php } ?>
					}

					else
					{
						$('#control-size-id').hide();	
					}
					<?php } ?>	

					//-- Price, get the activity price and put it on the input
					<?php $arrActivityPrice = $db->getActivityPrice(); ?>
					<?php if (count($arrActivityPrice) > 0) { ?>
						<?php foreach ($arrActivityPrice as $intActivityID => $strPrice) { ?>
							if ($('#frm_activity_id').val() == <?php echo $intActivityID; ?>) { $('#frm_booking_activity_price').val('<?php echo $strPrice; ?>'); }
						<?php } ?>
					<?php } ?>	

					$('#control-booking-activity-price').show(500);
					
					var currentSelect = $(this);
					var textOption = currentSelect.find("option:selected").html();
					var contains = (textOption.indexOf('Gondola End') > -1) && (textOption.indexOf('Supplier Merchandised') > -1); 
					
					if(contains){
						currentSelect.after('<img class="loadingImg" src="<?php echo $STR_URL; ?>img/loading.gif">');
						$.ajax({
							url : "ajax/supplier_check_contact.php",
							data: {supplier_id : $("#frm_supplier_id").val()},
							dataType:"json",
							success: function(rs){
								if(rs.status == 0){
									var string = '<p>Please fill in the column name, phone, and email on the supplier table.<p><a href="supplier.php?supplier_id='+$("#frm_supplier_id").val()+'&action=edit&tab=2" class="btn btn-info" target="_blank">Click here</a>';
									$("#modalBody").html(string);
									$("#modalContainer").modal("show");
								}
								currentSelect.next("img").remove();
							}
						});
					}

					var containsCatalogue = (textOption.indexOf('Catalogue') > -1);
					if(containsCatalogue){
						currentSelect.after('<img class="loadingImg" src="<?php echo $STR_URL; ?>img/loading.gif">');
						$.ajax({
							url : "ajax/supplier_check_catalogue_stock.php",
							data: {supplier_id : $("#frm_supplier_id").val(), text:textOption, year:stryear, month:strmonth},
							dataType:"json",
							success: function(rs){
								if(rs.status == 0){
									var string = '<p>Stock is full for this catalogue. Please select another one.</p>';
									$("#modalBody").html(string);
									$("#modalContainer").modal("show");
									currentSelect.val("");
								}
								currentSelect.next("img").remove();
							}
						});
					}
					
				});
				
				$('#frm_booking_activity_month').on('change', function(){
					$("#frm_activity_id").trigger('change');
				});
				$('#frm_booking_activity_year').on('change', function(){
					$("#frm_activity_id").trigger('change');
				});
				
				var mode = 1;
				//-- Submit the form: declare all variables sent to the database and then reset the form
				$('.btn-submit').click(function() { 
					var button_id = this.id;
					
					if (button_id == 'frm_add_product')
					{
						//-- alert('Seq = ' + $('#frm_product_seq').val() + ', Amount = ' + $('#frm_product_amount').val());
						
						var prod_seq = parseInt($('#frm_product_seq').val());
						
						$('#frm_booking_action_btn').val('frm_add_product');
						
						<?php if ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id']) { ?>
							if(mode == 1){
								$('#child_action').val('edit-product');
								mode=2;	
							}else{
								$('#child_action').val('add-product');
							}
						<?php } else { ?>
							$('#child_action').val('add-product');
						<?php } ?>
						submitFormBooking();
						prod_seq = parseInt(prod_seq + 1);
						$('#frm_product_seq').val(prod_seq);
						
						/*--alert($('#frm_booking_id_alt').val() + ', ' + $('#frm_booking_activity_id_alt').val() + ', ' + $('#frm_booking_product_id_alt').val());	*/
						
						if ($('#frm_booking').validate())
						{
							clearProduct();	
						}
						
						<?php if ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id']) { ?>
							$('#frm_product_nav').html('<strong>' + prod_seq + ' of ' + prod_seq + '</strong>');
						<?php } ?>	
						
					}
					//-- For Submit button
					else
					{
						$('#frm_booking_action_btn').val('frm_submit_2');
						if ($('#frm_booking_id_alt').val() == '' || $('#frm_booking_id_alt').val() == 0)
						{

						}
							
						<?php if ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id']) { ?>
							if(mode == 1){
								$('#child_action').val('edit-product');	
							}else{
								$('#child_action').val('add-product');
							}
						<?php } ?>

						submitFormBooking();
						
						if ($('#frm_booking').validate())
						{
							clearProduct();	
						}
						
					}					
															
			
				}); //-- $('.btn-submit')


				function submitFormBooking()
				{
					var validate_status = $('#frm_booking').validate();
					var straction = $('#action').val();
					<?php if ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id']) { ?>
						var booking_id = $('#booking_id').val();
						var booking_activity_id = $('#frm_booking_activity_id').val();
						var booking_product_id = $('#frm_booking_product_id').val();
					<?php } ?>
	    			
	      			if (validate_status)
					{
		      			var frm_booking_code = $('#frm_booking_code').val();
		    			var frm_booking_name = "Booking " + $('#frm_booking_code').val() + " for " + $('#frm_supplier_id option:selected').text();
		    			var frm_booking_supplier_po_ref_number = $('#frm_booking_supplier_po_ref_number').val();
		    			var frm_supplier_id = $('#frm_supplier_id option:selected').val();
		    			var frm_booking_date = $('#frm_booking_date').val();
		    			
		    			var frm_booking_activity_year = $('#frm_booking_activity_year').val();
		    			var frm_booking_activity_month = $('#frm_booking_activity_month').val();
		    			var frm_activity_id = $('#frm_activity_id option:selected').val();
		    			
		    			var frm_store_id = ""; 
		    			<?php $arrStores = $db->getStoreData(); ?>
		    			<?php if (count($arrStores) > 0) { ?>
		    			<?php $i = 0; ?>	
		    			<?php foreach ($arrStores as $intStoreID=>$strStoreName) { ?>
		    			var frm_store_id_<?php echo $intStoreID; ?>; if ($('#frm_store_id_<?php echo $intStoreID; ?>').is(':checked')) { frm_store_id_<?php echo $intStoreID; ?> = $('#frm_store_id_<?php echo $intStoreID; ?>').val(); }
		    			frm_store_id += frm_store_id_<?php echo $intStoreID; ?>;
		    			<?php if ($i == count($arrStores) - 1) { ?>
		    			frm_store_id += "";
		    			<?php } else { ?>
		    			frm_store_id += ",";
		    			<?php } ?>
		    			<?php $i++; ?>
		    			<?php } ?>
		    			<?php } ?>
		    			
		    			var frm_size_id = $('#frm_size_id option:selected').val();
		    			var frm_booking_activity_price = $('#frm_booking_activity_price').val();

		    			var frm_product_seq = $('#frm_product_seq').val();
		    			var frm_booking_product_code = $('#frm_booking_product_code').val();
		    			var frm_booking_product_name = $('#frm_booking_product_name').val();
						var frm_booking_department_id = $('#frm_booking_department_id').val();
		    			var frm_booking_product_normal_retail_price = $('#frm_booking_product_normal_retail_price').val();
		    			var frm_booking_product_promo_price = $('#frm_booking_product_promo_price').val();
		    			var frm_booking_product_cost_price = $('#frm_booking_product_cost_price').val();
		    			var frm_booking_product_discount = $('#frm_booking_product_discount').val();
		    			var frm_booking_product_recommended_retail_price = $('#frm_booking_product_recommended_retail_price').val();
		      			var frm_booking_product_special_offer_details = $('#frm_booking_product_special_offer_details').val();

		      			var frm_booking_action_btn = $('#frm_booking_action_btn').val();
		      			
		      			var frm_booking_id_alt = $('#frm_booking_id_alt').val();
		      			var frm_booking_activity_id_alt = $('#frm_booking_activity_id_alt').val();
		      			var frm_booking_product_id_alt = $('#frm_booking_product_id_alt').val();
		      			
		      			var child_action = $('#child_action').val();
		      			

		      			var dataString = "action=" + straction; 
		      			
		      			<?php if ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id']) { ?>
		      				dataString += "&<?php echo $strPageIDName; ?>=" + <?php echo $_REQUEST[$strPageIDName]; ?> + "";
		      				dataString += "&booking_activity_id=" + booking_activity_id + "";
		      				dataString += "&booking_product_id=" + booking_product_id + "";
		      			<?php } ?>	

						dataString += "&frm_booking_code=" + frm_booking_code + "&frm_booking_name=" + frm_booking_name + "&frm_booking_supplier_po_ref_number=" + frm_booking_supplier_po_ref_number;
						dataString += "&frm_supplier_id=" + frm_supplier_id + "&frm_booking_date=" + frm_booking_date + "&frm_booking_activity_year=" + frm_booking_activity_year;
						dataString += "&frm_booking_activity_month=" + frm_booking_activity_month + "&frm_activity_id=" + frm_activity_id + "&frm_store_id=" + frm_store_id + "&frm_size_id=" + frm_size_id;
						dataString += "&frm_booking_activity_price=" + frm_booking_activity_price + "&frm_product_seq=" + frm_product_seq + "&frm_booking_product_code=" + frm_booking_product_code + "&frm_booking_product_name=" + frm_booking_product_name + "&frm_booking_department_id=" + frm_booking_department_id;
						dataString += "&frm_booking_product_normal_retail_price=" + frm_booking_product_normal_retail_price + "&frm_booking_product_promo_price=" + frm_booking_product_promo_price;
						dataString += "&frm_booking_product_cost_price=" + frm_booking_product_cost_price + "&frm_booking_product_recommended_retail_price=" + frm_booking_product_recommended_retail_price + "&frm_booking_product_discount=" + frm_booking_product_discount;
						dataString += "&frm_booking_product_special_offer_details=" + frm_booking_product_special_offer_details + "&frm_booking_id_alt=" + frm_booking_id_alt + "&frm_booking_action_btn=" + frm_booking_action_btn;
						dataString += "&frm_booking_activity_id_alt=" + frm_booking_activity_id_alt + "&frm_booking_product_id_alt=" + frm_booking_product_id_alt + "&child_action=" + child_action;
		      				   
						var request = $.ajax({							    
							url: "ajax/booking_proc.php",
							type: "post", 
							data: dataString,
							success: function(msg) {

								$.gritter.add({				
									title: 'Info',				
									text: '<p>' + msg + '</p>',				
									image: '<?php echo $STR_URL; ?>img/accepted.png',				
									sticky: false,				
									time: '3000'
								});

								var s1 = msg.substr(38);
								var p1 = s1.search('</div>');
								var s2 = s1.substr(0,p1);

								var p2 = s2.search(',');
								var s3 = s2.substr(0,p2);

								var s4 = s2.substr(p2+1,p1);

								var p3 = s4.search(',');
								var s5 = s4.substr(0,p3);
									
								var s6 = s4.substr(parseInt(p3+1),parseInt(s4.length));
								

								var intID = s3;
								var intActivityID = s5;
								var intProductID = s6;
								

								$('#frm_booking_id_alt').val(intID);
								$('#frm_booking_activity_id_alt').val(intActivityID);
								$('#frm_booking_product_id_alt').val(intProductID);


								$('#first-tab').hide();
								$('#preview-tab').show();

								if (frm_booking_action_btn == 'frm_add_product') 
								{
									$('#second-tab').hide();
									$('#formtab-nav a[href="#tab3"]').tab('show');
								}

								else
								{
									$('#formtab-nav a[href="#tab4"]').tab('show');	
								}
								
								
								$('.booking-code-title').html('Booking ' + frm_booking_code);
								
								<?php if ($_REQUEST['action'] == 'edit' && $_REQUEST['booking_id']) { ?>
								$('#button-finish').attr('href', '<?php echo $STR_URL; ?>booking_view.php?booking_id=<?php echo $_REQUEST['booking_id']; ?>&action=view');
								$('#frm_preview').load('ajax/booking_activity_preview.php?booking_id=<?php echo $_REQUEST['booking_id']; ?>&action=edit');
								<?php } else { ?>	
								$('#button-finish').attr('href', '<?php echo $STR_URL; ?>booking_view.php?booking_id=' + intID + '&action=view');
								$('#frm_preview').load('ajax/booking_activity_preview.php?booking_id=' + intID);
								<?php } ?>
							
							} //-- function(msg) 
								    
						});	//-- request = $.ajax({	})				


					} //-- if (validate_status)

					$('#frm_add_activity').click(function() {
						
						getNewBookingActivityID();
						clearActivity();
						clearProduct();

						$('#second-tab').show();
						$('#formtab-nav a[href="#tab2"]').tab('show');
						$('#child_action').val('add-activity');
						$('#action').val('add');
						$('#frm_product_seq').val(1);

					});

					
				}


				function getNewBookingActivityID()
				{
					var str_action = 'add-activity';

					var request = $.ajax({
									url: 'ajax/activity_get_next_id.php',
									type: 'post',
									data: 'action=' + str_action,
									success: function(msg) {
										if (msg !== 'No results found')	
										{
											$('#frm_booking_activity_id_alt').val(msg);								
										}
									}
					});
				}


				function clearForm()
				{
					// clear				
					$('#frm_activity_id').val('');
					$('#frm_booking_activity_price').val('');
					$('#control-size-id').hide();
					$('#frm_size_id').val('');
					$('#control-store-id').hide();
					$('#control-booking-activity-price').hide();
					
					$('#frm_product_seq').val(1);
					$('#frm_booking_department_id').val('');
					$('#frm_booking_product_name').val('');
					$('#frm_booking_product_code').select2('val',[]);
					$('#frm_booking_product_normal_retail_price').val('');
					$('#frm_booking_product_promo_price').val('');
					$('#frm_booking_product_cost_price').val('');
					$('#frm_booking_product_discount').val('');
					$('#frm_booking_product_recommended_retail_price').val('');
					$('#frm_booking_product_special_offer_details').val('');

					$('#statusbox').html('');
					$('#frm_booking_activity_id_alt').val('');
					$('#frm_booking_product_id_alt').val('');

					<?php $arrStores = $db->getStoreData(); ?>
					<?php if (count($arrStores) > 0) { ?>
			    	<?php foreach ($arrStores as $intStoreID=>$strStoreName) { ?>
			    	$('#frm_store_id_<?php echo $intStoreID; ?>').prop('checked', false);
			    	<?php } ?>	
			    	<?php } ?>
			    	$('#frm_check_all').prop('checked', false);
			    	$('#store_count').html('<strong><em>0</strong></em>');	

					// set to focus
					$('#frm_booking_activity_year').focus();
				}


				function clearProduct()
				{					
					$('#frm_booking_department_id').val('');
					$('#frm_booking_product_name').val('');
					$('#frm_booking_product_code').select2('val',[]);
					$('#frm_booking_product_normal_retail_price').val('');
					$('#frm_booking_product_promo_price').val('');
					$('#frm_booking_product_cost_price').val('');
					$('#frm_booking_product_discount').val('');
					$('#frm_booking_product_recommended_retail_price').val('');
					$('#frm_booking_product_special_offer_details').val('');
				
				}


				function clearActivity()
				{
					$('#frm_activity_id').val('');
					$('#frm_booking_activity_price').val('');
					$('#control-size-id').hide();
					$('#frm_size_id').val('');
					$('#control-store-id').hide();
					$('#control-booking-activity-price').hide();					

					<?php $arrStores = $db->getStoreData(); ?>
					<?php if (count($arrStores) > 0) { ?>
			    	<?php foreach ($arrStores as $intStoreID=>$strStoreName) { ?>
			    	$('#frm_store_id_<?php echo $intStoreID; ?>').prop('checked', false);
			    	<?php } ?>	
			    	<?php } ?>
			    	$('#frm_check_all').prop('checked', false);
			    	$('#store_count').html('<strong><em>0</strong></em>');
				}

			});

			</script>			
			
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>