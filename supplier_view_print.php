<?php include('inc/_include.php'); ?>
<?php 
		if ($_REQUEST['supplier_id'] && $_REQUEST['action'] == "print")
		{
			$conn = $db->dbConnect();
			
			$query = "SELECT * FROM `mbs_suppliers` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' LIMIT 1";
			$result = mysql_query($query);
			
			if ($result) 
			{
				$row = mysql_fetch_assoc($result);
				
				// get account contacts
				$strQuery2 = "SELECT * FROM `mbs_suppliers_account_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' ORDER BY `supplier_account_id`";
				$result2 = mysql_query($strQuery2);
				
				if ($result2)
				{
					$arrAccounts = array();
					while ($row2 = mysql_fetch_assoc($result2))
					{
						$arrAccounts[] = $row2;
					}
				}


				// get marketing contacts
				$strQuery3 = "SELECT * FROM `mbs_suppliers_marketing_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' ORDER BY `supplier_contact_id`";
				$result3 = mysql_query($strQuery3);
				
				if ($result3)
				{
					$arrContacts = array();
					while ($row3 = mysql_fetch_assoc($result3))
					{
						$arrContacts[] = $row3;
					}
				}


				// get territory contacts
				$strQuery4 = "SELECT * FROM `mbs_suppliers_territory_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' ORDER BY `supplier_territory_id`";
				$result4 = mysql_query($strQuery4);
				
				if ($result4)
				{
					$arrTerritory = array();
					while ($row4 = mysql_fetch_assoc($result4))
					{
						$arrTerritory[] = $row4;
					}
				}

			} // if ($result)	

		} // if ($_REQUEST['supplier_id'] && $_REQUEST['action'] == "print")
									

?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> View Supplier | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>
	<style type="text/css">
	body, #wrapper, #content-wrapper, #content, #box { background: none; }
	</style>
</head> 

<body>

<div id="wrapper" class="container-fluid">
	
	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

			<div id="box" class="span12">
		   		
		   		<?php if ($_REQUEST['supplier_id'] && $_REQUEST['action'] == "print") { ?>

		   		<div class="pull-left"><img src="<?php echo $STR_URL;?>img/p4l_logo_print.png" /></div>
		   		<div style="margin-top:20px;text-align:right;font-weight:bold;"><?php echo stripslashes($arrSiteConfig['site_name']); ?><br /><em><?php echo date("d F Y"); ?></em></div>

		   		<h2 style="font-size:1.2em;font-weight:bold;">Suppliers &raquo; <?php echo stripslashes($row['supplier_name']); ?></h2>
		   		
		   		<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" bordercolor="#333;">
		   			<tr>
		   				<td colspan="2"><div style="font-weight:bold;">Data</div></td>
		   			</tr>		   			
		   			<tr>
		   				<td style="width:150px;">Name</td>
		   				<td><?php echo stripslashes($row['supplier_name']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Postal Address</td>
		   				<td><?php echo stripslashes($row['supplier_postal_address']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Contact Number</td>
		   				<td><?php echo stripslashes($row['supplier_phone_number']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Email</td>
		   				<td><?php echo stripslashes($row['supplier_email']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Ref. No</td>
		   				<td><?php echo stripslashes($row['supplier_po_ref_number']); ?></td>
		   			</tr>		   						   			
		   		</table>

		   		<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" bordercolor="#ccc">
		   			<tr>
		   				<td colspan="2"><div style="font-weight:bold;">Marketing Contact</div></td>
		   			</tr>		   					   			
		   			<tr>
		   				<td style="width:150px;">Name</td>
		   				<td><?php echo stripslashes($arrContacts[0]['supplier_contact_name']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Title/Postion</td>
		   				<td><?php echo stripslashes($arrContacts[0]['supplier_contact_position']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Postal Address (Billing Address)</td>
		   				<td><?php echo stripslashes($arrContacts[0]['supplier_contact_postal_address']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Contact Number</td>
		   				<td><?php echo stripslashes($arrContacts[0]['supplier_contact_phone_number']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Mobile Number</td>
		   				<td><?php echo stripslashes($arrContacts[0]['supplier_contact_mobile_number']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Email</td>
		   				<td><?php echo stripslashes($arrContacts[0]['supplier_contact_email']); ?></td>
		   			</tr>		   					   						   		
		   		</table>

		   		<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" bordercolor="#ccc">
		   			<tr>
		   				<td colspan="2"><div style="font-weight:bold;">Account Contact</div></td>
		   			</tr>		   					   			
		   			<tr>
		   				<td style="width:150px;">Name</td>
		   				<td><?php echo stripslashes($arrAccounts[0]['supplier_account_name']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Postal Address</td>
		   				<td><?php echo stripslashes($arrAccounts[0]['supplier_account_postal_address']); ?></td>
		   			</tr>
		   			<tr>
		   				<td>Contact Number</td>
		   				<td><?php echo stripslashes($arrAccounts[0]['supplier_account_phone_number']); ?></td>
		   			</tr>		   			
		   			<tr>
		   				<td>Email</td>
		   				<td><?php echo stripslashes($arrAccounts[0]['supplier_account_email']); ?></td>
		   			</tr>		   					   						   		
		   		</table>

		   		<?php for ($i = 0; $i < 6; $i++) { ?>
		   			<?php if ($arrTerritory[$i]['supplier_territory_name']) { ?>

			   		<table class="table table-bordered">
			   			<tr>
			   				<td colspan="2"><div style="font-weight:bold;">Territory Contact</div></td>
			   			</tr>		   					   			
			   			<tr>
			   				<td style="width:150px;">Area</td>
			   				<td><?php echo stripslashes($arrTerritory[$i]['territory_name']); ?></td>
			   			</tr>
			   			<tr>
			   				<td>Name</td>
			   				<td><?php echo stripslashes($arrTerritory[$i]['supplier_territory_name']); ?></td>
			   			</tr>
			   			<tr>
			   				<td>Contact Number</td>
			   				<td><?php echo stripslashes($arrTerritory[$i]['supplier_territory_phone_number']); ?></td>
			   			</tr>		   					   				   					   						   		
			   		</table>

		   			<?php } ?>
		   		<?php } ?>

		   	<?php } ?>	

			</div>	<!-- end #box -->	

			<script>
			$(document).ready(function () {
				window.print();
			});
			</script>	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->   

</div> <!-- end #wrapper -->

</body>
</html>