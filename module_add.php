<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Add Module</title> 
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
				
					$("#frm_module_name").blur(function() { 
				
						var module = $("#frm_module_name").val();
						
						if (module.length > 2)
						{
							$("#status").html('<img align="absmiddle" src="img/loading.gif" /> Periksa Name Modul...');
					
							$.ajax({ 
							type: "POST", 
							url: "ajax/module_check.php", 
							data: "frm_module_name="+ module, 
							success: function(msg) { 
							
							$("#status").ajaxComplete(function(event, request, settings) { 
							
								if(msg == 'yes')
								{ 
									$("#frm_module_name").removeClass('status_not_ok'); // if necessary
									$("#frm_module_name").addClass("status_ok");
									$(this).html('<img align="absmiddle" src="img/accepted.png" /> Name Modul belum ada!');
								} 
								else 
								{ 
									$("#frm_module_name").removeClass('status_ok'); // if necessary
									$("#frm_module_name").addClass("status_not_ok");
									$(this).html('<img align="absmiddle" src="img/unaccepted.png" /> Name Modul sudah ada di database! Silakan coba yang lain.');
								}});
							}});
						
						}
							
						else						
						{
							$("#status").html('<img align="absmiddle" src="img/unaccepted.png" /> Name Modul tidak boleh kurang dari 2 karakter.');
							$("#frm_module_name").removeClass('status_ok'); // if necessary
							$("#frm_module_name").addClass("status_not_ok");
							
						}
						
					});
				});
						
				//-->
			
			</script>
            
			<script type="text/javascript">
				function validateModuleAdd(form)
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
			  <li class="active">Add Module</li>
			</ul>	

			<div align="right"><a class="btn" href="module_list.php" title="List Module"><img src="img/list_icon.png" /> List</a></div>

            <div id="box" class="span12">
	            
				<h2>New Module</h2>
	            
				<form id="myform" class="cssform" enctype="multipart/form-data" method="post" action="module_add_exec.php">
				<input type="hidden" name="frm_action" value="add" />

				<fieldset>	
				<legend><strong>Data</strong></legend>		
				<p>
				<label for="frm_module_name">Name:</label>
				<input type="text" name="frm_module_name" id="frm_module_name" value="<?php if ($_REQUEST['frm_module_name']) { echo $_REQUEST['frm_module_name']; } ?>" onkeyup="this.value=this.value.toLowerCase()" /> *
				<div id="status" style="margin-left: 155px;"></div>
				</p>

				<p>
				<label for="frm_module_display">Display:</label>
				<input type="text" name="frm_module_display" id="frm_module_display" value="<?php if ($_REQUEST['frm_module_display']) { echo $_REQUEST['frm_module_display']; } ?>" maxlength="255" /> *
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
							
							echo ">" . $arrModuleData['module_name'] . " (" . $arrModuleData['module_display'] . ")</option>";
						}
					}
				?>
				<?php echo "\n</select> *"; ?>
				</p>			
				
				<p>
				<label for="frm_module_description">Description:</label>
				<textarea name="frm_module_description" id="frm_module_description" rows="5" cols="40"><?php if ($_REQUEST['frm_module_description']) { echo $_REQUEST['frm_module_description']; } ?></textarea> (optional)
				</p>
				</fieldset>
				
				<fieldset>	
				<legend><strong>Database</strong></legend>
				<p>
				<label for="frm_module_use_table">Table Name:</label>
				<input type="text" name="frm_module_use_table" id="frm_module_use_table" value="<?php if ($_REQUEST['frm_module_use_table']) { echo $_REQUEST['frm_module_use_table']; } ?>" maxlength="255" /> (optional)
				<br /><small>Description: Database table name for this module</small>
				</p>
				
				<p>
				<label for="frm_module_datetime_table_field_name">Datetime Field Name:</label>
				<input type="text" name="frm_module_datetime_table_field_name" id="frm_module_datetime_table_field_name" value="<?php if ($_REQUEST['frm_module_datetime_table_field_name']) { echo $_REQUEST['frm_module_datetime_table_field_name']; } ?>" maxlength="255" /> (optional)
				<br /><small>Description: Database datetime field name for reference for new input of this module</small>
				</p>
				</fieldset>
				
				<fieldset>	
				<legend><strong>File</strong></legend>
				<p>
				<label for="module_file_name_list">List File Name:</label>
				<input type="text" name="frm_module_file_name_list" id="frm_module_file_name_list" value="<?php if ($_REQUEST['frm_module_file_name_list']) { echo $_REQUEST['frm_module_file_name_list']; } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_add">Add File Name:</label>
				<input type="text" name="frm_module_file_name_add" id="frm_module_file_name_add" value="<?php if ($_REQUEST['frm_module_file_name_add']) { echo $_REQUEST['frm_module_file_name_add']; } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_edit">Edit File Name:</label>
				<input type="text" name="frm_module_file_name_edit" id="frm_module_file_name_edit" value="<?php if ($_REQUEST['frm_module_file_name_edit']) { echo $_REQUEST['frm_module_file_name_edit']; } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_delete">Delete File Name:</label>
				<input type="text" name="frm_module_file_name_delete" id="frm_module_file_name_delete" value="<?php if ($_REQUEST['frm_module_file_name_delete']) { echo $_REQUEST['frm_module_file_name_delete']; } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_view">View File Name:</label>
				<input type="text" name="frm_module_file_name_view" id="frm_module_file_name_view" value="<?php if ($_REQUEST['frm_module_file_name_view']) { echo $_REQUEST['frm_module_file_name_view']; } ?>" maxlength="255" /> (optional)
				</p>
				<p>
				<label for="frm_module_file_name_execute">Execute File Name:</label>
				<input type="text" name="frm_module_file_name_execute" id="frm_module_file_name_execute" value="<?php if ($_REQUEST['frm_module_file_name_execute']) { echo $_REQUEST['frm_module_file_name_execute']; } ?>" maxlength="255" /> (optional)
				</p>
				
				</fieldset>
							
				<p>
				<label for="frm_module_activate">Active:</label>
				Yes: <input type="radio" name="frm_module_activate" value="yes" /> No: <input type="radio" name="frm_module_activate" value="no" checked="checked" /> * <br />
				</p>
						
				<div style="margin-left: 150px;">
				<input class="btn" type="submit" name="frm_submit" value="Submit" onclick="return validateModuleAdd(this.form)" /> 
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