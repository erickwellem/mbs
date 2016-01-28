<?php include('inc/_include.php'); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
    <title>Password Reset <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	
    <meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
	
	<script>
	
	
	</script>
    
</head> 

<body>

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">	

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

    		
    		<!--// Password Reset Box begin //-->
            <div class="row-fluid">
                <div class="span4"></div>
                                    
                    <div class="row-fluid">
                        <div class="span4"> 

                            <h2>Password Reset</h2>
                            <p>Please enter your password reset code sent through to your email below.</p>

                            <form name="frm_password_reset" method="post" action=""> 
                                <div class="row-fluid">
                                    <div class="span12">                                        
                                        <label for="frm_user_password_reset_code"><i class="icon-user"></i> Code</label>                                        
                                        <input class="span12" type="text" name="frm_user_password_reset_code" id="frm_user_password_reset_code" value="<?php if ($_REQUEST['c']) { echo $_REQUEST['c']; } ?>" placeholder="Please type your password reset code" /> 
                                    </div>
                                </div>

                                <div class="row-fluid">
                                    <div class="span12">                                        
                                        <label for="frm_user_password">New Password</label>                                        
                                        <input class="span8" type="password" name="frm_user_password" id="frm_user_password" value="" placeholder="Please type your new password" /> 
                                    </div>
                                </div>

                                <div class="row-fluid">
                                    <div class="span12">                                        
                                        <label for="frm_user_password_confirm">Retype New Password</label>                                        
                                        <input class="span8" type="password" name="frm_user_password_confirm" id="frm_user_password_confirm" value="" placeholder="Please re-type your new password" /> 
                                    </div>
                                </div>
                                

                                <div id="status_message"></div>

                                <div class="row-fluid">    
                                    <div class="span12"> 
                                        <input id="frm_submit" class="btn btn-inverse" type="button" name="frm_submit" value="Submit" /> &nbsp;&nbsp;&nbsp;<span><a class="btn" href="<?php echo $STR_URL; ?>">Cancel</a></span>
                                    </div> 
                                </div>    
                            </form>
                        </div>
                    </div>    
                
                <div class="span4"></div>
            </div>
            <!--// Password Reset Box end //-->        
            
            <script>
                $(document).ready(function() {


                    $('#frm_submit').click(function() {                                                

                        var str_loader = '<img src="<?php echo $STR_URL; ?>img/loading.gif" /> Checking. Please wait...';
                        $('#status_message').show(300);
                        $('#status_message').html(str_loader);
    

                        if (checkPasswordForgotForm())
                        {
                            
                            var str_code = $('#frm_user_password_reset_code').val();
                            var str_password = $('#frm_user_password').val();                    
                            var str_password_confirm = $('#frm_user_password_confirm').val();

                            var dataString = "frm_user_password_reset_code=" + str_code + "&frm_user_password=" + str_password + "&frm_user_password_confirm=" + str_password_confirm;

                            var request = $.ajax({                              
                                url: "ajax/password_reset_proc.php",
                                type: "post", 
                                data: dataString,
                                success: function(msg) {
                                    $('#status_message').show(300);
                                    $('#status_message').addClass('alert-success well');
                                    $('#status_message').html(msg);

                                    $('#frm_user_password_reset_code').val('');
                                    $('#frm_user_password').val('');
                                    $('#frm_user_password_confirm').val('');
                                    
                                }

                            });
                        }

                    });


                    function checkPasswordForgotForm()
                    {
                        
                        var str_code = $('#frm_user_password_reset_code').val();
                        var str_password = $('#frm_user_password').val();                    
                        var str_password_confirm = $('#frm_user_password_confirm').val();
                        
                        if (str_code == '')
                        {
                            
                            $('#status_message').addClass('alert-error well');
                            $('#status_message').html('Please copy paste the code from the instruction sent to your email!');                    
                            $('#frm_user_password_reset_code').focus();
                            return false;
                        
                        }

                        if (str_password == '')
                        {
                            
                            $('#status_message').addClass('alert-error well');
                            $('#status_message').html('Please type your new password!');                    
                            $('#frm_user_password').focus();
                            return false;
                        
                        }

                        if (str_password && str_password.length < 8)
                        {
                            
                            $('#status_message').addClass('alert-error well');
                            $('#status_message').html('Passwords are minimum 8 characters long!');                    
                            $('#frm_user_password').focus();
                            return false;
                        
                        }

                        if (str_password_confirm == '')
                        {
                            
                            $('#status_message').addClass('alert-error well');
                            $('#status_message').html('Please re-type your new password!');                    
                            $('#frm_user_password_confirm').focus();
                            return false;
                        
                        }


                        if (str_password !== str_password_confirm)
                        {
                            
                            $('#status_message').addClass('alert-error well');
                            $('#status_message').html('Password does not match!');                    
                            $('#frm_user_password').focus();
                            return false;
                        
                        }
                        
                        return true;

                    }

                });
            </script>
            
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
    
    
	<?php include('inc/footer.php'); ?>
    <div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->

</body>
</html>