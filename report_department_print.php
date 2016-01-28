<?php include('inc/_include.php'); ?>
<?php require_once('lib/report.php'); ?>
<?php $report = new REPORT(); ?>

<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> Booking List | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
	<?php if(!isset($_REQUEST['print'])){?>
	<style type="text/css">
	body, #wrapper, #content-wrapper, #content, #box { background: none; }
	</style>
    <?php } ?>
</head> 

<body>

<div id="wrapper" class="container-fluid">
	
	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

    		<?php
    			if ($_REQUEST['frm_year_month']) { $intYearMonth = intval($_REQUEST['frm_year_month']); } else { $intYearMonth = date('Ym'); }
    			$intMonth = intval(substr($intYearMonth, -2, 2));
				$intYear = intval(substr($intYearMonth, 0, 4));
				$strReportTitle = HTML::getMonthName($intMonth) . " " . $intYear;
    		?>


			<div id="box" class="span12">

				<?php if ($_REQUEST['action'] == "print" && $_REQUEST['month'] && $_REQUEST['year'] )  { ?>

					<?php
						global $STR_URL;		
				
						$intMonth = $_GET['month'];
						$intYear = $_GET['year'];

						$strReportTitle = "Reports By Department for Catalogue Booked Activity <br /> " . HTML::getMonthName($intMonth) . " " . $intYear . " ";

					?>
					


							<div class="pull-left"><img src="<?php echo $STR_URL;?>img/p4l_logo_print.png" /></div>
					<div style="margin-top:20px;text-align:right;font-weight:bold;"><?php echo $strReportTitle; ?></div>

		<div style="text-align:center;"><h2 style="font-size:1.2em;font-weight:bold;">Catalogue Booked Activity by Department</h2></div>
				 
        
        <!-- In-Store ------------------------------------------------------------------------>		
		<table class="table table-bordered table-hover">
			<thead class="well">					
				<tr>
					<th style="text-align:center;width:50%;"><strong>Departments</strong></th>
                    <th style="text-align:center;"><strong>Total</strong></th>


                   



				</tr>
			  
			</thead>

			<tbody>
					
		<?php 		
			
			  $queryInStore = "SELECT * FROM `mbs_departments` ORDER BY `department_name`";
			  $resultInStore = mysql_query($queryInStore);

			  while ($rowInStore = mysql_fetch_assoc($resultInStore)) 
			  {	
		?>
						
			<tr>
				<td><div style="text-align:left;"><?php echo htmlspecialchars($rowInStore['department_name']); ?></div></td>
           			<?php
           				$department_id=$rowInStore['department_id'];
						$result_department_id = mysql_query("SELECT COUNT(*) FROM mbs_bookings_products WHERE mbs_bookings_products.booking_department_id = $department_id and DATE_FORMAT(booking_product_created_date,'%c')=$intMonth and DATE_FORMAT(booking_product_created_date,'%Y')=$intYear");
						$count_department_id = mysql_result($result_department_id, 0); 

					?>

					<td style="width:40%;"><div style="text-align:center;"><?php echo $count_department_id; ?></div></td>
					

			</tr>
           	
		
		<?php							
			}	// while ($rowStore = mysql_fetch_assoc($resultStore))
		?>
					
			</tbody>
			<tfoot>
				
			</tfoot>					
		</table>

				<?php } ?>	
            	
			</div>	<!-- end #box -->	
    
			<?php if(!isset($_REQUEST['print'])){?>			
				<script>
					$(document).ready(function () {
						window.print();
					});
				</script>
            <?php } ?>

    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->

</div> <!-- end #wrapper -->

</body>
</html>