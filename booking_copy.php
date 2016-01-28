<?php include('inc/_include.php'); ?>
<?php //$admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> Booking List | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
	
</head> 

<body>

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">

	<?php //if ($admin->isLoggedIn() > 0 && !$_REQUEST['pop']) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

			<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>			  
			  <li><a href="booking_list.php">Bookings</a> <span class="divider">/</span></li>
			  <li class="active">Booking Copy</li>
			</ul>
				
			<div id="box" class="span12">
            
		   	<?php 
				if(isset($_REQUEST['booking_id'])){
					$intNewBooking = $db->bookingCopy($_REQUEST['booking_id']);
					$html->redirectUser("booking.php?booking_id=".$intNewBooking."&action=edit", 0);
				}else{
					if(isset($_SERVER['HTTP_REFERER'])){
						$html->redirectUser($_SERVER['HTTP_REFERER'], 0); 
					}else{
						$html->redirectUser("home.php", 0);
					}
				}
			?>
            
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>