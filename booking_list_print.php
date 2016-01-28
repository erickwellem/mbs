
<?php include('inc/_include.php'); ?>
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

			<div id="box" class="span12">
		   		
		   		<?php if ($_REQUEST['action'] == "print") { ?>


		   		<div class="pull-left"><img src="<?php echo $STR_URL;?>img/p4l_logo_print.png" /></div>
				<div style="margin-top:20px;text-align:right;font-weight:bold;"><?php echo stripslashes($arrSiteConfig['site_name']); ?><br /><em><?php echo date("d F Y"); ?></em></div>

				<div style="text-align:center;"><h2 style="font-size:1.2em;font-weight:bold;">Bookings</h2></div>
				   		
				<table class="table table-bordered" border="1" bordercolor="#333;">
				  <tr>
				  	<td><div style="width:10%;font-weight:bold;text-align:right;">No.</div></td>
				   	<td><div style="font-weight:bold;">Date</div></td>
				   	<td><div style="font-weight:bold;">Code / Name</div></td>				   	
				   	<td><div style="font-weight:bold;">Activities</div></td>				   	
				  </tr>

		   		<?php

		   			$conn = $db->dbConnect();
			
					$query = "SELECT * FROM `mbs_bookings` ORDER BY `booking_date` DESC";
					$result = mysql_query($query);
					
					if ($result) 
					{
						$intNo = 0;
						while ($row = mysql_fetch_assoc($result)) 
						{
							$intNo++;

							$arrDate = split("-", $row['booking_date']);
							$strDate = $arrDate[2] . " " . $html->getMonthName(intval($arrDate[1])) . " " . $arrDate[0];
							$strSupplierName = $db->dbIDToField('mbs_suppliers', 'supplier_id', $row['supplier_id'], 'supplier_name');
							
							// activities
							$arrActivities = $db->getActivitiesInBooking($row['booking_id']);
					?>
								   			
				   			<tr>
				   				<td valign="top"><div style="text-align:right;"><?php echo $intNo; ?></div></td>
				   				<td valign="top"><?php echo $strDate; ?></td>
				   				<td valign="top"><?php echo stripslashes($row['booking_code']); ?> / <?php echo stripslashes($row['booking_name']); ?></td>
				   				<td>
				   				<?php				
				   					
				   					if (is_array($arrActivities) && count($arrActivities) > 0)
									{
								?>										
										<ul style="margin-left:20px;">

									<?php		
										for ($i = 0; $i < count($arrActivities); $i++)
										{
											$strActivityName = $db->dbIDToField('mbs_activities', 'activity_id', $arrActivities[$i]['activity_id'], 'activity_name');
									?>
											
											<li><strong><?php echo $strActivityName; ?></strong> in <?php echo $html->getMonthName($arrActivities[$i]['booking_activity_month']) . " " . $arrActivities[$i]['booking_activity_year'];

											$arrProducts = $db->getProductsInActivity($arrActivities[$i]['booking_activity_id']);									
											
											if(strpos($strActivityName, "Gondola End") !== false && strpos($strActivityName, "Supplier Merchandised") !== false){?>
												<br><br /><em><u>Supplier Contact</u></em>;
												<?php $rowSupplierAccount = DB::getSupplierAccount($row['supplier_id']);?>
												<br>Name : <?php echo $rowSupplierAccount['supplier_contact_name'];?>
												<br>Phone : <?php echo $rowSupplierAccount['supplier_contact_phone_number'];?>
												<br>Email : <?php echo $rowSupplierAccount['supplier_contact_email'];?><br>
											<?php } 
											
											if (is_array($arrProducts) && count($arrProducts) > 0)
											{
											?>	
												<br /><em><u>Products:</u></em>
												<ul style="margin-left:20px;">

											<?php 		
												for ($j = 0; $j < count($arrProducts); $j++)
												{
											?>		<li><?php echo $arrProducts[$j]['booking_product_name']; ?></li>
											<?php } ?>
											
												</ul>
											<?php	
											}	
											?>
											</li>
										<?php } ?>

										</ul>
									<?php } ?>

									</div></td>														
									<?php

									echo $strResult;	
									?>	
				   				</td>
				   			</tr>

					<?php	
						} // while ($row = mysql_fetch_assoc($result)) 
					} // if ($result)
		   		?>
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