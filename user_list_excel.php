<?php include('inc/_include.php'); ?>
<?php 
			if ($_REQUEST['xls_file'])
			{
				header("Content-type: application/octet-stream"); 
				header("Content-Disposition: attachment; filename=" . $_REQUEST['xls_file']); 
				header("Pragma: no-cache"); 
				header("Expires: 0"); 

			}
?>
<?php $admin->checkUserLogin(); ?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title><?php echo stripslashes($arrSiteConfig['site_name']); ?> &raquo; User List</title> 
</head> 

<body>

		   <?php $html->listUser(); ?>

</body>
</html>