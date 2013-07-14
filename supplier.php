<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<?php 
	// Page Config
	$strPageName = "Supplier";
	$strPageIDName = "supplier_id";
	$strDBTableName = "mbs_suppliers";
	$strDBFieldName = "supplier_name";

	// determine the state
	if (!$_REQUEST['action']) { $_REQUEST['action'] = "add"; } 
	
	$strState = "";
	
	if ($_REQUEST['action'] == "add") { $strState = "Insert New"; } elseif ($_REQUEST['action'] == "edit") { $strState = "Update"; } else { $strState = "Insert New"; }

	if ($_REQUEST[$strPageIDName])
	{
		$strPageItemName = DB::dbIDtoField($strDBTableName, $strPageIDName, $_REQUEST[$strPageIDName], $strDBFieldName);

		$strQuery = "SELECT * FROM `" . $strDBTableName . "` WHERE `" . $strPageIDName . "` = '" . mysql_real_escape_string($_REQUEST[$strPageIDName]) .  "' LIMIT 1";
		$result = mysql_query($strQuery);

		if ($result)
		{
			$row = mysql_fetch_assoc($result);
		}	


		// get account contacts
		$strQuery2 = "SELECT * FROM `mbs_suppliers_account_contacts` WHERE `" . $strPageIDName . "` = '" . mysql_real_escape_string($_REQUEST[$strPageIDName]) .  "' ORDER BY `supplier_account_id`";
		$result2 = mysql_query($strQuery2);
		
		if ($result2)
		{
			$arrAccounts = array();
			while ($row2 = mysql_fetch_assoc($result2))
			{
				$arrAccounts[] = $row2;
			}
		}


		// get marketing contacts
		$strQuery3 = "SELECT * FROM `mbs_suppliers_marketing_contacts` WHERE `" . $strPageIDName . "` = '" . mysql_real_escape_string($_REQUEST[$strPageIDName]) .  "' ORDER BY `supplier_contact_id`";
		$result3 = mysql_query($strQuery3);
		
		if ($result3)
		{
			$arrContacts = array();
			while ($row3 = mysql_fetch_assoc($result3))
			{
				$arrContacts[] = $row3;
			}
		}


		// get territory contacts
		$strQuery4 = "SELECT * FROM `mbs_suppliers_territory_contacts` WHERE `" . $strPageIDName . "` = '" . mysql_real_escape_string($_REQUEST[$strPageIDName]) .  "' ORDER BY `supplier_territory_id`";
		$result4 = mysql_query($strQuery4);
		
		if ($result4)
		{
			$arrTerritory = array();
			while ($row4 = mysql_fetch_assoc($result4))
			{
				$arrTerritory[] = $row4;
			}
		}


		// for delete
		if ($_REQUEST['action'] == "delete" && $strPageItemName)
		{
			$strQueryDelete = "DELETE FROM `" . $strDBTableName . "` WHERE `" . $strPageIDName . "` = '" . mysql_real_escape_string($_REQUEST[$strPageIDName]) .  "' LIMIT 1";
			$resultQueryDelete = mysql_query($strQueryDelete);

			$strLog = 'Supplier named "' . $strPageItemName . '" is successfully deleted.';
					
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

	
	<?php if ($_REQUEST['action'] == "add") { ?>
	<!-- JS for Page -->
	<script>

        pic1 = new Image(16, 16); 
		pic1.src = "<?php echo $STR_URL; ?>img/loading.gif";
				
		$(document).ready(function() {
				
			$("#frm_supplier_name").blur(function() { 
				
				var strcheck = $("#frm_supplier_name").val();
					
				if (strcheck.length >= 3)
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/loading.gif" /> Checking <?php echo $strPageName; ?>...');
					
						$.ajax({ 
								type: "post", 
								url: "ajax/supplier_check.php", 
								data: "frm_supplier_name="+strcheck, 
								success: function(msg) { 
									
									if (msg == "yes") 
									{
										$("#frm_supplier_name").removeClass("status_not_ok"); 
										$("#frm_supplier_name").addClass("status_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/accepted.png" /> <span class="greentext"><?php echo $strPageName; ?> is OK!</span>');		
									}

									else if (msg == "no") 
									{ 
										$("#frm_supplier_name").removeClass("status_ok"); 
										$("#frm_supplier_name").addClass("status_not_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <span class="redtext"><?php echo $strPageName; ?> is already exist in the database! Please try something else!</span>'); 
									}
									
								}
							
						});
						
				}
							
				else						
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <?php echo $strPageName; ?> name must contain of 3 characters minimum!');
					$("#frm_supplier_name").removeClass('status_ok'); // if necessary
					$("#frm_supplier_name").addClass("status_not_ok");
							
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
				$strMessage = $strPageName . " named \"" . $strPageItemName . "\" is successfully deleted!"; 
				$strImageInfo = "accepted.png";
			} 

			else 
			{ 
				$strMessage = "Failed to delete " . $strPageName . " or there is no record found!"; 
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

				clearForm();
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
			  <li><a href="master_data.php">Master Data</a> <span class="divider">/</span></li>
			  <li><a href="supplier_list.php">Suppliers</a> <span class="divider">/</span></li>
			  <li class="active"><?php if ($_REQUEST['action'] == "add") { ?>New<?php } elseif ($_REQUEST['action'] == "edit") { ?>Update<?php } ?> <?php echo $strPageName; ?></li>
			</ul>	


			<h2><?php echo $strState; ?> <?php echo $strPageName; ?> <?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { echo " &raquo; " . $strPageItemName; } ?></h2>
			
			<!-- Menu -->
			<div class="pull-right">
				<?php if ($admin->getModulePrivilege('suppliers', 'add') > 0 && $_REQUEST['action'] == "edit") { ?>
				<a class="btn btn-popover" href="supplier.php?action=add" rel="popover" data-content="Add new Supplier to the database" data-original-title="New <?php echo $strPageName; ?>" title="New <?php echo $strPageName; ?>"><i class="icon-plus"></i> New</a>
				<?php } ?>
				<?php if ($admin->getModulePrivilege('suppliers', 'view') > 0 && $_REQUEST['action'] == "edit") { ?>
				<a class="btn btn-popover" href="supplier_view.php?supplier_id=<?php echo $_REQUEST['supplier_id']; ?>&action=view" rel="popover" data-content="View Supplier details" data-original-title="View <?php echo $strPageName; ?>" title="View <?php echo $strPageName; ?>"><i class="icon-info-sign"></i> View</a>
				<?php } ?>
				<?php if ($admin->getModulePrivilege('suppliers', 'delete') > 0 && $_REQUEST['action'] == "edit") { ?>
				<a class="btn btn-popover" href="supplier.php?supplier_id=<?php echo $_REQUEST['supplier_id']; ?>&action=delete" onclick="return confirmDeleteSupplier(this.form)" rel="popover" data-content="Delete this Supplier from the database" data-original-title="Delete <?php echo $strPageName; ?>" title="Delete <?php echo $strPageName; ?>"><i class="icon-remove"></i> Delete</a>
				<?php } ?>
				<?php if ($admin->getModulePrivilege('suppliers', 'list') > 0) { ?>
				<a class="btn btn-popover" href="supplier_list.php" rel="popover" data-content="The Supplier List on the database" data-original-title="<?php echo $strPageName; ?> List" title="<?php echo $strPageName; ?> List"><i class="icon-list-alt"></i> List</a>			
				<?php } ?>
				<a class="btn btn-popover" href="documentation_list.php#suppliers" rel="popover" data-content="Look up for the Documentation about Supplier module" data-original-title="Help" title="Help"><i class="icon-info-sign"></i> Help</a>
			</div>

			<a href="#" id="top"></a>

			<div style="margin-bottom:20px;"></div>

			<!-- Form -->
			<form id="frm_supplier" class="form-horizontal" action="" method="post">
				<?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { ?><input type="hidden" name="<?php echo $strPageIDName; ?>" id="<?php echo $strPageIDName; ?>" value="<?php echo $_REQUEST[$strPageIDName]; ?>" /><?php } ?>
				<input type="hidden" name="action" id="action" value="<?php if ($_REQUEST['action'] == "edit") { echo "edit"; } else { echo "add"; } ?>" />
				
				<div class="tabbable">
					<ul class="nav nav-tabs" id="formtab-nav">
						<li class="active" id="first-tab"><a href="#tab1" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the Supplier Company data. Please fill all required fields (*)" data-original-title="1. Supplier Data Tab"><i class="icon-align-justify"></i> Supplier Data</a></li>
						<li id="second-tab"><a href="#tab2" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the Supplier's Marketing Contact data. Please fill all required fields (*)" data-original-title="2. Marketing Contact Data Tab"><i class="icon-align-justify"></i> Marketing Contact</a></li>
						<li id="third-tab"><a href="#tab3" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the Supplier's Account Contact data. Please fill all required fields (*)" data-original-title="3. Account Contact Tab"><i class="icon-align-justify"></i> Account Contact</a></li>
						<li id="fourth-tab"><a href="#tab4" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the Supplier's Territory Contacts data. Please fill all required fields (*)" data-original-title="4. Territory Contacts Tab"><i class="icon-align-justify"></i> Territory Contacts</a></li>
						<li id="fifth-tab"><a href="#tab5" data-toggle="tab" class="btn-popover" rel="popover" data-content="This tab is for the Supplier's booking related data. Please fill all required fields (*)" data-original-title="5. Supplier Misc. info Tab"><i class="icon-align-justify"></i> Miscellaneous</a></li>
					</ul>


					<div class="tab-content">	

						<div class="tab-pane active" id="tab1">

							<div class="container-fluid">
					  			<div class="row-fluid">
							
									<fieldset>
									    <div id="legend">
									    	<legend class="">Supplier Data</legend>
									    </div>
									    
									    <!-- Supplier -->										    
										<div class="control-group">
											<!-- Supplier Name -->
										    <label class="control-label" for="frm_supplier_name">Name</label>
										    <div class="controls">
										    	<input type="text" id="frm_supplier_name" name="frm_supplier_name" placeholder="Type Supplier name" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_name']) { echo stripslashes($_REQUEST['frm_supplier_name']); } elseif (!$_REQUEST['frm_supplier_name'] && $row['supplier_name']) { echo stripslashes($row['supplier_name']); } ?>" data-validation="required" /> *	        
										        <p class="help-block"></p>
										       	<div id="statusbox"></div>
										    </div>
										</div>
										 
										<div class="control-group">
										    <!-- Supplier Postal Address -->
										    <label class="control-label" for="frm_supplier_postal_address">Postal Address</label>
										    <div class="controls">
										    	<textarea name="frm_supplier_postal_address" id="frm_supplier_postal_address" placeholder="Type Supplier postal address" class="input-xlarge" rows="3" data-validation="required"><?php if ($_REQUEST['frm_supplier_postal_address']) { echo stripslashes($_REQUEST['frm_supplier_postal_address']); } elseif (!$_REQUEST['frm_supplier_postal_address'] && $row['supplier_postal_address']) { echo stripslashes($row['supplier_postal_address']); } ?></textarea> *
										        <p class="help-block"></p>
										    </div>
										</div>

										<div class="control-group">
										    <!-- Supplier Contact Number -->
										    <label class="control-label" for="frm_supplier_phone_number">Contact Number</label>
										    <div class="controls">
										        <input type="text" id="frm_supplier_phone_number" name="frm_supplier_phone_number" placeholder="Type Supplier contact number" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_phone_number']) { echo stripslashes($_REQUEST['frm_supplier_phone_number']); } elseif (!$_REQUEST['frm_supplier_phone_number'] && $row['supplier_phone_number']) { echo stripslashes($row['supplier_phone_number']); } ?>" data-validation="required" /> *	        
										        <p class="help-block"></p>				        
										    </div>
										</div>

										<div class="control-group">
										    <!-- Supplier Contact Email -->
										    <label class="control-label" for="frm_supplier_email">Contact Email</label>
										    <div class="controls">
										        <input type="text" id="frm_supplier_email" name="frm_supplier_email" placeholder="Type Supplier contact email" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_email']) { echo stripslashes($_REQUEST['frm_supplier_email']); } elseif (!$_REQUEST['frm_supplier_email'] && $row['supplier_email']) { echo stripslashes($row['supplier_email']); } ?>" data-validation="validate_email" /> *	        
										        <p class="help-block"></p>				        
										    </div>
										</div>

										<div class="control-group">
								      	<!-- Reference No for Order -->
									      <label class="control-label" for="frm_supplier_po_ref_number">Ref No required?</label>
									      <div class="controls">
									      	<input type="checkbox" name="frm_supplier_po_ref_number_required" id="frm_supplier_po_ref_number_required" value="yes"<?php if ($_REQUEST['frm_supplier_po_ref_number_required'] || $row['supplier_po_ref_number']) { ?>checked="checked"<?php } ?> /> 
									        <input type="text" id="frm_supplier_po_ref_number" name="frm_supplier_po_ref_number" placeholder="Type Supplier's Ref. No" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_po_ref_number']) { echo stripslashes($_REQUEST['frm_supplier_po_ref_number']); } elseif (!$_REQUEST['frm_supplier_po_ref_number'] && $row['supplier_po_ref_number']) { echo stripslashes($row['supplier_po_ref_number']); } ?>" data-validation="" /> 
									        <p class="help-block">Please check and type in the supplier's reference number for purchase order if required</p>
									      </div>
									    </div>
										 
										<div class="control-group">
										    <!-- Supplier Active -->
										    <label class="control-label" for="frm_supplier_active">Activate?</label>
										    <div class="controls">
										        <input type="checkbox" name="frm_supplier_active" id="frm_supplier_active" value="yes"<?php if ($_REQUEST['frm_supplier_active'] == "yes" || $row['supplier_active'] == "yes") { ?> checked="checked"<?php } ?> /> 
										        <p class="help-block">Set this to active?</p>
										    </div>
										</div>

										<div class="control-group">
											<!-- Button -->
											<div class="controls">
												<button class="btn" type="button" name="frm_next_1" id="frm_next_1">Next <i class="icon-forward"></i></button>
											</div>
										</div>	
										<!-- Supplier -->	
										
									</fieldset>	

								</div>
							</div>		

						</div>	<!--#tab1 -->


						<div class="tab-pane" id="tab2">

						<!-- Supplier > Marketing Contact -->
						<div class="container-fluid">
					  		<div class="row-fluid">	

					  			<fieldset>
								  	<div>
								  		<legend>Marketing Contact</legend>		
								  	</div>

								  	<input type="hidden" name="supplier_contact_id" id="supplier_contact_id" value="<?php if ($_REQUEST['supplier_contact_id']) { echo stripslashes($_REQUEST['supplier_contact_id']); } elseif (!$_REQUEST['supplier_contact_id'] && $arrContacts[0]['supplier_contact_id']) { echo stripslashes($arrContacts[0]['supplier_contact_id']); } ?>" />

								  	<div class="control-group">
								      <!-- Supplier Contact Name -->
								      <label class="control-label" for="frm_supplier_contact_name">Name</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_contact_name" name="frm_supplier_contact_name" placeholder="Type Supplier marketing contact name" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_contact_name']) { echo stripslashes($_REQUEST['frm_supplier_contact_name']); } elseif (!$_REQUEST['frm_supplier_contact_name'] && $arrContacts[0]['supplier_contact_name']) { echo stripslashes($arrContacts[0]['supplier_contact_name']); } ?>" data-validation="required" /> *
								        <p class="help-block">The supplier marketing contact name</p>				        
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Contact Position -->
								      <label class="control-label" for="frm_supplier_contact_position">Title/Position</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_contact_position" name="frm_supplier_contact_position" placeholder="Type Supplier marketing contact title or position" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_contact_position']) { echo stripslashes($_REQUEST['frm_supplier_contact_position']); } elseif (!$_REQUEST['frm_supplier_contact_position'] && $arrContacts[0]['supplier_contact_position']) { echo stripslashes($arrContacts[0]['supplier_contact_position']); } ?>" data-validation="required" /> *
								        <p class="help-block">The supplier marketing contact title or position</p>				        
								      </div>
								    </div>
								 
								    <div class="control-group">
								      <!-- Supplier Contact Postal Address -->
								      <label class="control-label" for="frm_supplier_contact_postal_address">Postal Address (Billing Address)</label>
								      <div class="controls">
								        <textarea name="frm_supplier_contact_postal_address" id="frm_supplier_contact_postal_address" placeholder="Type Supplier marketing contact postal address" class="input-xlarge" rows="3" data-validation="required"><?php if ($_REQUEST['frm_supplier_contact_postal_address']) { echo stripslashes($_REQUEST['frm_supplier_contact_postal_address']); } elseif (!$_REQUEST['frm_supplier_contact_postal_address'] && $arrContacts[0]['supplier_contact_postal_address']) { echo stripslashes($arrContacts[0]['supplier_contact_postal_address']); } ?></textarea> *
								        <p class="help-block">The supplier marketing contact postal address. This will be the Billing Address at the Booking document</p>
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Contact Number -->
								      <label class="control-label" for="frm_supplier_contact_phone_number">Contact Number</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_contact_phone_number" name="frm_supplier_contact_phone_number" placeholder="Type Supplier marketing contact number" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_contact_phone_number']) { echo stripslashes($_REQUEST['frm_supplier_contact_phone_number']); } elseif (!$_REQUEST['frm_supplier_contact_phone_number'] && $arrContacts[0]['supplier_contact_phone_number']) { echo stripslashes($arrContacts[0]['supplier_contact_phone_number']); } ?>" data-validation="required" /> *
								        <p class="help-block">The supplier marketing contact number</p>				        
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Contact Mobile -->
								      <label class="control-label" for="frm_supplier_contact_mobile_number">Mobile Number</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_contact_mobile_number" name="frm_supplier_contact_mobile_number" placeholder="Type Supplier marketing contact mobile number" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_contact_mobile_number']) { echo stripslashes($_REQUEST['frm_supplier_contact_mobile_number']); } elseif (!$_REQUEST['frm_supplier_contact_mobile_number'] && $arrContacts[0]['supplier_contact_mobile_number']) { echo stripslashes($arrContacts[0]['supplier_contact_mobile_number']); } ?>" data-validation="required" /> *
								        <p class="help-block">The supplier marketing contact mobile number</p>				        
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Contact Email -->
								      <label class="control-label" for="frm_supplier_contact_email">Contact Email</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_contact_email" name="frm_supplier_contact_email" placeholder="Type Supplier marketing contact email" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_contact_email']) { echo stripslashes($_REQUEST['frm_supplier_contact_email']); } elseif (!$_REQUEST['frm_supplier_contact_email'] && $arrContacts[0]['supplier_contact_email']) { echo stripslashes($arrContacts[0]['supplier_contact_email']); } ?>" data-validation="validate_email" />  *
								        <p class="help-block">The supplier marketing contact email</p>				       
								      </div>
								    </div>

								    <div class="control-group">
										<!-- Button -->
										<div class="controls">
											<button class="btn" type="button" name="frm_prev_1" id="frm_prev_1">Previous <i class="icon-backward"></i></button>&nbsp;&nbsp;&nbsp; <button class="btn" type="button" name="frm_next_2" id="frm_next_2">Next <i class="icon-forward"></i></button>
										</div>
									</div>	
									
							  	</fieldset>	

					  		</div>	
					  	</div>		  		
				  		<!-- Supplier > Marketing Contact -->

				  	</div> <!--#tab2 -->	

				  	
				  	<div class="tab-pane" id="tab3">
					  	<!-- Supplier > Accounts Contact -->
				  		<div class="container-fluid">
					  		<div class="row-fluid">

					  			<fieldset>
								  	<div>
								  		<legend>Accounts Contact</legend>		
								  	</div>

								  	<input type="hidden" name="supplier_account_id" id="supplier_account_id" value="<?php if ($_REQUEST['supplier_account_id']) { echo stripslashes($_REQUEST['supplier_account_id']); } elseif (!$_REQUEST['supplier_account_id'] && $arrAccounts[0]['supplier_account_id']) { echo stripslashes($arrAccounts[0]['supplier_account_id']); } ?>" />

								  	<div class="control-group">
								      <!-- Supplier Contact Name -->
								      <label class="control-label" for="frm_supplier_account_name">Name</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_account_name" name="frm_supplier_account_name" placeholder="Type Supplier account contact name" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_account_name']) { echo stripslashes($_REQUEST['frm_supplier_account_name']); } elseif (!$_REQUEST['frm_supplier_account_name'] && $arrAccounts[0]['supplier_account_name']) { echo stripslashes($arrAccounts[0]['supplier_account_name']); } ?>" data-validation="" /> 
								        <p class="help-block">The supplier account contact name</p>				        
								      </div>
								    </div>
								 
								    <div class="control-group">
								      <!-- Supplier Contact Postal Address -->
								      <label class="control-label" for="frm_supplier_account_postal_address">Postal Address</label>
								      <div class="controls">
								        <textarea name="frm_supplier_account_postal_address" id="frm_supplier_account_postal_address" placeholder="Type Supplier account contact postal address" class="input-xlarge" rows="3" data-validation=""><?php if ($_REQUEST['frm_supplier_account_postal_address']) { echo stripslashes($_REQUEST['frm_supplier_account_postal_address']); } elseif (!$_REQUEST['frm_supplier_account_postal_address'] && $arrAccounts[0]['supplier_account_postal_address']) { echo stripslashes($arrAccounts[0]['supplier_account_postal_address']); } ?></textarea> 
								        <p class="help-block">The supplier account contact postal address</p>
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Contact Number -->
								      <label class="control-label" for="frm_supplier_account_phone_number">Contact Number</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_account_phone_number" name="frm_supplier_account_phone_number" placeholder="Type Supplier account contact number" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_account_phone_number']) { echo stripslashes($_REQUEST['frm_supplier_account_phone_number']); } elseif (!$_REQUEST['frm_supplier_account_phone_number'] && $arrAccounts[0]['supplier_account_phone_number']) { echo stripslashes($arrAccounts[0]['supplier_account_phone_number']); } ?>" data-validation="" /> 
								        <p class="help-block">The supplier account contact number</p>				        
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Contact Email -->
								      <label class="control-label" for="frm_supplier_account_email">Contact Email</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_account_email" name="frm_supplier_account_email" placeholder="Type Supplier account contact email" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_account_email']) { echo stripslashes($_REQUEST['frm_supplier_account_email']); } elseif (!$_REQUEST['frm_supplier_account_email'] && $arrAccounts[0]['supplier_account_email']) { echo stripslashes($arrAccounts[0]['supplier_account_email']); } ?>" data-validation="" /> 
								        <p class="help-block">The supplier account contact email</p>				        
								      </div>
								    </div>

								    <div class="control-group">
										<!-- Button -->
										<div class="controls">
											<button class="btn" type="button" name="frm_prev_2" id="frm_prev_2">Previous <i class="icon-backward"></i></button>&nbsp;&nbsp;&nbsp; <button class="btn" type="button" name="frm_next_3" id="frm_next_3">Next <i class="icon-forward"></i></button>
										</div>
									</div>
								    
							  	</fieldset>

					  		</div>
					  	</div>		
				  		<!-- Supplier > Accounts Contact -->

			  		</div> <!--#tab3 -->	

			  		
			  		<div class="tab-pane" id="tab4">
				  		<!-- Supplier > Territory Contact -->	
					  	<div class="container-fluid">
					  		<div class="row-fluid">				      	
						      	
					  		<fieldset>
							  	<div>
							  		<legend>Territory Contact</legend>		
							  	</div>	

						      	
						      	
					  		<div class="row-fluid">

					      		<div class="span4">
						        	<!-- Supplier Territory Contact Area 1 -->
						        	<label for="frm_territory_id_1">Area</label>
						        	<!--
						        	<?php $arrTerritory1 = $db->getTerritoryData(); ?>
						      		<label for="frm_territory_id_1">Area</label>
						      		<?php echo "\n<select name=\"frm_territory_id_1\" id=\"frm_territory_id_1\">"; ?>
								    <?php echo "\n\t<option value=\"\">-- Not specified --</option>"; ?>
								    <?php
										if (is_array($arrTerritory1) && count($arrTerritory1) > 0)
										{
											foreach ($arrTerritory1 as $intTerritoryID=>$arrTerritoryData)
											{
												echo "\n\t<option value=\"" . $intTerritoryID . "\"";  
											
												if ($_REQUEST['frm_territory_id_1'] && $_REQUEST['frm_territory_id_1'] == $intTerritoryID)
												{
													echo " selected=\"selected\"";
												}

												elseif (!$_REQUEST['frm_territory_id_1'] && $arrTerritory[0]['territory_id'] == $intTerritoryID) {
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrTerritoryData['territory_name']) . "</option>";
											}
										}
								    ?>
								    <?php echo "\n</select>"; ?>-->
						        	<input type="text" id="frm_territory_name_1" name="frm_territory_name_1" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_territory_name_1']) { echo stripslashes($_REQUEST['frm_territory_name_1']); } elseif (!$_REQUEST['frm_territory_name_1'] && $arrTerritory[0]['territory_name']) { echo stripslashes($arrTerritory[0]['territory_name']); } ?>" data-validation="" />
						        	<p class="help-block">The supplier territory area</p>				        
					      		</div>

					      		<div class="span4">
						        	<!-- Supplier Territory Contact Name 1 -->
						      		<label for="frm_supplier_territory_name_1">Territory Contact 1</label>
						        	<input type="text" id="frm_supplier_territory_name_1" name="frm_supplier_territory_name_1" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_name_1']) { echo stripslashes($_REQUEST['frm_supplier_territory_name_1']); } elseif (!$_REQUEST['frm_supplier_territory_name_1'] && $arrTerritory[0]['supplier_territory_name']) { echo stripslashes($arrTerritory[0]['supplier_territory_name']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact name</p>				        
						      	</div>				      	

					      		<div class="span4">
						        	<!-- Supplier Territory Contact Number 1 -->
						      		<label for="frm_supplier_territory_phone_number_1">Contact Number</label>
						        	<input type="text" id="frm_supplier_territory_phone_number_1" name="frm_supplier_territory_phone_number_1" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_phone_number_1']) { echo stripslashes($_REQUEST['frm_supplier_territory_phone_number_1']); } elseif (!$_REQUEST['frm_supplier_territory_phone_number_1'] && $arrTerritory[0]['supplier_territory_phone_number']) { echo stripslashes($arrTerritory[0]['supplier_territory_phone_number']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact number</p>				        
					      		</div>

					      		<input type="hidden" name="frm_supplier_territory_id_1" id="frm_supplier_territory_id_1" value="<?php if ($_REQUEST['frm_supplier_territory_id_1']) { echo stripslashes($_REQUEST['frm_supplier_territory_id_1']); } elseif (!$_REQUEST['frm_supplier_territory_id_1'] && $arrTerritory[0]['supplier_territory_id']) { echo stripslashes($arrTerritory[0]['supplier_territory_id']); } ?>" />

					      	</div>

					      	<div class="row-fluid" style="margin-top:20px;">
					      		<div class="span4">
						        	<!-- Supplier Territory Contact Area 2 -->
						        	<label for="frm_territory_id_2">Area</label>
						        	<!--<?php $arrTerritory2 = $db->getTerritoryData(); ?>
						      		<label for="frm_territory_id_2">Area</label>
						      		<?php echo "\n<select name=\"frm_territory_id_2\" id=\"frm_territory_id_2\">"; ?>
								    <?php echo "\n\t<option value=\"\">-- Not specified --</option>"; ?>
								    <?php
										if (is_array($arrTerritory2) && count($arrTerritory2) > 0)
										{
											foreach ($arrTerritory2 as $intTerritoryID=>$arrTerritoryData)
											{
												echo "\n\t<option value=\"" . $intTerritoryID . "\"";  
											
												if ($_REQUEST['frm_territory_id_2'] && $_REQUEST['frm_territory_id_2'] == $intTerritoryID)
												{
													echo " selected=\"selected\"";
												}

												elseif (!$_REQUEST['frm_territory_id_2'] && $arrTerritory[1]['territory_id'] == $intTerritoryID) {
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrTerritoryData['territory_name']) . "</option>";
											}
										}
								    ?>
								    <?php echo "\n</select>"; ?>-->					      		
						        	<input type="text" id="frm_territory_name_2" name="frm_territory_name_2" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_territory_name_2']) { echo stripslashes($_REQUEST['frm_territory_name_2']); } elseif (!$_REQUEST['frm_territory_name_2'] && $arrTerritory[1]['territory_name']) { echo stripslashes($arrTerritory[1]['territory_name']); } ?>" data-validation="" />
						        	<p class="help-block">The supplier territory area</p>				        
					      		</div>

					      		<div class="span4">
						        	<!-- Supplier Territory Contact Name 2 -->
						      		<label for="frm_supplier_territory_name_2">Territory Contact 2</label>
						        	<input type="text" id="frm_supplier_territory_name_2" name="frm_supplier_territory_name_2" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_name_2']) { echo stripslashes($_REQUEST['frm_supplier_territory_name_2']); } elseif (!$_REQUEST['frm_supplier_territory_name_2'] && $arrTerritory[1]['supplier_territory_name']) { echo stripslashes($arrTerritory[1]['supplier_territory_name']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact name</p>				        
						      	</div>				      	

						      	<div class="span4">
						        	<!-- Supplier Territory Contact Number 2 -->
						      		<label for="frm_supplier_territory_phone_number_2">Contact Number</label>
						        	<input type="text" id="frm_supplier_territory_phone_number_2" name="frm_supplier_territory_phone_number_2" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_phone_number_2']) { echo stripslashes($_REQUEST['frm_supplier_territory_phone_number_2']); } elseif (!$_REQUEST['frm_supplier_territory_phone_number_2'] && $arrTerritory[1]['supplier_territory_phone_number']) { echo stripslashes($arrTerritory[1]['supplier_territory_phone_number']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact number</p>				        
					      		</div>	

					      		<input type="hidden" name="frm_supplier_territory_id_2" id="frm_supplier_territory_id_2" value="<?php if ($_REQUEST['frm_supplier_territory_id_2']) { echo stripslashes($_REQUEST['frm_supplier_territory_id_2']); } elseif (!$_REQUEST['frm_supplier_territory_id_2'] && $arrTerritory[1]['supplier_territory_id']) { echo stripslashes($arrTerritory[1]['supplier_territory_id']); } ?>" />

					      	</div>	


					      	<div class="row-fluid" style="margin-top:20px;">	
					      		<div class="span4">
						        	<!-- Supplier Territory Contact Area 3 -->
						      		<label for="frm_territory_id_3">Area</label>
						      		<!--
						      		<?php $arrTerritory3 = $db->getTerritoryData(); ?>
						      		<label for="frm_territory_id_3">Area</label>
						      		<?php echo "\n<select name=\"frm_territory_id_3\" id=\"frm_territory_id_3\">"; ?>
								    <?php echo "\n\t<option value=\"\">-- Not specified --</option>"; ?>
								    <?php
										if (is_array($arrTerritory3) && count($arrTerritory3) > 0)
										{
											foreach ($arrTerritory3 as $intTerritoryID=>$arrTerritoryData)
											{
												echo "\n\t<option value=\"" . $intTerritoryID . "\"";  
											
												if ($_REQUEST['frm_territory_id_3'] && $_REQUEST['frm_territory_id_3'] == $intTerritoryID)
												{
													echo " selected=\"selected\"";
												}

												elseif (!$_REQUEST['frm_territory_id_3'] && $arrTerritory[2]['territory_id'] == $intTerritoryID) {
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrTerritoryData['territory_name']) . "</option>";
											}
										}
								    ?>
								    <?php echo "\n</select>"; ?>-->
						        	<input type="text" id="frm_territory_name_3" name="frm_territory_name_3" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_territory_name_3']) { echo stripslashes($_REQUEST['frm_territory_name_3']); } elseif (!$_REQUEST['frm_territory_name_3'] && $arrTerritory[2]['territory_name']) { echo stripslashes($arrTerritory[2]['territory_name']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory area</p>				        
					      		</div>

					      		<div class="span4">
						        	<!-- Supplier Territory Contact Name 3 -->
						      		<label for="frm_supplier_territory_name_3">Territory Contact 3</label>
						        	<input type="text" id="frm_supplier_territory_name_3" name="frm_supplier_territory_name_3" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_name_3']) { echo stripslashes($_REQUEST['frm_supplier_territory_name_3']); } elseif (!$_REQUEST['frm_supplier_territory_name_3'] && $arrTerritory[2]['supplier_territory_name']) { echo stripslashes($arrTerritory[2]['supplier_territory_name']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact name</p>				        
						      	</div>				      	
					      		
					      		<div class="span4">
						        	<!-- Supplier Territory Contact Number 3 -->
						      		<label for="frm_supplier_territory_phone_number_3">Contact Number</label>
						        	<input type="text" id="frm_supplier_territory_phone_number_3" name="frm_supplier_territory_phone_number_3" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_phone_number_3']) { echo stripslashes($_REQUEST['frm_supplier_territory_phone_number_3']); } elseif (!$_REQUEST['frm_supplier_territory_phone_number_3'] && $arrTerritory[2]['supplier_territory_phone_number']) { echo stripslashes($arrTerritory[2]['supplier_territory_phone_number']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact number</p>				        
					      		</div>

					      		<input type="hidden" name="frm_supplier_territory_id_3" id="frm_supplier_territory_id_3" value="<?php if ($_REQUEST['frm_supplier_territory_id_3']) { echo stripslashes($_REQUEST['frm_supplier_territory_id_3']); } elseif (!$_REQUEST['frm_supplier_territory_id_3'] && $arrTerritory[2]['supplier_territory_id']) { echo stripslashes($arrTerritory[2]['supplier_territory_id']); } ?>" />

					      	</div>		

					      	<div class="row-fluid" style="margin-top:20px;">	
					      		<div class="span4">
						        	<!-- Supplier Territory Contact Area 4 -->
						      		<label for="frm_territory_id_4">Area</label>
						      		<!--
						      		<?php $arrTerritory4 = $db->getTerritoryData(); ?>
						      		<label for="frm_territory_id_4">Area</label>
						      		<?php echo "\n<select name=\"frm_territory_id_4\" id=\"frm_territory_id_4\">"; ?>
								    <?php echo "\n\t<option value=\"\">-- Not specified --</option>"; ?>
								    <?php
										if (is_array($arrTerritory4) && count($arrTerritory4) > 0)
										{
											foreach ($arrTerritory4 as $intTerritoryID=>$arrTerritoryData)
											{
												echo "\n\t<option value=\"" . $intTerritoryID . "\"";  
											
												if ($_REQUEST['frm_territory_id_4'] && $_REQUEST['frm_territory_id_4'] == $intTerritoryID)
												{
													echo " selected=\"selected\"";
												}

												elseif (!$_REQUEST['frm_territory_id_4'] && $arrTerritory[3]['territory_id'] == $intTerritoryID) {
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrTerritoryData['territory_name']) . "</option>";
											}
										}
								    ?>
								    <?php echo "\n</select>"; ?>-->
						        	<input type="text" id="frm_territory_name_4" name="frm_territory_name_4" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_territory_name_4']) { echo stripslashes($_REQUEST['frm_territory_name_4']); } elseif (!$_REQUEST['frm_territory_name_4'] && $arrTerritory[3]['territory_name']) { echo stripslashes($arrTerritory[3]['territory_name']); } ?>" data-validation="" />
						        	<p class="help-block">The supplier territory area</p>
						        </div>		

					      		<div class="span4">
						        	<!-- Supplier Territory Contact Name 4 -->
						      		<label for="frm_supplier_territory_name_4">Territory Contact 4</label>
						        	<input type="text" id="frm_supplier_territory_name_4" name="frm_supplier_territory_name_4" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_name_4']) { echo stripslashes($_REQUEST['frm_supplier_territory_name_4']); } elseif (!$_REQUEST['frm_supplier_territory_name_4'] && $arrTerritory[3]['supplier_territory_name']) { echo stripslashes($arrTerritory[3]['supplier_territory_name']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact name</p>				        
						      	</div>

						      	<div class="span4">
						        	<!-- Supplier Territory Contact Number 4 -->
						      		<label for="frm_supplier_territory_phone_number_4">Contact Number</label>
						        	<input type="text" id="frm_supplier_territory_phone_number_4" name="frm_supplier_territory_phone_number_4" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_phone_number_4']) { echo stripslashes($_REQUEST['frm_supplier_territory_phone_number_4']); } elseif (!$_REQUEST['frm_supplier_territory_phone_number_4'] && $arrTerritory[3]['supplier_territory_phone_number']) { echo stripslashes($arrTerritory[3]['supplier_territory_phone_number']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact number</p>				        
					      		</div>

					      		<input type="hidden" name="frm_supplier_territory_id_4" id="frm_supplier_territory_id_4" value="<?php if ($_REQUEST['frm_supplier_territory_id_4']) { echo stripslashes($_REQUEST['frm_supplier_territory_id_4']); } elseif (!$_REQUEST['frm_supplier_territory_id_4'] && $arrTerritory[3]['supplier_territory_id']) { echo stripslashes($arrTerritory[3]['supplier_territory_id']); } ?>" />

					      	</div>				      		
					      	
					      	<div class="row-fluid" style="margin-top:20px;">	
					      		<div class="span4">
						        	<!-- Supplier Territory Contact Area 5 -->
						      		<label for="frm_territory_id_5">Area</label>
						      		<!--
						      		<?php $arrTerritory5 = $db->getTerritoryData(); ?>
						      		<label for="frm_territory_id_5">Area</label>
						      		<?php echo "\n<select name=\"frm_territory_id_5\" id=\"frm_territory_id_5\">"; ?>
								    <?php echo "\n\t<option value=\"\">-- Not specified --</option>"; ?>
								    <?php
										if (is_array($arrTerritory5) && count($arrTerritory5) > 0)
										{
											foreach ($arrTerritory5 as $intTerritoryID=>$arrTerritoryData)
											{
												echo "\n\t<option value=\"" . $intTerritoryID . "\"";  
											
												if ($_REQUEST['frm_territory_id_5'] && $_REQUEST['frm_territory_id_5'] == $intTerritoryID)
												{
													echo " selected=\"selected\"";
												}

												elseif (!$_REQUEST['frm_territory_id_5'] && $arrTerritory[4]['territory_id'] == $intTerritoryID) {
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrTerritoryData['territory_name']) . "</option>";
											}
										}
								    ?>
								    <?php echo "\n</select>"; ?>-->
						        	<input type="text" id="frm_territory_name_5" name="frm_territory_name_5" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_territory_name_5']) { echo stripslashes($_REQUEST['frm_territory_name_5']); } elseif (!$_REQUEST['frm_territory_name_5'] && $arrTerritory[4]['territory_name']) { echo stripslashes($arrTerritory[4]['territory_name']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory area</p>
						        </div>		

					      		<div class="span4">
						        	<!-- Supplier Territory Contact Name 5 -->
						      		<label for="frm_supplier_territory_name_5">Territory Contact 5</label>
						        	<input type="text" id="frm_supplier_territory_name_5" name="frm_supplier_territory_name_5" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_name_5']) { echo stripslashes($_REQUEST['frm_supplier_territory_name_5']); } elseif (!$_REQUEST['frm_supplier_territory_name_5'] && $arrTerritory[4]['supplier_territory_name']) { echo stripslashes($arrTerritory[4]['supplier_territory_name']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact name</p>				        
						      	</div>

						      	<div class="span4">
						        	<!-- Supplier Territory Contact Number 5 -->
						      		<label for="frm_supplier_territory_phone_number_5">Contact Number</label>
						        	<input type="text" id="frm_supplier_territory_phone_number_5" name="frm_supplier_territory_phone_number_5" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_phone_number_5']) { echo stripslashes($_REQUEST['frm_supplier_territory_phone_number_5']); } elseif (!$_REQUEST['frm_supplier_territory_phone_number_5'] && $arrTerritory[4]['supplier_territory_phone_number']) { echo stripslashes($arrTerritory[4]['supplier_territory_phone_number']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact number</p>				        
					      		</div>

					      		<input type="hidden" name="frm_supplier_territory_id_5" id="frm_supplier_territory_id_5" value="<?php if ($_REQUEST['frm_supplier_territory_id_5']) { echo stripslashes($_REQUEST['frm_supplier_territory_id_5']); } elseif (!$_REQUEST['frm_supplier_territory_id_5'] && $arrTerritory[4]['supplier_territory_id']) { echo stripslashes($arrTerritory[4]['supplier_territory_id']); } ?>" />

					      	</div>	


					      	<div class="row-fluid" style="margin-top:20px;">	
					      		<div class="span4">
						        	<!-- Supplier Territory Contact Area 6 -->
						        	<label for="frm_territory_id_6">Area</label>
						      		<!--
						      		<?php $arrTerritory6 = $db->getTerritoryData(); ?>
						      		<label for="frm_territory_id_6">Area</label>
						      		<?php echo "\n<select name=\"frm_territory_id_6\" id=\"frm_territory_id_6\">"; ?>
								    <?php echo "\n\t<option value=\"\">-- Not specified --</option>"; ?>
								    <?php
										if (is_array($arrTerritory6) && count($arrTerritory6) > 0)
										{
											foreach ($arrTerritory6 as $intTerritoryID=>$arrTerritoryData)
											{
												echo "\n\t<option value=\"" . $intTerritoryID . "\"";  
											
												if ($_REQUEST['frm_territory_id_6'] && $_REQUEST['frm_territory_id_6'] == $intTerritoryID)
												{
													echo " selected=\"selected\"";
												}

												elseif (!$_REQUEST['frm_territory_id_6'] && $arrTerritory[5]['territory_id'] == $intTerritoryID) {
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrTerritoryData['territory_name']) . "</option>";
											}
										}
								    ?>
								    <?php echo "\n</select>"; ?>-->
						        	<input type="text" id="frm_territory_name_6" name="frm_territory_name_6" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_territory_name_6']) { echo stripslashes($_REQUEST['frm_territory_name_6']); } elseif (!$_REQUEST['frm_territory_name_6'] && $arrTerritory[5]['territory_name']) { echo stripslashes($arrTerritory[5]['territory_name']); } ?>" data-validation="" />
						        	<p class="help-block">The supplier territory area</p>
						        </div>		

					      		<div class="span4">
						        	<!-- Supplier Territory Contact Name 6 -->
						      		<label for="frm_supplier_territory_name_6">Territory Contact 6</label>
						        	<input type="text" id="frm_supplier_territory_name_6" name="frm_supplier_territory_name_6" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_name_6']) { echo stripslashes($_REQUEST['frm_supplier_territory_name_6']); } elseif (!$_REQUEST['frm_supplier_territory_name_6'] && $arrTerritory[5]['supplier_territory_name']) { echo stripslashes($arrTerritory[5]['supplier_territory_name']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact name</p>				        
						      	</div>

						      	<div class="span4">
						        	<!-- Supplier Territory Contact Number 6 -->
						      		<label for="frm_supplier_territory_phone_number_6">Contact Number</label>
						        	<input type="text" id="frm_supplier_territory_phone_number_6" name="frm_supplier_territory_phone_number_6" placeholder="" class="input-medium" value="<?php if ($_REQUEST['frm_supplier_territory_phone_number_6']) { echo stripslashes($_REQUEST['frm_supplier_territory_phone_number_6']); } elseif (!$_REQUEST['frm_supplier_territory_phone_number_6'] && $arrTerritory[5]['supplier_territory_phone_number']) { echo stripslashes($arrTerritory[5]['supplier_territory_phone_number']); } ?>" data-validation="" /> 
						        	<p class="help-block">The supplier territory contact number</p>				        
					      		</div>

					      		<input type="hidden" name="frm_supplier_territory_id_6" id="frm_supplier_territory_id_6" value="<?php if ($_REQUEST['frm_supplier_territory_id_6']) { echo stripslashes($_REQUEST['frm_supplier_territory_id_6']); } elseif (!$_REQUEST['frm_supplier_territory_id_6'] && $arrTerritory[5]['supplier_territory_id']) { echo stripslashes($arrTerritory[5]['supplier_territory_id']); } ?>" />

					      	</div>

					      			<div class="control-group">
										<!-- Button -->
										<div class="controls">
											<button class="btn" type="button" name="frm_prev_3" id="frm_prev_3">Previous <i class="icon-backward"></i></button>&nbsp;&nbsp;&nbsp; <button class="btn" type="button" name="frm_next_4" id="frm_next_4">Next <i class="icon-forward"></i></button>
										</div>
									</div>
					      			
					      		</fieldset>	

					  		</div>
					  	</div>	
					    <!-- Supplier > Territory Contact -->
					</div> <!--#tab4 -->	


					<div class="tab-pane" id="tab5">
						<!-- Supplier > Miscellaneous -->
				  		<div class="container-fluid">
					  		<div class="row-fluid">

					  			<fieldset>
								  	<div>
								  		<legend>Miscellaneous</legend>								  				
								  	</div>

								  	<div class="control-group">
								      <!-- Supplier Last Year Purchase -->
								      <label class="control-label" for="frm_supplier_last_year_purchase">Last year's purchase</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_last_year_purchase" name="frm_supplier_last_year_purchase" placeholder="Type Supplier's last year purchase" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_last_year_purchase']) { echo stripslashes($_REQUEST['frm_supplier_last_year_purchase']); } elseif (!$_REQUEST['frm_supplier_last_year_purchase'] && $row['supplier_last_year_purchase']) { echo stripslashes($row['supplier_last_year_purchase']); } ?>" data-validation="" /> 
								        <p class="help-block">The supplier's last year total purchase</p>				        
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Target -->
								      <label class="control-label" for="frm_supplier_target">Target</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_target" name="frm_supplier_target" placeholder="Type Supplier target" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_target']) { echo stripslashes($_REQUEST['frm_supplier_target']); } elseif (!$_REQUEST['frm_supplier_target'] && $row['supplier_target']) { echo stripslashes($row['supplier_target']); } ?>" data-validation="" /> 
								        <p class="help-block">The supplier's target</p>				        
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Growth Incentives -->
								      <label class="control-label" for="frm_supplier_growth_incentives">Growth Incentives</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_growth_incentives" name="frm_supplier_growth_incentives" placeholder="Type Supplier's growth incentives" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_growth_incentives']) { echo stripslashes($_REQUEST['frm_supplier_growth_incentives']); } elseif (!$_REQUEST['frm_supplier_growth_incentives'] && $row['supplier_growth_incentives']) { echo stripslashes($row['supplier_growth_incentives']); } ?>" data-validation="" /> 
								        <p class="help-block">The supplier's growth incentives</p>				        
								      </div>
								    </div>

								    <div class="control-group">
								      <!-- Supplier Budget -->
								      <label class="control-label" for="frm_supplier_budget">Co-op Budget</label>
								      <div class="controls">
								        <input type="text" id="frm_supplier_budget" name="frm_supplier_budget" placeholder="Type Supplier's co-op budget" class="input-xlarge" value="<?php if ($_REQUEST['frm_supplier_budget']) { echo stripslashes($_REQUEST['frm_supplier_budget']); } elseif (!$_REQUEST['frm_supplier_budget'] && $row['supplier_budget']) { echo stripslashes($row['supplier_budget']); } ?>" data-validation="" /> 
								        <p class="help-block">The supplier's co-op budget</p>				        
								      </div>
								    </div>

								    <div class="control-group">
										<!-- Button -->
										<div class="controls">
											<button class="btn" type="button" name="frm_prev_4" id="frm_prev_4">Previous <i class="icon-backward"></i></button>&nbsp;&nbsp;&nbsp; <button class="btn btn-submit" type="button"><?php echo $strState; ?> Supplier</button>
										</div>
									</div>
								    	
								</fieldset>
							</div>	
						</div>	    

					</div> <!--#tab5 -->							

					<p class="pull-left"><a class="btn" href="#top"><i class="icon-arrow-up"></i> Back to top</a></p>
				</div> <!-- .tab-content -->
				</div> <!-- .tabbable -->   	

			  
			</form>

			
			<!-- JS Validator -->
			<script src="<?php echo $STR_URL; ?>js/jquery.formvalidator.min.js"></script>
			<script>$("#frm_supplier").validateOnBlur();</script>	

			<!-- JS Form action -->
			<script>

			$(window).load(function() {
    			$('#formtab-nav a:first').tab('show');
			});	

			$(document).ready(function () {

				//-- Popover
				$('.btn-popover').popover({ 
					trigger: 'hover',
					placement: 'top'
				});

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

				//-- Prev Button 1
				$('#frm_prev_1').click(function() {
					$('#first-tab').show();
					$('#formtab-nav a[href="#tab1"]').tab('show');
				});

				//-- Next Button 3
				$('#frm_next_3').click(function() {
					$('#fourth-tab').show();
					$('#formtab-nav a[href="#tab4"]').tab('show');
				});

				//-- Prev Button 2
				$('#frm_prev_2').click(function() {
					$('#second-tab').show();
					$('#formtab-nav a[href="#tab2"]').tab('show');
				});


				//-- Next Button 4
				$('#frm_next_4').click(function() {
					$('#fifth-tab').show();
					$('#formtab-nav a[href="#tab5"]').tab('show');
				});

				//-- Prev Button 3
				$('#frm_prev_3').click(function() {
					$('#third-tab').show();
					$('#formtab-nav a[href="#tab3"]').tab('show');
				});

				//-- Prev Button 4
				$('#frm_prev_4').click(function() {
					$('#fourth-tab').show();
					$('#formtab-nav a[href="#tab4"]').tab('show');
				});


				<!-- Ref No -->
				var refno = $("#frm_supplier_po_ref_number");
				var refno_toggle = $("#frm_supplier_po_ref_number_required");
				
				if (refno_toggle.is(":checked"))
				{
					refno.show();
				}
				else
				{
					refno.hide();	
				}
				

				refno_toggle.click(function() {
					
					if ($(this).is(":checked")) {
						refno.show();
						refno.attr("data-validation", "required");
					}
					else 
					{
						refno.hide();
					}
				});

				<!-- Submit button -->
				$(".btn-submit").click(function() { 

					var validate_status = $("#frm_supplier").validate();
					var straction = $("#action").val();
					<?php if ($_REQUEST['action'] == "edit") { ?>var supplier_id = $("#supplier_id").val();<?php } ?>

					if (validate_status)
					{
						
		    			var frm_supplier_name = $("#frm_supplier_name").val();
		      			var frm_supplier_postal_address = $("#frm_supplier_postal_address").val();
		      			var frm_supplier_phone_number = $("#frm_supplier_phone_number").val();
		      			var frm_supplier_email = $("#frm_supplier_email").val();
		      			var frm_supplier_po_ref_number_required; if ($("#frm_supplier_po_ref_number_required").is(":checked")) { frm_supplier_po_ref_number_required  = $("#frm_supplier_po_ref_number_required").val(); } else { frm_supplier_po_ref_number_required = "no"; }
		      			var frm_supplier_po_ref_number = $("#frm_supplier_po_ref_number").val();
		      			var frm_supplier_active; if ($('#frm_supplier_active').is(":checked")) { frm_supplier_active = $("#frm_supplier_active").val(); } else { frm_supplier_active = "no" }

		      			var supplier_contact_id = $("#supplier_contact_id").val();
		      			var frm_supplier_contact_name = $("#frm_supplier_contact_name").val();
		      			var frm_supplier_contact_position = $("#frm_supplier_contact_position").val();
		      			var frm_supplier_contact_postal_address = $("#frm_supplier_contact_postal_address").val();
		      			var frm_supplier_contact_phone_number = $("#frm_supplier_contact_phone_number").val();
		      			var frm_supplier_contact_mobile_number = $("#frm_supplier_contact_mobile_number").val();
		      			var frm_supplier_contact_email = $("#frm_supplier_contact_email").val();

		      			var supplier_account_id = $("#supplier_account_id").val();
		      			var frm_supplier_account_name = $("#frm_supplier_account_name").val();
		      			var frm_supplier_account_postal_address = $("#frm_supplier_account_postal_address").val();
		      			var frm_supplier_account_phone_number = $("#frm_supplier_account_phone_number").val();
		      			var frm_supplier_account_email = $("#frm_supplier_account_email").val();

		      			var frm_territory_id_1 = $("#frm_territory_id_1").val();
		      			var frm_territory_name_1 = $("#frm_territory_name_1").val();
		      			var frm_supplier_territory_name_1 = $("#frm_supplier_territory_name_1").val();
		      			var frm_supplier_territory_phone_number_1 = $("#frm_supplier_territory_phone_number_1").val();
		      			var frm_supplier_territory_id_1 = $("#frm_supplier_territory_id_1").val();

		      			var frm_territory_id_2 = $("#frm_territory_id_2").val();
		      			var frm_territory_name_2 = $("#frm_territory_name_2").val();
		      			var frm_supplier_territory_name_2 = $("#frm_supplier_territory_name_2").val();
		      			var frm_supplier_territory_phone_number_2 = $("#frm_supplier_territory_phone_number_2").val();
		      			var frm_supplier_territory_id_2 = $("#frm_supplier_territory_id_2").val();

		      			var frm_territory_id_3 = $("#frm_territory_id_3").val();
		      			var frm_territory_name_3 = $("#frm_territory_name_3").val();
		      			var frm_supplier_territory_name_3 = $("#frm_supplier_territory_name_3").val();
		      			var frm_supplier_territory_phone_number_3 = $("#frm_supplier_territory_phone_number_3").val();
		      			var frm_supplier_territory_id_3 = $("#frm_supplier_territory_id_3").val();

		      			var frm_territory_id_4 = $("#frm_territory_id_4").val();
		      			var frm_territory_name_4 = $("#frm_territory_name_4").val();
		      			var frm_supplier_territory_name_4 = $("#frm_supplier_territory_name_4").val();
		      			var frm_supplier_territory_phone_number_4 = $("#frm_supplier_territory_phone_number_4").val();
		      			var frm_supplier_territory_id_4 = $("#frm_supplier_territory_id_4").val();

		      			var frm_territory_id_5 = $("#frm_territory_id_5").val();
		      			var frm_territory_name_5 = $("#frm_territory_name_5").val();
		      			var frm_supplier_territory_name_5 = $("#frm_supplier_territory_name_5").val();
		      			var frm_supplier_territory_phone_number_5 = $("#frm_supplier_territory_phone_number_5").val();
		      			var frm_supplier_territory_id_5 = $("#frm_supplier_territory_id_5").val();

		      			var frm_territory_id_6 = $("#frm_territory_id_6").val();
		      			var frm_territory_name_6 = $("#frm_territory_name_6").val();
		      			var frm_supplier_territory_name_6 = $("#frm_supplier_territory_name_6").val();
		      			var frm_supplier_territory_phone_number_6 = $("#frm_supplier_territory_phone_number_6").val();
		      			var frm_supplier_territory_id_6 = $("#frm_supplier_territory_id_6").val();

		      			var frm_supplier_last_year_purchase = $("#frm_supplier_last_year_purchase").val();
		      			var frm_supplier_target = $("#frm_supplier_target").val();
		      			var frm_supplier_growth_incentives = $("#frm_supplier_growth_incentives").val();
		      			var frm_supplier_budget = $("#frm_supplier_budget").val();

		      			var dataString = "action=" + straction + "&" + 
		      			
		      			<?php if ($_REQUEST['action'] == "edit") { ?>"&<?php echo $strPageIDName; ?>=" + <?php echo $_REQUEST[$strPageIDName]; ?> + "&" + <?php } ?>	

						"frm_supplier_name=" + frm_supplier_name + "&frm_supplier_postal_address=" + frm_supplier_postal_address + "&frm_supplier_phone_number=" + frm_supplier_phone_number + "&frm_supplier_email=" + frm_supplier_email + "&frm_supplier_po_ref_number_required=" + frm_supplier_po_ref_number_required + "&frm_supplier_po_ref_number=" + frm_supplier_po_ref_number + "&frm_supplier_active=" + frm_supplier_active + "&" +
		      			"supplier_contact_id=" + supplier_contact_id + "&frm_supplier_contact_name=" + frm_supplier_contact_name + "&frm_supplier_contact_position=" + frm_supplier_contact_position + "&frm_supplier_contact_postal_address=" + frm_supplier_contact_postal_address + "&frm_supplier_contact_phone_number=" + frm_supplier_contact_phone_number + "&frm_supplier_contact_mobile_number=" + frm_supplier_contact_mobile_number + "&frm_supplier_contact_email=" + frm_supplier_contact_email + "&" + 
		      			"supplier_account_id=" + supplier_account_id + "&frm_supplier_account_name=" + frm_supplier_account_name + "&frm_supplier_account_postal_address=" + frm_supplier_account_postal_address + "&frm_supplier_account_phone_number=" + frm_supplier_account_phone_number + "&frm_supplier_account_email=" + frm_supplier_account_email + "&" + 

		      			"frm_territory_id_1=" + frm_territory_id_1 + "&frm_territory_name_1=" + frm_territory_name_1 + "&frm_supplier_territory_name_1=" + frm_supplier_territory_name_1 + "&frm_supplier_territory_phone_number_1=" + frm_supplier_territory_phone_number_1 + "&frm_supplier_territory_id_1=" + frm_supplier_territory_id_1 + "&" +
		      			"frm_territory_id_2=" + frm_territory_id_2 + "&frm_territory_name_2=" + frm_territory_name_2 + "&frm_supplier_territory_name_2=" + frm_supplier_territory_name_2 + "&frm_supplier_territory_phone_number_2=" + frm_supplier_territory_phone_number_2 + "&frm_supplier_territory_id_2=" + frm_supplier_territory_id_2 + "&" +
		      			"frm_territory_id_3=" + frm_territory_id_3 + "&frm_territory_name_3=" + frm_territory_name_3 + "&frm_supplier_territory_name_3=" + frm_supplier_territory_name_3 + "&frm_supplier_territory_phone_number_3=" + frm_supplier_territory_phone_number_3 + "&frm_supplier_territory_id_3=" + frm_supplier_territory_id_3 + "&" +
		      			"frm_territory_id_4=" + frm_territory_id_4 + "&frm_territory_name_4=" + frm_territory_name_4 + "&frm_supplier_territory_name_4=" + frm_supplier_territory_name_4 + "&frm_supplier_territory_phone_number_4=" + frm_supplier_territory_phone_number_4 + "&frm_supplier_territory_id_4=" + frm_supplier_territory_id_4 + "&" +
		      			"frm_territory_id_5=" + frm_territory_id_5 + "&frm_territory_name_5=" + frm_territory_name_5 + "&frm_supplier_territory_name_5=" + frm_supplier_territory_name_5 + "&frm_supplier_territory_phone_number_5=" + frm_supplier_territory_phone_number_5 + "&frm_supplier_territory_id_5=" + frm_supplier_territory_id_5 + "&" +
		      			"frm_territory_id_6=" + frm_territory_id_6 + "&frm_territory_name_6=" + frm_territory_name_6 + "&frm_supplier_territory_name_6=" + frm_supplier_territory_name_6 + "&frm_supplier_territory_phone_number_6=" + frm_supplier_territory_phone_number_6 + "&frm_supplier_territory_id_6=" + frm_supplier_territory_id_6 + "&" +

		      			"frm_supplier_last_year_purchase=" + frm_supplier_last_year_purchase + "&frm_supplier_target=" + frm_supplier_target + "&frm_supplier_growth_incentives=" + frm_supplier_growth_incentives + "&frm_supplier_budget=" + frm_supplier_budget;

						var request = $.ajax({							    
							url: "ajax/supplier_proc.php",
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

							}
								    
						});

						<?php if ($_REQUEST['action'] !== "edit") { ?>
							<!-- Clear the Form -->
							clearForm();		
							
						<?php } ?>
					}
										

				});	
			
			});

			function clearForm()
			{
				// clear
				$("#frm_supplier_name").val("");
				$("#frm_supplier_postal_address").val("");
				$("#frm_supplier_phone_number").val("");	
				$("#frm_supplier_email").val("");
				$("#frm_supplier_po_ref_number_required").attr("checked", false);
				$("#frm_supplier_po_ref_number").val("");
				$("#frm_supplier_active").attr("checked", false);				
				$("#statusbox").html("");

				$("#frm_supplier_contact_id").val("");
				$("#frm_supplier_contact_name").val("");
				$("#frm_supplier_contact_position").val("");
				$("#frm_supplier_contact_postal_address").val("");
				$("#frm_supplier_contact_phone_number").val("");
				$("#frm_supplier_contact_mobile_number").val("");
				$("#frm_supplier_contact_email").val("");

				$("#frm_supplier_account_id").val("");
				$("#frm_supplier_account_name").val("");
				$("#frm_supplier_account_postal_address").val("");
				$("#frm_supplier_account_phone_number").val("");
				$("#frm_supplier_account_email").val("");

				$("#frm_territory_id_1").val("");
				$("#frm_territory_name_1").val("");
				$("#frm_supplier_territory_name_1").val("");
				$("#frm_supplier_territory_phone_number_1").val("");
				$("#frm_supplier_territory_id_1").val("");
				
				$("#frm_territory_id_2").val("");
				$("#frm_territory_name_2").val("");
				$("#frm_supplier_territory_name_2").val("");
				$("#frm_supplier_territory_phone_number_2").val("");
				$("#frm_supplier_territory_id_2").val("");

				$("#frm_territory_id_3").val("");
				$("#frm_territory_name_3").val("");
				$("#frm_supplier_territory_name_3").val("");
				$("#frm_supplier_territory_phone_number_3").val("");
				$("#frm_supplier_territory_id_3").val("");

				$("#frm_territory_id_4").val("");
				$("#frm_territory_name_4").val("");
				$("#frm_supplier_territory_name_4").val("");
				$("#frm_supplier_territory_phone_number_4").val("");
				$("#frm_supplier_territory_id_4").val("");

				$("#frm_territory_id_5").val("");
				$("#frm_territory_name_5").val("");
				$("#frm_supplier_territory_name_5").val("");
				$("#frm_supplier_territory_phone_number_5").val("");
				$("#frm_supplier_territory_id_5").val("");

				$("#frm_territory_id_6").val("");
				$("#frm_territory_name_6").val("");
				$("#frm_supplier_territory_name_6").val("");
				$("#frm_supplier_territory_phone_number_6").val("");
				$("#frm_supplier_territory_id_6").val("");

				// set to focus
				$("#frm_supplier_name").focus();
			}
			
			</script>			
			
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>