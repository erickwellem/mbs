<?php include('inc/_include.php'); ?>
<?php #$admin->checkUserLogin(); ?>
<?php require_once('lib/report.php'); ?>
<?php $report = new REPORT(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> Report | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
	
</head> 

<body>

	<?php include('inc/header.php'); ?>

<div id="wrapper" class="container-fluid">

	<?php if ($admin->isLoggedIn() > 0 && !$_REQUEST['pop']) { include('inc/menu.php'); } ?>

	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

			<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">Reports</a> <span class="divider">/</span></li>			  			  			  
			  <li class="active">Year <?php if ($_REQUEST['year']) { echo intval($_REQUEST['year']); } else { echo date('Y'); } ?></li>
			</ul>
				
			<div id="box" class="span12">

			   	<div style="text-align:right;">
			   		<a class="btn" href="<?php echo $_SERVER['REQUEST_URI']; ?>"><img src="<?php echo $STR_URL; ?>img/refresh_icon.png" /> Refresh</a>
					&nbsp;&nbsp;&nbsp;
			   		<a class="btn" href="<?php echo $STR_URL; ?>reports_print.php?action=print" target="_blank"><img src="<?php echo $STR_URL; ?>img/print_icon.png" /> Print</a>
					&nbsp;&nbsp;&nbsp;
					<a class="btn ajax callbacks cboxElement" href="<?php echo $STR_URL; ?>reports_email.php?action=email" title="Send Report to Email"><img src="<?php echo $STR_URL; ?>img/email_icon.png" /> Email</a>
				</div>					

				<?php if ($_REQUEST['year']) { $intYear = intval($_REQUEST['year']); } else { $intYear = date('Y'); } ?>
				<?php $report->showReportGeneralYearly($intYear); ?>			   	

            	<div><a class="btn" href="#content"><i class="icon-arrow-up"></i> Back to top</a></div>
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>