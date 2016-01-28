<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Edit Profile</title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
    
</head> 

<body>

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">

	<?php if ($admin->isLoggedIn() > 0) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">
    	
    		<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="user_profile_view.php?user_id=<?php echo $_SESSION['user']['id']; ?>">Account</a> <span class="divider">/</span></li>			  
			  <li class="active">Edit Profile</li>
			</ul>

    		<script type="text/javascript">
    			
    			function validateUserProfileEdit(form)
				{
					
					//-- frm_user_full_name
					if (form.frm_user_full_name.value == '') 
					{
						alert('Please fill Full Name Anda!')
						form.frm_user_full_name.value = ''
						form.frm_user_full_name.focus()
						return false
					}
					
					//-- frm_user_email
					if (form.frm_user_email.value == '') 
					{
						alert('Please fill Email Anda!')
						form.frm_user_email.value = ''
						form.frm_user_email.focus()
						return false
					}
					
					if (form.frm_user_email.value.indexOf('@') < 2) 
					{
				 		alert('"' + form.frm_user_email.value + '" tidak valid. Silakan diperbaiki!')
						form.frm_user_email.focus()
						return false
					}
			
				}
    		
    		</script>
   			
   		<?php
			if ($_SESSION['user']['id']) 
			{
				$conn = $db->dbConnect();
				$query = "SELECT * FROM `users` WHERE `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
				$result = mysql_query($query, $conn);
				
				if ($result) 
				{
					$row = mysql_fetch_assoc($result);
				}
				
				if ($row['user_photo'] && $_REQUEST['action'] == 'del_image' && $_SESSION['user']['id']) 
				{
					@unlink('uploads/user/' . $row['user_photo']);				
					$queryDelImg = "UPDATE `users` SET `user_photo` = '' WHERE `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
					$resultDelImg = mysql_query($queryDelImg, $conn);
					$html->redirectUser($_SERVER['PHP_SELF'] . "?user_id=" . $_SESSION['user']['id'], 1);
				}
								
			}
		?>
                        
            
			<div align="right">
				<a class="btn ajax callsbacks cboxElement" href="user_profile_view.php?user_id=<?php echo $_SESSION['user']['id']; ?>&pop=yes" title="View Profile"><img src="img/view_icon.png" /> View</a>
			</div>
            
            <div id="box" class="span12">
	       	        	

				<form id="frm_profile_edit" class="form-horizontal" enctype="multipart/form-data" method="post" action="user_profile_edit_exec.php">
				<input type="hidden" name="frm_action" value="edit" />
				<input type="hidden" name="frm_user_id" value="<?php echo $_SESSION['user']['id']; ?>" />
				
				<fieldset>
			    <div id="legend">
			      <legend class="">Edit Profile</legend>
			    </div>

			    <div class="control-group">
			    	<label class="control-label" for="frm_user_photo">Photo</label>
			      	<div class="controls">	
					<?php 
						if ($row['user_photo']) 
						{ 
							?>
							<img src="uploads/user/<?php echo $row['user_photo']; ?>" class="img-circle" style="width:100px; margin-right:8px; border-top: 1px solid #ccc; border-right: 2px solid #999; border-bottom: 2px solid #999; border-left: 1px solid #ccc; padding: 3px;" />
							<br /><br />
							<a class="btn" href="<?php echo $_SERVER['PHP_SELF']; ?>?user_id=<?php echo $_SESSION['user']['id']; ?>&action=del_image" onclick="return confirmDeletePhoto(this.form)"><img src="img/delete_icon.png" /> Delete Photo</a>
							<?php
						}

						else 
						{ 
							echo $row['user_photo']; 
						} 
					?>
					</div>
				</div>

				<div class="control-group">
			      <!-- Username -->
			      <label class="control-label" for="frm_supplier_name">Username</label>
			      <div class="controls">			        
			        <input type="hidden" name="frm_user_login_name" id="frm_user_login_name" value="<?php echo $row['user_login_name']; ?>" /> 				
					<strong><?php echo strtolower($row['user_login_name']); ?></strong>
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- User Full Name -->
			      <label class="control-label" for="frm_user_full_name">Name</label>
			      <div class="controls">
			        <input type="text" id="frm_user_full_name" name="frm_user_full_name" placeholder="Type your full name" class="input-xlarge" value="<?php if ($_REQUEST['frm_user_full_name']) { echo stripslashes($_REQUEST['frm_user_full_name']); } elseif (!$_REQUEST['frm_user_full_name'] && $row['user_full_name']) { echo stripslashes($row['user_full_name']); } ?>" data-validation="required" /> *	        
			        <p class="help-block">Your full name</p>			        
			      </div>
			    </div>
				
				<div class="control-group">
			      <!-- Email -->
			      <label class="control-label" for="frm_user_email">Email</label>
			      <div class="controls">
			        <input type="text" id="frm_user_email" name="frm_user_email" placeholder="user@domain.tld" class="input-xlarge" value="<?php if ($_REQUEST['frm_user_email']) { echo stripslashes($_REQUEST['frm_user_email']); } elseif (!$_REQUEST['frm_user_email'] && $row['user_email']) { echo stripslashes($row['user_email']); } ?>" data-validation="required" /> *	        
			        <p class="help-block">Your valid email</p>			        
			      </div>
			    </div>
				
				<div class="control-group">
			      <!-- Description -->
			      <label class="control-label" for="frm_user_description">Description</label>
			      <div class="controls">
			        <textarea name="frm_user_description" id="frm_user_description" class="input-xlarge" rows="3"><?php if ($_REQUEST['frm_user_description']) { echo stripslashes($_REQUEST['frm_user_description']); } elseif (!$_REQUEST['frm_user_description'] && $row['user_description']) { echo stripslashes($row['user_description']); } ?></textarea> (optional)
			        <p class="help-block">The account's description</p>
			      </div>
			    </div>
				
				<div class="control-group">
			      <!-- Photo -->
			      <label class="control-label" for="frm_user_description">Photo</label>
			      <div class="controls">
			        <input type="file" name="frm_user_photo" id="frm_user_photo" value="<?php echo $_REQUEST['frm_user_photo']; ?>" /> (optional)
					<input type="hidden" name="frm_user_photo" value="<?php echo $row['user_photo']; ?>" />
			        <p class="help-block">Your photo</p>
			      </div>
			    </div>
														
				<div style="margin-left: 150px;">
				<input class="btn" type="submit" name="submit" value="Update Profile" onClick="return validateUserProfileEdit(this.form)" /> 
				</div>
				
				<br />
				<div class="control-group">				    
				    <label class="control-label"></label>
				    <div class="controls">
						<span>*) Required</span>
					</div>
				</div>
				</fieldset>
				</form>
				
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>