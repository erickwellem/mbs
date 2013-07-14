<?php include('inc/_include.php'); ?>
<?php 

		// Edit upload location here
   		$strDestinationPath = $STR_PATH . "uploads/csv/";

   		if ($_FILES['frm_file']['name'])
   		{
   			$strResult = 0;

	   		$strTargetPath = $strDestinationPath . basename($_FILES['frm_file']['name']);

		   	if (move_uploaded_file($_FILES['frm_file']['tmp_name'], $strTargetPath)) 
		   	{
		   		// get file path
				$strFilePath = "uploads/csv/";

				// get file name
				$strFileName = basename($_FILES['frm_file']['name']);
					
		   	}
		
   		}
   		
		#print_r($_FILES); 
		#echo $strDestinationPath;
		#echo $strTargetPath;
		#Array ( [frm_file] => Array ( [name] => screenshot.jpg [type] => image/jpeg [tmp_name] => /Applications/XAMPP/xamppfiles/temp/phpROsieb [error] => 0 [size] => 58684 ) );

   	   #sleep(1);
?>
<!DOCTYPE html> 
<!--[if IE 7 ]><html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<title> View Supplier | <?php echo stripslashes($arrSiteConfig['site_name']); ?></title> 
	<meta name="description" content="<?php echo stripslashes($arrSiteConfig['site_description']); ?>" /> 
	<?php include('inc/init.php'); ?>

	<script>
		$(document).ready(function () {
			<?php if ($strResult == 1) { ?>
			$("#upload_process").hide();
			<?php } ?>		
		});
	</script>
</head> 

<body>

<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>

<div id="wrapper" class="container-fluid">
	
	<div id="content-wrapper" class="row-fluid">
    
    	<div id="content" class="span12">

			<div id="box" class="span12">


		   		<div class="container-fluid">
					<div class="row-fluid">
						
						<form id="frm_upload" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" target="upload_target">	
							<fieldset>
								<div id="legend">
									<legend class="">Import Supplier CSV</legend>
								</div>
																
								<div class="alert">
  									<button type="button" class="close" data-dismiss="alert">&times;</button>
  									<strong>Note:</strong> Please upload a valid formatted Supplier CSV file. <br />A valid format example can be downloaded by clicking the "Export to CSV" on the Supplier List
								</div>
								
								<input type="hidden" name="action" id="action" value="import-csv" />
										    
								<!-- File -->										    
								<div class="control-group">
									<label class="control-label" for="frm_file">File</label>
									<div class="controls">
										<input type="file" id="frm_file" name="frm_file" placeholder="Select .CSV file to upload" class="input-xlarge" value="" data-validation="required" /> * 
										<p class="help-block">Please upload a valid format of CSV file for Supplier only!</p>
										<p id="upload_process">Loading...<br/><img src="<?php echo $STR_URL; ?>img/loading.gif" /></p>
									</div>
								</div>

								<div class="control-group">									
									<div class="controls">
										<input class="btn btn-submit" type="submit" value="Import" />
									</div>
								</div>

							</fieldset>
						</form>	

						
					</div>
				</div>			
		   		

			</div>	<!-- end #box -->	
				
    
    	</div> <!-- end #content -->
    </div> <!-- end #content-wrapper -->   

</div> <!-- end #wrapper -->


<!-- JS Validator -->
<script src="<?php echo $STR_URL; ?>js/jquery.formvalidator.min.js"></script>
<script>$("#frm_upload").validateOnBlur();</script>

<!-- Form Action -->

<script>
	$(document).ready(function () {

		$("#upload_process").hide();

		$(".btn-submit").click(function () {

			$("#frm_upload").validate();
						
			$("#upload_process").show();				
			
			var action = $("#action").val();
			var frm_file = $("#frm_file").val();
			
			var dataString = "action=" + action + "&frm_file=" + frm_file + "";

			var request = $.ajax({							    
								url: "supplier_list_csv.php",
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

									$("#upload_process").hide();	
								}
							    
							});

			
				
			
		});
	});
</script>


<!-- Gritter -->
<script src="<?php echo $STR_URL; ?>js/jquery.gritter.js"></script>


</body>
</html>