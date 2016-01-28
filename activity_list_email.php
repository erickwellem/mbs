<?php include('inc/_include.php'); ?>
<?php 
			
		// get current user's email
		$strEmail = $db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_email');
?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title>Activity List | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>

</head> 

<body>

<div id="wrapper" class="container-fluid">
	
	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

			<div id="box" class="span12">

		   		<div class="container-fluid">
					<div class="row-fluid">
						<ul class="nav nav-tabs" style="background:none">
                          <li class="active"><a href="#sendtoemail" data-toggle="tab">Send to Email</a></li>
                          <li><a href="#preview-sendtoemail" data-toggle="tab">Preview</a></li>
                        </ul>
                         
                        <div class="tab-content">
                          <div class="tab-pane active" id="sendtoemail">
								<form id="frm_email" class="form-horizontal" action="" method="post">	
									<fieldset>
										<div id="legend">
											<legend class="">Send to Email</legend>
										</div>
										
										<input type="hidden" name="action" id="action" value="email_list" />
												    
										<!-- Recipient -->										    
										<div class="control-group">
											<label class="control-label" for="frm_email_to">To</label>
											<div class="controls">
												<input type="text" id="frm_email_to" name="frm_email_to" placeholder="Type recipient's email" class="input-xlarge" value="<?php if ($_REQUEST['frm_email_to']) { echo stripslashes($_REQUEST['frm_email_to']); } ?>" data-validation="validate_email" /> * 
												<p class="help-block"></p>
											</div>
										</div>

										<!-- Additional Message -->
										<div class="control-group">
											<label class="control-label" for="frm_message">Additional message</label>
											<div class="controls">
												<textarea id="frm_message" name="frm_message" placeholder="Type additional message to the recipient" class="input-xlarge" value="<?php if ($_REQUEST['frm_message']) { echo stripslashes($_REQUEST['frm_message']); } ?>" row="3" data-validation="" /></textarea> (optional) 
												<p class="help-block"></p>
											</div>
										</div>

										<!-- Send a Copy -->
										<div class="control-group">
											<label class="control-label" for="frm_send_copy">Send me a copy</label>
											<div class="controls">
												<input type="checkbox" id="frm_send_copy" name="frm_send_copy" value="yes" /> (optional) 
												<p class="help-block">Check to send a copy to my email: <?php echo $strEmail; ?></p>
											</div>
										</div>	

										<div class="control-group">									
											<div class="controls">
												<button class="btn btn-submit" type="button">Send</button>
											</div>
										</div>

									</fieldset>
								</form>
							</div>
                          <div class="tab-pane" id="preview-sendtoemail">
                          	<div class="alert">Preview</div>
                            
                            <div>
                            	<div id="message-container"></div>
								<?php 
								if(isset($_REQUEST['year'])){
									$intYear = $_REQUEST['year'];
								}else{
									$intYear  = date("Y");
								}
								echo file_get_contents($STR_URL . 'activity_list_print.php?action=print&print=false&pop=yes&year='.$intYear);?>
                            </div>
                          </div>
                        </div>

					</div>
				</div>			
		   		

			</div>	<!-- end #box -->	
				
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->   

</div> <!-- end #wrapper -->


<!-- JS Validator -->
<script src="<?php echo $STR_URL; ?>js/jquery.formvalidator.min.js"></script>
<script>$("#frm_email").validateOnBlur();</script>

<!-- Form Action -->
<script>
	$(document).ready(function () {
		$(".btn-submit").click(function () {
			$("#frm_email").validate();
			
			var action = $("#action").val();
			var frm_email_to = $("#frm_email_to").val();
			var frm_message = $("#frm_message").val();
			var frm_send_copy; if ($("#frm_send_copy").is(":checked")) { frm_send_copy = $("#frm_send_copy").val(); } else { frm_send_copy = "no"; }

			var dataString = "action=" + action + "&frm_email_to=" + frm_email_to + "&frm_message=" + frm_message + "&frm_send_copy=" + frm_send_copy + "&year="+ <?php echo $intYear;?>;

			var request = $.ajax({							    
								url: "ajax/activity_proc.php",
								type: "post", 
								data: dataString,
								success: function(msg) {
							
									$.gritter.add({				
										title: 'Info',				
										text: '<p>' + msg + '</p>',				
										image: '<?php echo $STR_URL; ?>img/accepted.png',				
										sticky: false,				
										time: '3000'
									});

								}
							    
							});

			<!-- Clear the Form -->
			clearForm();		
						
			function clearForm()
			{
				// clear
				$("#frm_email_to").val("");
				$("#frm_message").val("");
				$("#frm_send_copy").attr("checked", false);
				
				// set to focus
				$("#frm_email_to").focus(); 
			}		

		});

		$("#frm_message").on('keyup', function(){
			var value = $(this).val();
			$("#message-container").html("<p>"+value+"</p>");
		});
	});
</script>

<!-- Gritter -->
<script src="<?php echo $STR_URL; ?>js/jquery.gritter.js"></script>


</body>
</html>