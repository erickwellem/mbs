<?php include('inc/_include.php'); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; Logout</title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
</head> 

<body>

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">

	

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">
			
            <?php   
				if ($admin->logoutUser($_SESSION['user']['log_id']) > 0) 
				{
					$html->showAlert("You are successfully logged out! Please re-login " . $html->makeLink("here", './'), FALSE);
					$html->redirectUser ('./', 0);
	
				} 
				
				else 				
				{
					$html->showAlert("Failed to log you out. Please re-login " . $html->makeLink("here", './'), FALSE);
    				$html->redirectUser('./', 0);
				
				}

			?>
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>