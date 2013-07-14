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
    	
   		<?php
			if ($_REQUEST['user_id']) 
			{
				$conn = $db->dbConnect();
				$query = "SELECT * FROM `users` WHERE `user_id` = '" . $_REQUEST['user_id'] . "' LIMIT 1";
				$result = mysql_query($query, $conn);
				
				if ($result) 
				{
					$row = mysql_fetch_assoc($result);
				}
				
				if ($row['user_photo'] && $_REQUEST['action'] == 'del_image' && $_REQUEST['user_id']) 
				{
					@unlink('uploads/user/' . $row['user_photo']);				
					$queryDelImg = "UPDATE `users` SET `user_photo` = '' WHERE `user_id` = '" . $_REQUEST['user_id'] . "' LIMIT 1";
					$resultDelImg = mysql_query($queryDelImg, $conn);
					$html->redirectUser($_SERVER['PHP_SELF'] . "?user_id=" . $_REQUEST['user_id'], 1);
				}
								
			}
		?>
             

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
	            
				<form id="myform" class="cssform" enctype="multipart/form-data" method="post" action="user_edit_exec.php">
				<input type="hidden" name="frm_action" value="edit" />
				<input type="hidden" name="frm_user_id" value="<?php echo $_REQUEST['user_id']; ?>" />
				
				<fieldset>
				<legend><strong>Login</strong></legend>
				<p>
				<label for="frm_user_login_name">Username:</label>
				<input type="hidden" name="frm_user_login_name" id="frm_user_login_name" value="<?php echo $row['user_login_name']; ?>" /> 				
				<strong><?php echo strtolower($row['user_login_name']); ?></strong>
				</p>
				
				<p>
				<label for="frm_user_level">Type:</label>
				<input type="radio" name="frm_user_level" value="admin"<?php if ($_REQUEST['frm_user_level'] && $_REQUEST['frm_user_level'] == 'admin') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_level'] && $row['user_level'] == 'admin') { echo ' checked="checked"'; } ?> /> Admin
				<input type="radio" name="frm_user_level" value="user"<?php if ($_REQUEST['frm_user_level'] && $_REQUEST['frm_user_level'] == 'user') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_level'] && $row['user_level'] == 'user') { echo ' checked="checked"'; } ?> /> User * <br />
				</p>
				
				<p>
				<label for="frm_user_user_group">User Group:</label>
				<?php $arrUserGroup = $db->getUserGroupData(); ?>								
				<?php echo "\n<select name=\"frm_user_group_id\" id=\"frm_user_group_id\">"; ?>
				<?php echo "\n\t<option value=\"\">-- Please choose --</option>"; ?>
				<?php
					if (is_array($arrUserGroup) && count($arrUserGroup) > 0)
					{
						foreach ($arrUserGroup as $intUserGroupID=>$arrUserGroupData)
						{
							echo "\n\t<option value=\"" . $intUserGroupID . "\"";  
							
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
				<br /><small>User Group is not in the list? Please <a href="user_group_add.php">input a new one</a>. <br />User group will get administrator access is when user type is "Admin"!</small>
				</p>
																
				<p>
				<label for="frm_user_subscription_start">Active Period:</label>
				<select name="frm_user_subscription_start_day" id="frm_user_subscription_start_day">
				<?php
					// set date
					$sdate = substr($row['user_subscription_start'], 0, 10);
					$sdate = explode("-", $sdate);
				?>
				
		        <?php 
					
					for ($i = 1; $i <= 31; $i++) 
					{
								
						if (strlen($i) == 1) { $i = '0' . $i; }
								
						echo "<option value=\"" . $i ."\"";
								
						if ($_REQUEST['frm_user_subscription_start_day'] && 
							$_REQUEST['frm_user_subscription_start_day'] == $i) 
						{
							echo " selected";
						} 
						elseif (!$_REQUEST['frm_user_subscription_start_day'] && ($sdate[2] == $i)) 
						{		
							echo " selected";
						} 
						elseif (!$_REQUEST['frm_user_subscription_start_day'] && !$sdate[2] && date('d') == $i) 
						{
							echo " selected"; 						
						}					  	
						
						echo ">" . $i . "</option>\n";
					}					
		
				?>
				</select>
				<select name="frm_user_subscription_start_month" id="frm_user_subscription_start_month">				
		        <?php 
					
				   	for ($i = 1; $i <= 12; $i++) 
				   	{
							
						if (strlen($i) == 1) { $i = '0' . $i; }
								
						echo "<option value=\"" . $i . "\"";						
								
						if ($_REQUEST['frm_user_subscription_start_month'] && 
							$_REQUEST['frm_user_subscription_start_month'] == $i) 
						{
							echo " selected"; 
						} elseif (!$_REQUEST['frm_user_subscription_start_month'] && ($sdate[1] == $i)) 
						{		
							echo " selected";
						} elseif (!$_REQUEST['frm_user_subscription_start_month'] && !$sdate[1] && date('n') == $i) 
						{
							echo " selected"; 						
						}
		
						echo ">" . date('F', mktime(0, 0, 0, $i + 1, 0, 0)) . "</option>\n"; 
				   	}			
				?>
			  	</select>
		        <select name="frm_user_subscription_start_year" id="frm_user_subscription_start_year">
		        <?php 
		
		        	for ($i = (date('Y') - 5); $i <= (date('Y') + 5); $i++) 
		        	{
						echo "<option value=\"" . $i . "\"";						
								
						if ($_REQUEST['frm_user_subscription_start_year'] && $_REQUEST['frm_user_subscription_start_year'] == $i) 
						{ 
							echo " selected"; 
						} 
						elseif (!$_REQUEST['frm_user_subscription_start_year'] && ($sdate[0] == $i)) 
						{
							echo " selected";
						} elseif (!$_REQUEST['frm_user_subscription_start_year'] && !$sdate[0] && date('Y') == $i) 
						{
							echo " selected";
						}					  	
		
						echo ">" . $i . "</option>\n"; 
					
		        	}				    						
				?>
				</select> to <br /> 
				
				
				<select name="frm_user_subscription_end_day" id="frm_user_subscription_end_day">
		        <?php 			        	
					// set date
					$edate = substr($row['user_subscription_end'], 0, 10);
					$edate = explode("-", $edate);
				?>
				
		        <?php 
					
					for ($i = 1; $i <= 31; $i++) 
					{
								
						if (strlen($i) == 1) { $i = '0' . $i; }
								
						echo "<option value=\"" . $i ."\"";
								
						if ($_REQUEST['frm_user_subscription_end_day'] && 
							$_REQUEST['frm_user_subscription_end_day'] == $i) 
						{
							echo " selected";
						} 
						elseif (!$_REQUEST['frm_user_subscription_end_day'] && ($edate[2] == $i)) 
						{		
							echo " selected";
						} 
						elseif (!$_REQUEST['frm_user_subscription_end_day'] && !$edate[2] && date('d') == $i) 
						{
							echo " selected"; 						
						}					  	
						
						echo ">" . $i . "</option>\n";
					}					
		
				?>
				</select>
				<select name="frm_user_subscription_end_month" id="frm_user_subscription_end_month">				
		        <?php 
					
				   	for ($i = 1; $i <= 12; $i++) 
				   	{
							
						if (strlen($i) == 1) { $i = '0' . $i; }
								
						echo "<option value=\"" . $i . "\"";						
								
						if ($_REQUEST['frm_user_subscription_end_month'] && 
							$_REQUEST['frm_user_subscription_end_month'] == $i) 
						{
							echo " selected"; 
						} elseif (!$_REQUEST['frm_user_subscription_end_month'] && ($edate[1] == $i)) 
						{		
							echo " selected";
						} elseif (!$_REQUEST['frm_user_subscription_end_month'] && !$edate[1] && date('n') == $i) 
						{
							echo " selected"; 						
						}
		
						echo ">" . date('F', mktime(0, 0, 0, $i + 1, 0, 0)) . "</option>\n"; 
				   	}			
				?>
			  	</select>
		        <select name="frm_user_subscription_end_year" id="frm_user_subscription_end_year">
		        <?php 
		
		        	for ($i = (date('Y') - 5); $i <= (date('Y') + 5); $i++) 
		        	{
						echo "<option value=\"" . $i . "\"";						
								
						if ($_REQUEST['frm_user_subscription_end_year'] && $_REQUEST['frm_user_subscription_end_year'] == $i) 
						{ 
							echo " selected"; 
						} 
						elseif (!$_REQUEST['frm_user_subscription_end_year'] && ($edate[0] == $i)) 
						{
							echo " selected";
						} elseif (!$_REQUEST['frm_user_subscription_end_year'] && !$edate[0] && date('Y') == $i) 
						{
							echo " selected";
						}					  	
		
						echo ">" . $i . "</option>\n"; 
					
		        	}				    						
				?>
				</select> * <br />
				</p>
				</fieldset>
				
				
				<fieldset>
				<legend><strong>Profile</strong></legend>
				<p>
				<label for="frm_user_full_name">Name:</label>
				<input type="text" name="frm_user_full_name" id="frm_user_full_name" value="<?php if ($_REQUEST['frm_user_full_name']) { echo $_REQUEST['frm_user_full_name']; }  else { echo stripslashes($row['user_full_name']); } ?>" maxlength="255" /> *
				</p>
				
				<p>
				<label for="frm_user_email">Email:</label>
				<input type="text" name="frm_user_email" id="frm_user_email" value="<?php if ($_REQUEST['frm_user_email']) { echo $_REQUEST['frm_user_email']; }  else { echo stripslashes($row['user_email']); } ?>" maxlength="255" /> *
				</p>
				
				<p>
				<label for="frm_user_description">Description:</label>
				<textarea name="frm_user_description" id="frm_user_description" rows="5" cols="40"><?php if ($_REQUEST['frm_user_description']) { echo $_REQUEST['frm_user_description']; }  else { echo stripslashes($row['user_description']); } ?></textarea> (optional)
				</p>
								
				<p>
				<label for="frm_user_photo">Photo</label>
				<input type="file" name="frm_user_photo" id="frm_user_photo" value="<?php echo $_REQUEST['frm_user_photo']; ?>" maxlength="255" /> (optional)
				<input type="hidden" name="frm_user_photo" value="<?php echo $row['user_photo']; ?>" />
				<br />
				<?php if ($row['user_photo']) { echo "<img src=\"uploads/user/" . $row['user_photo'] . "\" style=\"width: 100px; margin-right:8px; border-top: 1px solid #ccc; border-right: 2px solid #999; border-bottom: 2px solid #999; border-left: 1px solid #ccc; padding: 3px;\" />\n<br /><a class=\"btn\" href=\"" . $_SERVER['PHP_SELF'] . "?user_id=" . $_REQUEST['user_id'] . "&action=del_image\" onclick=\"return confirmDeletePhoto(this.form)\"><img src=\"img/delete_icon.png\" /> Delete Photo</a>"; } else { echo $row['user_photo']; } ?>
				</p>
				</fieldset>
				
				
				<p>
				<label for="frm_user_activate">Active:</label>
				Yes: <input type="radio" name="frm_user_activate" value="yes"<?php if ($_REQUEST['frm_user_activate'] && $_REQUEST['frm_user_activate'] == 'yes') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_activate'] && $row['user_activate'] == 'yes') { echo ' checked="checked"'; } ?> /> No: <input type="radio" name="frm_user_activate" value="no"<?php if ($_REQUEST['frm_user_activate'] && $_REQUEST['frm_user_activate'] == 'no') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_activate'] && $row['user_activate'] == 'no') { echo ' checked="checked"'; } ?> /> * <br />
				</p>
						
				<div style="margin-left: 150px;">
				<input class="btn" type="submit" name="frm_submit" value="Submit" onClick="return validateUserEdit(this.form)" /> 
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