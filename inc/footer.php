	<div id="copyright">     

    	<p><?php echo stripslashes($arrSiteConfig['site_footer_text']); ?></p>    

    </div> <!-- end #copyright -->

	

    <div class="modal hide" id="modalContainer">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

        <h3>Pharmacy4Less</h3>

      </div>

      <div class="modal-body" id="modalBody">

        

      </div>

      <div class="modal-footer">

        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

      </div>

    </div>
    <?php if(!isset($_REQUEST['pop'])):?>
    <script src="<?php echo $STR_URL; ?>js/bootstrap.min.js"></script>

    <script src="<?php echo $STR_URL; ?>js/bootstrap-collapse.js"></script> 

    <script src="<?php echo $STR_URL; ?>js/jquery-ui-1.10.0.custom.min.js"></script>   

    <script src="<?php echo $STR_URL; ?>js/dropdownmenu.js"></script>

    <script src="<?php echo $STR_URL; ?>js/jquery.gritter.js"></script>        

    <script src="<?php echo $STR_URL; ?>js/validations.js"></script>
    <?php endif;?>


	    <!-- JQuery Colorbox -->

        <script>

            $(document).ready(function() {

                /*$(".ajax").colorbox();

                $(".callbacks").colorbox({ 

                    onCleanup:function() {    				

                        <!-- location.reload(); -->

                    }

                });*/

                $(".ajax").unbind('click').on('click', function(e){

                    e.preventDefault();

                    var cur = $(this);

                    var href = cur.attr('href');

                    

                    cur.after('<img class="loadingImg" src="<?php echo $STR_URL; ?>img/loading.gif">');

                    

                    $.ajax({

                            url:href,

                            success: function(html){

                                $("#modalContainer").modal("show");
					
					$("#modalContainer").css({'width':'80%','margin-left':'-40%'});

                                $("#modalBody").html(html);

                                cur.next("img").remove();

                        }

                    });

                });
		
		$(window).on('resize', function(){
      			var win = $(this); //this = window
      			if (win.width() < 768) { 
				$("#modalContainer").css({'width':'90%','margin-left':'0'}); 
			}else{
				$("#modalContainer").css({'width':'80%','margin-left':'-40%'});
			}
		});	

            });

        </script>

    