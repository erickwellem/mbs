<?php include('inc/_include.php'); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
    <title><?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	
    <meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
	
	<script>
	
	   function init() 
       { 
    	   if (!document.getElementById) return false; 
    	   var f = document.getElementById("auto_off"); 
    	   var u = f.elements[0]; 
    	   f.setAttribute("autocomplete", "off"); 
    	   u.focus(); 
	   } 
	
	</script>
    
</head> 

<body onload="init();">

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">

	<?php if ($admin->isLoggedIn() > 0) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">
   
    	
    		<?php 

            if ($admin->isLoggedIn() > 0) 
            { 
                $html->redirectUser('home.php', 0); 
            } 

            else 

            { 

            ?>
            
    		
    		<!--// Login Box begin //-->
            <div class="row-fluid">
                <div class="span4"></div>
                <div id="login-box" class="span4">
                    <div class="row-fluid">
                        <div class="span12"> 
                            <form name="login" method="post" action="authorize.php" id="auto_off"> 
                                <div class="row-fluid">
                                    <div class="span12">                                        
                                        <label for="frm_user_login"><i class="icon-user"></i> Username</label>                                        
                                        <input class="span12" type="text" name="frm_user_login" id="frm_user_login" value="<?php if ($_REQUEST['frm_user_login']) { echo $_REQUEST['frm_user_login']; } ?>" placeholder="Please type your username" /> 
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12"> 
                                        <label for="frm_user_password"><i class="icon-eye-close"></i> Password</label>
                                        <input class="span12" type="password" name="frm_user_password" id="frm_user_password" placeholder="Please type your password" value="" /> 
                                    </div>
                                </div>
                                <div class="row-fluid">    
                                    <div class="span12"> 
                                        <input class="btn small" type="submit" name="frm_submit" value="Login" onclick="return validateLoginBox(this.form)" /> 
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <!--<p><a href="password-forgot.php">Forgot your password?</a></p>-->
                                            </div>
                                        </div>
                                    </div> 
                                </div>    
                            </form>
                        </div>
                    </div>    
                </div> <!-- end #login-box -->
                <div class="span4"></div>
            </div>
            <!--// Login Box end //-->        
            
            <script>
                $(document).ready(function() {
                    $('#frm_user_login').keyup(function() {
                        this.value = this.value.toLowerCase();
                        this.value = this.value.trim();
                    });        
                });
            </script>


            <?php } ?>
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>
    <div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>