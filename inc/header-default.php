<?php include('inc/_include.php'); ?>
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

<div id="wrapper" class="container-fluid">

<div id="masthead-wrapper" class="container-fluid">
	<div id="masthead" class="row-fluid">
		<div id="logo" class="span12">
			<a href="<?php if ($admin->isLoggedIn() > 0) { ?><?php echo $STR_URL; ?>home.php<?php } else { ?><?php echo $STR_URL; ?>index.php<?php } ?>"><img src="<?php echo $STR_URL; ?>img/p4l_logo.jpg" title="<?php echo stripslashes($arrSiteConfig['site_name']); ?>" style="float:left;" /> <h1><?php echo stripslashes($arrSiteConfig['site_name']); ?></h1></a>
		</div>
	</div>
</div>

<div id="content-wrapper" class="row-fluid"> 
    
    	<div id="content" class="span12">