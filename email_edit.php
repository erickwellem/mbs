<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Edit Email</title> 
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
			if ($_REQUEST['email_id']) 
			{
				$conn = $db->dbConnect();
				$query = "SELECT * FROM `mbs_emails` WHERE `email_id` = '" . $_REQUEST['email_id'] . "' LIMIT 1";
				$result = mysql_query($query, $conn);
				
				if ($result) 
				{
					$row = mysql_fetch_assoc($result);
				}
								
			}
		?>
			
			<script type="text/javascript">
				function validateEmailEdit(form)
				{
					//-- frm_email_name
					if (form.frm_email_name.value == '') 
					{
						alert('Please fill User Group Name!')
						form.frm_email_name.value = ''
						form.frm_email_name.focus()
						return false
					}

					//-- frm_email_address
					if (form.frm_email_address.value == '') 
					{
						alert('Please fill Email!');
						form.frm_email_address.value = '';
						form.frm_email_address.focus();
						return false;
					}
			
				}
			
			</script>

			<?php
			if ($_REQUEST['email_id']) 
			{
				$conn = $db->dbConnect();
				$query = "SELECT * FROM `mbs_emails` WHERE `email_id` = '" . (int)mysql_real_escape_string($_REQUEST['email_id']) . "' LIMIT 1";
				$result = mysql_query($query, $conn);
				
				if ($result) 
				{
					$row = mysql_fetch_assoc($result);
				}
				

								
			}
		?>
            

            <!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">System Administration</a> <span class="divider">/</span></li>			  
			  <li><a href="email_list.php">Email</a> <span class="divider">/</span></li>			  
			  <li class="active">Edit Email</li>
			</ul>
            
			<div align="right"><a class="btn" href="email_view.php?email_id=<?php echo $_REQUEST['email_id']; ?>" title="View Email"><img src="img/view_icon.png" /> View</a> &nbsp;&nbsp; <a class="btn" href="email_list.php" title="Email List"><img src="img/list_icon.png" /> List</a></div>	

            <div id="box" class="span12">
	            
            	<h2>Edit Email</h2>

				<form id="myform" class="cssform" enctype="multipart/form-data" method="post" action="email_edit_exec.php">
				<input type="hidden" name="frm_action" value="edit" />
				<input type="hidden" name="frm_email_id" value="<?php echo $row['email_id']; ?>" />
				
				<p>
				<label for="frm_store_id">Store:</label>
				<?php $arrStores = $db->getStoreData(); ?>	
			  	<select name="frm_store_id" id="frm_store_id" class="input-large">
			  		<option value="">-- Please select Store --</option>
				  	<?php
						if (is_array($arrStores) && count($arrStores) > 0)
						{									
							foreach ($arrStores as $intStoreIDVal=>$arrStoresData)
							{
								echo "\n\t<option value=\"" . $intStoreIDVal . "\"";  
								
								if ($arrStoresData['store_id']==$row['store_id'])
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
				<input type="text" name="frm_email_name" id="frm_email_name" placeholder="Please type Name" value="<?php if ($row['email_name']) { echo $row['email_name']; } ?>" maxlength="255" /> *
				<div id="status" style="margin-le	ft: 155px;"></div>
				</p>
				
				<p>
				<label for="frm_email_address">Email Address:</label>
				<input type="email" name="frm_email_address" id="frm_email_address" placeholder="Please type User's email" value="<?php if ($row['email_address']) { echo $row['email_address']; } ?>" maxlength="255" /> *
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