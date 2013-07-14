<?php include('inc/_include.php'); ?>
<?php 
		if ($_REQUEST['supplier_id'] && $_REQUEST['action'] == "vcard")
		{
			$conn = $db->dbConnect();
			
			$query = "SELECT * FROM `mbs_suppliers` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' LIMIT 1";
			$result = mysql_query($query);
			
			if ($result) 
			{
				$row = mysql_fetch_assoc($result);
				
				$strResult = writevCard($row['supplier_name'], "", "", $row['supplier_name'], "", $row['supplier_postal_address'], $row['supplier_email'], $row['supplier_phone_number'], "");
				$strFileName = urlencode($row['supplier_name']);

				if ($_REQUEST['supplier_contact_id'])
				{
					// get marketing contacts
					$strQuery2 = "SELECT * FROM `mbs_suppliers_marketing_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' 
																					 AND `supplier_contact_id` = '" . mysql_real_escape_string($_REQUEST['supplier_contact_id']) . "' LIMIT 1";
					$result2 = mysql_query($strQuery2);
				
					if ($result2)
					{
						$row2 = mysql_fetch_assoc($result2);

						$strName = split(" ", $row2['supplier_contact_name']);
						if (is_array($strName) && count($strName) > 0) 
						{ 
							$strLN = $strName[count($strName)-1]; 
							$strFN = "";
							for ($i = 0; $i < count($strName); $i++)
							{
								$strFN .= $strName[$i];

								if ($i == (count($strName)-2))
								{									
									break;
								}

								else
								{
									$strFN .= " ";
								}
							}
							
						}

						$strResult = writevCard($row2['supplier_contact_name'], $strFN, $strLN, $row['supplier_name'], $row2['supplier_contact_position'], $row2['supplier_contact_postal_address'], $row2['supplier_contact_email'], $row2['supplier_contact_phone_number'], $row2['supplier_contact_mobile_number']);
						$strFileName = urlencode($row2['supplier_contact_name']);	
					}
				
				}

				if ($_REQUEST['supplier_account_id'])
				{
					// get account contacts
					$strQuery3 = "SELECT * FROM `mbs_suppliers_account_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' 
																				   AND `supplier_account_id` = '" . mysql_real_escape_string($_REQUEST['supplier_account_id']) . "' LIMIT 1";
					$result3 = mysql_query($strQuery3);
				
					if ($result3)
					{
						$row3 = mysql_fetch_assoc($result3);

						$strName = split(" ", $row3['supplier_account_name']);
						if (is_array($strName) && count($strName) > 0) 
						{ 
							$strLN = $strName[count($strName)-1]; 
							$strFN = "";
							for ($i = 0; $i < count($strName); $i++)
							{
								$strFN .= $strName[$i];

								if ($i == (count($strName)-2))
								{									
									break;
								}

								else
								{
									$strFN .= " ";
								}
							}
							
						}

						$strResult = writevCard($row3['supplier_account_name'], $strFN, $strLN, $row['supplier_name'], "", $row3['supplier_account_postal_address'], $row3['supplier_account_email'], $row3['supplier_account_phone_number'], "");
						$strFileName = urlencode($row3['supplier_account_name']);	
					}
				
				}

				if ($_REQUEST['supplier_territory_id'])
				{
					// get account contacts
					$strQuery4 = "SELECT * FROM `mbs_suppliers_territory_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' 
																				     AND `supplier_territory_id` = '" . mysql_real_escape_string($_REQUEST['supplier_territory_id']) . "' LIMIT 1";
					$result4 = mysql_query($strQuery4);
				
					if ($result4)
					{
						$row4 = mysql_fetch_assoc($result4);

						$strName = split(" ", $row4['supplier_territory_name']);
						if (is_array($strName) && count($strName) > 0) 
						{ 
							$strLN = $strName[count($strName)-1]; 
							$strFN = "";
							for ($i = 0; $i < count($strName); $i++)
							{
								$strFN .= $strName[$i];

								if ($i == (count($strName)-2))
								{									
									break;
								}

								else
								{
									$strFN .= " ";
								}
							}
							
						}

						$strResult = writevCard($row4['supplier_territory_name'], $strFN, $strLN, $row['supplier_name'], $row4['territory_name'], $row4['territory_name'], "", $row4['supplier_territory_phone_number'], "");
						$strFileName = urlencode($row4['supplier_territory_name']);	
					}
				
				}

			} // if ($result)	


		} // if ($_REQUEST['supplier_id'] && $_REQUEST['action'] == "vcard")
						

function writevCard($strFullName, $strFirstName, $strLastName, $strOrganization, $strTitle, $strAddress, $strEmail, $strPhone, $strMobile)
{
	$strResult = "";
	$strResult .= "BEGIN:VCARD
				   VERSION:4.0
				   N:" . $strLastName . ";" . $strFirstName . ";;;
				   FN:" . $strFullName . "
				   ORG:" . $strOrganization . "
				   TITLE:" . $strTitle . "
				   PHOTO;MEDIATYPE=:
				   TEL;TYPE=WORK,VOICE:" . $strMobile . "
				   TEL;TYPE=HOME,VOICE:" . $strPhone . "				   
				   ADR;TYPE=WORK:;;" . $strAddress . ";;;
				   LABEL;TYPE=WORK:" . $strAddress . "
				   ADR;TYPE=HOME:;;
				   LABEL;TYPE=HOME:
				   EMAIL:" . $strEmail . "
				   REV:" . date('Ymd') . "T" . date('his') . "Z
				   END:VCARD";

	return $strResult;			   
}


header('Content-Type: text/x-vcard');
header('Content-Disposition: inline; filename=vCard-' . $strFileName . '.vcf');

echo $strResult;
?>