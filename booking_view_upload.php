<?php include('inc/_include.php'); ?>
<?php 

		$conn = $db->dbConnect();
		
		$query = "SELECT * FROM `mbs_bookings` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);

		} // if ($result)				
		
		// Edit upload location here
   		$strDestinationPath = $STR_PATH . "uploads/" . date('Y') . "/" . date('m') . "/";

   		if ($_FILES['frm_file']['name'])
   		{
   			$strResult = 0;

	   		$strTargetPath = $strDestinationPath . basename($_FILES['frm_file']['name']);

		   	if (move_uploaded_file($_FILES['frm_file']['tmp_name'], $strTargetPath)) 
		   	{
		   		// get file path
				$strFilePath = "uploads/" . date('Y') . "/" . date('m') . "/";

				// get file name
				$strFileName = basename($_FILES['frm_file']['name']);
	
				$query = "UPDATE `mbs_bookings` SET `booking_file_path` = '" . $strFilePath . "', `booking_file_name` = '" . $strFileName . "' WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' LIMIT 1";	
				$result = mysql_query($query);

		    	$strResult = 1;
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
									<legend class="">Upload</legend>
								</div>
																
								<div class="alert">
  									<button type="button" class="close" data-dismiss="alert">&times;</button>
  									<strong>Note:</strong> Existing file will be overwritten. 
								</div>

								<input type="hidden" name="booking_id" id="booking_id" value="<?php echo $row['booking_id']; ?>" />
								<input type="hidden" name="action" id="action" value="upload" />
										    
								<!-- File -->										    
								<div class="control-group">
									<label class="control-label" for="frm_file">File</label>
									<div class="controls">
										<input type="file" id="frm_file" name="frm_file" placeholder="Select file to upload" class="input-xlarge" value="" data-validation="required" /> * 
										<p class="help-block">Upload the scanned file of the signed document to server. Please upload in JPG, GIF, PNG or PDF format!</p>
										<p id="upload_process">Loading...<br/><img src="<?php echo $STR_URL; ?>img/loading.gif" /></p>
									</div>
								</div>

								<div class="control-group">									
									<div class="controls">
										<input class="btn btn-submit" type="submit" value="Upload" />
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

			var booking_id = $("#booking_id").val();
			var action = $("#action").val();
			var frm_file = $("#frm_file").val();
			
			var dataString = "action=" + action + "&booking_id=" + booking_id + "&frm_file=" + frm_file + "";

			var request = $.ajax({							    
								url: "ajax/booking_proc.php",
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