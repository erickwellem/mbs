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
			  <li class="active">Report</li>
			</ul>
				
			<div id="box" class="span12">

		   	<div style="text-align:right;">
		   		<a class="btn" href="<?php echo $_SERVER['REQUEST_URI']; ?>" target="_blank"><img src="<?php echo $STR_URL; ?>img/refresh_icon.png" /> Refresh</a>
				&nbsp;&nbsp;&nbsp;
		   		<a class="btn" href="<?php echo $STR_URL; ?>reports_print.php?action=print" target="_blank"><img src="<?php echo $STR_URL; ?>img/print_icon.png" /> Print</a>
				&nbsp;&nbsp;&nbsp;
				<a class="btn ajax callbacks cboxElement" href="<?php echo $STR_URL; ?>reports_email.php?action=email" title="Send Report to Email"><img src="<?php echo $STR_URL; ?>img/email_icon.png" /> Email</a>
			</div>	

			<div style="text-align:center;"><h2>Report</h2></div>

			<!-- Report Form -->
			<div class="accordion" id="accordion2">  
            	<div class="accordion-group">  
              		<div class="accordion-heading">  
               	 		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">  
                  		<i class="icon-list"></i> Show Form 
                		</a>  
              		</div>  
              		<div id="collapseOne" class="accordion-body collapse" style="height: 0px; ">  
	                	<div class="accordion-inner">  
	                  	
		                	<div class="row-fluid span4" style="">
								<?php include('reports_form.php'); ?>
							</div>	

	                	</div>  
              		</div>  
            	</div>
            </div>  			
			<!-- Report Form -->

		   	<?php
		   		//-- By Store
		   		if ($_REQUEST['store_id'])
		   		{
		   			//-- Products
		   			if ($_REQUEST['report_sort_by'] == 'products')
		   			{
		   				$report->showReportStoreToBookingProduct($_REQUEST['store_id'], $_REQUEST['booking_product_id']);
		   			}	
		   			//-- Suppliers
		   			elseif ($_REQUEST['report_sort_by'] == 'suppliers')
		   			{
		   				$report->showReportStoreToSupplier($_REQUEST['store_id'], $_REQUEST['supplier_id']);
		   			}
		   			//-- Spots
		   			elseif ($_REQUEST['report_sort_by'] == 'spots')
		   			{
		   				$report->showReportStoreToSize($_REQUEST['store_id'], $_REQUEST['size_id']);
		   			}
		   			//-- Dollars
		   			elseif ($_REQUEST['report_sort_by'] == 'dollars')
		   			{
		   				$report->showReportStoreToDollar($_REQUEST['store_id'], $_REQUEST['dollar_id']);
		   			}
		   			//-- Month
		   			elseif ($_REQUEST['report_sort_by'] == 'months')
		   			{
		   				$report->showReportStoreToMonth($_REQUEST['store_id'], $_REQUEST['month']);
		   			}
		   			//-- Availability by Month
		   			elseif ($_REQUEST['report_sort_by'] == 'availability-by-month')
		   			{
		   				$report->showReportStoreToAvailabilityByMonth($_REQUEST['store_id'], $_REQUEST['availability_by_month']);
		   			}
		   			//-- Availability by Year
		   			elseif ($_REQUEST['report_sort_by'] == 'availability-by-year')
		   			{
		   				$report->showReportStoreToAvailabilityByYear($_REQUEST['store_id'], $_REQUEST['availability_by_year']);
		   			}
		   			
		   		}

		   		//-- By Activity
		   		elseif ($_REQUEST['activity_id'])
		   		{
		   			//-- Products
		   			if ($_REQUEST['report_sort_by'] == 'products')
		   			{
		   				$report->showReportActivityToBookingProduct($_REQUEST['activity_id'], $_REQUEST['booking_product_id']);
		   			}	
		   			//-- Suppliers
		   			elseif ($_REQUEST['report_sort_by'] == 'supplier')
		   			{
		   				$report->showReportActivityToSupplier($_REQUEST['activity_id'], $_REQUEST['supplier_id']);
		   			}
		   			//-- Spots
		   			elseif ($_REQUEST['report_sort_by'] == 'spots')
		   			{
		   				$report->showReportActivityToSize($_REQUEST['activity_id'], $_REQUEST['size_id']);
		   			}
		   			//-- Dollars
		   			elseif ($_REQUEST['report_sort_by'] == 'dollars')
		   			{
		   				$report->showReportActivityToDollar($_REQUEST['activity_id'], $_REQUEST['dollar_id']);
		   			}
		   			//-- Month
		   			elseif ($_REQUEST['report_sort_by'] == 'month')
		   			{
		   				$report->showReportActivityToMonth($_REQUEST['activity_id'], $_REQUEST['month']);
		   			}
		   			//-- Availability by Month
		   			elseif ($_REQUEST['report_sort_by'] == 'availability-by-month')
		   			{
		   				$report->showReportActivityToAvailabilityByMonth($_REQUEST['activity_id'], $_REQUEST['availability_by_month']);
		   			}
		   			//-- Availability by Year
		   			elseif ($_REQUEST['report_sort_by'] == 'availability-by-year')
		   			{
		   				$report->showReportActivityToAvailabilityByYear($_REQUEST['activity_id'], $_REQUEST['availability_by_year']);
		   			}
		   		}


		   	?>		   	

            	<div><a class="btn" href="#content"><i class="icon-arrow-up"></i> Back to top</a></div>
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>