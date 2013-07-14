    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta http-equiv="Content-Language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 	
    
    <link rel="shortcut icon" href="<?php echo $STR_URL; ?>favicon.ico">
    
    <!-- CSS -->
    <link href="<?php echo $STR_URL; ?>css/bootstrap.min.css" rel="stylesheet" />    
    <link href="<?php echo $STR_URL; ?>css/base.css" rel="stylesheet" class="links-css" /> 	
	<link href="<?php echo $STR_URL; ?>css/bootstrap-responsive.css" rel="stylesheet" />         
    <link href="<?php echo $STR_URL; ?>css/media-fluid.css" rel="stylesheet" />    
    <link href="<?php echo $STR_URL; ?>css/datepicker.css" rel="stylesheet" />
    <link href="<?php echo $STR_URL; ?>css/jquery.jqplot.css" rel="stylesheet" /> 
    <link href="<?php echo $STR_URL; ?>css/style.css" rel="stylesheet" media="screen" />   

	<link href="<?php echo $STR_URL; ?>css/smoothness/jquery-ui-1.10.0.custom.min.css" rel="stylesheet" media="screen" />    
    <link href="<?php echo $STR_URL; ?>css/colorbox.css" rel="stylesheet" />
    
    <!--[if lte IE 7]>
	<style type="text/css">
		html .jqueryslidemenu{height: 1%;}     
	</style>
	<![endif]-->
    <!--[if lte IE 8]>
	<style type="text/css">
		html .jqueryslidemenu{height: 1%;}     
	</style>
	<![endif]-->
	
	<!--[if lt IE 9]>
    	<script src="<?php echo $STR_URL; ?>js/html5.js"></script>
    <![endif]-->

    <!-- JQuery -->
    <script src="<?php echo $STR_URL; ?>js/jquery-1.9.0.min.js"></script>
    <!-- Colorbox -->
    <script src="<?php echo $STR_URL; ?>js/jquery.colorbox.js"></script>

    <?php 
    
    if (class_exists('ADMIN')) { $admin = new ADMIN(); } 
    if (class_exists('DB')) { $db = new DB(); }     
    $arrSiteConfig = $db->getSiteConfig(); 
    
    ?>
