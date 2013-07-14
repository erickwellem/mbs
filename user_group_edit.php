<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Edit User Group</title> 
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
            
            <?php
			if ($_REQUEST['user_group_id']) 
			{
				$conn = $db->dbConnect();
				$query = "SELECT * FROM `user_groups` WHERE `user_group_id` = '" . $_REQUEST['user_group_id'] . "' LIMIT 1";
				$result = mysql_query($query, $conn);
				
				if ($result) 
				{
					$row = mysql_fetch_assoc($result);
				}
								
			}
		?>
			
			<script type="text/javascript">
				function validateUserGroupEdit(form)
				{
					//-- frm_user_group_name
					if (form.frm_user_group_name.value == '') 
					{
						alert('Please fill User Group Name!')
						form.frm_user_group_name.value = ''
						form.frm_user_group_name.focus()
						return false
					}
			
				}
			
			</script>
            

            <!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">System Administration</a> <span class="divider">/</span></li>			  
			  <li><a href="user_group_list.php">User Group</a> <span class="divider">/</span></li>			  
			  <li class="active">Edit User Group</li>
			</ul>
            
			<div align="right"><a class="btn" href="user_group_view.php?user_group_id=<?php echo $_REQUEST['user_group_id']; ?>" title="View User Group"><img src="img/view_icon.png" /> View</a> &nbsp;&nbsp; <a class="btn" href="user_group_list.php" title="User Group List"><img src="img/list_icon.png" /> List</a></div>	

            <div id="box" class="span12">
	            
            	<h2>Edit User Group</h2>
	                        	
				<form id="myform" class="cssform" enctype="multipart/form-data" method="post" action="user_group_edit_exec.php">
				<input type="hidden" name="frm_action" value="edit" />
				<input type="hidden" name="frm_user_group_id" value="<?php echo $row['user_group_id']; ?>" />
								
				<p>
				<label for="frm_user_group_name">Name:</label>
				<input type="text" name="frm_user_group_name" id="frm_user_group_name" value="<?php if ($_REQUEST['frm_user_group_name']) { echo $_REQUEST['frm_user_group_name']; } else { echo stripslashes($row['user_group_name']); } ?>" maxlength="255" /> *
				</p>
				
				<p>
				<label for="frm_user_group_description">Description:</label>
				<textarea name="frm_user_group_description" id="frm_user_group_description" rows="5" cols="40"><?php if ($_REQUEST['frm_user_group_description']) { echo $_REQUEST['frm_user_group_description']; } else { echo stripslashes($row['user_group_description']); } ?></textarea> (optional)
				</p>
												
				<p>
				<label for="frm_user_group_activate">Active:</label>
				Yes: <input type="radio" name="frm_user_group_activate" value="yes"<?php if ($_REQUEST['frm_user_group_activate'] && $_REQUEST['frm_user_group_activate'] == 'yes') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_group_activate'] && $row['user_group_activate'] == 'yes') { echo ' checked="checked"'; } ?> /> No: <input type="radio" name="frm_user_group_activate" value="no"<?php if ($_REQUEST['frm_user_group_activate'] && $_REQUEST['frm_user_group_activate'] == 'no') { echo ' checked="checked"'; } elseif (!$_REQUEST['frm_user_group_activate'] && $row['user_group_activate'] == 'no') { echo ' checked="checked"'; } ?> /> * <br />
				</p>
						
				<div style="margin-left: 150px;">
				<input class="btn" type="submit" name="frm_submit" value="Submit" onclick="return validateUserGroupEdit(this.form)" /> 
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