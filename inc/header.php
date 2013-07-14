<div id="masthead-wrapper" class="container-fluid">
	<div id="masthead" class="row-fluid">
		<div id="logo" class="span12">
			<a href="<?php if ($admin->isLoggedIn() > 0) { ?><?php echo $STR_URL; ?>home.php<?php } else { ?><?php echo $STR_URL; ?>index.php<?php } ?>"><img src="<?php echo $STR_URL; ?>img/p4l_logo.jpg" title="<?php echo stripslashes($arrSiteConfig['site_name']); ?>" style="float:left;" /> <h1><?php echo stripslashes($arrSiteConfig['site_name']); ?></h1></a>
		</div>
	</div>
</div>
