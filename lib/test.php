<?php
	function listBooking()
	{
		global $arrSiteConfig;
		global $STR_URL;
		global $TABLE_MAX_ROW_PER_PAGE;

		DB::dbConnect();
				
		// If page number not set, set it to 1
		if (!$_REQUEST['page_num']) { $_REQUEST['page_num'] = 1; }			

		// Setting queries and pages	
		$offset = ($_REQUEST['page_num'] - 1) * $TABLE_MAX_ROW_PER_PAGE;
	
		$this->conn = DB::dbConnect();		
		$strSearchText = stripslashes($_REQUEST['frm_search_text']);
		
		
		// sort variables		
		if (!$_REQUEST['sortmode']) { $_REQUEST['sortmode'] = "asc"; }
		$strSortMode = $_REQUEST['sortmode']; 
		
		
		if ($_REQUEST['frm_search_text'])
		{
			// search query	*********************************************************************************		
			$query = "SELECT * FROM `mbs_bookings` 
					  		  WHERE (`booking_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'
					  		  		 OR `booking_code` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			     OR `booking_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')
					  			     ORDER BY "; 
				
			if ($_REQUEST['sortby'])
			{
				$query .= "`" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `booking_id`";
			}								
				
			else 
			{				
				$query .= "`booking_code` ASC, `booking_name` ASC, `booking_created_date` DESC";
			}							
					    
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			
			// search query	total ***************************************************************************			
			$queryTotal = "SELECT COUNT(*) FROM `mbs_bookings` 
					  			          WHERE (`booking_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			          	     OR `booking_code` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'
					  			                 OR `booking_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')";				
			
		
		}
		
		else 		
		{
			
			// the query ************************************************************************************						
			$query = "SELECT * FROM `mbs_bookings` ORDER BY ";							  		

			if ($_REQUEST['sortby'])
			{
				$query .= " `" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `booking_id`";
			}

			else
			{
				$query .= " `booking_code`, `booking_name`";
			}								
							  
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			

			// the query total ******************************************************************************
			$queryTotal = "SELECT COUNT(*) FROM `mbs_bookings`";
			
		
		}
		
		$result = mysql_query($query, $this->conn);
		
		$resultTotal = mysql_query($queryTotal, $this->conn);
		$rowTotal = mysql_fetch_row($resultTotal);
		
		$totalPage = ceil ($rowTotal[0]/$TABLE_MAX_ROW_PER_PAGE);
		
		$strResult = "";
		
		#echo "<div style=\"padding:15px; background-color:#eee;\">";
		#echo "<strong>Query:</strong> " . $query . "<br /><br />";
		#echo "<strong>Query Total:</strong> " . $queryTotal . "<br /><br />";
		#echo "</div>";
		
		// javascript to pop up message
		$strResult .= "
		
					";
		
		// search form
		$strResult .= 
			"
			<form name=\"search_booking_data\" method=\"post\" action=\"" . $STR_URL . "booking_list.php\">
				<input type=\"hidden\" name=\"frm_search_referer\" value=\"" . $_SERVER['PHP_SELF'] . "\" />
				<input type=\"text\" name=\"frm_search_text\" size=\"40\" maxlength=\"128\" value=\""; if ($_REQUEST['frm_search_text']) { $strResult .= stripslashes($_REQUEST['frm_search_text']); }  $strResult .= "\" />				
				<input class=\"btn\" type=\"submit\" name=\"frm_search_submit\" value=\"Search Bookings\" onclick=\"return validateSearch(this.form)\" /><br />
			</form>
			";

		// the form
		$strResult .= 
			"
			<form id=\"frm_booking\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" />
			";

				
		$strResult .= "<div align=\"right\">";
		
		// the refresh link
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('bookings', 'list') !== 0) 
		{
			$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . ADMIN::getModuleFile('bookings', 'list') . "\" title=\"Booking List\"><img src=\"" . $STR_URL . "img/refresh_icon.png\" /> Refresh</a>";		
		}

		$strResult .= "&nbsp;&nbsp;&nbsp;";
		
		// the add link	
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('bookings', 'add') !== 0) 
		{
			$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"" . $STR_URL . ADMIN::getModuleFile('bookings', 'add') . "?pop=yes\" title=\"New Booking\"><img src=\"" . $STR_URL . "img/add_icon.png\" /> New Booking</a>";
		}
		
		$strResult .= "	</div>";
		
		
		
		if ($strSortMode == "asc") { $strSortMode = "desc"; } elseif ($strSortMode == "desc") { $strSortMode = "asc"; }
		
		// the table
		$strResult .= 
			"			
			<div align=\"center\"><h2>Booking List</h2></div>
			<div align=\"right\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
																						 array('pop', urlencode('yes')),
																						 array('sortby', urlencode($_REQUEST['sortby'])),
																						 array('sortmode', urlencode($_REQUEST['sortmode']))
																						 )) . "</div>

			<section id=\"table_booking_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Booking List\">
			<caption>Booking List</caption>
			<thead>
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>																				
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=booking_name&sortmode=" . $strSortMode . "\">Code/Name</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=booking_normal_retail_price&sortmode=" . $strSortMode . "\">Normal Retail Price</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=booking_promo_price&sortmode=" . $strSortMode . "\">Promo Price</a></div></th>															
					";
		
		// edit / delete column																				 
		if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('bookings', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('bookings', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
		{																				 
		$strResult .= "	
					<th scope=\"col\" width=\"20%\"><div align=\"center\">Edit/Delete</div></th>
					";
		}
		
		$strResult .= "
				</tr>
			</thead>	
			
			<tbody>
			";			
			
			if ($rowTotal[0] > 0) 
			{			
				$no = $offset;		
				while ($row = mysql_fetch_assoc($result)) 
				{
					$no++;
										
					// link 
					$strLink = ADMIN::getModuleFile('bookings', 'view') . "?booking_id=" . urlencode($row['booking_id']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&page_num=" . $_REQUEST['page_num'] . "&pop=yes";
										
					$strResult .= 
						"
						<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
							<td id=\"r" . $row['booking_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
							<td><div align=\"left\">"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { $strResult .= "<a class=\"ajax callbacks cboxElement\" href=\"" . $STR_URL . $strLink . "\" title=\"" . html_entity_decode(strtoupper($row['booking_name'])) . "\">"; } $strResult .= "<strong>" . html_entity_decode(stripslashes($row['booking_code'])) . " / " . html_entity_decode(stripslashes($row['booking_name'])) . "</strong>"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { "</a>"; } $strResult .= "</div></td>
							<td><div align=\"right\"><strong>$" . html_entity_decode(stripslashes($row['booking_normal_retail_price'])) . "</strong></div></td>
							<td><div align=\"right\"><strong>$" . html_entity_decode(stripslashes($row['booking_promo_price'])) . "</strong></div></td>							
						";								
					
					// action column
					if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('bookings', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('bookings', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
					{	
					
						$strResult .= "<td><div align=\"center\">";
						
						// edit
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('bookings', 'edit') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{	
							$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"" . $STR_URL . "booking.php?booking_id=" . html_entity_decode($row['booking_id']) . "&action=edit&pop=yes\" title=\"Edit Booking\"><img src=\"" . $STR_URL . "img/edit_icon.png\" /> Edit</a>";
						}
						
						$strResult .= "&nbsp;&nbsp;";
						
						// delete
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('bookings', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{
							$strResult .= "<a id=\"frm_delete_button_" . $row['booking_id'] . "\" class=\"btn\" href=\"" . $STR_URL . "booking_list.php?booking_id=" . $row['booking_id'] . "&action=delete\" title=\"Delete Booking\"><img src=\"" . $STR_URL . "img/delete_icon.png\" /> Delete</a> ";
						}
						
						$strResult .= "</div></td>";
					
					}
					
					$strResult .= "
						</tr>
						";
		
				} // end while($row = )
			
			} // end if($rowTotal[0]) 
			
			else 			
			{
				$strResult .= "<tr><td colspan=\"5\"><div align=\"center\">Found no data</div></td></tr>";			
			}
		
		$strResult .= 
			"
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"3\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
																							  array('pop', urlencode('yes')),
																							  array('sortby', urlencode($_REQUEST['sortby'])),
																							  array('sortmode', urlencode($_REQUEST['sortmode']))
																							  )) . "</td>
				</tr>
			</tfoot>
			</table>
			</section>
			</form>
			<a class=\"btn\" href=\"#content\"><i class=\"icon-arrow-up\"></i> Back to top</a>


			<script>
				$(document).ready(function () {
					var strID;
					var intID;
					var deleteConf;	
			
					$('a').click(function(event) {
        				strID = event.target.id;        				        				
						intID = strID.replace('frm_delete_button_', '');						

						if (intID && intID !== '')
						{							
							if (confirmDeleteBooking())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&booking_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/booking_proc.php',
									type: 'post', 
									data: dataString,
									success: function(msg) {
										
										$.gritter.add({				
											title: 'Info',				
											text: '<p>' + msg + '</p>',				
											image: '" . $STR_URL . "img/accepted.png',				
											sticky: false,				
											time: '3000'
										});

									}
										    
								});		
								
							}

							return false;	
										
						}			
						
    				});
 
				});
			</script>
			";		

		// The Log	
		$strLog = "View the Booking List";
			
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
		

		echo $strResult; 

	} // listBooking()

?>	