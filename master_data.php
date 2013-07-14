<?php include('inc/_include.php'); ?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> Master Data | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
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
			  <li><a href="home.php">Home</a> <span class="divider">/</span></li>			  
			  <li class="active">Master Data</li>
			</ul>

			<div id="box" class="span12">
		   			   	
				<h2>Master Data</h2>
		   		<ul id="master_data_list">
		   			<li><a href="activity_list.php" data-toggle="tooltip" title="Activities"><strong>Activities Price</strong></a>
		   				<ul>
		   					<li><a href="activity.php?action=add">New Activity Price</a></li>	
		   					<li><a href="activity_list.php">Activity Price List</a></li>	
		   				</ul>	
		   			</li>		   			
		   			<li><a href="size_list.php" rel="tooltip" title="Sizes"><strong>Sizes</strong></a>
		   				<ul>
		   					<li><a href="size.php?action=add">New Size</strong></a></li>	
		   					<li><a href="size_list.php">Size List</a></li>	
		   				</ul>		
		   			</li>	
		   			<li><a href="store_list.php" rel="tooltip" title="Stores"><strong>Stores</strong></a>
		   				<ul>
		   					<li><a href="store.php?action=add">New Store</a></li>	
		   					<li><a href="store_list.php">Store List</a></li>	
		   				</ul>	
		   			</li>	
		   			<li><a href="supplier_list.php" rel="tooltip" title="Supplier"><strong>Suppliers</strong></a>
		   				<ul>
		   					<li><a href="supplier.php?action=add">New Supplier</a></li>	
		   					<li><a href="supplier_list.php">Supplier List</a></li>	
		   				</ul>
		   			</li>	
		   		</ul>


		   	<script src="<?php echo $STR_URL; ?>js/bootstrap-tooltip.js"></script>
			<script>
				$(document).ready(function () {
					$("#master_data_list").tooltip({
                  				'placement': 'top', 
                	});
				});
			</script>
            
			</div>	<!-- end #box -->	
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->
   
    
	<?php include('inc/footer.php'); ?>

<div id="bottom"></div> <!-- end #bottom -->
</div> <!-- end #wrapper -->


</body>
</html>