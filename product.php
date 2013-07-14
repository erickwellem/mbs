<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<?php 
	// Page Config
	$strPageName = "Product";
	$strPageIDName = "product_id";
	$strDBTableName = "mbs_products";
	$strDBFieldName = "product_name";
	$strDBFieldCode = "product_code";

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

			$strLog = 'Product named "' . $strPageItemName . ' (Code: ' . $strPageItemCode . ')" is successfully deleted.';
					
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
				
			$("#frm_product_code").blur(function() { 
				
				var strcheck = $("#frm_product_code").val();
					
				if (strcheck.length >= 3)
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/loading.gif" /> Checking <?php echo $strPageName; ?>...');
					
						$.ajax({ 
								type: "post", 
								url: "ajax/product_check.php", 
								data: "frm_product_code="+strcheck, 
								success: function(msg) { 
									
									if (msg == "yes") 
									{
										$("#frm_product_code").removeClass("status_not_ok"); 
										$("#frm_product_code").addClass("status_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/accepted.png" /> <span class="greentext"><?php echo $strPageName; ?> Code is available!</span>');		
									}

									else if (msg == "no") 
									{ 
										$("#frm_product_code").removeClass("status_ok"); 
										$("#frm_product_code").addClass("status_not_ok");
										$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <span class="redtext"><?php echo $strPageName; ?> Code is already exist in the database! Please try something else!</span>'); 
									}
									
								}
							
						});
						
				}
							
				else						
				{
					$("#statusbox").html('<img align="absmiddle" src="<?php echo $STR_URL; ?>img/unaccepted.png" /> <?php echo $strPageName; ?> Code must contain of 3 characters minimum!');
					$("#frm_product_code").removeClass('status_ok'); // if necessary
					$("#frm_product_code").addClass("status_not_ok");
							
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
			  <li><a href="product_list.php">Products</a> <span class="divider">/</span></li>
			  <li class="active"><?php if ($_REQUEST['action'] == "add") { ?>New<?php } elseif ($_REQUEST['action'] == "edit") { ?>Update<?php } ?> <?php echo $strPageName; ?></li>
			</ul>	

			<!-- Menu -->
			<?php if ($admin->getModulePrivilege('products', 'add') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="product.php?action=add" title="New <?php echo $strPageName; ?>"><i class="icon-plus"></i> New</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('products', 'view') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="product_view.php?product_id=<?php echo $_REQUEST['product_id']; ?>&action=view" title="View <?php echo $strPageName; ?>"><i class="icon-info-sign"></i> View</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('products', 'delete') > 0 && $_REQUEST['action'] == "edit") { ?>
			<a class="btn" href="product.php?product_id=<?php echo $_REQUEST['product_id']; ?>&action=delete" onclick="return confirmDeleteProduct(this.form)" title="Delete <?php echo $strPageName; ?>"><i class="icon-remove"></i> Delete</a>
			<?php } ?>
			<?php if ($admin->getModulePrivilege('products', 'list') > 0) { ?>
			<a class="btn" href="product_list.php" title="<?php echo $strPageName; ?> List"><i class="icon-list-alt"></i> List</a>			
			<?php } ?>
			

			<!-- Form -->
			<form id="frm_product" class="form-horizontal" action="" method="post">
				<?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { ?><input type="hidden" name="<?php echo $strPageIDName; ?>" id="<?php echo $strPageIDName; ?>" value="<?php echo $_REQUEST[$strPageIDName]; ?>" /><?php } ?>
				<input type="hidden" name="action" id="action" value="<?php if ($_REQUEST['action'] == "edit") { echo "edit"; } else { echo "add"; } ?>" />
				<fieldset>
			    <div id="legend">
			      <legend class=""><?php echo $strState; ?> <?php echo $strPageName; ?> <?php if ($_REQUEST['action'] == "edit" && $strPageIDName) { echo " &raquo; " . $strPageItemName; } ?></legend>
			    </div>
			    
			    <div class="control-group">
			      <!-- Product Code -->
			      <label class="control-label" for="frm_product_code">Code</label>
			      <div class="controls">
			        <input type="text" id="frm_product_code" name="frm_product_code" placeholder="Type Product Code" class="input-xlarge" value="<?php if ($_REQUEST['frm_product_code']) { echo stripslashes($_REQUEST['frm_product_code']); } elseif (!$_REQUEST['frm_product_code'] && $row['product_code']) { echo stripslashes($row['product_code']); } ?>" data-validation="required" onkeyup="this.value=this.value.toUpperCase()" /> *	        
			        <p class="help-block">The product code</p>
			        <div id="statusbox"></div>
			      </div>
			    </div>	


			    <div class="control-group">
			      <!-- Product Name -->
			      <label class="control-label" for="frm_product_name">Name</label>
			      <div class="controls">
			        <input type="text" id="frm_product_name" name="frm_product_name" placeholder="Type Product name" class="input-xlarge" value="<?php if ($_REQUEST['frm_product_name']) { echo stripslashes($_REQUEST['frm_product_name']); } elseif (!$_REQUEST['frm_product_name'] && $row['product_name']) { echo stripslashes($row['product_name']); } ?>" data-validation="required" /> *	        
			        <p class="help-block">The product name</p>			        
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Product Normal Retail Price -->
			      <label class="control-label" for="frm_product_normal_retail_price">Normal Retail Price $</label>
			      <div class="controls">			      	
			        <input type="text" id="frm_product_normal_retail_price" name="frm_product_normal_retail_price" placeholder="Type the normal retail price" class="input-xlarge" value="<?php if ($_REQUEST['frm_product_normal_retail_price']) { echo stripslashes($_REQUEST['frm_product_normal_retail_price']); } elseif (!$_REQUEST['frm_product_normal_retail_price'] && $row['product_normal_retail_price']) { echo stripslashes($row['product_normal_retail_price']); } ?>" data-validation="required" /> *	        			        
			        <p class="help-block">The product normal retail price. Only numeric is allowed</p>			        
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Product Promo Price -->
			      <label class="control-label" for="frm_product_promo_price">Promo Price $</label>
			      <div class="controls">			      	
			        <input type="text" id="frm_product_promo_price" name="frm_product_promo_price" placeholder="Type the promo price" class="input-xlarge" value="<?php if ($_REQUEST['frm_product_promo_price']) { echo stripslashes($_REQUEST['frm_product_promo_price']); } elseif (!$_REQUEST['frm_product_promo_price'] && $row['product_promo_price']) { echo stripslashes($row['product_promo_price']); } ?>" data-validation="required" /> *	        			        
			        <p class="help-block">The product promo price. Only numeric is allowed</p>			        
			      </div>
			    </div>
			 

			    <div class="control-group">
			      <!-- Product Special Offer Details -->
			      <label class="control-label" for="frm_product_special_offer_details">Special Offer Details</label>
			      <div class="controls">
			        <textarea name="frm_product_special_offer_details" id="frm_product_special_offer_details" class="input-xlarge" rows="3"><?php if ($_REQUEST['frm_product_special_offer_details']) { echo stripslashes($_REQUEST['frm_product_special_offer_details']); } elseif (!$_REQUEST['frm_product_special_offer_details'] && $row['product_special_offer_details']) { echo stripslashes($row['product_special_offer_details']); } ?></textarea> (optional)
			        <p class="help-block">The product special offer details</p>
			      </div>
			    </div>
			 
			    <div class="control-group">
			      <!-- Product Active -->
			      <label class="control-label" for="frm_product_active">Activate?</label>
			      <div class="controls">
			        <input type="checkbox" name="frm_product_active" id="frm_product_active" value="yes"<?php if ($_REQUEST['frm_product_active'] == "yes" || $row['product_active'] == "yes") { ?> checked="checked"<?php } ?> /> 
			        <p class="help-block">Set this to active?</p>
			      </div>
			    </div>	

			    <div class="control-group">
			      <!-- Button -->
			      <div class="controls">
			        <button class="btn" type="button" name="frm_submit" id="frm_submit"><?php echo $strState; ?> Product</button>
			      </div>
			    </div>
			  </fieldset>
			</form>
			
			<!-- JS Validator -->
			<script src="<?php echo $STR_URL; ?>js/jquery.formvalidator.min.js"></script>
			<script>$("#frm_product").validateOnBlur();</script>	

			<!-- JS Form action -->
			<script>
			$(document).ready(function () {

				$("#frm_submit").click(function() { 

					var straction = $("#action").val();
					<?php if ($_REQUEST['action'] == "edit") { ?>var product_id = $("#product_id").val();<?php } ?>
	    			var frm_product_code = $("#frm_product_code").val();
	    			var frm_product_name = $("#frm_product_name").val();
	    			var frm_product_normal_retail_price = $("#frm_product_normal_retail_price").val();
	    			var frm_product_promo_price = $("#frm_product_promo_price").val();
	      			var frm_product_special_offer_details = $("#frm_product_special_offer_details").val();
	      			var frm_product_active; if ($('#frm_product_active').is(":checked")) { frm_product_active = $("#frm_product_active").val(); } else { frm_product_active = "no" }

	      			var dataString = "action=" + straction + "&" + 
	      			
	      			<?php if ($_REQUEST['action'] == "edit") { ?>"&<?php echo $strPageIDName; ?>=" + <?php echo $_REQUEST[$strPageIDName]; ?> + "&" + <?php } ?>	

					"frm_product_code=" + frm_product_code + "&frm_product_name=" + frm_product_name + "&frm_product_normal_retail_price=" + frm_product_normal_retail_price + "&frm_product_promo_price=" + frm_product_promo_price + "&frm_product_special_offer_details=" + frm_product_special_offer_details + "&frm_product_active=" + frm_product_active;
	      				   
					var request = $.ajax({							    
						url: "ajax/product_proc.php",
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

				});	
			
			});

			function clearForm()
			{
				// clear
				$("#frm_product_code").val("");
				$("#frm_product_name").val("");
				$("#frm_product_normal_retail_price").val("");
				$("#frm_product_promo_price").val("");
				$("#frm_product_special_offer_details").val("");	
				$("#frm_product_active").attr("checked", false);
				$("#statusbox").html("");

				// set to focus
				$("#frm_product_code").focus();
			}
			
			</script>			
			
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>