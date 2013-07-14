<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Edit Module</title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
    
</head> 

<body>

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">

	<?php if ($admin->isLoggedIn() > 0) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">
   			
            <?php
			if ($_REQUEST['module_id']) 
			{
				$conn = $db->dbConnect();
				$query = "SELECT * FROM `modules` WHERE `module_id` = '" . $_REQUEST['module_id'] . "' LIMIT 1";
				$result = mysql_query($query, $conn);
				
				if ($result) 
				{
					$row = mysql_fetch_assoc($result);
				}
				
			}
			?>
            
			<script type="text/javascript">
				function validateModuleEdit(form)
				{
					//-- frm_module_name
					if (form.frm_module_name.value == '') 
					{
						alert('Please fill Module Name!')
						form.frm_module_name.value = ''
						form.frm_module_name.focus()
						return false
					}
					
					//-- frm_module_display
					if (form.frm_module_display.value == '') 
					{
						alert('PLease fill Module Display!')
						form.frm_module_display.value = ''
						form.frm_module_display.focus()
						return false
					}
										
				}            
			</script>
			

			<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">System Administration</a> <span class="divider">/</span></li>			  
			  <li><a href="module_list.php">Modules</a> <span class="divider">/</span></li>			  
			  <li class="active">Edit Module</li>
			</ul>


			<div align="right"><a class="btn" href="module_view.php?module_id=<?php echo $_REQUEST['module_id']; ?>" title="View Module"><img src="img/view_icon.png" /> View</a> &nbsp;&nbsp; <a class="btn" href="module_list.php" title="List Module"><img src="img/list_icon.png" /> List</a></div>	

            <div id="box" class="span12">
	            
				<h2>Edit Module &raquo; <?php echo stripslashes($row['module_name']); ?></h2>
	            
				<form id="myform" class="cssform" enctype="multipart/form-data" method="post" action="module_edit_exec.php">
				<input type="hidden" name="frm_action" value="edit" />
				<input type="hidden" name="frm_module_id" value="<?php echo $row['module_id']; ?>" />

				<fieldset>	
				<legend><strong>Data</strong></legend>		
				<p>
				<label for="frm_module_name">Name:</label>
				<input type="text" name="frm_module_name" id="frm_module_name" value="<?php if ($_REQUEST['frm_module_name']) { echo $_REQUEST['frm_module_name']; } else { echo stripslashes($row['module_name']); } ?>" onkeyup="this.value=this.value.toLowerCase()" /> *
				<div id="status" style="margin-left: 155px;"></div>
				</p>

				<p>
				<label for="frm_module_display">Display:</label>
				<input type="text" name="frm_module_display" id="frm_module_display" value="<?php if ($_REQUEST['frm_module_display']) { echo $_REQUEST['frm_module_display']; } else { echo stripslashes($row['module_display']); } ?>" maxlength="255" /> *
				</p>
				
				<p>
				<label for="frm_module_parent_id">Parent:</label>
				<?php $arrModules = $db->getModulesData(); ?>				
				<?php echo "\n<select name=\"frm_module_parent_id\" id=\"frm_module_parent_id\">"; ?>
				<?php echo "\n\t<option value=\"\">Top Parent</option>"; ?>
				<?php
					if (is_array($arrModules) && count($arrModules) > 0)
					{
						foreach ($arrModules as $intModuleID=>$arrModuleData)
						{
							echo "\n\t<option value=\"" . $intModuleID . "\"";  
							
							if ($_REQUEST['frm_module_parent_id'] && $_REQUEST['frm_module_parent_id'] == $intModuleID)
							{
								echo " selected=\"selected\"";
							}
							
							elseif (!$_REQUEST['frm_module_parent_id'] && $row['module_parent_id'] == $intModuleID)
							{
								echo " selected=\"selected\"";
							}
							
							echo ">" . $arrModuleData['module_name'] . " (" . $arrModuleData['module_display'] . ")</option>";
						}
					}
				?>
				<?php echo "\n</select> *"; ?>
				</p>			
				
				<p>
				<label for="frm_module_description">Description:</label>
				<textarea name="frm_module_description" id="frm_module_description" rows="5" cols="40"><?php if ($_REQUEST['frm_module_description']) { echo $_REQUEST['frm_module_description']; } else { echo stripslashes($row['module_description']); } ?></textarea> (optional)
				</p>
				</fieldset>
				
				<fieldset>	
				<legend><strong>Database</strong></legend>
				<p>
				<label for="frm_module_use_table">Table Name:</label>
				<input type="text" name="frm_module_use_table" id="frm_module_use_table" value="<?php if ($_REQUEST['frm_module_use_table']) { echo $_REQUEST['frm_module_use_table']; } else { echo stripslashes($row['module_use_table']); } ?>" maxlength="255" /> (optional)
				<br /><small>Description: Database table name for this module</small>
				</p>
				
				<p>
				<label for="frm_module_datetime_table_field_name">Datetime Field Name:</label>
				<input type="text" name="frm_module_datetime_table_field_name" id="frm_module_datetime_table_field_name" value="<?php if ($_REQUEST['frm_module_datetime_table_field_name']) { echo $_REQUEST['frm_module_datetime_table_field_name']; } else { echo stripslashes($row['module_datetime_table_field_name']); } ?>" maxlength="255" /> (optional)
				<br /><small>Description: Database datetime field name for reference for new input of this module</small>
				</p>
				</fieldset>
				
				<fieldset>	
				<legend><strong>File</strong></legend>
				<p>
				<label for="module_file_name_list">List File Name:</label>
				<input type="text" name="frm_module_file_name_list" id="frm_module_file_name_list" value="<?php if ($_REQUEST['frm_module_file_name_list']) { echo $_REQUEST['frm_module_file_name_list']; } else { echo stripslashes($row['module_file_name_list']); } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_add">Add File Name:</label>
				<input type="text" name="frm_module_file_name_add" id="frm_module_file_name_add" value="<?php if ($_REQUEST['frm_module_file_name_add']) { echo $_REQUEST['frm_module_file_name_add']; } else { echo stripslashes($row['module_file_name_add']); } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_edit">Edit File Name:</label>
				<input type="text" name="frm_module_file_name_edit" id="frm_module_file_name_edit" value="<?php if ($_REQUEST['frm_module_file_name_edit']) { echo $_REQUEST['frm_module_file_name_edit']; } else { echo stripslashes($row['module_file_name_edit']); } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_delete">Delete File Name:</label>
				<input type="text" name="frm_module_file_name_delete" id="frm_module_file_name_delete" value="<?php if ($_REQUEST['frm_module_file_name_delete']) { echo $_REQUEST['frm_module_file_name_delete']; } else { echo stripslashes($row['module_file_name_delete']); } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_view">View File Name:</label>
				<input type="text" name="frm_module_file_name_view" id="frm_module_file_name_view" value="<?php if ($_REQUEST['frm_module_file_name_view']) { echo $_REQUEST['frm_module_file_name_view']; } else { echo stripslashes($row['module_file_name_view']); } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_execute">Execute File Name:</label>
				<input type="text" name="frm_module_file_name_execute" id="frm_module_file_name_execute" value="<?php if ($_REQUEST['frm_module_file_name_execute']) { echo $_REQUEST['frm_module_file_name_execute']; } else { echo stripslashes($row['module_file_name_execute']); } ?>" maxlength="255" /> (optional)
				</p>
				
				</fieldset>
							
				<p>
				<label for="frm_module_activate">Active:</label>
				Yes: <input type="radio" name="frm_module_activate" value="yes"<?php if ($_REQUEST['frm_module_activate'] && $_REQUEST['frm_module_activate'] == 'yes') { echo " checked=\"checked\""; } elseif (!$_REQUEST['frm_module_activate'] && $row['module_activate'] == 'yes') { echo " checked=\"checked\""; } ?> /> No: <input type="radio" name="frm_module_activate" value="no"<?php if ($_REQUEST['frm_module_activate'] && $_REQUEST['frm_module_activate'] == 'no') { echo " checked=\"checked\""; } elseif (!$_REQUEST['frm_module_activate'] && $row['module_activate'] == 'no') { echo " checked=\"checked\""; } ?> /> * <br />
				</p>
						
				<div style="margin-left: 150px;">
				<input class="btn" type="submit" name="frm_submit" value="Submit" onclick="return validateModuleEdit(this.form)" /> 
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