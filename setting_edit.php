<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Edit Configuration</title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
    
</head> 

<body>

	<?php if (!$_REQUEST['pop']) { include('inc/header.php'); } ?>

<div id="wrapper" class="container-fluid">

	<?php if ($admin->isLoggedIn() > 0 && !$_REQUEST['pop']) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">
   			
   		<?php
			if ($_REQUEST['sn']) 
			{
				$conn = $db->dbConnect();
				$query = "SELECT * FROM `settings` WHERE `setting_name` = '" . $_REQUEST['sn'] . "' LIMIT 1";
				$result = mysql_query($query, $conn);
				
				if ($result) 
				{
					$row = mysql_fetch_assoc($result);
				}
								
			}
		?>
            <script type="text/javascript">
				function validateSettingsEdit(form)
				{
					
					//-- frm_setting_name
					if (form.frm_setting_name.value == '') 
					{
						alert('Mohon isi Name Configuration!')
						form.frm_setting_name.value = ''
						form.frm_setting_name.focus()
						return false
					}
					
					form.frm_submit.value = 'Loading...'
					
				}            
			</script>
			

			<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">System Administration</a> <span class="divider">/</span></li>			  
			  <li><a href="setting_list.php">System Configuration</a> <span class="divider">/</span></li>			  
			  <li class="active">Edit Configuration</li>
			</ul>

			<div align="right"><a class="btn" href="setting_list.php" title="List Configuration"><img src="img/list_icon.png" /> List</a></div>	

            <div id="box" class="span12">
	            <h2>Edit Configuration</h2>
	            
				<form id="myform" class="cssform" enctype="multipart/form-data" method="post" action="setting_edit_exec.php">
				<input type="hidden" name="frm_action" value="edit" />
				<input type="hidden" name="frm_setting_name" value="<?php echo $row['setting_name']; ?>" />
				<input type="hidden" name="frm_setting_text" value="<?php echo $row['setting_text']; ?>" />
				
				<p>
				<label for="frm_setting_value"><?php echo stripslashes($row['setting_text']); ?>:</label>
				<input type="text" name="frm_setting_value" id="frm_setting_value" value="<?php if ($_REQUEST['frm_setting_value']) { echo $_REQUEST['frm_setting_value']; }  else { echo htmlspecialchars($row['setting_value']); } ?>" maxlength="255" /> *
				</p>
				
				<div style="margin-left: 150px;">
				<input class="btn" type="submit" name="frm_submit" value="Submit" onClick="return validateSettingsEdit(this.form)" /> 
				</div>
				
				<br />
				<span>*) Required</span>
				</form>
				
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php if (!$_REQUEST['pop']) { include('inc/footer.php'); } ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>