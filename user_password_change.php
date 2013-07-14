<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Ubah Password</title> 
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
			  <li><a href="#">Account</a> <span class="divider">/</span></li>			  
			  <li class="active">Change Password</li>
			</ul>

    		<script type="text/javascript">
    			
    			function validateUserPasswordChange(form)
				{
					
					//-- frm_user_current_password
					if (form.frm_user_current_password.value == '') 
					{
						alert('Please fill Current Password!')
						form.frm_user_current_password.value = ''
						form.frm_user_current_password.focus()
						return false
					}
					
					//-- frm_user_password
					if (form.frm_user_password.value == '') 
					{
						alert('Please fill Password!')
						form.frm_user_password.value = ''
						form.frm_user_password.focus()
						return false
					}
					
					//-- frm_user_password_confirm
					if (form.frm_user_password_confirm.value == '') 
					{
						alert('Please fill ulang password Anda!')
						form.frm_user_password_confirm.value = ''
						form.frm_user_password_confirm.focus()
						return false
					}
					
					// password and confirmastion dont match
					if (form.frm_user_password.value !== form.frm_user_password_confirm.value) {
						alert('Password yang Anda masukkan tidak cocok')			
						form.frm_user_password.focus()
						return false
					}		
			
				}
    		</script>
    	
    	
            <?php #print_r($_REQUEST); #print_r($_SESSION); #print_r($arrPrivileges); ?>            
	
			<div align="right">
				<a class="btn" href="user_profile_view.php?user_id=<?php echo $_SESSION['user']['id']; ?>" title="View Profile"><img src="img/view_icon.png" /> View</a>
			</div>

        	<div id="box" class="span12">
	            
	            
				<form id="frm_password_change" enctype="multipart/form-data" method="post" action="user_password_change_exec.php">
				<input type="hidden" name="frm_action" value="change_password" />
				
				<fieldset>
			    <div id="legend">
			      <legend class="">Change Password</legend>
			    </div>

				<div class="control-group">
					<!-- Username -->	
					<label class="control-label" for="frm_user_login_name">Username:</label>
					<div class="controls">
						<input type="hidden" name="frm_user_login_name" id="frm_user_login_name" value="<?php echo $_SESSION['user']['login_name']; ?>" /> 				
						<p><strong><?php echo strtolower($_SESSION['user']['login_name']); ?></strong></p>
					</div>
				</div>								

				<div class="control-group">
					<!-- Current Password -->
					<label class="control-label" for="frm_user_current_password">Current Password:</label>
					<div class="controls">
						<input type="password" name="frm_user_current_password" id="frm_user_current_password" value="<?php echo $_REQUEST['frm_user_current_password']; ?>" maxlength="255" /> *
						<p class="help-block">Please type in your current password</p>
					</div>
				</div>
				
				<div class="control-group">
					<!-- New Password -->
					<label for="frm_user_password">New Password:</label>
					<div class="controls">
						<input type="password" name="frm_user_password" id="frm_user_password" value="<?php echo $_REQUEST['frm_user_password']; ?>" maxlength="255" /> *
						<p class="help-block">Please type in your new password</p>
					</div>
				</div>		
				
				<div class="control-group">
					<!-- Re-type New Password -->
					<label for="frm_user_password_confirm">Re-type New Password:</label>
					<div class="controls">
						<input type="password" name="frm_user_password_confirm" id="frm_user_password_confirm" value="<?php echo $_REQUEST['frm_user_password_confirm']; ?>" maxlength="255" /> *
						<p class="help-block">Please re-type your new password</p>
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<input class="btn" type="submit" name="submit" value="Submit" onclick="return validateUserPasswordChange(this.form)" /> 
					</div>
				</div>
				
				</fieldset>

				</form>
			</div> <!-- end #box -->
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>