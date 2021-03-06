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
    			
    			$strReportTitle = "";

    			if ($_REQUEST['frm_store_id'])
				{
					$strStoreName = DB::dbIDToField('mbs_stores', 'store_id', intval($_REQUEST['frm_store_id']), 'store_name');
					$strReportTitle .= $strStoreName . " Store ";						
				}

    			if ($_REQUEST['frm_year']) { $intYear = intval($_REQUEST['frm_year']); } else { $intYear = date('Y'); }    			
				$strReportTitle .= $intYear;

				$intStoreID = intval($_REQUEST['frm_store_id']);
    		?>

			<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>
			  <li><a href="#">Reports</a> <span class="divider">/</span></li>
			  <li><a href="#">In-Store</a> <span class="divider">/</span></li>			  
			  <li class="active"><?php echo $strReportTitle; ?></li>			  
			</ul>			

			<div id="box" class="span12">

			   	<div style="text-align:right;">
			   		<a class="btn" href="<?php echo $_SERVER['REQUEST_URI']; ?>"><img src="<?php echo $STR_URL; ?>img/refresh_icon.png" /> Refresh</a>
					&nbsp;&nbsp;&nbsp;
			   		<a class="btn" href="<?php echo $STR_URL; ?>reports_print.php?action=print&frm_store_id=<?php echo $intStoreID; ?>&frm_year=<?php echo $intYear; ?>&report=<?php echo basename($_SERVER['PHP_SELF']); ?>" target="_blank"><img src="<?php echo $STR_URL; ?>img/print_icon.png" /> Print</a>
					&nbsp;&nbsp;&nbsp;
					<a class="btn ajax callbacks cboxElement" href="<?php echo $STR_URL; ?>reports_email.php?action=email&pop=yes&frm_store_id=<?php echo $intStoreID; ?>&frm_year=<?php echo $intYear; ?>&report=<?php echo basename($_SERVER['PHP_SELF']); ?>" title="Send Report to Email"><img src="<?php echo $STR_URL; ?>img/email_icon.png" /> Email</a>
				</div>


				<div class="container-fluid">
					<div class="row-fluid">
						
				      	<!-- Store -->
				      	Store: &nbsp;
				      	<?php $arrStores = $db->getStoreData(); ?>								
					  	<select name="frm_store_id" id="frm_store_id" class="input-large">
					  		<option value="">-- Please select Store --</option>
						  	<?php
								if (is_array($arrStores) && count($arrStores) > 0)
								{									
									foreach ($arrStores as $intStoreIDVal=>$arrStoresData)
									{
										echo "\n\t<option value=\"" . $intStoreIDVal . "\"";  
										
										if ($_REQUEST['frm_store_id'] && $_REQUEST['frm_store_id'] == $intStoreIDVal)
										{
											echo " selected=\"selected\"";
										}
										
										echo ">" . stripslashes($arrStoresData['store_name']) . "</option>";
									}
								}
						   	?>
					  	</select>
							      
					</div>
				</div>	

				<div class="container-fluid">
					<div class="row-fluid">
						
						Year: &nbsp;
						<select id="frm_year" name="frm_year">
							<?php for ($year = intval(date('Y'))-3; $year < (intval(date('Y'))+5); $year++) { ?>									
								<option value="<?php echo $year; ?>"<?php if ($intYear == ($year)) { echo " selected=\"selected\""; } ?>><?php echo $year; ?></option>
							<?php } ?>
						</select>
						
					</div>
				</div>					

				<?php 					 
					
					if ($intStoreID && $intYear)
					{
						$report->showReportGeneralInStoreByYear($intStoreID, $intYear); 
					}					 
					
					else
					{
						?>
						<div style="clear:both;text-align:center;"><h2>In-Store Activities by Year (Single Store)</h3></div>
						<div style="text-align:center;">Please select Store and Year</div>
						<?php
					}

				?>

            	<div><a class="btn" href="#content"><i class="icon-arrow-up"></i> Back to top</a></div>
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   

   <script>
   		$(document).ready(function() {   			
   			$('#frm_store_id').change(function() {
				window.location = '?frm_store_id=' + $('#frm_store_id option:selected').val() + '&frm_year=' + $('#frm_year option:selected').val();
			});
   			$('#frm_year').change(function() {
				window.location = '?frm_store_id=' + $('#frm_store_id option:selected').val() + '&frm_year=' + $('#frm_year option:selected').val();
			});
   		});
   </script>
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>