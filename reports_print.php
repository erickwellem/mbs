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
<title> View Supplier | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
	<?php if(!isset($_REQUEST['print'])):?>	
	<style type="text/css">
	body, #wrapper, #content-wrapper, #content, #box { background: none; }
	</style>
	<?php endif;?>
</head> 

<body>

<div id="wrapper" class="container-fluid">
	
	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

			<div id="box" class="span12">

		   		<?php if ($_REQUEST['action'] == "print" && $_REQUEST['report']) { ?>
		   		<?php
	    			
	    			if ($_REQUEST['frm_year_month']) { $intYearMonth = intval($_REQUEST['frm_year_month']); } else { $intYearMonth = date('Ym'); }
	    			
	    			$intMonth = intval(substr($intYearMonth, -2, 2));
					$intYear = intval(substr($intYearMonth, 0, 4));
					$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear;

					$strReport = htmlspecialchars($_REQUEST['report']);

					$intStoreID = intval($_REQUEST['frm_store_id']);
					$intSupplierID = intval($_REQUEST['frm_supplier_id']);

					if ($_REQUEST['frm_year']) { $intYear = intval($_REQUEST['frm_year']); } else { $intYear = date('Y'); }    
    			?>			

				<div class="pull-left"><img src="<?php echo $STR_URL;?>img/p4l_logo_print.png" /></div>						
				<?php 	
					// In-Store All Store
					if ($strReport == 'reports_general_in_store_all.php')
					{
						$report->showReportGeneralInStoreAllStores($intYearMonth); 	
					}
					elseif ($strReport == 'reports_general_departments.php')
					{
						$report->showReportGeneralDepartments($intYearMonth); 	
					}
					elseif ($strReport == 'reports_booking_by_supplier.php')
					{
						$report->showReportBookingBySupplier($intSupplierID, $intYearMonth); 	
					}
					// In-Store Single Store by Year
					elseif ($strReport == 'reports_general_in_store_single_by_year.php')
					{
						$report->showReportGeneralInStoreByYear($intStoreID, $intYear); 
					}
					// In-Store Single by Month
					elseif ($strReport == 'reports_general_in_store_single.php')
					{
						$report->showReportGeneralInStoreSingleStores($intStoreID, $intYearMonth);
					}
					// Catalogue
					elseif ($strReport == 'reports_general_catalogue.php')
					{
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
					
					}
					// Newspaper
					elseif ($strReport == 'reports_general_newspaper.php')
					{
						if ($_REQUEST['frm_report_by'] == 'supplier') 
						{ 
							$report->showReportGeneralNewspaperBySupplier($intYearMonth); 
						} 

						elseif ($_REQUEST['frm_report_by'] == 'product') 
						{ 
							$report->showReportGeneralNewspaperByProduct($intYearMonth); 
						} 

						else 
						{ 
							$report->showReportGeneralNewspaperByActivity($intYearMonth); 
						}
					}
					// Email
					elseif ($strReport == 'reports_general_email.php')
					{
						if ($_REQUEST['frm_report_by'] == 'supplier') 
						{ 
							$report->showReportGeneralEmailBySupplier($intYearMonth); 
						} 

						elseif ($_REQUEST['frm_report_by'] == 'product') 
						{ 
							$report->showReportGeneralEmailByProduct($intYearMonth); 
						} 

						else 
						{ 
							$report->showReportGeneralEmailByActivity($intYearMonth); 
						} 

					}
					// PREP School
					elseif ($strReport == 'reports_general_prep_school.php') 
					{
						if ($_REQUEST['frm_report_by'] == 'supplier') 
						{ 
							$report->showReportGeneralPREPSchoolBySupplier($intYearMonth); 
						} 

						elseif ($_REQUEST['frm_report_by'] == 'product') 
						{ 
							$report->showReportGeneralPREPSchoolByProduct($intYearMonth); 
						} 

						else 
						{ 
							$report->showReportGeneralPREPSchoolBySupplier($intYearMonth); 
						} 
					}
					
				?>
			<?php } ?>            	
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   

   <?php if(!isset($_REQUEST['print'])){?>			
		<script>
			$(document).ready(function () {
				window.print();
			});
		</script>
    <?php } ?>


</div> <!-- end #wrapper -->


</body>
</html>