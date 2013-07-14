<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
		
</head> 

<body>

	<?php include('inc/header.php'); ?>

	<div id="wrapper" class="container-fluid">

		<?php if ($admin->isLoggedIn() > 0) { include('inc/menu.php'); } ?>

		<div id="content-wrapper" class="row-fluid">
    
    		<div id="content" class="span12">
   			
   			<!-- Breadcrumb -->	
    		<ul class="breadcrumb">
			  <li><a href="home.php">Home</a> </li>			  
			</ul>	

            	<?php #print_r($_SESSION); print_r($arrPrivileges); ?>
            	<div class="alert">
  					<button type="button" class="close" data-dismiss="alert">&times;</button>
  					<strong>Note from the Administrator:</strong> 
  					<ul style="margin-left:20px;">	
  						<li>This web app is currently running in Test Mode for demo and testing</li> 
  						<li>Report Module and Documentation are still under construction</li>
  					</ul>
				</div>

            	
				<div class="container-fluid">
					
					<!-- Report Form -->					
					<div class="row-fluid span6">
						<div class="tabbable">
							<ul class="nav nav-tabs" id="formtab-nav">
								<li class="active" id="first-tab"><a href="#tab1" data-toggle="tab"><i class="icon-align-justify"></i> General Reports</a></li>
								<li id="second-tab"><a href="#tab2" data-toggle="tab"><i class="icon-align-justify"></i> Advanced</a></li>
							</ul>

							<div class="tab-content" style="min-height:400px;">
								<div class="tab-pane active" id="tab1">
									
									<fieldset>
										<div>
											<legend>Reports</legend>
										</div>	

										<ul style="margin-left:20px;">
											<li><a href="reports_general_monthly.php">Monthly General Report</a></li>
											<li><a href="reports_general_yearly.php">Yearly General Report</a></li>											
										</ul>
									</fieldset>	

								</div>	

								<div class="tab-pane" id="tab2">
									<?php include('reports_form.php'); ?>	
								</div>	
							</div>
						</div>	
							
					</div>
					<!-- Report Form -->


					<!-- Quick Booking Links -->
					<div class="row-fluid span6">
						<fieldset>
							<div>
								<legend>Quick Links</legend>	
							</div>	
							
							<a class="btn btn-popover" href="booking.php?action=add" rel="popover" data-content="Insert new Booking to the database" data-original-title="New Booking" title="New Booking"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Booking</a>
							<a class="btn btn-popover" href="booking_list.php" rel="popover" data-content="Refresh the Booking List to the latest update" data-original-title="Booking List" title="Booking List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a> 
							<a class="btn btn-popover" href="documentation_list.php#bookings" rel="popover" data-content="Look up for the Documentation about Booking module" data-original-title="Help" title="Help"><i class="icon-info-sign"></i> Help</a>
                
						</fieldset>
					</div>	
					<!-- Quick Booking Links -->

					<!-- Recent Bookings -->
					<div class="row-fluid span6">
						<fieldset>
							<div>
								<legend>Recent Bookings</legend>	
							</div>	
							<?php $arrBookings = $db->getBookingsLatest(5); ?>

							<?php $intBookingsCount = count($arrBookings); ?>
							<?php if ($intBookingsCount > 0) { ?>
							<?php foreach ($arrBookings as $intBookingID=>$arrBookingsData) { ?>
							<div class="row-fluid">
								<p><a href="booking_view.php?booking_id=<?php echo $intBookingID; ?>&action=view" title="<?php echo $arrBookingsData['booking_code']; ?> / <?php echo $arrBookingsData['booking_name']; ?>"><strong><?php echo $arrBookingsData['booking_code']; ?></strong></a> <em>by</em> <a href="supplier_view.php?supplier_id=<?php echo $arrBookingsData['booking_supplier_id']; ?>&action=view" title="<?php echo $arrBookingsData['booking_supplier_name']; ?>"><strong><?php echo $arrBookingsData['booking_supplier_name']; ?></strong></a> <em><?php echo $html->relativeDateTime($html->getTimeDifference($arrBookingsData['booking_created_date'])); ?></em></p>	
							</div>
							<?php } ?>
							<?php } else { ?>
								<p>No Bookings were found in the database yet</p>
							<?php } ?>
						</fieldset>
					</div>

					<!-- Activities Due -->
					<div class="row-fluid span6">
						<fieldset>
							<div>
								<legend>Activities Due</legend>	
							</div>	

							<?php $arrActivitiesDue = $db->getActivitiesDue(5); ?>
							<?php $intActivitiesDueCount = count($arrActivitiesDue); ?>
							<?php if ($intActivitiesDueCount > 0) { ?>
							<?php foreach ($arrActivitiesDue as $intActivitiesDueID=>$arrActivitiesDueData) { ?>
							<div class="row-fluid">
								<p><a href="booking_view.php?booking_id=<?php echo $intBookingID; ?>&action=view#id<?php echo $intActivitiesDueID; ?>" title="<?php echo $arrActivitiesDueData['booking_code']; ?> / <?php echo $arrActivitiesDueData['booking_name']; ?>"><strong><?php echo $arrActivitiesDueData['activity_name']; ?></strong></a> in <strong><?php echo $arrActivitiesDueData['booking_code']; ?></strong> <em>-</em> <strong><?php echo $arrActivitiesDueData['supplier_name']; ?></strong> <em> due <?php if ($arrActivitiesDueData['booking_activity_due_in_days'] > 0) {?>in<?php } ?> <?php echo $html->convertNoOfDays($arrActivitiesDueData['booking_activity_due_in_days']); ?></em></p>	
							</div>
							<?php } ?>
							<?php } else { ?>
								<p>No Activities due in 30 days were found in the database</p>
							<?php } ?>	
						</fieldset>
					</div>

				</div>

            
    		</div> <!-- end #content -->
    	</div> <!-- end #content-wrapper -->

    	<script>
			$(function () { 
				$('.btn-popover').popover({ 
					trigger: 'hover',
					placement: 'top'
				});
			});
		</script>
    
		<?php include('inc/footer.php'); ?>

		<div id="bottom"></div> <!-- end #bottom -->
	
	</div> <!-- end #wrapper -->


</body>
</html>