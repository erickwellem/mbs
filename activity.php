<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<?php 
	// Page Config
	$strPageName = "Activity";
	$strPageIDName = "activity_id";
	$strDBTableName = "mbs_activities";
	$strDBFieldName = "activity_name";

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

		// for delete
		if ($_REQUEST['action'] == "delete" && $strPageItemName)
		{
			$strQueryDelete = "DELETE FROM `" . $strDBTableName . "` WHERE `" . $strPageIDName . "` = '" . mysql_real_escape_string($_REQUEST[$strPageIDName]) .  "' LIMIT 1";
			$resultQueryDelete = mysql_query($strQueryDelete);

			$strLog = 'Activity named "' . $strPageItemName . '" is successfully deleted.';
					
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
				
			$("#frm_activity_name").blur(function() { 
				
				var strcheck = $("#frm_activity_name").val();
					
				if (strcheck.length >= 3)
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/loading.gif" /> Checking <?php echo $strPageName; ?>...');
					
						$.ajax({ 
								type: "post", 
								url: "ajax/activity_check.php", 
								data: "frm_activity_name="+strcheck, 
								success: function(msg) { 
									
									if (msg == "yes") 
									{
										$("#frm_activity_name").removeClass("status_not_ok"); 
										$("#frm_activity_name").addClass("status_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/accepted.png" /> <span class="greentext"><?php echo $strPageName; ?> is OK!</span>');		
									}

									else if (msg == "no") 
									{ 
										$("#frm_activity_name").removeClass("status_ok"); 
										$("#frm_activity_name").addClass("status_not_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <span class="redtext"><?php echo $strPageName; ?> is already exist in the database! Please try something else!</span>'); 
									}
									
								}
							
						});
						
				}
							
				else						
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <?php echo $strPageName; ?> must contain of 3 characters minimum!');
					$("#frm_activity_name").removeClass('status_ok'); // if necessary
					$("#frm_activity_name").addClass("status_not_ok");
							
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
			  <li><a href="activity_list.php">Activities</a> <span class="divider">/</span></li>
			  <li class="active"><?php if ($_REQUEST['action'] == "add") { ?>New<?php } elseif ($_REQUEST['action'] == "edit") { ?>Update<?php } ?> <?php echo $strPageName; ?></li>
			</ul>	

			<!-- Menu -->
			<div class="pull-right">
			<?php if ($admin->getModulePrivilege('activities', 'add') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="activity.php?action=add" title="New <?php echo $strPageName; ?>"><i class="icon-plus"></i> New</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('activities', 'view') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="activity_view.php?activity_id=<?php echo $_REQUEST['activity_id']; ?>&action=view" title="View <?php echo $strPageName; ?>"><i class="icon-info-sign"></i> View</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('activities', 'delete') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="activity.php?activity_id=<?php echo $_REQUEST['activity_id']; ?>&action=delete" onclick="return confirmDeleteActivity(this.form)" title="Delete <?php echo $strPageName; ?>"><i class="icon-remove"></i> Delete</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('activities', 'list') > 0) { ?>
			<a class="btn" href="activity_list.php" title="<?php echo $strPageName; ?> List"><i class="icon-list-alt"></i> List</a>			
			<?php } ?>
			</div>
			
			<div style="clear:both;"></div>

			<!-- Form -->
			<form id="frm_activity" class="form-horizontal" action="" method="post">
				<?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { ?><input type="hidden" name="<?php echo $strPageIDName; ?>" id="<?php echo $strPageIDName; ?>" value="<?php echo $_REQUEST[$strPageIDName]; ?>" /><?php } ?>
				<input type="hidden" name="action" id="action" value="<?php if ($_REQUEST['action'] == "edit") { echo "edit"; } else { echo "add"; } ?>" />
				<fieldset>
			    <div id="legend">
			      <legend class=""><?php echo $strState; ?> <?php echo $strPageName; ?> <?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { echo " &raquo; " . $strPageItemName; } ?></legend>
			    </div>
			    <div class="control-group">
			      <!-- Activity Name -->
			      <label class="control-label" for="frm_activity_name">Name</label>
			      <div class="controls">
			        <input type="text" id="frm_activity_name" name="frm_activity_name" placeholder="Type Activity name" class="input-xlarge" value="<?php if ($_REQUEST['frm_activity_name']) { echo stripslashes($_REQUEST['frm_activity_name']); } elseif (!$_REQUEST['frm_activity_name'] && $row['activity_name']) { echo stripslashes($row['activity_name']); } ?>" data-validation="required" /> *	        
			        <p class="help-block">The activity name</p>
			        <div id="statusbox"></div>
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Activity Category -->
			      <label class="control-label" for="frm_activity_category">Category</label>
			      <div class="controls">
			        <select name="frm_activity_category" id="frm_activity_category" data-validation="required">
			        	<option value=""> --Please select -- </option>
			        	<option value="in-store"<?php if ($_REQUEST['frm_activity_category'] && $_REQUEST['frm_activity_category'] == 'in-store') { echo " selected=\"selected\""; } elseif (!$_REQUEST['frm_activity_category'] && $row['activity_category'] == 'in-store') { echo " selected=\"selected\""; } ?>>In-Store Activity</option>
			        	<option value="catalogue"<?php if ($_REQUEST['frm_activity_category'] && $_REQUEST['frm_activity_category'] == 'catalogue') { echo " selected=\"selected\""; } elseif (!$_REQUEST['frm_activity_category'] && $row['activity_category'] == 'catalogue') { echo " selected=\"selected\""; } ?>>Catalogue</option>
			        	<option value="email"<?php if ($_REQUEST['frm_activity_category'] && $_REQUEST['frm_activity_category'] == 'email') { echo " selected=\"selected\""; } elseif (!$_REQUEST['frm_activity_category'] && $row['activity_category'] == 'email') { echo " selected=\"selected\""; } ?>>Email</option>
			        	<option value="newspaper"<?php if ($_REQUEST['frm_activity_category'] && $_REQUEST['frm_activity_category'] == 'newspaper') { echo " selected=\"selected\""; } elseif (!$_REQUEST['frm_activity_category'] && $row['activity_category'] == 'newspaper') { echo " selected=\"selected\""; } ?>>Newspaper</option>
			        	<option value="other"<?php if ($_REQUEST['frm_activity_category'] && $_REQUEST['frm_activity_category'] == 'other') { echo " selected=\"selected\""; } elseif (!$_REQUEST['frm_activity_category'] && $row['activity_category'] == 'other') { echo " selected=\"selected\""; } ?>>Other</option>
			        </select> *	 
			        <p class="help-block">The activity category</p>			        
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Activity Price -->
			      <label class="control-label" for="frm_activity_price">Price $</label>
			      <div class="controls">			      	
			        <input type="text" id="frm_activity_price" name="frm_activity_price" placeholder="Type activity price" class="input-xlarge" value="<?php if ($_REQUEST['frm_activity_price']) { echo stripslashes($_REQUEST['frm_activity_price']); } elseif (!$_REQUEST['frm_activity_price'] && $row['activity_price']) { echo stripslashes($row['activity_price']); } ?>" data-validation="required" /> *	        			        
			        <p class="help-block">The activity price. Only numeric is allowed</p>			        
			      </div>
			    </div>
			 
			    <div class="control-group">
			      <!-- Activity Description -->
			      <label class="control-label" for="frm_activity_description">Description</label>
			      <div class="controls">
			        <textarea name="frm_activity_description" id="frm_activity_description" class="input-xlarge" rows="3" placeholder=""><?php if ($_REQUEST['frm_activity_description']) { echo stripslashes($_REQUEST['frm_activity_description']); } elseif (!$_REQUEST['frm_activity_description'] && $row['activity_description']) { echo stripslashes($row['activity_description']); } ?></textarea> (optional)
			        <p class="help-block">The activity description</p>
			      </div>
			    </div>
			 
			    <div class="control-group">
			      <!-- Activity Size -->
			      <label class="control-label" for="frm_size_id">Size</label>
			      <div class="controls">
			      <?php $arrSizes = $db->getSizeData(); ?>								
				  <?php echo "\n<select name=\"frm_size_id\" id=\"frm_size_id\">"; ?>
				  <?php echo "\n\t<option value=\"\">-- Not specified --</option>"; ?>
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

							elseif (!$_REQUEST['frm_size_id'] && $row['size_id'] == $intSizeID) {
								echo " selected=\"selected\"";
							}
							
							echo ">" . stripslashes($arrSizesData['size_name']) . "</option>";
						}
					}
				   ?>
				  <?php echo "\n</select> (optional)"; ?>
				  <br /><small>Size is not in the list? Please <a class="ajax callbacks cboxElement" href="size.php?action=add&pop=yes">insert a new one</a>.</small>

			      </div>
			    </div>  	

			    <div class="control-group">
			      <!-- Activity Store Related -->
			      <label class="control-label" for="frm_activity_store_related">Store Related</label>
			      <div class="controls">
			        <input type="checkbox" name="frm_activity_store_related" id="frm_activity_store_related" value="yes"<?php if ($_REQUEST['frm_activity_store_related'] == "yes" || $row['activity_store_related'] == "yes") { ?> checked="checked"<?php } ?> /> 
			        <p class="help-block">Is this activity store related?</p>
			      </div>
			    </div>	


			    <div class="control-group">
			      <!-- Activity Active -->
			      <label class="control-label" for="frm_activity_active">Activate?</label>
			      <div class="controls">
			        <input type="checkbox" name="frm_activity_active" id="frm_activity_active" value="yes"<?php if ($_REQUEST['frm_activity_active'] == "yes" || $row['activity_active'] == "yes") { ?> checked="checked"<?php } ?> /> 
			        <p class="help-block">Set this to active?</p>
			      </div>
			    </div>	

			    <div class="control-group">
			      <!-- Button -->
			      <div class="controls">
			        <button class="btn" type="button" name="frm_submit" id="frm_submit"><?php echo $strState; ?> Activity</button>
			      </div>
			    </div>
			  </fieldset>
			</form>
			
			<!-- JS Validator -->
			<script src="<?php echo $STR_URL; ?>js/jquery.formvalidator.min.js"></script>
			<script>$("#frm_activity").validateOnBlur();</script>	

			<!-- JS Form action -->
			<script>
			$(document).ready(function () {

				$('#frm_activity_category').change(function () {
					if ($('#frm_activity_category option:selected').val() == 'in-store')
					{
						$('#frm_activity_store_related').prop('checked', true);
					}
					
					else
					{
						$('#frm_activity_store_related').prop('checked', false);
					}
				});


				$("#frm_submit").click(function() { 

					var validate_status = $('#frm_activity').validate();	

					var straction = $("#action").val();
					<?php if ($_REQUEST['action'] == "edit") { ?>var activity_id = $("#activity_id").val();<?php } ?>
	    			var frm_activity_name = $("#frm_activity_name").val();
	    			var frm_activity_category = $("#frm_activity_category").val();
	    			var frm_activity_price = $("#frm_activity_price").val();
	      			var frm_activity_description = $("#frm_activity_description").val();
	      			var frm_size_id = $("#frm_size_id").val();
	      			var frm_activity_store_related; if ($('#frm_activity_store_related').is(":checked")) { frm_activity_store_related = $("#frm_activity_store_related").val(); } else { frm_activity_store_related = "no"; }
	      			var frm_activity_active; if ($('#frm_activity_active').is(":checked")) { frm_activity_active = $("#frm_activity_active").val(); } else { frm_activity_active = "no" }


	      			if (validate_status)
	      			{
	      				var dataString = "action=" + straction + "&" + 
	      			
		      			<?php if ($_REQUEST['action'] == "edit") { ?>"&<?php echo $strPageIDName; ?>=" + <?php echo $_REQUEST[$strPageIDName]; ?> + "&" + <?php } ?>	

						"frm_activity_name=" + frm_activity_name + "&frm_activity_category=" + frm_activity_category + "&frm_activity_price=" + frm_activity_price + "&frm_activity_description=" + frm_activity_description + "&frm_size_id=" + frm_size_id + "&frm_activity_store_related=" + frm_activity_store_related + "&frm_activity_active=" + frm_activity_active;
		      				   
						var request = $.ajax({							    
							url: "ajax/activity_proc.php",
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
				$("#frm_activity_name").val("");
				$("#frm_activity_category").val("");
				$("#frm_activity_price").val("");
				$("#frm_activity_description").val("");	
				$("#frm_size_id").val("");	
				$("#frm_activity_store_related").attr("checked", false);
				$("#frm_activity_active").attr("checked", false);
				$("#statusbox").html("");

				// set to focus
				$("#frm_activity_name").focus();
			}
			
			</script>			
			
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>