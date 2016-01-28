<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<?php 
	// Page Config
	$strPageName = "Store";
	$strPageIDName = "store_id";
	$strDBTableName = "mbs_stores";
	$strDBFieldName = "store_name";

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

			$strLog = 'Store named "' . $strPageItemName . '" is successfully deleted.';
					
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
				
			$("#frm_store_name").blur(function() { 
				
				var strcheck = $("#frm_store_name").val();
					
				if (strcheck.length >= 3)
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/loading.gif" /> Checking <?php echo $strPageName; ?> name...');
					
						$.ajax({ 
								type: "post", 
								url: "ajax/store_check.php", 
								data: "frm_store_name="+strcheck, 
								success: function(msg) { 
									
									if (msg == "yes") 
									{
										$("#frm_store_name").removeClass("status_not_ok"); 
										$("#frm_store_name").addClass("status_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/accepted.png" /> <span class="greentext"><?php echo $strPageName; ?> name is OK!</span>');		
									}

									else if (msg == "no") 
									{ 
										$("#frm_store_name").removeClass("status_ok"); 
										$("#frm_store_name").addClass("status_not_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <span class="redtext"><?php echo $strPageName; ?> is already exist in the database! Please try something else!</span>'); 
									}
									
								}
							
						});
						
				}
							
				else						
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <?php echo $strPageName; ?> must contain of 3 characters minimum!');
					$("#frm_store_name").removeClass('status_ok'); // if necessary
					$("#frm_store_name").addClass("status_not_ok");
							
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
			  <li><a href="store_list.php">Stores</a> <span class="divider">/</span></li>
			  <li class="active"><?php if ($_REQUEST['action'] == "add") { ?>New<?php } elseif ($_REQUEST['action'] == "edit") { ?>Update<?php } ?> <?php echo $strPageName; ?></li>
			</ul>	

			<!-- Menu -->
			<div class="pull-right">
			<?php if ($admin->getModulePrivilege('stores', 'add') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="store.php?action=add" title="New <?php echo $strPageName; ?>"><i class="icon-plus"></i> New</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('stores', 'view') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="store_view.php?store_id=<?php echo $_REQUEST['store_id']; ?>&action=view" title="View <?php echo $strPageName; ?>"><i class="icon-info-sign"></i> View</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('stores', 'delete') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="store.php?store_id=<?php echo $_REQUEST['store_id']; ?>&action=delete" onclick="return confirmDeleteStore(this.form)" title="Delete <?php echo $strPageName; ?>"><i class="icon-remove"></i> Delete</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('stores', 'list') > 0) { ?>
			<a class="btn" href="store_list.php" title="<?php echo $strPageName; ?> List"><i class="icon-list-alt"></i> List</a>			
			<?php } ?>
			</div>

			<div style="clear:both;"></div>

			<!-- Form -->
			<form id="frm_store" class="form-horizontal" action="" method="post">
				<?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { ?><input type="hidden" name="<?php echo $strPageIDName; ?>" id="<?php echo $strPageIDName; ?>" value="<?php echo $_REQUEST[$strPageIDName]; ?>" /><?php } ?>
				<input type="hidden" name="action" id="action" value="<?php if ($_REQUEST['action'] == "edit") { echo "edit"; } else { echo "add"; } ?>" />
				<fieldset>
			    <div id="legend">
			      <legend class=""><?php echo $strState; ?> <?php echo $strPageName; ?> <?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { echo " &raquo; " . $strPageItemName; } ?></legend>
			    </div>
			    
			    <div class="control-group">
			      <!-- Store Name -->
			      <label class="control-label" for="frm_store_name">Name</label>
			      <div class="controls">
			        <input type="text" id="frm_store_name" name="frm_store_name" placeholder="Type Store name" class="input-xlarge" value="<?php if ($_REQUEST['frm_store_name']) { echo stripslashes($_REQUEST['frm_store_name']); } elseif (!$_REQUEST['frm_store_name'] && $row['store_name']) { echo stripslashes($row['store_name']); } ?>" data-validation="required" /> *	        
			        <p class="help-block">The store name</p>
			        <div id="statusbox"></div>
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Store API ACC -->
			      <label class="control-label" for="frm_store_api_acc">API ACC #</label>
			      <div class="controls">
			        <input type="text" id="frm_store_api_acc" name="frm_store_api_acc" placeholder="Type Store API ACC number" class="input-xlarge" value="<?php if ($_REQUEST['frm_store_api_acc']) { echo stripslashes($_REQUEST['frm_store_api_acc']); } elseif (!$_REQUEST['frm_store_api_acc'] && $row['store_api_acc']) { echo stripslashes($row['store_api_acc']); } ?>" data-validation="required" /> *	        
			        <p class="help-block">The store name</p>
			        <div id="statusbox"></div>
			      </div>
			    </div>
			 
			    <div class="control-group">
			      <!-- Store Address -->
			      <label class="control-label" for="frm_store_address">Address</label>
			      <div class="controls">
			        <textarea name="frm_store_address" id="frm_store_address" class="input-xlarge" rows="3" placeholder="Type Store address" data-validation="required"><?php if ($_REQUEST['frm_store_address']) { echo stripslashes($_REQUEST['frm_store_address']); } elseif (!$_REQUEST['frm_store_address'] && $row['store_address']) { echo stripslashes($row['store_address']); } ?></textarea> *
			        <p class="help-block">The store description</p>
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Store Phone -->
			      <label class="control-label" for="frm_store_phone">Phone</label>
			      <div class="controls">
			        <input type="text" id="frm_store_phone" name="frm_store_phone" placeholder="Type Store phone" class="input-xlarge" value="<?php if ($_REQUEST['frm_store_phone']) { echo stripslashes($_REQUEST['frm_store_phone']); } elseif (!$_REQUEST['frm_store_phone'] && $row['store_phone']) { echo stripslashes($row['store_phone']); } ?>" data-validation="required" /> *	        
			        <p class="help-block">The store name</p>
			        <div id="statusbox"></div>
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Store Fax -->
			      <label class="control-label" for="frm_store_fax">Fax</label>
			      <div class="controls">
			        <input type="text" id="frm_store_fax" name="frm_store_fax" placeholder="Type Store fax" class="input-xlarge" value="<?php if ($_REQUEST['frm_store_fax']) { echo stripslashes($_REQUEST['frm_store_fax']); } elseif (!$_REQUEST['frm_store_fax'] && $row['store_fax']) { echo stripslashes($row['store_fax']); } ?>" /> 	        
			        <p class="help-block">The store name</p>
			        <div id="statusbox"></div>
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Store Email -->
			      <label class="control-label" for="frm_store_email">Email</label>
			      <div class="controls">
			        <input type="text" id="frm_store_email" name="frm_store_email" placeholder="Type Store email" class="input-xlarge" value="<?php if ($_REQUEST['frm_store_email']) { echo stripslashes($_REQUEST['frm_store_email']); } elseif (!$_REQUEST['frm_store_email'] && $row['store_email']) { echo stripslashes($row['store_email']); } ?>" /> 	        
			        <p class="help-block">The store name</p>
			        <div id="statusbox"></div>
			      </div>
			    </div>
			 
			    <div class="control-group">
			      <!-- Store Contact -->
			      <label class="control-label" for="frm_store_contact">Contact</label>
			      <div class="controls">
			        <input type="text" id="frm_store_contact" name="frm_store_contact" placeholder="Type Store contact" class="input-xlarge" value="<?php if ($_REQUEST['frm_store_contact']) { echo stripslashes($_REQUEST['frm_store_contact']); } elseif (!$_REQUEST['frm_store_contact'] && $row['store_contact']) { echo stripslashes($row['store_contact']); } ?>" /> 	        
			        <p class="help-block">The store name</p>
			        <div id="statusbox"></div>
			      </div>
			    </div>	

			    <div class="control-group">
			      <!-- Store Active -->
			      <label class="control-label" for="frm_store_active">Activate?</label>
			      <div class="controls">
			        <input type="checkbox" name="frm_store_active" id="frm_store_active" value="yes"<?php if ($_REQUEST['frm_store_active'] == "yes" || $row['store_active'] == "yes") { ?> checked="checked"<?php } ?> /> 
			        <p class="help-block">Set this to active?</p>
			      </div>
			    </div>	

			    <div class="control-group">
			      <!-- Button -->
			      <div class="controls">
			        <button class="btn" type="button" name="frm_submit" id="frm_submit"><?php echo $strState; ?> Store</button>
			      </div>
			    </div>
			  </fieldset>
			</form>
			
			<!-- JS Validator -->
			<script src="<?php echo $STR_URL; ?>js/jquery.formvalidator.min.js"></script>
			<script>$("#frm_store").validateOnBlur();</script>	

			<!-- JS Form action -->
			<script>
			$(document).ready(function () {

				$("#frm_submit").click(function() { 

					var validate_status = $('#frm_store').validate();

					var straction = $("#action").val();
					<?php if ($_REQUEST['action'] == "edit") { ?>var store_id = $("#store_id").val();<?php } ?>
	    			var frm_store_name = $("#frm_store_name").val();
	    			var frm_store_api_acc = $("#frm_store_api_acc").val();
	      			var frm_store_address = $("#frm_store_address").val();
	      			var frm_store_phone = $("#frm_store_phone").val();
	      			var frm_store_fax = $("#frm_store_fax").val();
	      			var frm_store_email = $("#frm_store_email").val();
	      			var frm_store_contact = $("#frm_store_contact").val();
	      			var frm_store_active = $("#frm_store_active").val();


	      			if (validate_status)
	      			{
	      				var dataString = "action=" + straction + "&" + 
	      			
		      			<?php if ($_REQUEST['action'] == "edit") { ?>"&<?php echo $strPageIDName; ?>=" + <?php echo $_REQUEST[$strPageIDName]; ?> + "&" + <?php } ?>	

						"frm_store_name=" + frm_store_name + "&frm_store_api_acc=" + frm_store_api_acc + "&frm_store_address=" + frm_store_address + "&frm_store_phone=" + frm_store_phone + "&frm_store_fax=" + frm_store_fax + "&frm_store_email=" + frm_store_email + "&frm_store_contact=" + frm_store_contact + "&frm_store_active=" + frm_store_active;
		      				   
						var request = $.ajax({							    
							url: "ajax/store_proc.php",
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
				$("#frm_store_name").val("");
				$("#frm_store_api_acc").val("");
				$("#frm_store_address").val("");
				$("#frm_store_phone").val("");
				$("#frm_store_fax").val("");
				$("#frm_store_email").val("");
				$("#frm_store_contact").val("");
				$("#frm_store_active").attr("checked", false);
				$("#statusbox").html("");

				// set to focus
				$("#frm_store_name").focus();
			}
			
			</script>			
			
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>