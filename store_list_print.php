
<?php include('inc/_include.php'); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> Store List | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
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
		   		
		   		<?php if ($_REQUEST['action'] == "print") { ?>


		   		<div class="pull-left"><img src="<?php echo $STR_URL;?>img/p4l_logo_print.png" /></div>
				<div style="margin-top:20px;text-align:right;font-weight:bold;"><?php echo stripslashes($arrSiteConfig['site_name']); ?><br /><em><?php echo date("d F Y"); ?></em></div>

				<div style="text-align:center;clear:both;"><h2 style="font-size:1.2em;font-weight:bold;">Stores</h2></div>
				   		
				<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" bordercolor="#ccc">
				  <tr>
				  	<td><div style="width:80px;font-weight:bold;text-align:right;">No.</div></td>
				   	<td><div style="font-weight:bold;">Name</div></td>
				   	<td><div style="font-weight:bold;">API ACC</div></td>
				   	<td><div style="font-weight:bold;">Address</div></td>
				   	<td><div style="font-weight:bold;">Phone</div></td>
				   	<td><div style="font-weight:bold;">Fax</div></td>
				   	<td><div style="font-weight:bold;">Email</div></td>
				   	<td><div style="font-weight:bold;">Contact</div></td>
				  </tr>

		   		<?php

		   			$conn = $db->dbConnect();
			
					$query = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
					$result = mysql_query($query);
					
					if ($result) 
					{
						$intNo = 0;
						while ($row = mysql_fetch_assoc($result)) 
						{
							$intNo++;
					?>
								   			
				   			<tr>
				   				<td><div style="text-align:right;"><?php echo $intNo; ?></div></td>
				   				<td><?php echo stripslashes($row['store_name']); ?></td>
				   				<td><?php echo stripslashes($row['store_api_acc']); ?></td>
				   				<td><?php echo stripslashes($row['store_address']); ?></td>
				   				<td><?php echo stripslashes($row['store_phone']); ?></td>
				   				<td><?php echo stripslashes($row['store_fax']); ?></td>
				   				<td><?php echo stripslashes($row['store_email']); ?></td>
				   				<td><?php echo stripslashes($row['store_contact']); ?></td>
				   			</tr>

					<?php	
						} // while ($row = mysql_fetch_assoc($result)) 
					} // if ($result)
		   		?>
		   	</table>
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