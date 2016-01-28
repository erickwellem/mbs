	</div> <!-- end #content --> 
    </div> <!-- end #content-wrapper --> 
    
	<div id="copyright">     
    	<p><?php echo stripslashes($arrSiteConfig['site_footer_text']); ?></p>    
    </div> <!-- end #copyright -->

    <?php if(!isset($_REQUEST['pop'])):?>
    <script src="<?php echo $STR_URL; ?>js/bootstrap.min.js"></script> 
    <script src="<?php echo $STR_URL; ?>js/jquery-ui-1.10.0.custom.min.js"></script>   
    <script src="<?php echo $STR_URL; ?>js/dropdownmenu.js"></script>
    <script src="<?php echo $STR_URL; ?>js/jquery.gritter.js"></script>
    <script src="<?php echo $STR_URL; ?>js/jquery.colorbox.js"></script>
    <script src="<?php echo $STR_URL; ?>js/validations.js"></script>
    <?php endif;?>
    
	</div> <!-- end #wrapper -->

	<div id="bottom"></div> <!-- end #bottom -->

</body>
</html>    