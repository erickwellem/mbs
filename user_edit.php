<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Edit User</title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
    
</head> 

<body>

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">

	<?php if ($admin->isLoggedIn() > 0) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">
   			
    		<script type="text/javascript">
    			function validateUserEdit(form)
				{

    				//-- frm_user_group_id
					if (form.frm_user_group_id.value == '') 
					{
						alert('Please choose User Group!')
						form.frm_user_group_id.value = ''
						form.frm_user_group_id.focus()
						return false
					}
					
					//-- frm_user_full_name
					if (form.frm_user_full_name.value == '') 
					{
						alert('Please fill Full Name!')
						form.frm_user_full_name.value = ''
						form.frm_user_full_name.focus()
						return false
					}
					
					//-- frm_user_email
					if (form.frm_user_email.value == '') 
					{
						alert('Please fill Email!')
						form.frm_user_email.value = ''
						form.frm_user_email.focus()
						return false
					}
					
					if (form.frm_user_email.value.indexOf('@') < 2) 
					{
				 		alert('"' + form.frm_user_email.value + '" is invalid. Please correct it!')
						form.frm_user_email.focus()
						return false
					}
			
				}
    		
    		</script>
    	
   		
             

            <!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">System Administration</a> <span class="divider">/</span></li>			  
			  <li><a href="user_list.php">User</a> <span class="divider">/</span></li>			  
			  <li class="active">Edit User</li>
			</ul>	            

			<div align="right">
				<a class="btn" href="user_view.php?user_id=<?php echo $_REQUEST['user_id']; ?>" title="View User"><img src="img/view_icon.png" /> View</a> &nbsp;&nbsp; <a class="btn" href="user_list.php" title="User List"><img src="img/list_icon.png" /> List</a>
			</div>

            <div id="box" class="span12">
            
	            <h2>Edit User</h2>
	            
				<form id="frm_users" class="form-horizontal" enctype="multipart/form-data" method="post" action="user_edit_exec.php">
				<input type="hidden" name="frm_action" value="edit" />
				<input type="hidden" name="frm_user_id" value="<?php echo (int)$_REQUEST['user_id']; ?>" />
				
				<div class="tabbable">
					<ul class="nav nav-tabs" id="formtab-nav">
						<li class="active" id="first-tab"><a href="#tab1" data-toggle="tab"><i class="icon-align-justify"></i> Login</a></li>
						<li id="second-tab"><a href="#tab2" data-toggle="tab"><i class="icon-align-justify"></i> Profile</a></li>						
					</ul>

					<div class="tab-content">

						<div class="tab-pane active" id="tab1">
							<!-- USER -->
							<fieldset>
							    <div id="legend">
							      	<legend class="">Login</legend>
							    </div>							   							    

							    <div class="control-group">
							      <!-- Username -->
							      <label class="control-label" for="frm_user_login_name">Username</label>
							      <div class="controls">
							      	<strong><?php if ($row['user_login_name']) { echo $row['user_login_name']; } ?></strong>
							      </div>
							    </div>

							    <div class="control-group">
							      <!-- Password -->
							      <label class="control-label" for="frm_user_password">Password</label>
							      <div class="controls">
							        <?php if ($_SESSION['user']['login_name'] == $row['user_login_name']) {?><a href="user_password_change.php?user_id=<?php echo (int)$_REQUEST['user_id']; ?>">Change password</a><?php } ?>
							        <p class="help-block"></p>							        
							      </div>
							    </div>							    

							    <div class="control-group">
							      <!-- Type -->
							      <label class="control-label" for="frm_user_level">Type</label>
							      <div class="controls">
							        Admin: <input type="radio" name="frm_user_level" id="frm_user_level_1" value="admin"<?php if ($_REQUEST['frm_user_level'] && $_REQUEST['frm_user_level'] == 'admin') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_level'] && $row['user_level'] == 'admin') { echo ' checked="checked"'; } ?> /> 
							        User: <input type="radio" name="frm_user_level" id="frm_user_level_2" value="user"<?php if ($_REQUEST['frm_user_level'] && $_REQUEST['frm_user_level'] == 'user') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_level'] && $row['user_level'] == 'user') { echo ' checked="checked"'; } ?> /> * <br />
							        <p class="help-block"></p>							        
							      </div>
							    </div>		

							    <div class="control-group">
							      <!-- Usergroup -->
							      <label class="control-label" for="frm_user_user_group">Usergroup</label>
							      <div class="controls">
							        <?php $arrUserGroup = $db->getUserGroupData(); ?>								
									<?php echo "\n<select name=\"frm_user_group_id\" id=\"frm_user_group_id\">"; ?>
									<?php echo "\n\t<option value=\"\">-- Please select --</option>"; ?>
									<?php
										if (is_array($arrUserGroup) && count($arrUserGroup) > 0)
										{
											foreach ($arrUserGroup as $intUserGroupID=>$arrUserGroupData)
											{
												echo "\n\t<option 	value=\"" . $intUserGroupID . "\"";  
												
												if ($_REQUEST['frm_user_group_id'] && $_REQUEST['frm_user_group_id'] == $intUserGroupID)
												{
													echo " selected=\"selected\"";
												}
												
												elseif (!$_REQUEST['frm_user_group_id'] && $row['user_group_id'] == $intUserGroupID)
												{
													echo " selected=\"selected\"";
												}
												
												echo ">" . stripslashes($arrUserGroupData['user_group_name']) . "</option>";
											}
										}
									?>
									<?php echo "\n</select> *"; ?>
									<br /><small>User Group is not in the list? Please <a href="user_group_add.php">input a new one</a>. <br />User group will automatically get administrator access when user type is "Admin"!</small>
							        <p class="help-block"></p>							        
							      </div>
							    </div>
							    
							    <div class="control-group">
							      <!-- Type -->
							      <label class="control-label" for="frm_user_subscription_start">Active Period</label>
							      <div class="controls"><?php echo $row['user_subscription_start']; ?> to <?php echo $row['user_subscription_end']; ?>
							        From <input type="text" id="frm_user_subscription_start" name="frm_user_subscription_start" placeholder="Type the date in yyyy-mm-dd format" class="input-small" value="<?php if ($_REQUEST['frm_user_subscription_start'] && !$row['user_subscription_start']) { echo stripslashes($_REQUEST['frm_user_subscription_start']); } elseif (!$_REQUEST['frm_user_subscription_start'] && $row['user_subscription_start']) { echo $row['user_subscription_start']; } elseif (!$_REQUEST['frm_user_subscription_start'] && !$row['user_subscription_start']) { echo date('d-m-Y'); } ?>" /> 
							        to <input type="text" id="frm_user_subscription_end" name="frm_user_subscription_end" placeholder="Type the date in yyyy-mm-dd format" class="input-small" value="<?php if ($_REQUEST['frm_user_subscription_end'] && !$row['user_subscription_end']) { echo stripslashes($_REQUEST['frm_user_subscription_end']); } elseif (!$_REQUEST['frm_user_subscription_end'] && $row['user_subscription_end']) { echo $row['user_subscription_end']; } elseif (!$_REQUEST['frm_user_subscription_start'] && !$row['user_subscription_start']) { echo date('d-m-Y'); } ?>" /> 
							        <p class="help-block"></p>							        
							      </div>
							    </div>

							    <div class="control-group">
							      	<!-- Active -->
							      	<label class="control-label" for="frm_user_activate">Active</label>
							      	<div class="controls">							
									Yes: <input type="radio" name="frm_user_activate" value="yes"<?php if ($_REQUEST['frm_user_activate'] && $_REQUEST['frm_user_activate'] == 'yes') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_activate'] && $row['user_activate'] == 'yes') { echo ' checked="checked"'; } ?> /> 
									No: <input type="radio" name="frm_user_activate" value="no"<?php if ($_REQUEST['frm_user_activate'] && $_REQUEST['frm_user_activate'] == 'no') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_activate'] && $row['user_activate'] == 'no') { echo ' checked="checked"'; } ?> /> * 
									<p class="help-block"></p>
									</div>	
								</div>	

								<br /><br />
								
								<div class="control-group">
									<div class="controls">
										<button class="btn" type="button" name="frm_next_1" id="frm_next_1"><i class="icon-forward"></i> Next</button>
									</div>
								</div>

							</fieldset>  

						</div> <!--#tab1-->

						<div class="tab-pane" id="tab2">
							<!-- USER -->
							<fieldset>
							    <div id="legend">
							      <legend class="">Profile</legend>
							    </div>
							    							    	
							    <div class="control-group">
							    	<!--Full Name-->
									<label class="control-label" for="frm_user_full_name">Name:</label>
									<div class="controls">
										<input class="input-xlarge" type="text" name="frm_user_full_name" id="frm_user_full_name" placeholder="Please type User's full name" value="<?php if ($_REQUEST['frm_user_full_name']) { echo stripslashes($_REQUEST['frm_user_full_name']); }  else { echo stripslashes($row['user_full_name']); } ?>" maxlength="255" /> *
										<p class="help-block"></p>
									</div>
								</div>
								
								<div class="control-group">
									<!--Email-->
									<label class="control-label" for="frm_user_email">Email:</label>
									<div class="controls">
										<input class="input-xlarge" type="text" name="frm_user_email" id="frm_user_email" placeholder="Please type User's email" value="<?php if ($_REQUEST['frm_user_email']) { echo stripslashes($_REQUEST['frm_user_email']); } else { echo stripslashes($row['user_email']); } ?>" maxlength="64" /> *
										<p class="help-block"></p>
									</div>	
								</div>
								
								<div class="control-group">
									<!--Description-->
									<label class="control-label" for="frm_user_description">Description:</label>
									<div class="controls">
										<textarea class="input-xlarge" name="frm_user_description" id="frm_user_description" rows="5" cols="40" placeholder="Please type User's description"><?php if ($_REQUEST['frm_user_description']) { echo stripslashes($_REQUEST['frm_user_description']); } else { echo stripslashes($row['user_description']); } ?></textarea> (optional)
										<p class="help-block"></p>	
									</div>	
								</div>
												
								<div class="control-group">
									<!--Photo-->
									<label class="control-label" for="frm_user_photo">Photo</label>
									<div class="controls">
										<input class="input-xlarge" type="file" name="frm_user_photo" id="frm_user_photo" value="<?php echo $_REQUEST['frm_user_photo']; ?>" maxlength="255" /> (optional)
											<input type="hidden" name="frm_user_photo" value="<?php echo $row['user_photo']; ?>" />
											<br />
											<?php if ($row['user_photo']) { echo "<img src=\"uploads/user/" . $row['user_photo'] . "\" style=\"width: 100px; margin-right:8px; border-top: 1px solid #ccc; border-right: 2px solid #999; border-bottom: 2px solid #999; border-left: 1px solid #ccc; padding: 3px;\" />\n<br /><a class=\"btn\" href=\"" . $_SERVER['PHP_SELF'] . "?user_id=" . (int)$_REQUEST['user_id'] . "&action=del_image\" onclick=\"return confirmDeletePhoto(this.form)\"><img src=\"img/delete_icon.png\" /> Delete Photo</a>"; } else { echo $row['user_photo']; } ?>
										<p class="help-block"></p>	
									</div>
								</div>	

								<br /><br />

								<div class="control-group">
									<div class="controls">
										<button class="btn" type="button" name="frm_prev_1" id="frm_prev_1"><i class="icon-backward"></i> Previous</button>&nbsp;&nbsp;&nbsp; <input class="btn" type="submit" name="frm_submit" value="Submit" onclick="return validateUserAdd(this.form)" />										
									</div>
								</div>	


							</fieldset>  

						</div> <!--#tab2-->

					</div> <!--.tab-content-->	
				</div> <!--.tabbable-->		  
				
				</form>
				<p class="pull-right"><a class="btn" href="#top"><i class="icon-arrow-up"></i> Back to top</a></p>


				<!-- JS Validator -->
				<script src="<?php echo $STR_URL; ?>js/jquery.formvalidator.min.js"></script>
				<script>$("#frm_users").validateOnBlur();</script>

			    <script>
			    	$(function() {

			    		//-- Next Button 1
						$('#frm_next_1').click(function() {
							$('#second-tab').show();
							$('#formtab-nav a[href="#tab2"]').tab('show');
						});

						//-- Previous Button 1
						$('#frm_prev_1').click(function() {
							$('#first-tab').show();
							$('#formtab-nav a[href="#tab1"]').tab('show');
						});
								
						//-- User Level
						$('#frm_user_level_1').click(function() {				
							if ($('#frm_user_level_1').val() == 'admin') 
							{
								$('#frm_user_group_id').val(1);
							}
						});

						$('#frm_user_level_2').click(function() {				
							if ($('#frm_user_level_2').val() == 'user') 
							{
								$('#frm_user_group_id').val(2);
							}
						});

						//-- User Group
						$('#frm_user_group_id').change(function() {
							
							if ($('#frm_user_group_id option:selected').val() == 1)
							{
								$('#frm_user_level_1').prop('checked', true);
								$('#frm_user_level_2').prop('checked', false);
							}

							else if ($('#frm_user_group_id option:selected').val() == 2)
							{
								$('#frm_user_level_1').prop('checked', false);
								$('#frm_user_level_2').prop('checked', true);
							}

						});
					    
					    $("#frm_user_subscription_start").datepicker({
					      defaultDate: "+1w",
					      changeYear: true,
					      changeMonth: true,
					      dateFormat: "dd-mm-yy",
					      numberOfMonths: 1,
					      onClose: function( selectedDate ) {
					        $("#frm_user_subscription_end").datepicker( "option", "minDate", selectedDate );
					      }
					    });
					    
					    $("#frm_user_subscription_end").datepicker({
					      defaultDate: "+1w",
					      changeYear: true,
					      changeMonth: true,
					      dateFormat: "dd-mm-yy",
					      numberOfMonths: 1,
					      onClose: function( selectedDate ) {
					        $("#frm_user_subscription_start").datepicker( "option", "maxDate", selectedDate );
					      }
					    });

					    /*$('#frm_user_subscription_end').datepicker("setDate", new Date(<?php echo intval(date('Y'))+1; ?>, <?php echo intval(date('m'))-1; ?>, <?php echo date('d'); ?>) );*/


					});
			    </script>
				
				
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>