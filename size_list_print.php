
<?php include('inc/_include.php'); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> Size List | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
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
		   		
		   		<?php if ($_REQUEST['action'] == "print") { ?>


		   		<div class="pull-left"><img src="<?php echo $STR_URL;?>img/p4l_logo_print.png" /></div>
				<div style="margin-top:20px;text-align:right;font-weight:bold;"><?php echo stripslashes($arrSiteConfig['site_name']); ?><br /><em><?php echo date("d F Y"); ?></em></div>

				<div style="text-align:center;clear:both;"><h2 style="font-size:1.2em;font-weight:bold;">Size</h2></div>
				   		
				<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" bordercolor="#333;">
				  <tr>
				  	<td><div style="width:80px;font-weight:bold;text-align:right;">No.</div></td>
				   	<td><div style="font-weight:bold;">Name</div></td>
				   	<td><div style="font-weight:bold;">Description</div></td>				   	
				  </tr>

		   		<?php

		   			$conn = $db->dbConnect();
			
					$query = "SELECT * FROM `mbs_sizes` ORDER BY `size_name`";
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
				   				<td><?php echo stripslashes($row['size_name']); ?></td>
				   				<td><?php echo stripslashes($row['size_description']); ?></td>				   				
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