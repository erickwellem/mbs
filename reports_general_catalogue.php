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

    		<?php
    			if ($_REQUEST['frm_year_month']) { $intYearMonth = intval($_REQUEST['frm_year_month']); } else { $intYearMonth = date('Ym'); }
    			$intMonth = intval(substr($intYearMonth, -2, 2));
				$intYear = intval(substr($intYearMonth, 0, 4));
				$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear;
    		?>

			<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">Reports</a> <span class="divider">/</span></li>
			  <li><a href="#">Catalogue</a> <span class="divider">/</span></li>
			  <li><a href="#"><?php echo $strReportTitle; ?></a> <span class="divider">/</span></li>
			  <li class="active"><?php if ($_REQUEST['frm_report_by'] == 'supplier') { echo "By Supplier"; } elseif ($_REQUEST['frm_report_by'] == 'product') { echo "By Product"; } else { echo "By Activity Size"; } ?></li>
			</ul>			

			<div id="box" class="span12">

			   	<div style="text-align:right;">
			   		<a class="btn" href="<?php echo $_SERVER['REQUEST_URI']; ?>"><img src="<?php echo $STR_URL; ?>img/refresh_icon.png" /> Refresh</a>
					&nbsp;&nbsp;&nbsp;
			   		<a class="btn" href="<?php echo $STR_URL; ?>reports_print.php?action=print&frm_year_month=<?php echo $intYearMonth; ?>&frm_report_by=<?php echo urlencode($_REQUEST['frm_report_by']); ?>&frm_report_theme=<?php echo urlencode($_REQUEST['frm_report_theme']); ?>&report=<?php echo basename($_SERVER['PHP_SELF']); ?>" target="_blank"><img src="<?php echo $STR_URL; ?>img/print_icon.png" /> Print</a>
					&nbsp;&nbsp;&nbsp;
					<a class="btn ajax callbacks cboxElement" href="<?php echo $STR_URL; ?>reports_email.php?action=email&pop=yes&frm_year_month=<?php echo $intYearMonth; ?>&frm_report_by=<?php echo urlencode($_REQUEST['frm_report_by']); ?>&frm_report_theme=<?php echo urlencode($_REQUEST['frm_report_theme']); ?>&report=<?php echo basename($_SERVER['PHP_SELF']); ?>" title="Send Report to Email"><img src="<?php echo $STR_URL; ?>img/email_icon.png" /> Email</a>
				</div>


				<div class="container-fluid">
					<div class="row-fluid">
						<form id="frm_catalogue_report_by" method="get" action="">
							Report by &nbsp;
							<select id="frm_report_by" name="frm_report_by">
								<option value="activity"<?php if ($_REQUEST['frm_report_by'] && $_REQUEST['frm_report_by'] == 'activity') { echo ' selected="selected"'; } ?>>Activity Size</option>
								<option value="product"<?php if ($_REQUEST['frm_report_by'] && $_REQUEST['frm_report_by'] == 'product') { echo ' selected="selected"'; } ?>>Product</option>
								<option value="supplier"<?php if ($_REQUEST['frm_report_by'] && $_REQUEST['frm_report_by'] == 'supplier') { echo ' selected="selected"'; } ?>>Supplier</option>
							</select>
							<br /><br />
							Month &nbsp;
							<select id="frm_year_month" name="frm_year_month">
								<?php for ($year = intval(date('Y'))-3; $year < (intval(date('Y'))+5); $year++) { ?>
									<?php for ($month = 1; $month <= 12; $month++) { ?>
									<?php if (strlen($month) < 2) { $strMonthNumber = "0" . $month; } else { $strMonthNumber = $month; } ?>
										<option value="<?php echo $year . $strMonthNumber; ?>"<?php if ($intYearMonth == ($year . $strMonthNumber)) { echo " selected=\"selected\""; } ?>><?php echo HTML::getMonthName($month) . " " . $year; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
							<br /><br />
							Catalogue &nbsp;
							<select id="frm_report_theme" name="frm_report_theme">
								<option value="pharmacy4less"<?php if ($_REQUEST['frm_report_theme'] && $_REQUEST['frm_report_theme'] == 'pharmacy4less') { echo ' selected="selected"'; } ?>>Pharmacy4Less</option>
								<option value="royyoung"<?php if ($_REQUEST['frm_report_theme'] && $_REQUEST['frm_report_theme'] == 'royyoung') { echo ' selected="selected"'; } ?>>Roy Young</option>
								<option value="themed-pharmacy4less"<?php if ($_REQUEST['frm_report_theme'] && $_REQUEST['frm_report_theme'] == 'themed-pharmacy4less') { echo ' selected="selected"'; } ?>>Themed Pharmacy4Less</option>
								<option value="themed-royyoung"<?php if ($_REQUEST['frm_report_theme'] && $_REQUEST['frm_report_theme'] == 'themed-royyoung') { echo ' selected="selected"'; } ?>>Themed Roy Young</option>
								<option value="in2health"<?php if ($_REQUEST['frm_report_theme'] && $_REQUEST['frm_report_theme'] == 'in2health') { echo ' selected="selected"'; } ?>>IN 2 Health</option>
							</select>
						</form>
					</div>
				</div>					

				<?php 
					if ($_REQUEST['frm_report_by'] == 'supplier') 
					{ 
						$report->showReportGeneralCatalogueBySupplier($intYearMonth); 
					} 

					elseif ($_REQUEST['frm_report_by'] == 'product') 
					{ 
						$report->showReportGeneralCatalogueByProduct($intYearMonth); 

					} 

					else 
					{ 
						$report->showReportGeneralCatalogueByActivity($intYearMonth); 

					} 

				?>

            	<div><a class="btn" href="#content"><i class="icon-arrow-up"></i> Back to top</a></div>
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   

   <script>
   		$(document).ready(function() {
   			$('#frm_report_by,#frm_year_month,#frm_report_theme').change(function() {
   				window.location = '?frm_report_by=' + $('#frm_report_by option:selected').val() + '&frm_year_month=' + $('#frm_year_month option:selected').val() + '&frm_report_theme=' + $('#frm_report_theme option:selected').val();
   			});
   		});
   </script>
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>