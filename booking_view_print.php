
<?php include('inc/_include.php'); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> Booking View | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
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
		   		
		   		<?php if ($_REQUEST['action'] == "print" && $_REQUEST['booking_id']) { ?>


			   		   		
				<?php

					$db->dbConnect();
		
					$query = "SELECT * FROM `mbs_bookings` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' LIMIT 1";
					$result = mysql_query($query);
					
					if ($result) 
					{
						$row = mysql_fetch_assoc($result);

						// get some variables
						$intBookingYear = substr($row['booking_date'], 0, 4);
									
						// get supplier data
						$strQuerySupplier = "SELECT * FROM `mbs_suppliers` WHERE `supplier_id` = '" . mysql_real_escape_string($row['supplier_id']) . "'";
						$resultSupplier = mysql_query($strQuerySupplier);
						
						if ($resultSupplier)
						{				
							$rowSupplier = mysql_fetch_assoc($resultSupplier);	

							// get marketing contact
							$strQueryContact = "SELECT * FROM `mbs_suppliers_marketing_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($rowSupplier['supplier_id']) . "'";
							$resultContact = mysql_query($strQueryContact);
						
							if ($resultContact)
							{
								$rowContact = mysql_fetch_assoc($resultContact);				
							}			
						}


						// Get the booking activity
						$queryBookingActivity = "SELECT * FROM `mbs_bookings_activities` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' ORDER BY `booking_activity_month`";
						$resultBookingActivity = mysql_query($queryBookingActivity);

						$arrBookingActivityData = array();
						while ($rowBookingActivity = mysql_fetch_assoc($resultBookingActivity))
						{
							$arrBookingActivityData[] = $rowBookingActivity;
						}

						// Get the booking activity amount
						$queryBookingActivityAmount = "SELECT COUNT(*) FROM `mbs_bookings_activities` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "'";
						$resultBookingActivityAmount = mysql_query($queryBookingActivityAmount);
						$rowBookingActivityAmount = mysql_fetch_row($resultBookingActivityAmount);				
						$intBookingActivityAmount = $rowBookingActivityAmount[0];
					
				?>

				<div class="pull-left"><img src="<?php echo $STR_URL;?>img/p4l_logo_print.png" /></div>
				<div style="margin-top:20px;text-align:right;font-weight:bold;"><?php echo stripslashes($arrSiteConfig['site_name']); ?><br /><!--<em><?php echo date("d F Y"); ?></em>--></div>

				<div style="text-align:center;clear:both;"><h2 style="font-size:1.5em;font-weight:bold;">Promotional Activity <?php echo $intBookingYear; ?></h2></div>
					

				<table style="padding:10px;width:100%;">
				  <tr>
				  	<td><div style="text-align:left;"><p><strong>Supplier Name: <?php echo $rowSupplier['supplier_name']; ?></strong></p></div><td>
					<td><div style="text-align:right;"><p><strong>Date: <?php echo HTML::convertDateTime($row['booking_date']); ?></strong></p></div></td>
				  </tr>
				</table>

				<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" bordercolor="#333;">			  		  
					<thead class="well">
					<tr>
						<th style="text-align:center;"><strong>Month/Year</strong></th>
					  	<th style="text-align:center;"><strong>Promotional Agreement</strong></th>
					  	<th style="text-align:center;"><strong>Price</strong></th>					  	
					</tr>			  
					</thead>

					<tbody>
					<?php if ($intBookingActivityAmount > 0) { ?>
					<?php for ($i = 0; $i < count($arrBookingActivityData); $i++) { ?>
					<?php if ($arrBookingActivityData[$i]['store_id']) { $arrStoreID = explode(',', $arrBookingActivityData[$i]['store_id']); $intStoreCount = count($arrStoreID); } ?>
					<?php if ($arrBookingActivityData[$i]['store_id']) { $strPrice = $arrBookingActivityData[$i]['booking_activity_price']*$intStoreCount; } else { $strPrice = $arrBookingActivityData[$i]['booking_activity_price']; } ?>
					<tr>
					  	<td valign="top"><?php echo $html->getMonthName($arrBookingActivityData[$i]['booking_activity_month']); ?> <?php echo stripslashes($arrBookingActivityData[$i]['booking_activity_year']); ?></td>
					  	<?php if(isset($_REQUEST['for_email'])):?>
					  		<td valign="top"><?php echo preg_replace("/Normal Retail Price: ([\w\D]*)/", "", stripslashes($arrBookingActivityData[$i]['booking_activity_description'])); ?></td>
					  	<?php else:?>
					  		<td valign="top"><?php echo stripslashes($arrBookingActivityData[$i]['booking_activity_description']); ?></td>
					  	<?php endif;?>
					  	<td valign="top" style="width:20%;"><div style="text-align:right;">$<?php echo number_format($strPrice, 2); ?></div></td>					  	
					</tr>
					<?php $intTotalAmount += $strPrice; ?>
					<?php } ?>	
					<?php } else { ?>
					<tr>
						<td colspan="4"><div align="center">No Promo Activity yet.</div></td>
					</tr>	
					<?php } ?>
					<tr>
						<td colspan="2"><div style="text-align:right;"><strong>Total</strong></div></td>
						<td><div style="text-align:right;"><strong>$<?php echo number_format($intTotalAmount, 2); ?></strong></div></td>						
					</tr>	

					</tbody>
				</table>


				<table style="padding:10px;float:right;">
				  <tr>
				  	<td style="padding:5px;"><div style="text-align:right;">Purchases in <?php echo intval($intBookingYear) - 1; ?>:</div></td>
				  	<td style="border-bottom:1px solid #eee;"><div style="text-align:right;"><?php echo $rowSupplier['supplier_last_year_purchase']; ?></div></td>
				  </tr>
				  <tr>
				  	<td style="padding:5px;"><div style="text-align:right;"><?php echo intval($intBookingYear); ?> Target:</div></td>
				  	<td style="border-bottom:1px solid #eee;"><div style="text-align:right;"><?php echo stripslashes($rowSupplier['supplier_target']); ?></div></td>
				  </tr>
				  <tr>
				  	<td style="padding:5px;"><div style="text-align:right;">Growth Incentives:</div></td>
				  	<td style="border-bottom:1px solid #eee;"><div style="text-align:right;"><?php echo stripslashes($rowSupplier['supplier_growth_incentives']); ?></div></td>
				  </tr>
				  <tr>
				  	<td style="padding:5px;"><div style="text-align:right;">Co-op Budget:</div></td>
				  	<td style="border-bottom:1px solid #eee;"><div style="text-align:right;"><?php echo stripslashes($rowSupplier['supplier_budget']); ?></div></td>
				  </tr>
				</table>

				<table style="padding:10px;clear:both;width:100%;">				  
				  <tr style="height:100px;">
				  	<td style="padding:10px;width:10%;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  </tr>
				  <tr>
				  	<td style="padding:10px;width:10%;"><div style="text-align:right;">Signed:</div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  </tr>
				  <tr>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="border-bottom:1px solid #000;width:30%;"><div style="text-align:right;font-weight:bold;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="border-bottom:1px solid #000;width:30%;"><div style="text-align:right;font-weight:bold;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  </tr>
				  <tr>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:center;"><p style="color:#999;font-size:0.8em;">For &amp; on behalf of supplier</p></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:center;"><p style="color:#999;font-size:0.8em;">For &amp; on behalf of Pharmacy 4 Less</p></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  </tr>
				  <tr style="height:20px;">
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;font-weight:bold;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;font-weight:bold;"></div></td>
				  	<td style="padding:10px;"><div style="text-align:right;"></div></td>
				  </tr>
				</table>
				
				<table style="padding:10px;clear:both;width:100%;">  
				  <tr>
				  	<td><div style="text-align:right;"><p>Name :</p></div></td>
				  	<td><div style="text-align:left;font-weight:bold;border-bottom:1px solid #eee;"><p><?php echo $rowContact['supplier_contact_name']; ?></p></div></td>
				  	<td><div style="text-align:right;"><p>Name : </p></div></td>
				  	<td><div style="text-align:left;font-weight:bold;border-bottom:1px solid #eee;"><p><?php echo stripslashes($arrSiteConfig['mbs_p4l_on_behalf_name']) ?></p></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  </tr>
				  <tr>
				  	<td><div style="text-align:right;"><p>Title :</p></div></td>
				  	<td><div style="text-align:left;border-bottom:1px solid #eee;"><p><?php echo $rowContact['supplier_contact_position']; ?></p></div></td>
				  	<td><div style="text-align:right;"><p>Title : </p></div></td>
				  	<td><div style="text-align:left;border-bottom:1px solid #eee;"><p><?php echo stripslashes($arrSiteConfig['mbs_p4l_on_behalf_position']) ?></p></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  </tr>
				  <tr>
				  	<td><div style="text-align:right;"><p>Date :</p></div></td>
				  	<td><div style="text-align:left;border-bottom:1px solid #eee;"><p><?php echo HTML::convertDateTime($row['booking_date']); ?></p></div></td>
				  	<td><div style="text-align:right;"><p>Date : </p></div></td>
				  	<td><div style="text-align:left;border-bottom:1px solid #eee;"><p><?php echo HTML::convertDateTime($row['booking_date']); ?></p></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  </tr>
				  <tr>
				  	<td><div style="text-align:right;"><p>Phone :</p></div></td>
				  	<td><div style="text-align:left;border-bottom:1px solid #eee;"><p><?php echo $rowContact['supplier_contact_phone_number']; ?></p></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  	<td><div style="text-align:left;"></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  </tr>
				  <tr>
				  	<td><div style="text-align:right;"><p>Mobile :</p></div></td>
				  	<td><div style="text-align:left;border-bottom:1px solid #eee;"><p><?php echo $rowContact['supplier_contact_mobile_number']; ?></p></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  	<td><div style="text-align:left;"></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  </tr>
				  <tr>
				  	<td><div style="text-align:right;"><p>Billing Address :</p></div></td>
				  	<td><div style="text-align:left;border-bottom:1px solid #eee;"><p><?php echo $rowContact['supplier_contact_postal_address']; ?></p></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  	<td><div style="text-align:left;"></div></td>
				  	<td><div style="text-align:right;"></div></td>
				  </tr>	
				</table>	
				

					<?php } ?>
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