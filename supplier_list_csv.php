<?php include('inc/_include.php'); ?>
<?php 
	
	//-- Export CSV
	if ($_REQUEST['action'] == "export-csv") 
	{ 

		$strFileName = "Supplier List - " . date("Y-m-d-his");

		$conn = $db->dbConnect();
			
		$query = "SELECT * FROM `mbs_suppliers` t1, 
								`mbs_suppliers_marketing_contacts` t2, 
								`mbs_suppliers_account_contacts` t3 
						WHERE t2.`supplier_id` = t1.`supplier_id`
						AND t3.`supplier_id` = t2.`supplier_id` 
						AND t3.`supplier_id` = t1.`supplier_id`

						ORDER BY t1.`supplier_name`";

		$result = mysql_query($query);
					
		if ($result) 
		{
			$intNo = 0;			
			$strResult = "NO.,NAME,EMAIL,TELEPHONE,ADDRESS,REF. NUMBER,";
			$strResult .= "ACCOUNT NAME,ACCOUNT EMAIL,ACCOUNT PHONE,ACCOUNT ADDRESS,";
			$strResult .= "MARKETING NAME,MARKETING EMAIL,MARKETING PHONE,MARKETING ADDRESS\n";

			while ($row = mysql_fetch_assoc($result)) 
			{
				$intNo++;
				
				$strResult .= $intNo . ",\"" . $row['supplier_name'] . "\",\"" . $row['supplier_email'] . "\",\"" . $row['supplier_phone_number'] . "\",\"" . $row['supplier_postal_address'] . "\",\"" . $row['supplier_po_ref_number'] . "\",";
				$strResult .= "\"" . $row['supplier_account_name'] . "\",\"" . $row['supplier_account_email'] . "\",\"" . $row['supplier_account_phone_number'] . "\",\"" . $row['supplier_account_postal_address'] . "\",";
				$strResult .= "\"" . $row['supplier_contact_name'] . "\",\"" . $row['supplier_contact_email'] . "\",\"" . $row['supplier_contact_phone_number'] . "\",\"" . $row['supplier_contact_postal_address'] . "\"\n";

			} // while ($row = mysql_fetch_assoc($result)) 
		} // if ($result)


		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename=' . $strFileName . '.csv');

		echo $strResult;

	}

	//-- Import CSV
	elseif ($_REQUEST['action'] == "import-csv") 
	{

		$conn = $db->dbConnect();
		$uploaddir = 'uploads/csv/';

		if (is_writable($uploaddir))
		{
			$uploadfile = $uploaddir . substr_replace($_REQUEST['frm_file'], '', 0, 12);
						
			//move_uploaded_file($_FILES['frm_file']['tmp_name'], $uploadfile);			copy($_FILES['frm_file']['tmp_name'], $uploadfile);

			// determine separator ; or ,
			$arrFile = file($uploadfile);

			$aFile1 = explode(';', $arrFile[0]);
			$aFile2 = explode(',', $arrFile[0]);

			if (count($aFile1) > count($aFile2)) { $strSeparator = ';'; } else { $strSeparator = ','; }
			
			// determine data: NO.,NAME,EMAIL,TELEPHONE,ADDRESS,REF. NUMBER,ACCOUNT NAME,ACCOUNT EMAIL,ACCOUNT PHONE,ACCOUNT ADDRESS,MARKETING NAME,MARKETING EMAIL,MARKETING PHONE,MARKETING ADDRESS
			
			ini_set('auto_detect_line_endings', 1);

			$row = 0;
			$handle = fopen($uploadfile, "r");
			while (($data = fgetcsv($handle, 3000, $strSeparator)) !== FALSE) 
			{
				if (strlen($data[1]) > 0)
				{	
					$row++;
				    $num = count($data);
				    #echo $row . " == " . $num . "<br />";
				    if ($row > 1) 
				    {
				    	/*
				    	echo "<p> $num fields in line $row:</p>\n";
				    	echo "NO.: " . $data[0] . "<br />";
				    	echo "NAME: " . $data[1] . "<br />";
				    	echo "EMAIL: " . $data[2] . "<br />";
				    	echo "TELEPHONE: " . $data[3] . "<br />";
				    	echo "ADDRESS: " . $data[4] . "<br />";
				    	echo "REF. NUMBER: " . $data[5] . "<br />";
				    	echo "ACCOUNT NAME: " . $data[6] . "<br />";
				    	echo "ACCOUNT EMAIL: " . $data[7] . "<br />";
				    	echo "ACCOUNT PHONE: " . $data[8] . "<br />";
				    	echo "ACCOUNT ADDRESS: " . $data[9] . "<br />";
				    	echo "MARKETING NAME: " . $data[10] . "<br />";
				    	echo "MARKETING EMAIL: " . $data[11] . "<br />";
				    	echo "MARKETING PHONE: " . $data[12] . "<br />";
				    	echo "MARKETING ADDRESS: " . $data[13] . "<br />";
				    	*/


				    	// filter input: NAME -> `supplier_name`
				    	// filter input: EMAIL -> `supplier_email`
						// filter input: TELEPHONE -> `supplier_phone_number`
						// filter input: ADDRESS -> `supplier_postal_address`
						// filter input: REF. NUMBER -> `supplier_po_ref_number`
						// filter input: ACCOUNT NAME -> `supplier_account_name`
						// filter input: ACCOUNT EMAIL -> `supplier_account_email`
						// filter input: ACCOUNT PHONE -> `supplier_account_phone_number`
						// filter input: ACCOUNT ADDRESS -> `supplier_account_postal_address`
						// filter input: MARKETING NAME -> `supplier_contact_name`
						// filter input: MARKETING EMAIL -> `supplier_contact_email`
						// filter input: MARKETING PHONE -> `supplier_contact_phone_number`
						// filter input: MARKETING ADDRESS -> `supplier_contact_postal_address`
						
						
						// check if the number exists, delete and then insert the latest as an update
						$queryCheck = "SELECT COUNT(`supplier_name`) FROM `mbs_suppliers` 
									   WHERE `supplier_name` = '" . trim($data[1]) . "'";
						
						$resultCheck = mysql_query($queryCheck);
						$rowCheck = mysql_fetch_row($resultCheck);

						$countCheck = $rowCheck[0];

						if ($countCheck == 0)
						{
							// insert supplier
							$query = "INSERT INTO `mbs_suppliers` (`supplier_id`, 
																   `supplier_name`, 
																   `supplier_email`, 
																   `supplier_phone_number`, 
																   `supplier_postal_address`, 
																   `supplier_po_ref_number`, 
																   `supplier_active`, 
																   `supplier_created_date`, 
																   `supplier_created_by`, 
																   `supplier_modified_date`, 
																   `supplier_modified_by`)
															
															VALUES (NULL , 
																	'" . mysql_real_escape_string($data[1]) . "', 	
																	'" . mysql_real_escape_string(strtolower($data[2])) . "', 
																	'" . mysql_real_escape_string($data[3]) . "', 
																	'" . mysql_real_escape_string($data[4]) . "', 																 
																	'" . mysql_real_escape_string($data[5]) . "', 
																	'yes', 																
																	'" . date('Y-m-d H:i:s') . "', 
																	'" . addslashes($_SESSION['user']['login_name']) . " (imported)', 
																	'" . date('Y-m-d H:i:s') . "', 
																	'" . addslashes($_SESSION['user']['login_name']) . " (imported)'
																	)";
							#echo $query . "<br /><br />";
								
							$result = mysql_query($query);
							$intID = mysql_insert_id();
								

							if ($result && $intID)
							{
									
								// insert supplier account
								$queryAccount = "INSERT INTO `mbs_suppliers_account_contacts` (`supplier_account_id`, 
																							   `supplier_id`, 
																							   `supplier_account_name`, 
																							   `supplier_account_email`, 
																							   `supplier_account_phone_number`, 
																							   `supplier_account_postal_address`, 
																							   `supplier_account_active`, 
																							   `supplier_account_created_date`, 
																							   `supplier_account_created_by`, 
																							   `supplier_account_modified_date`, 
																							   `supplier_account_modified_by`)
														
														VALUES (NULL , 
																'" . $intID . "', 	
																'" . mysql_real_escape_string($data[6]) . "', 
																'" . mysql_real_escape_string(strtolower($data[7])) . "', 
																'" . mysql_real_escape_string($data[8]) . "', 																 
																'" . mysql_real_escape_string($data[9]) . "', 
																'yes', 																
																'" . date('Y-m-d H:i:s') . "', 
																'" . addslashes($_SESSION['user']['login_name']) . " (imported)', 
																'" . date('Y-m-d H:i:s') . "', 
																'" . addslashes($_SESSION['user']['login_name']) . " (imported)'
																)";
							
							
								$resultAccount = mysql_query($queryAccount);

								// insert supplier contact
								$queryContact = "INSERT INTO `mbs_suppliers_marketing_contacts` (`supplier_contact_id`, 
																							   	 `supplier_id`, 
																							     `supplier_contact_name`, 
																							     `supplier_contact_email`, 
																							     `supplier_contact_phone_number`, 
																							     `supplier_contact_postal_address`, 
																							     `supplier_contact_active`, 
																							     `supplier_contact_created_date`, 
																							     `supplier_contact_created_by`, 
																							     `supplier_contact_modified_date`, 
																							     `supplier_contact_modified_by`)
														
														VALUES (NULL , 
																'" . $intID . "', 	
																'" . mysql_real_escape_string($data[10]) . "', 
																'" . mysql_real_escape_string(strtolower($data[11])) . "', 
																'" . mysql_real_escape_string($data[12]) . "', 																 
																'" . mysql_real_escape_string($data[13]) . "', 
																'yes', 																
																'" . date('Y-m-d H:i:s') . "', 
																'" . addslashes($_SESSION['user']['login_name']) . " (imported)', 
																'" . date('Y-m-d H:i:s') . "', 
																'" . addslashes($_SESSION['user']['login_name']) . " (imported)'
																)";
							
							
								$resultContact = mysql_query($queryContact);	


								// add to logs
								$strLog = "Supplier named \"" . stripslashes($data[1]) . "\" is successfully imported.";
								
								$queryLog = "INSERT INTO `logs` (`log_id`, 
																 `log_user`, 
																 `log_action`, 
																 `log_time`, 
																 `log_from`, 
																 `log_logout`)

											VALUES (NULL, 
													'" . $_SESSION['user']['login_name'] . "',
													'" . mysql_real_escape_string($strLog) . "',
													'" . date('Y-m-d H:i:s') . "',
													'" . $_SESSION['user']['ip_address'] . "', 
													NULL)";			
									
								$resultLog = mysql_query($queryLog);
									
																	
							} // end if ($result) 

						} // if ($countCheck == 0)
						
						else
						{
							echo "\"" . stripslashes($data[1]) . "\" already exists. The import was terminated.<br />Please make sure the CSV file does not contain duplicated Supplier data!<br />";
							exit;
						}
						
								
					} // end if ($row > 1)

					else
					{
						#echo "Error: Found no valid data on the file. Please make sure the file is containing valid data in CSV format!<br />";
					}

				} // end if ($data[1]) {}	
			    
			} // end while()
							
			fclose($handle);
			
			
			if ($result)
			{
				// alert			
				echo "" . ($row-1) . " Supplier(s) from \"" . substr_replace($_REQUEST['frm_file'], '', 0, 12) . "\" is successfully imported!<br />";
				
			}
			
				
		} // if (is_writable($uploaddir)) 
		
		else
		{
			echo "Error! CSV upload directory is not writable!<br />";
		}
		

	} // elseif ($_REQUEST['action'] == "import-csv") 

?>