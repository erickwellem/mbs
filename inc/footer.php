	<div id="copyright">     
    	<p><?php echo stripslashes($arrSiteConfig['site_footer_text']); ?></p>    
    </div> <!-- end #copyright -->

    
    <script src="<?php echo $STR_URL; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo $STR_URL; ?>js/bootstrap-collapse.js"></script> 
    <script src="<?php echo $STR_URL; ?>js/jquery-ui-1.10.0.custom.min.js"></script>   
    <script src="<?php echo $STR_URL; ?>js/dropdownmenu.js"></script>
    <script src="<?php echo $STR_URL; ?>js/jquery.gritter.js"></script>        
    <script src="<?php echo $STR_URL; ?>js/validations.js"></script>

    <!-- JQuery Colorbox -->
    <script>
    	$(document).ready(function() {
    		$(".ajax").colorbox();
    		$(".callbacks").colorbox({ 
    			onCleanup:function() {    				
    				<!-- location.reload(); -->
    			}
    		});	
    	});
    </script>   	