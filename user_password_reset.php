<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Reset User Password</title> 
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
    				
    				function validateUserPasswordReset(form)
					{						
						//-- frm_user_id
						if (form.frm_user_id.value == '') 
						{
							alert('Please select a Username!')
							form.frm_user_id.value = ''
							form.frm_user_id.focus()
							return false
						}
						
						//-- frm_user_password
						if (form.frm_user_password.value == '') 
						{
							alert('Please type Password!')
							form.frm_user_password.value = ''
							form.frm_user_password.focus()
							return false
						}
						
						//-- frm_user_password_confirm
						if (form.frm_user_password_confirm.value == '') 
						{
							alert('Please retype Password!')
							form.frm_user_password_confirm.value = ''
							form.frm_user_password_confirm.focus()
							return false
						}
						
						// password and confirmastion dont match
						if (form.frm_user_password.value !== form.frm_user_password_confirm.value) {
							alert('Passwords are not match')			
							form.frm_user_password.focus()
							return false
						}		
				
				}
			</script>
   			
            <?php #print_r($_REQUEST); #print_r($_SESSION); #print_r($arrPrivileges); ?>            
	        
            <!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">System Administration</a> <span class="divider">/</span></li>			  
			  <li><a href="#">User</a> <span class="divider">/</span></li>			  
			  <li class="active">Reset User Password</li>
			</ul>


        	<div id="box" class="span12">
	            <h2>Reset User Password</h2>
	            <div align="right"><a href="user_list.php" title="User List"><img src="img/list_icon.png" /> List</a></div>
				<form id="myform" class="cssform" enctype="multipart/form-data" method="post" action="user_password_reset_exec.php">
				<input type="hidden" name="frm_action" value="reset_password" />
				
				<p>
				<label for="frm_user_id">Username:</label>
				<select name="frm_user_id" id="frm_user_id">
					<option value="">-- Please choose --</option>
					<?php $arrUsers = $db->getUserData(); ?>
					<?php foreach ($arrUsers as $id => $username) { ?>
							<option value="<?php echo $id; ?>"><?php echo stripslashes($username); ?></option>				
					<?php }	?>
				</select> *
				</p>
				
				<p>
				<label for="frm_user_password">Password:</label>
				<input type="password" name="frm_user_password" id="frm_user_password" value="<?php echo $_REQUEST['frm_user_password']; ?>" maxlength="255" /> *
				</p>		
				
				<p>
				<label for="frm_user_password_confirm">Re-type Password:</label>
				<input type="password" name="frm_user_password_confirm" id="frm_user_password_confirm" value="<?php echo $_REQUEST['frm_user_password_confirm']; ?>" maxlength="255" /> *
				</p>
				
				<div style="margin-left: 150px;">
				<input type="submit" name="submit" value="Submit" onclick="return validateUserPasswordReset(this.form)" /> 
				</div>
				
				</form>
			</div> <!-- end #box -->
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>