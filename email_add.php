<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Add Email</title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
    
</head> 

<body>

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">

	<?php if ($admin->isLoggedIn() > 0) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">
   			
            <?php #print_r($_SESSION); print_r($arrPrivileges); ?>

            <script type="text/javascript">
				
            	pic1 = new Image(16, 16); 
				pic1.src = "img/loading.gif";
				
				$(document).ready(function() {
				
					$("#frm_user_group_name").blur(function() { 
				
						var usr = $("#frm_user_group_name").val();
					
						if (usr.length >= 3)
						{
							$("#status").html('<img align="absmiddle" src="img/loading.gif" /> Checking User Group Name...');
					
							$.ajax({ 
								type: "POST", 
								url: "ajax/user_group_check.php", 
								data: "frm_user_group_name="+ usr, 
								success: function(msg) { 
							
									$("#status").ajaxComplete(function(event, request, settings) { 
							
										if (msg == 'yes')
										{ 
											$("#frm_user_group_name").removeClass('status_not_ok'); // if necessary
											$("#frm_user_group_name").addClass("status_ok");
											$(this).html('<img align="absmiddle" src="img/accepted.png" /> User Group Name is available!');
										} 
									
										else 
										{ 
											$("#frm_user_group_name").removeClass('status_ok'); // if necessary
											$("#frm_user_group_name").addClass("status_not_ok");
											$(this).html('<img align="absmiddle" src="img/unaccepted.png" /> User Group Name is not available! Please try something else.');
										}
									});
								}
							
							});
						
						}
							
						else						
						{
							$("#status").html('<img align="absmiddle" src="img/unaccepted.png" /> User Group Name must contain 3 characters minimum.');
							$("#frm_user_group_name").removeClass('status_ok'); // if necessary
							$("#frm_user_group_name").addClass("status_not_ok");
							
						}
					});
				});						
				//-->
			
			</script>
			
			<script type="text/javascript">
				function validateEmailAdd(form)
				{

					//-- frm_store_id
					if (form.frm_store_id.value == '') 
					{
						alert('Please fill Store!')
						form.frm_store_id.value = ''
						form.frm_store_id.focus()
						return false
					}

					//-- frm_email_name
					if (form.frm_email_name.value == '') 
					{
						alert('Please fill Name!')
						form.frm_email_name.value = ''
						form.frm_email_name.focus()
						return false
					}

					//-- frm_email_address
					if (form.frm_email_address.value == '') 
					{
						alert('Please fill Email Address!');
						form.frm_email_address.value = '';
						form.frm_email_address.focus();
						return false;
					}
			
				}
			
			</script>
            

            <!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">System Administration</a> <span class="divider">/</span></li>			  
			  <li><a href="email_list.php">Email</a> <span class="divider">/</span></li>			  
			  <li class="active">New Email</li>
			</ul>
            
			<div align="right"><a class="btn" href="email_list.php" title="Email List"><img src="img/list_icon.png" /> List</a></div>

            <div id="box" class="span12">
	            
            	<h2>New Email</h2>
	            
            	
				<form id="myform" class="cssform" enctype="multipart/form-data" method="post" action="email_add_exec.php">
				<input type="hidden" name="frm_action" value="add" />
				

				<p>
				<label for="frm_store_name">Store:</label>

				<?php $arrStores = $db->getStoreData(); ?>								
			  	<select name="frm_store_id" id="frm_store_id" class="input-large">
			  		<option value="">-- Please select Store --</option>
				  	<?php
						if (is_array($arrStores) && count($arrStores) > 0)
						{									
							foreach ($arrStores as $intStoreIDVal=>$arrStoresData)
							{
								echo "\n\t<option value=\"" . $intStoreIDVal . "\"";  
								
								if ($_REQUEST['frm_store_id'] && $_REQUEST['frm_store_id'] == $intStoreIDVal)
								{
									echo " selected=\"selected\"";
								}
								
								echo ">" . stripslashes($arrStoresData['store_name']) . "</option>";
							}
						}
				   	?>
			  	</select>
				<div id="status" style="margin-left: 155px;"></div>
				</p>

				<p>
				<label for="frm_email_name">Name:</label>
				<input type="text" name="frm_email_name" id="frm_email_name" placeholder="Please type Name" value="<?php if ($_REQUEST['frm_email_name']) { echo $_REQUEST['frm_email_name']; } ?>" maxlength="255" /> *
				<div id="status" style="margin-left: 155px;"></div>
				</p>
				
				<p>
				<label for="frm_email_address">Email Address:</label>
				<input type="email" name="frm_email_address" id="frm_email_address" placeholder="Please type User's email" value="<?php if ($_REQUEST['frm_email_address']) { echo $_REQUEST['frm_email_address']; } ?>" maxlength="255" /> *
				</p>
												
				
				<div style="margin-left: 150px;">
				<input class="btn" type="submit" name="frm_submit" value="Submit" onclick="return validateUserGroupAdd(this.form)" /> 
				</div>
				
				<br />
				<span>*) Required</span>
				
				</form>
				
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>