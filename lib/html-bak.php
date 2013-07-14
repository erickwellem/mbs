<?php
/*************************************************************
 * HTML Class
 * @Author		: Erick Wellem (me@erickwellem.com)
 * @Date		: March 8, 2009
 * @Description	: class for html and layout manipulations
 ************************************************************/

class HTML 
{
	function showHeader()
	{
		include('config.php');
		global $HEADER_INCLUDE;
		include($HEADER_INCLUDE);	
	
	} // showHeader()
	
	function showFooter() 
	{
		include('config.php');
		global $FOOTER_INCLUDE;
		include($FOOTER_INCLUDE);
	
	} // showFooter()
	
	function showAlert($message, $withBackLink = TRUE) 
	{
		
		echo "\n<div id=\"alert-wrapper\">\n";
		echo "<div id=\"alert-box\">\n";
		echo "<p>" . $message . "</p>\n";
		
		if ($withBackLink) 
		{
			echo "<input class=\"btn btn-danger\" type=\"button\" name=\"back\" value=\"Back\" onClick=\"history.go(-1)\" />";
		}
		
		echo "</div>\n</div>\n";
	
	} // showAlert()
	
	function redirectUser($targetURL, $delayTime) 
	{
		$strResult = "<meta http-equiv=\"refresh\" content=\"" . $delayTime . ";url=" . $targetURL . "\">\n";
		
		echo $strResult;
	
	} // redirectUser()
	
	function makeLink($text, $url, $isBold = FALSE) 
	{
		
		$strResult = "<a href=\"" . $url . "\">";	

		if ($isBold == TRUE) { $strResult .= "<strong>"; }
	
		$strResult .= $text;
	
		if ($isBold == TRUE) { $strResult .= "</strong>"; }
	
		$strResult .= "</a>";
		
		return $strResult;
	
	} // makeLink()
	
	function convertDate($dateString) 
	{
		global $arrSiteConfig;
		
		$strResult = '';		
		$arrDate = explode('-', $dateString);
		$month = date('F', mktime(0,0,0,intval($arrDate[1]+1),0,0));
		
		if ($arrSiteConfig['site_language'] == "id")
		{
			switch (strtolower($month))
			{
				case 'january':
						$month = 'Januari';
					break;
				case 'february':
						$month = 'Februari';
					break;	
				case 'march':
						$month = 'Maret';
					break;
				case 'april':
						$month = 'April';
					break;
				case 'may':
						$month = 'Mei';
					break;
				case 'june':
						$month = 'Juni';
					break;
				case 'july':
						$month = 'Juli';
					break;
				case 'august':
						$month = 'Agustus';
					break;
				case 'september':
						$month = 'September';
					break;
				case 'october':
						$month = 'Oktober';
					break;
				case 'november':
						$month = 'November';
					break;
				case 'december':
						$month = 'Desember';
					break;
			}
		}
		
		$strResult .= $arrDate[2] .' ' . $month . ' ' . $arrDate[0];
		
		return $strResult;
		
		
	} // convertDate()

	function convertDateTime($dateTimeString) 
	{
		global $arrSiteConfig;
		
		$strResult = '';		
		
		// Implode the datetime data obtained from MySQL database
	    $year = substr($dateTimeString, 0, 4);
	    $month = substr($dateTimeString, 5, 2);
	    $dateday = substr($dateTimeString, 8, 2);
	    $hour = substr($dateTimeString, 11, 2);
	    $minute = substr($dateTimeString, 14, 2);
	    $second = substr($dateTimeString, 17, 2);
		$strMonth = date('F', mktime(0,0,0,intval($month+1),0,0));
	    
		if ($arrSiteConfig['site_language'] == "id")
		{
		    switch (strtolower($strMonth))
			{
				case 'january':
						$strMonth = 'Januari';
					break;
				case 'february':
						$strMonth = 'Februari';
					break;	
				case 'march':
						$strMonth = 'Maret';
					break;
				case 'april':
						$strMonth = 'April';
					break;
				case 'may':
						$strMonth = 'Mei';
					break;
				case 'june':
						$strMonth = 'Juni';
					break;
				case 'july':
						$strMonth = 'Juli';
					break;
				case 'august':
						$strMonth = 'Agustus';
					break;
				case 'september':
						$strMonth = 'September';
					break;
				case 'october':
						$strMonth = 'Oktober';
					break;
				case 'november':
						$strMonth = 'November';
					break;
				case 'december':
						$strMonth = 'Desember';
					break;
			}
		
		}
		
		$strResult .= $dateday .' ' . $strMonth . ' ' . $year;
	    
	    if (strlen($dateTimeString) > 10)
	    {
	    	$strResult .= ' ' . $hour . ':' . $minute;
	    }
		
		return $strResult;
				
	} // convertDateTime()

	function getMonthName($intMonth, $strLanguage)
	{
		if ($strLanguage == 'id')
		{
			$arrMonth = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
			return $arrMonth[$intMonth - 1]; 
		}
		
		else 
		{
			return date("F", mktime(0,0,0,$intMonth+1,0,0)); 
		}
					
	} // getMonthName()
	
	
	function greetUser() 
	{
	
		$time = date('G');
		
		if ($time >= 0 && $time <= 10) { $strResult = _GOOD_MORNING; }
		elseif ($time > 10 && $time <= 12) { $strResult = _GOOD_DAY; }
		elseif ($time > 12 && $time <= 18) { $strResult = _GOOD_AFTERNOON; }
		else { $strResult = _GOOD_EVENING; }	
	
		return stripslashes($strResult);
		
	} // greetUser()
	
	function showLogBox($rowNum) 
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `logs` ORDER BY `log_id` DESC LIMIT " . $rowNum;
		$result = mysql_query($query, $this->conn);
		
		$strResult .= "<p><strong>Recent events</strong></p>\n";
		
		if ($result) 
		{
			$strResult .= "<ul>\n";
			while ($row = mysql_fetch_assoc($result)) {
				$strResult .= "<li><strong>" . $this->convertDateTime($row['log_time']) . "</strong> &raquo; " . $row['log_action'] . " - <strong><em>" . $row['log_user'] . "</em></strong></li>\n";
			}
			$strResult .= "</ul>\n";
		
		}
		
		echo $strResult;
	
	} // showLogBox()
	
	function showPaging ($totalRow, 
						 $totalPage, 
						 $slideLimit, 
						 $addVar = NULL) 
						 {
						
		
		$strResult = "";
		/*$strResult .= "<br>Total pages => " . $totalPage;	
		$strResult .= "<br>Total rows => " . $totalRow;*/
				
		if ($totalRow > 0) 
		{
			
			$strResult .= "\n\n<div align=\"right\">";			
			$strResult .= "Page [ ";
			
			if ($_REQUEST['page_num'] != 1) 
			{
	       		
				// $string01
				$string01 = "<a href=\"" . $_SERVER['PHP_SELF'] . "?";
				
				if ($addVar) 
				{
					for ($i = 0; $i < count($addVar); $i++) 
					{ 					
						$string01 .= $addVar[$i][0] . "=" .$addVar[$i][1] . "&";					
					}
				}			
				
				$string01 .= "page_num=1\">";
							
				$string01 .= "<strong>|&laquo;</strong>";
				$string01 .= "</a>"; 
				$strResult .= $string01;
				
		   		// $string02
				$string02 = "<a href=\"" . $_SERVER['PHP_SELF'] . "?";
			
				if ($addVar) 
				{ 
					for ($i = 0; $i < count($addVar); $i++) 
					{
						$string02 .= $addVar[$i][0] . "=" . $addVar[$i][1] . "&"; 
					}
				}
			
				
				$string02 .= "page_num=" . ($_REQUEST['page_num'] - 1) . "\">\n";
				
				$string02 .= "<strong>Previous</strong>";
				$string02 .= "</a> ";
				
				$strResult .= $string02;
	       
		   		if (($_REQUEST['page_num'] - $slideLimit) > 1) { $strResult .= " ... "; }
	    	}
			
			for ($i = 1; $i <= $totalPage; $i++) 
			{
	    		for ($j = ($_REQUEST['page_num'] - $slideLimit); $j <= ($_REQUEST['page_num'] + $slideLimit); $j++) 
	    		{
	      			if ($j != $_REQUEST['page_num'] && $j >= 1 && $j <= $totalPage) 
	      			{
			   			
						// $string03
						$string03 = " <a href=\"" . $_SERVER['PHP_SELF'] . "?";
			
						if ($addVar) 
						{
							for ($k = 0; $k < count($addVar); $k++) 
							{
								$string03 .= $addVar[$k][0] . "=" . $addVar[$k][1] . "&"; 
							}
						}
						
						$string03 .= "page_num=" . $j . "\"> ";
						$string03 .= "<strong>" . $j . "</strong> ";
						$string03 .= "</a> ";
						$strResult .= $string03;
					
					}
				
					if (($j == $_REQUEST['page_num']) && ($j >= 1) && ($j <= $totalPage)) 
					{
			   			$strResult .= " <span class=\"pages\"><strong>" . $j . "</strong></span> ";
	        		} 
	      		}    
	      		break;
	    	}
	
			if ($_REQUEST['page_num'] != $totalPage) 
			{
	    		if (($_REQUEST['page_num'] + $slideLimit) < $totalPage) { $strResult .= " ... "; }
	 	   			
					// $string04
					$string04 = " <a href=\"" . $_SERVER['PHP_SELF'] . "?";
	
					if ($addVar) 
					{ 
						for ($i = 0; $i < count($addVar); $i++) 
						{
							$string04 .= $addVar[$i][0] . "=" . $addVar[$i][1] . "&"; 
						}
					}				
					
					$string04 .= "page_num=" . ($_REQUEST['page_num'] + 1) . "\">";
					$string04 .= "<strong>Next</strong>";
		   			$string04 .= "</a> ";
					
					$strResult .= $string04;
					
					// $string05
		   			$string05 .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?";
	
					if ($addVar) 
					{ 
						for ($i = 0; $i < count($addVar); $i++) 
						{
							$string05 .= $addVar[$i][0] . "=" . $addVar[$i][1] . "&"; 
						}
					}				
					
					$string05 .= "page_num=" . $totalPage . "\">";
	
		   			$string05 .= "<strong>&raquo;|</strong>";
		   			$string05 .= "</a>"; 
					
					$strResult .= $string05;
	    	}
	
				$strResult .= " ] from ";			
				$strResult .= "<strong>" . $totalPage . "</strong>";
				$strResult .= "</div><!-- end div paging -->\n\n";
		}
		
		return $strResult;
		
	} // showPaging()
	
	
	function sendEmail ($arrFrom, 
					    $arrTo, 
					    $subject, 
					    $message, 
					    $type ='plain-text', 
					    $importance = 'normal', 
					    $arrAttachment = NULL, 
					    $arrAttachmentExt = NULL, 
					    $arrFilter = NULL) {
	
		/* filter input before anything else */
		if (is_array($arrFilter) && count($arrFilter) > 0) {
			for ($i = 0; $i < count($arrFilter); $i++) {
				if (preg_match("/" . $arrFilter[$i] . "/i", $subject) || preg_match("/" . $arrFilter[$i] . "/i", $message)) {	
					echo "Sorry, your inquiry is suspected as spam!";
					exit;
				}
			}
		}				    	
	       		
		/* composing header */
		$strHeader = "Return-Path: ";
		// Return-Path
		if (is_array($arrFrom['return-path']) && count($arrFrom['return-path']) > 0) { 
			for ($i = 0; $i < count($arrFrom['return-path']); $i++) {
				$strHeader .= $arrFrom['return-path'][$i];
				if ($i == count($arrFrom['return-path']) - 1) { $strHeader .= ""; } else { $strHeader .= ", "; }			 
			}
		} else { 
			for ($i = 0; $i < count($arrFrom['from']); $i++) {
				$strHeader .= $arrFrom['from'][$i]; 
				if ($i == count($arrFrom['from']) - 1) { $strHeader .= ""; } else { $strHeader .= ", "; }
			}
		}
		
		$strHeader .= "\n";
		
		// From			
		$strHeader .= "From: ";
	
		$strEliminatePatterns = array(',', ';');
		$strReplacementPatterns = array(' ', '');
		
		if (is_array($arrFrom['from']) && count($arrFrom['from']) > 0) {
			$strFrom = "";
			for ($i = 0; $i < count($arrFrom['from']); $i++) {
				$strHeader .= str_replace($strEliminatePatterns, $strReplacementPatterns, $arrFrom['from'][$i]);
				$strFrom .= str_replace($strEliminatePatterns, $strReplacementPatterns, $arrFrom['from'][$i]);
				if ($i == count($arrFrom['from']) - 1) { 
					$strHeader .= ""; 
					$strFrom .= ""; 
				} else { 
					$strHeader .= ", "; 
					$strFrom .= ", "; 
				}
			}
		} else {
			echo "Sorry, email is not defined at email header!";
			exit;
		}
		
		$strHeader .= "\n";
		
		$strHeader .= "Reply-To: ";
		// Reply-To
		if (is_array($arrFrom['reply-to']) && count($arrFrom['reply-to']) > 0) { 
			for ($i = 0; $i < count($arrFrom['reply-to']); $i++) {
				$strHeader .= $arrFrom['reply-to'][$i];
				if ($i == count($arrFrom['reply-to']) - 1) { $strHeader .= ""; } else { $strHeader .= ", "; }			 
			}
		} else { 
			for ($i = 0; $i < count($arrFrom['from']); $i++) {
				$strHeader .= $arrFrom['from'][$i]; 
				if ($i == count($arrFrom['from']) - 1) { $strHeader .= ""; } else { $strHeader .= ", "; }
			}
		}
		
		$strHeader .= "\n";
	
		// populate $arrTo
		if (is_array($arrTo) && count($arrTo) > 0) {
			
			// To
			$strHeader .= "To: ";
			if (is_array($arrTo['to']) && count($arrTo['to']) > 0) {
				
				$strTo = "";
				for ($i = 0; $i < count($arrTo['to']); $i++) {
					
					$strHeader .= str_replace($strEliminatePatterns, $strReplacementPatterns, $arrTo['to'][$i]);
					$strTo .= str_replace($strEliminatePatterns, $strReplacementPatterns, $arrTo['to'][$i]);
					
					if ($i == count($arrTo['to']) - 1) { 
						$strHeader .= ""; 
						$strTo .= ""; 
					} else { 
						$strHeader .= ", "; $strTo .= ", "; 
					}				
				}
				
			} else {
				echo "Sorry, recipient email is not defined yet!";
				exit;
			}
			
			$strHeader .= "\n";
			
			// Cc
			if (is_array($arrTo['cc']) && count($arrTo['cc']) > 0) {
				$strHeader .= "Cc: ";
				for ($i = 0; $i < count($arrTo['cc']); $i++) {
					$strHeader .= str_replace($strEliminatePatterns, $strReplacementPatterns, $arrTo['cc'][$i]);
					if ($i == count($arrTo['cc']) - 1) { $strHeader .= ""; } else { $strHeader .= ", "; }
				}
				
				$strHeader .= "\n";
				
			}
			
			// Bcc
			if (is_array($arrTo['bcc']) && count($arrTo['bcc']) > 0) {
				$strHeader .= "Bcc: ";
				for ($i = 0; $i < count($arrTo['bcc']); $i++) {
					$strHeader .= str_replace($strEliminatePatterns, $strReplacementPatterns, $arrTo['bcc'][$i]);
					if ($i == count($arrTo['bcc']) - 1) { $strHeader .= ""; } else { $strHeader .= ", "; }
				}
				
				$strHeader .= "\n";
				
			}
		
		} else {
			echo "Sorry, destination email array is not defined yet!";
			exit;
		}
		
		// Subject
	/*	if ($subject) {
			$strHeader .= "Subject: " . stripslashes($subject) . "\n";
		} else {
			echo "Sorry, the email Subject has not been define yet!";
			exit;
		}
	*/	
		// X-Priority
		if ($importance !== 'normal') {
			$strHeader .= "X-Priority: 1 (Highest)\n";
			$strHeader .= "Importance: High\n";
			// receipt notification, actually sometimes this is annoying ;)
			if ($strFrom) {
				$strHeader .= "Disposition-Notification-To: " . $strFrom;
			}
			
		}
		
		// MIME-Version
		$strHeader .= "MIME-Version: 1.0\n";
		
		// Content-Type
		$strHeader .= "Content-Type: ";
		// MIME boundary
		$strRandom = md5(time());			
		$strMIMEBoundary = "----=_NextPart_{$strRandom}";		
		
		if ($type == 'html') {
			$strHeader .= "text/html; charset=utf-8\n";
		}
		elseif ($type == 'html2') {		
			$strHeader .= "multipart/mixed;\n";				
		} else {
			$strHeader .= "text/plain; charset=us-ascii\n";
		}
			
		// read attachments	and processing message
		if (is_array($arrAttachment) && count($arrAttachment) > 0) {
			
			// if attachment(s) exist, modify the header first, make sure we put MIME boundary to separate contents
			$strHeader .= " boundary=\"{$strMIMEBoundary}\"";
			
			// add message
			$message = "This is a multi-part message in MIME format.\n\n" .
	            	   "--{$strMIMEBoundary}\n" .            	   
	            	   "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
	            	   "Content-Transfer-Encoding: 7bit\n\n" .             	    
	            	   stripslashes($message) . "\n\n";
	            	   
			
			for ($i = 0; $i < count($arrAttachment); $i++) {
	 			
				if (file_exists($arrAttachment[$i])) {
	
					$file = fopen($arrAttachment[$i],'rb');
	 				$data = fread($file, filesize($arrAttachment[$i]));
	 				fclose($file);
	 			
	 				$strFileName = basename($arrAttachment[$i]);
	 				$strExt = strrev(strtolower($strFileName));
	 				$strExtPos = strpos($strExt, '.');
	 				$strExt = substr($strExt, -$strExtPos);
	 				
	 				
	 				// if mime_content_type exist, use it, but if not define it manually
	 				if (function_exists('mime_content_type')) {
	 					$strFileType = mime_content_type($arrAttachment[$i]);
	 				} else { 					
	 					
	 					switch ($strExt) {
	 						case 'pdf': $strFileType = 'application/x-pdf';
	 							break;
	 						case 'jpg': $strFileType = 'image/jpeg';
	 							break;
	 						case 'gif': $strFileType = 'image/gif';
	 							break;
	 						case 'png': $strFileType = 'image/x-png';
	 							break;
	 						case 'xls': $strFileType = 'application/vnd.ms-excel';
	 							break;
	 						case 'doc': $strFileType = 'application/msword';
	 							break;
	 						case 'txt': $strFileType = 'text/plain';
	 							break;
	 						case 'csv': $strFileType = 'text/plain';
	 							break;
	 						case 'htm': $strFileType = 'text/html'; 
	 							break;
	 						case 'html': $strFileType = 'text/html'; 
	 							break;	
	 						default: $strFileType = 'application/octet-stream';
	 							break;
	 					}
	 				}
	 				
	 				
	 				// if attachment exist then check the allowed extensions array
					if (is_array($arrAttachmentExt) && count($arrAttachmentExt) > 0) {
				
						for ($j = 0; $j < count($arrAttachmentExt); $j++) {
							if ($strExt !== $arrAttachmentExt[$j]) {
								echo "Sorry, \"." . $strExt . "\" files are not allowed!";
								exit;
							}
						}
				
					}
	 				
	 			
	 				$data = chunk_split(base64_encode($data));
	 			
	 				$message .= "--{$strMIMEBoundary}\n" .
	             				"Content-Type: {$strFileType};\n" .
	             				" name=\"{$strFileName}\"\n" .
	             				"Content-Disposition: attachment;\n" .
	             				" filename=\"{$strFileName}\"\n" .
	             				"Content-Transfer-Encoding: base64\n\n" .
	             				$data . "\n\n";
	             
					if ($i == count($arrAttachment) - 1) {             						
	             		$message .=	"--{$strMIMEBoundary}--\n"; 
					} // end attacment boundary
					
				} // end attachment
			} // end attachment process
					
		} else {
		
			$message = stripslashes($message);			
			
		}
		
		// set $strTo to empty string to avoid double header
		$strTo = "";		
		
		// mail the message
		if (@mail($strTo, $subject, $message, $strHeader)) 
		{
			return 1;	
		} else {
			return 0;
		} 	
	
	} // sendEmail()
	
	function isSpamFree($arrFilter) 
	{
            	
	   	global $arrBadWords;
	
	   	if (is_array($arrFilter) && count($arrFilter) > 0) {
	   		for ($i = 0; $i < count($arrFilter); $i++) {
	   			for ($j = 0; $j < count($arrBadWords); $j++) {
	   				if (preg_match("/\b" . $arrBadWords[$j] . "\b/i", $arrFilter[$i])) {
	   					return FALSE;
		   			} else {
						return TRUE;
					}
					
	   			}
	   		}
	   	} else {
	   		return TRUE;	
	   	}            	
	
	} // isSpamFree()
	
	
	function validateFormChangePassword() 
	{
		
		if (!$_REQUEST['frm_user_current_password'] || 
			!$_REQUEST['frm_user_new_password'] || 
			!$_REQUEST['frm_user_password_confirm']) 
		{
				
				#$this->showHeader();
				$this->showAlert("Sorry, the form is not complete. Please fill all required fields marked with *!", FALSE);
				#$this->showFooter();		

				?>				
				<br /> 
	 			<div align="center">
				<form method="post" action="password_change.php">		 		
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
				<?php
				
				return 0;
		}
		
		elseif (ADMIN::checkPassword($_SESSION['user']['login_name'], $_REQUEST['frm_user_current_password']) == 0)
		{ 
				$this->showAlert("Sorry, wrong current password. Please correct it!", FALSE);

				?>				
				<br /> 
	 			<div align="center">
				<form method="post" action="password_change.php">		 		
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
				<?php
				
				return 0;
		
		}
			
		else 
		{
			return 1;
		}
		
	} // validateFormChangePassword()
	
	
	function listUser() 
	{
		
		global $TABLE_MAX_ROW_PER_PAGE;
				
		/* If page number not set, set it to 1 */
		if (!$_REQUEST['page_num']) { $_REQUEST['page_num'] = 1; }			

		// Setting queries and pages	
		$offset = ($_REQUEST['page_num'] - 1) * $TABLE_MAX_ROW_PER_PAGE;
	
		$this->conn = DB::dbConnect();		
		$strSearchText = stripslashes($_REQUEST['frm_search_text']);
		
		if ($_REQUEST['frm_search_text'])
		{

			$query = "SELECT * FROM `users` 
					  WHERE `user_login_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `user_full_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `user_email` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  ORDER BY `user_login_name` ASC
					  LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			$queryTotal = "SELECT COUNT(*) FROM `users` 
						   WHERE `user_login_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  	   OR `user_full_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  	   OR `user_email` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'";
		
		}
		
		else 		
		{
			$query = "SELECT * FROM `users` ORDER BY `user_login_name` ASC LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			$queryTotal = "SELECT COUNT(*) FROM `users`";
		
		}
		
		$result = mysql_query($query, $this->conn);
		
		$resultTotal = mysql_query($queryTotal, $this->conn);
		$rowTotal = mysql_fetch_row($resultTotal);
		
		$totalPage = ceil ($rowTotal[0]/$TABLE_MAX_ROW_PER_PAGE);
		
		
		
		$strResult = "";
		
		?>

		<?php if (basename($_SERVER['SCRIPT_FILENAME']) !== 'user_list_excel.php') { ?>

			<form name="search_user" method="post" action="user_list.php">
				<input type="hidden" name="frm_search_referer" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
				<input type="text" name="frm_search_text" size="40" maxlength="128" value="<?php if ($_REQUEST['frm_search_text']) { echo stripslashes($_REQUEST['frm_search_text']); }  ?>" />
				<input class="btn" type="submit" name="frm_search_submit" value="Search User" onclick="return validateSearch(this.form)" /><br />
			</form>
			
			<div align="right">
				<a class="btn" href="user_add.php" title="New User"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New User</a> 
			</div>
			
		<?php } ?>
		
		
		<?php
		
			$strResult .= "			
			<div align=\"center\"><h2>User List</h2></div>
			<section id=\"table_user_list\">
			<table class=\"table table-bordered table-hover\" summary=\"User List\">
			<caption>User List</caption>
			<thead>
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>										
					<th scope=\"col\"><div align=\"center\">Photo</div></th>
					<th scope=\"col\"><div align=\"center\">Username</div></th>
					<th scope=\"col\"><div align=\"center\">Full Name</div></th>
					<th scope=\"col\"><div align=\"center\">Group</div></th>
					<th scope=\"col\"><div align=\"center\">Level</div></th>
					<th scope=\"col\"><div align=\"center\">Email</div></th>					
					<th scope=\"col\" width=\"20%\"><div align=\"center\">Edit/Delete/Privileges</div></th>
				</tr>
			</thead>	
			
			<tbody>";
			
		?>
			
		<?php
			
			if ($rowTotal[0] > 0) 
			{
			
				$no = $offset;		
				while ($row = mysql_fetch_assoc($result)) 
				{
					$no++;
		?>	
					
		<?php
		
				$strResult .= "
					<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
						<td id=\"r" . $row['user_id'] . "\"><div align=\"right\">" . $no . ".</div></td>
						<td><div align=\"center\">"; if ($row['user_photo']) { $strResult .= "<div align=\"center\"><a href=\"user_view.php?user_id=" . $row['user_id'] . "\"><img src=\"uploads/user/" . $row['user_photo'] . "\" style=\"width:50px; border: 1px solid #000;\" title=\"" . stripslashes($row['user_full_name']) . "\" /></a></div>"; } else { $strResult .= "<img src=\"img/user_icon.png\" /> ";  } $strResult .= "</td>
						<td><div align=\"left\"><a href=\"user_view.php?user_id=" . $row['user_id'] . "\"><strong>" . stripslashes($row['user_login_name']) . "</strong></a></div></td>
						<td><div align=\"left\">" . stripslashes($row['user_full_name']) . "</div></td>
						<td><div align=\"left\">" . stripslashes(DB::dbIDToField('user_groups', 'user_group_id', $row['user_group_id'], 'user_group_name')) . "</div></td>
						<td><div align=\"left\">" . stripslashes($row['user_level']) . "</div></td>
						<td><div align=\"left\"><a href=\"mailto:" . strtolower($row['user_email']) . "\">" . stripslashes(strtolower($row['user_email'])) . "</a></div></td>
						<td><div align=\"center\"><a class=\"btn\" href=\"user_edit.php?user_id=" . $row['user_id'] . "\" title=\"Edit User\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp; <a class=\"btn\" href=\"user_delete.php?user_id=" . $row['user_id'] . "&action=delete\" title=\"Delete User\" onclick=\"return confirmDeleteUser(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a> &nbsp;&nbsp;<br /><br /><a class=\"btn\" href=\"privileges_list.php?frm_user_id=" . $row['user_id'] . "\" title=\"User Privileges\"><img src=\"img/priv_icon.png\" /> Privileges</a></div></td>
					</tr>";
		
		?>
									
		<?php
				}
			
			} 
			
			else 
			
			{
				$strResult .= "<tr><td colspan=\"8\"><div align=\"center\">Found no data</div></td></tr>";
			
			}
		?>
		
		<?php
		
		$strResult .= "
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"6\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', $_REQUEST['frm_search_text']))) . "</td>
				</tr>
			</tfoot>
			</table>
			</section>
			<a class=\"btn\" href=\"#content\"><i class=\"icon-arrow-up\"></i> Back to top</a>
			";
		
		// The Log
		$strLog = "View the User List";
			
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
				
		
	} // listUser()

	
	function viewUser() 
	{
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `users` WHERE `user_id` = '" . $_REQUEST['user_id'] . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
			
			if ($row['user_photo'] && $_REQUEST['action'] == 'del_image' && $_REQUEST['user_id']) 
			{
				@unlink('uploads/user/' . $row['user_photo']);				
				$queryDelImg = "UPDATE `users` SET `user_photo` = '' WHERE `user_id` = '" . $_REQUEST['user_id'] . "' LIMIT 1";
				$resultDelImg = mysql_query($queryDelImg, $this->conn);
				HTML::redirectUser($_SERVER['PHP_SELF'] . "?user_id=" . $_REQUEST['user_id'], 1);
			}
			
			?>

			<h2>User Information &raquo; <?php echo stripslashes($row['user_login_name']); ?></h2>
			<table cellpadding="5" cellspacing="1" border="0">
			  <tr>
			  	<td colspan="2">
			  	<div align="left">
			  	<?php if ($_SESSION['user']['type'] == 'admin') { ?><a class="btn" href="user_add.php" title="New User"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New User</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_edit.php?user_id=<?php echo $row['user_id']; ?>" title="Edit User"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_delete.php?user_id=<?php echo $row['user_id']; ?>&action=delete" title="Delete User" onclick="return confirmDeleteUser(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_list.php" title="User List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a><?php } else { echo '&nbsp;'; } ?>			  	
				 </div>			  	
			  	</td>
			  </tr>			  
			  <tr>
			  	<td colspan="2"><?php if ($row['user_photo']) { echo "<img src=\"uploads/user/" . $row['user_photo'] . "\" style=\"width: 100px; margin-right:8px; border-top: 1px solid #ccc; border-right: 2px solid #999; border-bottom: 2px solid #999; border-left: 1px solid #ccc; padding: 3px;\" />\n<br /><a class=\"btn\" href=\"" . $_SERVER['PHP_SELF'] . "?user_id=" . $_REQUEST['user_id'] . "&action=del_image\" onclick=\"return confirmDeletePhoto(this.form)\" title=\"Delete Photo\"><img src=\"img/delete_icon.png\" /> Delete Photo</a>"; } else { echo $row['user_photo']; } ?></td>
			  </tr>
			  <tr>
			  	<td colspan="2"><strong><u>Login Information</u></strong></td>			  	
			  </tr>			
			  <tr>
			  	<td width="30%"><strong>Username</strong></td>
			  	<td>: <strong><?php echo stripslashes($row['user_login_name']); ?></strong></td>
			  </tr>
			  <tr>
			  	<td><strong>Group</strong></td>
			  	<td>: <?php echo stripslashes(DB::dbIDToField('user_groups', 'user_group_id', $row['user_group_id'], 'user_group_name')); ?></td>
			  </tr>
			  <tr>
			  	<td><strong>Level</strong></td>
			  	<td>: <?php echo stripslashes($row['user_level']); ?></td>
			  </tr>
			  <tr>
			  	<td><strong>Last Login</strong></td>
			  	<td>: <?php if ($row['user_last_login_time'] == '0000-00-00 00:00:00') { echo 'Belum pernah login'; } else { echo HTML::convertDateTime($row['user_last_login_time']); } if ($row['user_last_login_from']) { ?> from <?php echo $row['user_last_login_from']; } ?></td>
			  </tr>
			  <tr>
			  	<td><strong>Active Period</strong></td>
			  	<td>: <?php echo HTML::convertDate($row['user_subscription_start']); ?> s/d <?php echo HTML::convertDate($row['user_subscription_end']); ?></td>
			  </tr>
			  <tr>
			  	<td colspan="2">&nbsp;</td>			  	
			  </tr>
  			  <tr>
			  	<td colspan="2"><strong><u>Profil</u></strong></td>			  	
			  </tr>			
			  <tr>
			  	<td><strong>Full Name</strong></td>
			  	<td>: <?php echo stripslashes($row['user_full_name']); ?></td>
			  </tr>
			  <tr>
			  	<td><strong>Email</strong></td>
			  	<td>: <a href="mailto:<?php echo stripslashes(strtolower($row['user_email'])); ?>"><?php echo stripslashes(strtolower($row['user_email'])); ?></a></td>
			  </tr>
			  <tr>
			  	<td><strong>Description</strong></td>
			  	<td>: <?php echo stripslashes($row['user_description']); ?></td>
			  </tr>
			</table>
			
			
			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['user_created_date']); ?> by <strong><?php echo stripslashes($row['user_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['user_modified_date']); ?> by <strong><?php echo stripslashes($row['user_modified_by']); ?></strong></li>
			</ul>
			<?php
		}
		
		else 
		{
			echo "<div align=\"center\"><p><strong>Found no data!</strong></p></div>";
		}
		
		?>
		<div align="center">
		<form name="myform" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "user_list.php"; } else { echo "user_list.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
			<input class="btn" type="submit" value="Back" onclick="this.value='Loading...'">
		</form>
		</div>		
		<?php  
		
	} // viewUser()
	
	function viewUserProfile() 
	{
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `users` WHERE `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
			
			if ($row['user_photo'] && $_REQUEST['action'] == 'del_image' && $_SESSION['user']['id']) 
			{
				@unlink('uploads/user/' . $row['user_photo']);				
				$queryDelImg = "UPDATE `users` SET `user_photo` = '' WHERE `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
				$resultDelImg = mysql_query($queryDelImg, $this->conn);
				HTML::redirectUser($_SERVER['PHP_SELF'] . "?user_id=" . $_SESSION['user']['id'], 1);
			}
			
			?>

			<h2>Profile</h2>
			<table cellpadding="5" cellspacing="1" border="0">
			  <tr>
			  	<td colspan="2">
			  	<div align="left">
			  	<?php if ($_SESSION['user']['type'] == 'admin') { ?><a class="btn" href="user_add.php" title="New User"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New User</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_edit.php?user_id=<?php echo $row['user_id']; ?>" title="Edit User"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_delete.php?user_id=<?php echo $row['user_id']; ?>&action=delete" title="Delete User" onclick="return confirmDeleteUser(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_list.php" title="User List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a><br /><br /><?php } else { echo '&nbsp;'; } ?>
			  	<a class="btn" href="user_profile_edit.php?user_id="<?php echo $_SESSION['user']['id']; ?> title="Edit Profile"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit Profile</a> &nbsp;&nbsp;&nbsp; 
				 </div>			  	
			  	</td>
			  </tr>			  
			  <tr>
			  	<td colspan="2"><?php if ($row['user_photo']) { echo "<img src=\"uploads/user/" . $row['user_photo'] . "\" style=\"width: 100px; margin-right:8px; border-top: 1px solid #ccc; border-right: 2px solid #999; border-bottom: 2px solid #999; border-left: 1px solid #ccc; padding: 3px;\" />\n<br /><a class=\"btn\" href=\"" . $_SERVER['PHP_SELF'] . "?user_id=" . $_SESSION['user']['id'] . "&action=del_image\" onclick=\"return confirmDeletePhoto(this.form)\" title=\"Delete Photo\"><img src=\"img/delete_icon.png\" /> Delete Photo</a>"; } else { echo $row['user_photo']; } ?></td>
			  </tr>
			  <tr>
			  	<td colspan="2"><strong><u>Login Information</u></strong></td>			  	
			  </tr>			
			  <tr>
			  	<td width="30%"><strong>Username</strong></td>
			  	<td>: <?php echo stripslashes($row['user_login_name']); ?></td>
			  </tr>
			  <tr>
			  	<td><strong>Last Login</strong></td>
			  	<td>: <?php if ($row['user_last_login_time'] == '0000-00-00 00:00:00') { echo 'Belum pernah login'; } else { echo HTML::convertDateTime($row['user_last_login_time']); } if ($row['user_last_login_from']) { ?> from <?php echo $row['user_last_login_from']; } ?></td>
			  </tr>
			  <tr>
			  	<td><strong>Active Period</strong></td>
			  	<td>: <?php echo HTML::convertDate($row['user_subscription_start']); ?> s/d <?php echo HTML::convertDate($row['user_subscription_end']); ?></td>
			  </tr>
			  <tr>
			  	<td colspan="2">&nbsp;</td>			  	
			  </tr>
  			  <tr>
			  	<td colspan="2"><strong><u>Profile</u></strong></td>			  	
			  </tr>			
			  <tr>
			  	<td><strong>Full Name</strong></td>
			  	<td>: <?php echo stripslashes($row['user_full_name']); ?></td>
			  </tr>
			  <tr>
			  	<td><strong>Email</strong></td>
			  	<td>: <a href="mailto:<?php echo stripslashes(strtolower($row['user_email'])); ?>"><?php echo stripslashes(strtolower($row['user_email'])); ?></a></td>
			  </tr>
			  <tr>
			  	<td><strong>Description</strong></td>
			  	<td>: <?php echo stripslashes($row['user_description']); ?></td>
			  </tr>
			</table>
			
			
			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['user_created_date']); ?> by <strong><?php echo stripslashes($row['user_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['user_modified_date']); ?> by <strong><?php echo stripslashes($row['user_modified_by']); ?></strong></li>
			</ul>
			<?php
		}
		
		else 
		{
			echo "<div align=\"center\"><p><strong>Found no data!</strong></p></div>";
		}
		
		?>
		<div align="center">
		<form name="myform" action="<?php echo $_SERVER['HTTP_REFERER']; ?>">
			<input class="btn" type="submit" value="Back" onclick="this.value='Loading...'">
		</form>
		</div>
		
		<?php 

		// The Log	
		$strLog = "View the User Profile";
			
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

		
	} // viewUserProfile()
	
	
	function validateFormUserProfile() 
	{				
		
		if ($_SESSION['user']['id']) 
		{
										
				if (!$_REQUEST['frm_user_full_name'] || 
					!$_REQUEST['frm_user_email']) 
					{
						
						$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);						
										
						?>				
						<br /> 
			 			<div align="center">
						<form method="post" action="user_profile_edit.php">
						<input type="hidden" name="user_id" value="<?php echo $_REQUEST['user_id']; ?>">
		 				<input type="hidden" name="user_photo" value="<?php echo $_REQUEST['user_photo']; ?>">
				 		<input type="hidden" name="frm_user_full_name" value="<?php echo $_REQUEST['frm_user_full_name']; ?>">
				 		<input type="hidden" name="frm_user_email" value="<?php echo $_REQUEST['frm_user_email']; ?>">
				 		<input type="hidden" name="frm_user_description" value="<?php echo $_REQUEST['frm_user_description']; ?>">
						<input type="submit" name="correct" value="Fix it">
						</form>
						</div>
						<?php
						
						return 0;
						
					}
					
					elseif (!(eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$', $_REQUEST['frm_user_email']))) 
		   			
		   			{
		      					
						$this->showAlert ("Sorry, \"" . $_REQUEST['frm_user_email'] . "\" tidak benar!", FALSE);	      
		      	
						?>				
						<br /> 
			 			<div align="center">
						<form method="post" action="user_add.php">
				 		<input type="hidden" name="frm_user_login_name" value="<?php echo strtolower($_REQUEST['frm_user_login_name']); ?>">
				 		<input type="hidden" name="frm_user_full_name" value="<?php echo $_REQUEST['frm_user_full_name']; ?>">
				 		<input type="hidden" name="frm_user_email" value="<?php echo $_REQUEST['frm_user_email']; ?>">
				 		<input type="hidden" name="frm_user_description" value="<?php echo $_REQUEST['frm_user_description']; ?>">
						<input type="submit" name="correct" value="Fix it">
						</form>
						</div>
						<?php
						
						return 0;
						
					}
					 
					else 
					{
						return 1;
					}
										
			
		}
		
		else 
		
		{
			$this->showAlert ("Sorry, you cannot update the profile!", FALSE);	      
		
		} 
		
	} // validateFormUserProfile()
	
	
	function validateFormUserPasswordChange()
	{
		
		if (!$_REQUEST['frm_user_current_password'] || 
			!$_REQUEST['frm_user_password'] || 
			!$_REQUEST['frm_user_password_confirm']) 
			{
						
				$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);						
										
				?>				
				<br /> 
	 			<div align="center">
				<form method="post" action="user_password_change.php">
				<input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['id']; ?>">
 				<input type="hidden" name="frm_user_current_password" value="<?php echo $_REQUEST['frm_user_current_password']; ?>">
 				<input type="hidden" name="frm_user_password" value="<?php echo $_REQUEST['frm_user_password']; ?>">
 				<input type="hidden" name="frm_user_password_confirm" value="<?php echo $_REQUEST['frm_user_password_confirm']; ?>">
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
				<?php
						
				return 0;
						
			}
			
			elseif (ADMIN::checkPassword($_SESSION['user']['login_name'], $_REQUEST['frm_user_current_password']) == '0')
			{
				$this->showAlert("Sorry, you didn't fill the current password correctly. Please fix it!", FALSE);
										
				?>				
				<br /> 
	 			<div align="center">
				<form method="post" action="user_password_change.php">
				<input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['id']; ?>">
 				<input type="hidden" name="frm_user_current_password" value="<?php echo $_REQUEST['frm_user_current_password']; ?>">
 				<input type="hidden" name="frm_user_password" value="<?php echo $_REQUEST['frm_user_password']; ?>">
 				<input type="hidden" name="frm_user_password_confirm" value="<?php echo $_REQUEST['frm_user_password_confirm']; ?>">
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
				<?php
						
				return 0;
					
			}
			
			else 
			
			{
				return 1;
			
			}
				
	} // validateFormUserPasswordChange()
	
	
	
	function validateFormUser() 
	{				
		
		if ($_REQUEST['frm_user_id']) 
		{
						
				// for user edit	
				if (!$_REQUEST['frm_user_group_id'] ||
					!$_REQUEST['frm_user_full_name'] || 
					!$_REQUEST['frm_user_email'] || 
					!$_REQUEST['frm_user_level']) 
					{
						
						$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);						
										
						?>				
						<br /> 
			 			<div align="center">
						<form method="post" action="user_edit.php">
						<input type="hidden" name="user_id" value="<?php echo $_REQUEST['user_id']; ?>">
		 				<input type="hidden" name="user_photo" value="<?php echo $_REQUEST['user_photo']; ?>">
				 		<input type="hidden" name="frm_user_full_name" value="<?php echo $_REQUEST['frm_user_full_name']; ?>">
				 		<input type="hidden" name="frm_user_email" value="<?php echo $_REQUEST['frm_user_email']; ?>">
				 		<input type="hidden" name="frm_user_description" value="<?php echo $_REQUEST['frm_user_description']; ?>">
				 		<input type="hidden" name="frm_user_group_id" value="<?php echo $_REQUEST['frm_user_group_id']; ?>">
				 		<input type="hidden" name="frm_user_level" value="<?php echo $_REQUEST['frm_user_level']; ?>">
				 		<input type="hidden" name="frm_user_subscription_start_day" value="<?php echo $_REQUEST['frm_user_subscription_start_day']; ?>">
				 		<input type="hidden" name="frm_user_subscription_start_month" value="<?php echo $_REQUEST['frm_user_subscription_start_month']; ?>">
				 		<input type="hidden" name="frm_user_subscription_start_year" value="<?php echo $_REQUEST['frm_user_subscription_start_year']; ?>">
				 		<input type="hidden" name="frm_user_subscription_end_day" value="<?php echo $_REQUEST['frm_user_subscription_end_day']; ?>">
				 		<input type="hidden" name="frm_user_subscription_end_month" value="<?php echo $_REQUEST['frm_user_subscription_end_month']; ?>">
				 		<input type="hidden" name="frm_user_subscription_end_year" value="<?php echo $_REQUEST['frm_user_subscription_end_year']; ?>">
				 		
						<input type="submit" name="correct" value="Fix it">
						</form>
						</div>
						<?php
						
						return 0;
						
					} 
					
					else 
					{
						return 1;
					}
					
			
		} 
		
		else 		
		{
		
			if (!$_REQUEST['frm_user_login_name'] || 
				!$_REQUEST['frm_user_password'] || 
				!$_REQUEST['frm_user_password_confirm'] || 
				!$_REQUEST['frm_user_group_id'] ||				
				!$_REQUEST['frm_user_full_name'] || 
				!$_REQUEST['frm_user_email'] || 
				!$_REQUEST['frm_user_level']) 
				{
					
					$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);					
			
					?>				
					<br /> 
		 			<div align="center">
					<form method="post" action="user_add.php">
			 		<input type="hidden" name="frm_user_login_name" value="<?php echo strtolower($_REQUEST['frm_user_login_name']); ?>">
			 		<input type="hidden" name="frm_user_full_name" value="<?php echo $_REQUEST['frm_user_full_name']; ?>">
			 		<input type="hidden" name="frm_user_email" value="<?php echo $_REQUEST['frm_user_email']; ?>">
			 		<input type="hidden" name="frm_user_description" value="<?php echo $_REQUEST['frm_user_description']; ?>">
			 		<input type="hidden" name="frm_user_group_id" value="<?php echo $_REQUEST['frm_user_group_id']; ?>">
			 		<input type="hidden" name="frm_user_level" value="<?php echo $_REQUEST['frm_user_level']; ?>">
			 		<input type="hidden" name="frm_user_subscription_start_day" value="<?php echo $_REQUEST['frm_user_subscription_start_day']; ?>">
				 	<input type="hidden" name="frm_user_subscription_start_month" value="<?php echo $_REQUEST['frm_user_subscription_start_month']; ?>">
				 	<input type="hidden" name="frm_user_subscription_start_year" value="<?php echo $_REQUEST['frm_user_subscription_start_year']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_day" value="<?php echo $_REQUEST['frm_user_subscription_end_day']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_month" value="<?php echo $_REQUEST['frm_user_subscription_end_month']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_year" value="<?php echo $_REQUEST['frm_user_subscription_end_year']; ?>">
					<input type="submit" name="correct" value="Fix it">
					</form>
					</div>
					<?php
					
					return 0;
					
				} 
				
				elseif (ADMIN::checkUser(strtolower($_REQUEST['frm_user_login_name'])) > 0) 				
				{
			
				   	$this->showAlert("Sorry, username \"" . strtolower($_REQUEST['frm_user_login_name']) . "\" is not available!", FALSE);
			
					?>				
					<br /> 
		 			<div align="center">
					<form method="post" action="user_add.php">
			 		<input type="hidden" name="frm_user_login_name" value="<?php echo strtolower($_REQUEST['frm_user_login_name']); ?>">
			 		<input type="hidden" name="frm_user_full_name" value="<?php echo $_REQUEST['frm_user_full_name']; ?>">
			 		<input type="hidden" name="frm_user_email" value="<?php echo $_REQUEST['frm_user_email']; ?>">
			 		<input type="hidden" name="frm_user_description" value="<?php echo $_REQUEST['frm_user_description']; ?>">
			 		<input type="hidden" name="frm_user_group_id" value="<?php echo $_REQUEST['frm_user_group_id']; ?>">
			 		<input type="hidden" name="frm_user_level" value="<?php echo $_REQUEST['frm_user_level']; ?>">
			 		<input type="hidden" name="frm_user_subscription_start_day" value="<?php echo $_REQUEST['frm_user_subscription_start_day']; ?>">
				 	<input type="hidden" name="frm_user_subscription_start_month" value="<?php echo $_REQUEST['frm_user_subscription_start_month']; ?>">
				 	<input type="hidden" name="frm_user_subscription_start_year" value="<?php echo $_REQUEST['frm_user_subscription_start_year']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_day" value="<?php echo $_REQUEST['frm_user_subscription_end_day']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_month" value="<?php echo $_REQUEST['frm_user_subscription_end_month']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_year" value="<?php echo $_REQUEST['frm_user_subscription_end_year']; ?>">
					<input type="submit" name="correct" value="Fix it">
					</form>
					</div>
					<?php
		   			
					return 0;
					
	   			} 
	   			
	   			elseif (!(ereg('^[_a-z0-9-]+$', strtolower($_REQUEST['frm_user_login_name']))))   				
	   			{
	      	
	   				
	   				$this->showAlert ("Sorry, username cannot contain reserved characters such as <i>\"\$\",\"^\", \"~\", \"@\", \"#\", \"\"\", \"'\"</i> etc.", FALSE);
	      				      	
					?>				
					<br /> 
		 			<div align="center">
					<form method="post" action="user_add.php">
			 		<input type="hidden" name="frm_user_login_name" value="<?php echo strtolower($_REQUEST['frm_user_login_name']); ?>">
			 		<input type="hidden" name="frm_user_full_name" value="<?php echo $_REQUEST['frm_user_full_name']; ?>">
			 		<input type="hidden" name="frm_user_email" value="<?php echo $_REQUEST['frm_user_email']; ?>">
			 		<input type="hidden" name="frm_user_description" value="<?php echo $_REQUEST['frm_user_description']; ?>">
			 		<input type="hidden" name="frm_user_group_id" value="<?php echo $_REQUEST['frm_user_group_id']; ?>">
			 		<input type="hidden" name="frm_user_level" value="<?php echo $_REQUEST['frm_user_level']; ?>">
			 		<input type="hidden" name="frm_user_subscription_start_day" value="<?php echo $_REQUEST['frm_user_subscription_start_day']; ?>">
				 	<input type="hidden" name="frm_user_subscription_start_month" value="<?php echo $_REQUEST['frm_user_subscription_start_month']; ?>">
				 	<input type="hidden" name="frm_user_subscription_start_year" value="<?php echo $_REQUEST['frm_user_subscription_start_year']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_day" value="<?php echo $_REQUEST['frm_user_subscription_end_day']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_month" value="<?php echo $_REQUEST['frm_user_subscription_end_month']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_year" value="<?php echo $_REQUEST['frm_user_subscription_end_year']; ?>">
					<input type="submit" name="correct" value="Fix it">
					</form>
					</div>
					<?php
							
					return 0;
					
	   			} 
	   			
	   			elseif (!(eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$', $_REQUEST['frm_user_email']))) 	   			
	   			{
	      					
					$this->showAlert ("Sorry, \"" . $_REQUEST['frm_user_email'] . "\" is invalid!", FALSE);	      
	      	
					?>				
					<br /> 
		 			<div align="center">
					<form method="post" action="user_add.php">
			 		<input type="hidden" name="frm_user_login_name" value="<?php echo strtolower($_REQUEST['frm_user_login_name']); ?>">
			 		<input type="hidden" name="frm_user_full_name" value="<?php echo $_REQUEST['frm_user_full_name']; ?>">
			 		<input type="hidden" name="frm_user_email" value="<?php echo $_REQUEST['frm_user_email']; ?>">
			 		<input type="hidden" name="frm_user_description" value="<?php echo $_REQUEST['frm_user_description']; ?>">
			 		<input type="hidden" name="frm_user_group_id" value="<?php echo $_REQUEST['frm_user_group_id']; ?>">
			 		<input type="hidden" name="frm_user_level" value="<?php echo $_REQUEST['frm_user_level']; ?>">
			 		<input type="hidden" name="frm_user_subscription_start_day" value="<?php echo $_REQUEST['frm_user_subscription_start_day']; ?>">
				 	<input type="hidden" name="frm_user_subscription_start_month" value="<?php echo $_REQUEST['frm_user_subscription_start_month']; ?>">
				 	<input type="hidden" name="frm_user_subscription_start_year" value="<?php echo $_REQUEST['frm_user_subscription_start_year']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_day" value="<?php echo $_REQUEST['frm_user_subscription_end_day']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_month" value="<?php echo $_REQUEST['frm_user_subscription_end_month']; ?>">
				 	<input type="hidden" name="frm_user_subscription_end_year" value="<?php echo $_REQUEST['frm_user_subscription_end_year']; ?>">
					<input type="submit" name="correct" value="Fix it">
					</form>
					</div>
					<?php
					
					return 0;
					
				} 
				
				else 				
				{
					return 1;
				}
			
		}
		
	} // validateFormUser()
	
	
	function listUserGroup() 
	{
		
		global $TABLE_MAX_ROW_PER_PAGE;
				
		/* If page number not set, set it to 1 */
		if (!$_REQUEST['page_num']) { $_REQUEST['page_num'] = 1; }			

		// Setting queries and pages	
		$offset = ($_REQUEST['page_num'] - 1) * $TABLE_MAX_ROW_PER_PAGE;
	
		$this->conn = DB::dbConnect();		
		$strSearchText = stripslashes($_REQUEST['frm_search_text']);
		
		if ($_REQUEST['frm_search_text'])
		{

			$query = "SELECT * FROM `user_groups` 
					  WHERE `user_group_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `user_group_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  ORDER BY `user_group_name` ASC
					  LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			$queryTotal = "SELECT COUNT(*) FROM `user_groups` 
					       WHERE `user_group_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					       OR `user_group_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'";
		
		}
		
		else 		
		{
			$query = "SELECT * FROM `user_groups` ORDER BY `user_group_name` ASC LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			$queryTotal = "SELECT COUNT(*) FROM `user_groups`";
		
		}
		
		$result = mysql_query($query, $this->conn);
		
		$resultTotal = mysql_query($queryTotal, $this->conn);
		$rowTotal = mysql_fetch_row($resultTotal);
		
		$totalPage = ceil ($rowTotal[0]/$TABLE_MAX_ROW_PER_PAGE);
		
		$strResult = "";
		?>
			
			<form name="search_user_group" method="post" action="user_group_list.php">
				<input type="hidden" name="frm_search_referer" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
				<input type="text" name="frm_search_text" size="40" maxlength="128" value="<?php if ($_REQUEST['frm_search_text']) { echo stripslashes($_REQUEST['frm_search_text']); }  ?>" />
				<input class="btn" type="submit" name="frm_search_submit" value="Search User Group" onclick="return validateSearch(this.form)" /><br />
			</form>
											
			
			<div align="right">
				<a class="btn" href="user_group_add.php" title="New User Group"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New User Group</a> 
			</div>
		
		<?php
		
			$strResult .= "			
			<div align=\"center\"><h2>User Group List</h2></div>
			<section id=\"table_user_group_list\">
			<table class=\"table table-bordered table-hover\" summary=\"User Group List\" width=\"100%\" border=\"1\">
			<caption>User Group List</caption>
			<thead>
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>															
					<th scope=\"col\"><div align=\"center\">Usergroup</div></th>
					<th scope=\"col\"><div align=\"center\">Description</div></th>
					<th scope=\"col\"><div align=\"center\">Aktif</div></th>					
					<th scope=\"col\" width=\"20%\"><div align=\"center\">Edit/Delete/Privileges</div></th>
				</tr>
			</thead>	
			
			<tbody>";
			
		?>
			
		<?php
			
			if ($rowTotal[0] > 0) 
			{
			
				$no = $offset;		
				while ($row = mysql_fetch_assoc($result)) 
				{
					$no++;
		?>	
					
		<?php
		
				$strResult .= "
					<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
						<td id=\"r" . $row['user_group_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
						<td><div align=\"left\"><a href=\"user_group_view.php?user_group_id=" . $row['user_group_id'] . "\" title=\"" . stripslashes($row['user_group_name']) . "\"><strong>" . stripslashes($row['user_group_name']) . "</strong></a></div></td>
						<td><div align=\"left\">" . stripslashes($row['user_group_description']) . "</div></td>
						<td><div align=\"center\">"; if ($row['user_group_activate'] == 'yes') { $strResult .= "Yes"; } else { $strResult .= "No"; }  $strResult .= "</div></td>						
						<td><div align=\"center\"><a class=\"btn\" href=\"user_group_edit.php?user_group_id=" . $row['user_group_id'] . "\" title=\"Edit User Group\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp; <a class=\"btn\" href=\"user_group_delete.php?user_group_id=" . $row['user_group_id'] . "&action=delete\" title=\"Delete User Group\" onclick=\"return confirmDeleteUserGroup(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a> &nbsp;&nbsp;<br /><br /><a class=\"btn\" href=\"privileges_list.php?frm_user_group_id=" . $row['user_group_id'] . "\" title=\"User Privileges Group\"><img src=\"img/priv_icon.png\" /> Privileges</a></div></td>
					</tr>";
		
		?>
									
		<?php
				}
			
			} 
			
			else 
			
			{
				$strResult .= "<tr><td colspan=\"5\"><div align=\"center\">Found no data</div></td></tr>";
			
			}
		?>
		
		<?php
		
		$strResult .= "
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"3\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', $_REQUEST['frm_search_text']))) . "</td>
				</tr>
			</tfoot>
			</table>
			</section>
			<a class=\"btn\" href=\"#content\"><i class=\"icon-arrow-up\"></i> Back to top</a>
			";
		
		// The Log
		$strLog = "View the Activity List";
			
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
		
	} // listUserGroup()
	
	
	function viewUserGroup() 
	{
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `user_groups` WHERE `user_group_id` = '" . $_REQUEST['user_group_id'] . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
						
			?>

			<h2>User Group Information &raquo; <?php echo stripslashes($row['user_group_name']); ?></h2>
			<table cellpadding="5" cellspacing="1" border="0">
			  <tr>
			  	<td colspan="2">
			  	<div align="left">
			  	<?php if ($_SESSION['user']['type'] == 'admin') { ?><a class="btn" href="user_group_add.php" title="New User Group"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New User Group</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_group_edit.php?user_group_id=<?php echo $row['user_group_id']; ?>" title="Edit User Group"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_group_delete.php?user_group_id=<?php echo $row['user_group_id']; ?>&action=delete" title="Delete User Group" onclick="return confirmDeleteUserGroup(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="user_group_list.php" title="User Group List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a><?php } else { echo '&nbsp;'; } ?>			  	
				 </div>			  	
			  	</td>
			  </tr>			  
			  
			  
			  <tr>
			  	<td width="30%"><strong>User Group Name</strong></td>
			  	<td>: <strong><?php echo stripslashes($row['user_group_name']); ?></strong></td>
			  </tr>			  
			  <tr>
			  	<td><strong>Description</strong></td>
			  	<td>: <?php echo stripslashes($row['user_group_description']); ?></td>
			  </tr>
			  <tr>
			  	<td><strong>Active</strong></td>
			  	<td>: <?php if ($row['user_group_activate'] == 'yes') { echo "Yes"; } else { echo "No"; } ?></td>
			  </tr>
			</table>
			
			
			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['user_group_created_date']); ?> by <strong><?php echo stripslashes($row['user_group_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['user_group_modified_date']); ?> by <strong><?php echo stripslashes($row['user_group_modified_by']); ?></strong></li>
			</ul>
			<?php
		}
		
		else 
		{
			echo "<div align=\"center\"><p><strong>Found no data!</strong></p></div>";
		}
		
		?>
		<div align="center">
		<form name="myform" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "user_group_list.php"; } else { echo "user_group_list.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
			<input class="btn" type="submit" value="Back">
		</form>
		</div>		
		<?php 
		
	} // viewUserGroup()
	
	
	function listPrivileges() 
	{

		// determine $_REQUEST['user_id'] and $_REQUEST['frm_user_id']
		if (!$_REQUEST['user_id'] && $_REQUEST['frm_user_id']) 
		{
			$_REQUEST['user_id'] = $_REQUEST['frm_user_id'];
		}
		
		else 		
		{
			$_REQUEST['frm_user_id'] = $_REQUEST['user_id'];				
		}
		
		// determine $_REQUEST['user_group_id'] and $_REQUEST['frm_user_group_id']
		if (!$_REQUEST['user_group_id'] && $_REQUEST['frm_user_group_id']) 
		{
			$_REQUEST['user_group_id'] = $_REQUEST['frm_user_group_id'];
		}
		
		else 		
		{
			$_REQUEST['frm_user_group_id'] = $_REQUEST['user_group_id'];				
		}
		
		// always clean up orphaned privileges first
		$queryCleanUpUser = "DELETE FROM `privileges` WHERE `user_id` NOT IN (SELECT `user_id` FROM `users`) AND `user_id` <> 0";
		$resultCleanUpUser = mysql_query($queryCleanUpUser);

		$queryCleanUpUserGroup = "DELETE FROM `privileges` WHERE `user_group_id` NOT IN (SELECT `user_group_id` FROM `user_groups`) AND `user_group_id` <> 0";
		$resultCleanUpUserGroup = mysql_query($queryCleanUpUserGroup);
		
		#print_r($_REQUEST);
		#echo "<br><br>";
		#print_r($_REQUEST['frm_module_id']);
		
		$this->conn = DB::dbConnect();		
		
		// insert module privilege(s)
		if (is_array($_REQUEST['frm_module_id']) && count($_REQUEST['frm_module_id']) > 0) 
		{
			
			for ($i = 0; $i < count($_REQUEST['frm_module_id']); $i++)
			{
				if (!$_REQUEST['frm_user_id']) { $_REQUEST['frm_user_id'] = '0'; }
				if (!$_REQUEST['frm_user_group_id']) { $_REQUEST['frm_user_group_id'] = '0'; }
				
				
				$queryPrivInsert = "INSERT INTO `privileges` (`priv_id`, 
															  `user_id`, 
															  `user_group_id`, 
															  `module_id`, 
															  `priv_list`, 
															  `priv_add`, 
															  `priv_edit`, 
															  `priv_delete`, 
															  `priv_view`, 
															  `priv_execute`, 
															  `priv_create_date`, 
															  `priv_created_by`, 
															  `priv_modify_date`, 
															  `priv_modified_by`) 
															  
														VALUES (NULL, 
																'" . $_REQUEST['frm_user_id'] . "', 
																'" . $_REQUEST['frm_user_group_id'] . "',  
																'" . $_REQUEST['frm_module_id'][$i] . "', 
																'no', 
																'no', 
																'no', 
																'no', 
																'no', 
																'no', 
																NOW(), 
																'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', 
																NOW(), 
																'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "')		  
															  ";
				
				$queryPrivUpdate = "UPDATE `privileges` SET `priv_list` = 'no', 
															`priv_add` = 'no', 
															`priv_edit` = 'no', 
															`priv_delete` = 'no', 
															`priv_view` = 'no', 
															`priv_execute` = 'no', 															 
															`priv_modify_date` = NOW(),  
															`priv_modified_by` = '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "' 
															 
													WHERE ";
				
				if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id']) 
				{
					$queryPrivUpdate .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
					$queryPrivUpdate .= "`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
				{
					$queryPrivUpdate .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
				{
					$queryPrivUpdate .= "`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}

				$queryPrivUpdate .= "AND `module_id` = '" . $_REQUEST['frm_module_id'][$i] . "' 
									 LIMIT 1";
				
				
				if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id']) 
				{
					if (ADMIN::isModulePrivilegeExist($_REQUEST['frm_user_id'], $_REQUEST['frm_module_id'][$i], 'user') == '0' && ADMIN::isModulePrivilegeExist($_REQUEST['frm_user_group_id'], $_REQUEST['frm_module_id'][$i], 'group') == '0')
					{
						$resultPrivInsert = mysql_query($queryPrivInsert);
						#echo "<br />" . $queryPrivInsert . "#1<br /><br />";
					}
					
					else 
					{
						$resultPrivUpdate = mysql_query($queryPrivUpdate);
						#echo "<br />" . $queryPrivUpdate . "#1<br /><br />";					
					}
				}
				
				elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
				{
					if (ADMIN::isModulePrivilegeExist($_REQUEST['frm_user_id'], $_REQUEST['frm_module_id'][$i], 'user') == '0')
					{
						$resultPrivInsert = mysql_query($queryPrivInsert);
						#echo "<br />" . $queryPrivInsert . "#2<br /><br />";
					}
					
					else 
					{
						$resultPrivUpdate = mysql_query($queryPrivUpdate);
						#echo "<br />" . $queryPrivUpdate . "#2<br /><br />";					
					}
				}
				
				elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id']) 
				{
					if (ADMIN::isModulePrivilegeExist($_REQUEST['frm_user_group_id'], $_REQUEST['frm_module_id'][$i], 'group') == '0')
					{
						$resultPrivInsert = mysql_query($queryPrivInsert);
						#echo "<br />" . $queryPrivInsert . "#3<br /><br />";
					}
					
					else 
					{
						$resultPrivUpdate = mysql_query($queryPrivUpdate);
						#echo "<br />" . $queryPrivUpdate . "#3<br /><br />";					
					}
				}
				
			}
		
		}
		
		
		// save add privileges updates
		if (is_array($_REQUEST['frm_priv_add']) && count($_REQUEST['frm_priv_add']) > 0) 
		{
			foreach ($_REQUEST['frm_priv_add'] as $moduleID=>$value) 
			{
				
				$queryPrivAdd = "UPDATE `privileges` SET `priv_add` = 'yes' 
								 WHERE `module_id` = '" . $moduleID . "' 
								 AND ";
				
				if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id']) 
				{
					$queryPrivAdd .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
					$queryPrivAdd .= " AND `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
				{				
					$queryPrivAdd .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
				{
					$queryPrivAdd .= "`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				$queryPrivAdd .= "LIMIT 1";
				
				$resultPrivAdd = mysql_query($queryPrivAdd);
				#echo $queryPrivAdd . "<br /><br />";
			}
				
		}

		
		// save edit privileges updates
		if (is_array($_REQUEST['frm_priv_edit']) && count($_REQUEST['frm_priv_edit']) > 0) 
		{
			foreach ($_REQUEST['frm_priv_edit'] as $moduleID=>$value) 
			{
				
				$queryPrivEdit = "UPDATE `privileges` SET `priv_edit` = 'yes' 
								 WHERE `module_id` = '" . $moduleID . "' 
								 AND ";
				
				if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id'])
				{
					$queryPrivEdit .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
					$queryPrivEdit .= " AND `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
				{		
					$queryPrivEdit .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
				{
					$queryPrivEdit .= "`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				$queryPrivEdit .= "LIMIT 1";
				
				$resultPrivEdit = mysql_query($queryPrivEdit);
				#echo $queryPrivEdit . "<br /><br />";
			}
				
		}
		
		// save delete privileges updates
		if (is_array($_REQUEST['frm_priv_delete']) && count($_REQUEST['frm_priv_delete']) > 0) 
		{
			foreach ($_REQUEST['frm_priv_delete'] as $moduleID=>$value) 
			{
				
				$queryPrivDelete = "UPDATE `privileges` SET `priv_delete` = 'yes' 
								 WHERE `module_id` = '" . $moduleID . "' 
								 AND ";
				
				if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id'])
				{
					$queryPrivDelete .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
					$queryPrivDelete .= " AND `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
				{		
					$queryPrivDelete .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
				{
					$queryPrivDelete .= "`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				$queryPrivDelete .= "LIMIT 1";		
				
				$resultPrivDelete = mysql_query($queryPrivDelete);
				#echo $queryPrivDelete . "<br /><br />";
			}
				
		}
		
		// save list privileges updates
		if (is_array($_REQUEST['frm_priv_list']) && count($_REQUEST['frm_priv_list']) > 0) 
		{
			foreach ($_REQUEST['frm_priv_list'] as $moduleID=>$value) 
			{
				
				$queryPrivList = "UPDATE `privileges` SET `priv_list` = 'yes' 
								 WHERE `module_id` = '" . $moduleID . "' 
								 AND ";
				
				
				if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id'])
				{
					$queryPrivList .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
					$queryPrivList .= " AND `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
				{		
					$queryPrivList .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
				{
					$queryPrivList .= "`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				$queryPrivList .= "LIMIT 1";
				
				$resultPrivList = mysql_query($queryPrivList);
				#echo $queryPrivList . "<br /><br />";
			}
				
		}
		
		// save view privileges updates
		if (is_array($_REQUEST['frm_priv_view']) && count($_REQUEST['frm_priv_view']) > 0) 
		{
			foreach ($_REQUEST['frm_priv_view'] as $moduleID=>$value) 
			{
				
				$queryPrivView = "UPDATE `privileges` SET `priv_view` = 'yes' 
								 WHERE `module_id` = '" . $moduleID . "' 
								 AND ";
				
				if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id'])
				{
					$queryPrivView .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
					$queryPrivView .= " AND `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
				{		
					$queryPrivView .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
				{
					$queryPrivView .= "`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				$queryPrivView .= "LIMIT 1";
				
				$resultPrivView = mysql_query($queryPrivView);
				#echo $queryPrivView . "<br /><br />";
			}
				
		}
		
		
		// save execute privileges updates
		if (is_array($_REQUEST['frm_priv_execute']) && count($_REQUEST['frm_priv_execute']) > 0) 
		{
			foreach ($_REQUEST['frm_priv_execute'] as $moduleID=>$value) 
			{
				
				$queryPrivExecute = "UPDATE `privileges` SET `priv_execute` = 'yes' 
								 WHERE `module_id` = '" . $moduleID . "' 
								 AND ";
				
				if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id'])
				{
					$queryPrivExecute .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
					$queryPrivExecute .= " AND `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
				{		
					$queryPrivExecute .= "`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
				}
				
				elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
				{
					$queryPrivExecute .= "`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
				}
				
				$queryPrivExecute .= "LIMIT 1";				 
				
				$resultPrivExecute = mysql_query($queryPrivExecute);
				#echo "<br />" . $queryPrivExecute . "<br /><br />";
			}
				
		}
		
		// privileges list
		$query = "SELECT * 
				  FROM `privileges` t1, `modules` t2 
				  WHERE t2.`module_id` = t1.`module_id` 
				  AND ";
		
		if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id'])
		{
			$query .= " t1.`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
			$query .= " AND t1.`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
		}
		
		elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
		{
			$query .= " t1.`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
		}

		elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
		{
			$query .= " t1.`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' AND t1.`user_id` = '0' ";
		}
				  
		$query .= " ORDER BY `module_display` ASC";
		
		
		// total	
		$queryTotal = "SELECT COUNT(*) 
					   FROM `privileges` t1, `modules` t2 
					   WHERE t2.`module_id` = t1.`module_id` 
					   AND ";
					   
		if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_group_id'])
		{
			$queryTotal .= "t1.`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
			$queryTotal .= " AND t1.`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ";
		}
		
		elseif ($_REQUEST['frm_user_id'] && !$_REQUEST['frm_user_group_id'])
		{
			$queryTotal .= "t1.`user_id` = '" . $_REQUEST['frm_user_id'] . "' ";
		}

		elseif ($_REQUEST['frm_user_group_id'] && !$_REQUEST['frm_user_id'])
		{
			$queryTotal .= "t1.`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' AND t1.`user_id` = '0' ";
		}			   
		
		
		
		$result = mysql_query($query, $this->conn);
		
		$resultTotal = mysql_query($queryTotal, $this->conn);
		$rowTotal = mysql_fetch_row($resultTotal);
		
		#echo $query . "<br /><br />";
		#echo $queryTotal . "<br /><br />";		
		?>
		
		<script type="text/javascript">
			function getUserPrivileges(form)
			{
				var newIndex = form.frm_user_id.selectedIndex
				
				strURL = '<?php echo $_SERVER['PHP_SELF']; ?>?frm_user_id=' + form.frm_user_id.options[newIndex].value
				window.location.assign( strURL ); 
				
			}
			
			function getUserGroupPrivileges(form)
			{
				var newIndex = form.frm_user_group_id.selectedIndex
				
				strURL = '<?php echo $_SERVER['PHP_SELF']; ?>?frm_user_group_id=' + form.frm_user_group_id.options[newIndex].value
				window.location.assign( strURL ); 
				
			}

		</script>

		<?php
		
		$strResult = "<div align=\"center\"><h2 style=\"font-size:1.5em;\">Privileges List";
		?>
		
		<?php if ($_REQUEST['frm_user_id']) { $strResult .= ": User \"" . DB::dbIDToField('users', 'user_id', $_REQUEST['frm_user_id'], 'user_login_name') . "\""; } ?>
		<?php if ($_REQUEST['frm_user_group_id']) { $strResult .= ": Group \"" . DB::dbIDToField('user_groups', 'user_group_id', $_REQUEST['frm_user_group_id'], 'user_group_name') . "\""; } ?>
		
		<?php
		$strResult .= "</h2></div>";
		?>

		<div align="right">
				<a class="btn" href="user_add.php" title="New User"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New User</a> &nbsp;
				<a class="btn" href="user_group_add.php" title="New User Group"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New User Group</a> &nbsp;
				
				<?php if ($_REQUEST['frm_user_id']) { ?>
				<a class="btn" href="user_edit.php?user_id=<?php echo $_REQUEST['frm_user_id']; ?>" title="Update User"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a> &nbsp;
				<a class="btn" href="user_delete.php?user_id=<?php echo $_REQUEST['frm_user_id']; ?>&action=delete" title="Delete User" onclick="return confirmDeleteUser(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a> 
				<?php } ?>
				
				<?php if ($_REQUEST['frm_user_group_id']) { ?>
				<a class="btn" href="user_group_edit.php?user_group_id=<?php echo $_REQUEST['frm_user_group_id']; ?>" title="Update User Group"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a> &nbsp;
				<a class="btn" href="user_group_delete.php?user_group_id=<?php echo $_REQUEST['frm_user_group_id']; ?>&action=delete" title="Delete User Group" onclick="return confirmDeleteUser(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a> 
				<?php } ?>
		</div>
		
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" />
				<div align="center">
				<h2>Privileges <?php if ($_REQUEST['frm_user_id']) { echo ": \"" . DB::dbIDToField('users', 'user_id', $_REQUEST['frm_user_id'], 'user_login_name') . "\""; } ?></h2>
				</div>
				
				<div style="background-color:#eee;padding:10px;-moz-border-radius: 5px;	-webkit-border-radius: 5px;">
				<p>
				Manage privileges by: 
				<label for="frm_user_id"><strong>Username:</strong></label>
				<select name="frm_user_id" id="frm_user_id" onchange="return getUserPrivileges(this.form)">
					<option value="">-- Please choose --</option>
					<?php $arrUsers = DB::getUserData('user'); ?>
					<?php foreach ($arrUsers as $id => $username) { ?>
							<option value="<?php echo $id; ?>"<?php if ($_REQUEST['frm_user_id'] && $_REQUEST['frm_user_id'] == $id) { echo " selected"; } ?>><?php echo stripslashes($username); ?></option>
					<?php }	?>
				</select> *
				
				or
				
				<label for="frm_user_group_id"><strong>Group:</strong></label>
				<select name="frm_user_group_id" id="frm_user_group_id" onchange="return getUserGroupPrivileges(this.form)">
					<option value="">-- Please choose --</option>
					<?php $arrUserGroups = DB::getUserGroupData(); ?>
					<?php foreach ($arrUserGroups as $id => $userGroupName) { ?>
					<?php 	if ($id !== 1) { ?>	
							<option value="<?php echo $id; ?>"<?php if ($_REQUEST['frm_user_group_id'] && $_REQUEST['frm_user_group_id'] == $id) { echo " selected"; } ?>><?php echo stripslashes($userGroupName['user_group_name']); ?></option>
					<?php 	} ?>
					<?php }	?>
				</select>  
				</p>
				</div>
				
			</div>	<!-- end #box -->	
			
			<script type="text/javascript">

			var status = false
			
			function check_uncheck_all(form)
			{
				
				if (status == false)
				{
					for (i = 0; i < form.length; i++)
					{
						form[i].checked = true						
					}
					
					status = true
				}
				else 
				{
					for (i = 0; i < form.length; i++)
					{
						form[i].checked = false											
					}
					status = false
				}
				
			}
			
			</script> 
		
		
		
		
		<?php
		
		if ($_REQUEST['frm_user_id'] || $_REQUEST['frm_user_group_id'])
		{
			
			$strResult .= "
			<br />
			<div align=\"center\">
			<input class=\"btn\" type=\"submit\" name=\"submit\" value=\"Update Privileges\" onclick=\"return validateUserEdit(this.form)\" /> <input class=\"btn\" type=\"button\" value=\"Cancel\" onclick=\"history.go(-1)\" />
			</div>			
			<br />
			<table class=\"table table-bordered table-hover\" summary=\"Privileges List\">
			<caption>Privileges List</caption>
			<thead>
				<tr>
					<th scope=\"col\" width=\"20\"><div align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"\" value=\"\" id=\"module_id\" onclick=\"this.value=check_uncheck_all(this.form.module_id)\"></div></th>
					<th scope=\"col\" width=\"20\"><div align=\"center\">No</div></th>										
					<th scope=\"col\"><div align=\"center\">Module Name</div></th>
					<th scope=\"col\"><div align=\"center\">Module ID</div></th>
					<th scope=\"col\"><div align=\"center\">Priv. Add</div></th>
					<th scope=\"col\"><div align=\"center\">Priv. Edit</div></th>
					<th scope=\"col\"><div align=\"center\">Priv. Delete</div></th>
					<th scope=\"col\"><div align=\"center\">Priv. List</div></th>
					<th scope=\"col\"><div align=\"center\">Priv. View</div></th>
					<th scope=\"col\"><div align=\"center\">Priv. Execute</div></th>					
				</tr>
			</thead>	
			
			<tbody>";
			
		
			
			if ($rowTotal[0] > 0) 
			{
			
				$no = 0;		
				while ($row = mysql_fetch_assoc($result)) 
				{
					$no++;
					$intID[] = $row['module_id'];
		
		
				$strResult .= "
					<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
						<td><div align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"\" value=\"\" id=\"module_id\" onclick=\"this.value=check_uncheck_all(this.form.frm_priv_" . $row['module_id'] . ")\"></div></td>
						<td id=\"r" . $row['user_id'] . "\"><div align=\"right\">" . $no . ".</div></td>
						<td><div align=\"left\">" . stripslashes($row['module_display']) . "</div></td>
						<td><div align=\"right\">" . $row['module_id'] . "<input type=\"hidden\" name=\"frm_module_id[]\" value=\"" . $row['module_id'] . "\" /></div></td>
						<td><div align=\"center\">"; if ($row['module_file_name_add']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_add[" . $row['module_id'] . "]\" id=\"frm_priv_" . $row['module_id'] . "\" value=\"yes\""; if ($row['priv_add'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
						<td><div align=\"center\">"; if ($row['module_file_name_edit']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_edit[" . $row['module_id'] . "]\" id=\"frm_priv_" . $row['module_id'] . "\" value=\"yes\""; if ($row['priv_edit'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
						<td><div align=\"center\">"; if ($row['module_file_name_delete']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_delete[" . $row['module_id'] . "]\" id=\"frm_priv_" . $row['module_id'] . "\" value=\"yes\""; if ($row['priv_delete'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
						<td><div align=\"center\">"; if ($row['module_file_name_list']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_list[" . $row['module_id'] . "]\" id=\"frm_priv_" . $row['module_id'] . "\" value=\"yes\""; if ($row['priv_list'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
						<td><div align=\"center\">"; if ($row['module_file_name_view']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_view[" . $row['module_id'] . "]\" id=\"frm_priv_" . $row['module_id'] . "\" value=\"yes\""; if ($row['priv_view'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
						<td><div align=\"center\">"; if ($row['module_file_name_execute']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_execute[" . $row['module_id'] . "]\" id=\"frm_priv_" . $row['module_id'] . "\" value=\"yes\""; if ($row['priv_execute'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>						
					</tr>";
		
		
				}
			
			} 
			
			else 			
			{
				$strResult .= "<tr><td colspan=\"10\"><div align=\"center\">Found no data</div></td></tr>";			
			}
		
		
		$strResult .= "
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"8\">&nbsp;</td>
				</tr>
			</tfoot>
			</table>
			<br /><br />
			<div align=\"center\">
			<input class=\"btn\" type=\"submit\" name=\"submit\" value=\"Update Privileges\" onclick=\"return validateUserEdit(this.form)\" /> <input type=\"button\" value=\"Cancel\" onclick=\"history.go(-1)\" />
			</div>
			";
		
		}
		
		// Modules
		if ($_REQUEST['frm_user_id'] || $_REQUEST['frm_user_group_id'])
		{
			
			$queryModule = "SELECT * FROM `modules` WHERE ";
			
			if (is_array($intID) && count($intID) > 0)
			{

				$queryModule .= "`module_id` NOT IN (";
	    	
					for ($i = 0; $i < count($intID); $i++) 
					{
	    			
						$queryModule .= $intID[$i];
	    		
	    				if ($i == (count($intID) - 1)) 
	    				{ 
	    					$queryModule .= ""; 
	    				} 
	    				
	    				else 
	    				
	    				{ 
	    					
	    					$queryModule .= ", "; 
	    				
	    				}
	    			
					}
					
					$queryModule .= ") AND ";
					
			}
    		
			$queryModule .= " `module_activate` = 'yes' ORDER BY `module_display` ASC";
			
			$queryTotalModule = "SELECT COUNT(*) FROM `modules`	
								 WHERE ";
								 
			if (is_array($intID) && count($intID) > 0)
			{				 
				$queryTotalModule .= " `module_id` NOT IN (";
    	
				for ($i = 0; $i < count($intID); $i++) 
				{
				    			
					$queryTotalModule .= $intID[$i];
				    		
					if ($i == (count($intID) - 1)) 
					{ 
						$queryTotalModule .= ""; 
					} 
				    				
					else 
				    				
					{ 				    					
						$queryTotalModule .= ", "; 				    				
					}
				    			
				}
				
				$queryTotalModule .= ") AND ";
								
			}
			
    		$queryTotalModule .= " `module_activate` = 'yes'";
			
				
		}
		
		else 
		{
			$queryModule = "SELECT * FROM `modules` ORDER BY `module_display` ASC";		
			
			$queryTotalModule = "SELECT COUNT(*) FROM `modules`";
		
		}
		
		
		$resultModule = mysql_query($queryModule);
		
		$resultTotalModule = mysql_query($queryTotalModule);
		$rowTotalModule = mysql_fetch_row($resultTotalModule);
		
		if ($resultModule)
		{
			
			if ($rowTotalModule[0] > 0) 
			{
				$strResult .= "			
				<br />
				<table class=\"table table-bordered table-hover\" summary=\"Privileges List\">
				<caption>Modules List</caption>
				<thead>
					<tr>					
						<th scope=\"col\" width=\"20\"><div align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"\" value=\"\" id=\"module_id\" onclick=\"this.value=check(this.form.module_id)\"></div></th>
						<th scope=\"col\" width=\"20\"><div align=\"center\">No</div></th>										
						<th scope=\"col\"><div align=\"center\">Module Name</div></th>
						<th scope=\"col\"><div align=\"center\">Module ID</div></th>
						<th scope=\"col\"><div align=\"center\">Priv. Add</div></th>
						<th scope=\"col\"><div align=\"center\">Priv. Edit</div></th>
						<th scope=\"col\"><div align=\"center\">Priv. Delete</div></th>
						<th scope=\"col\"><div align=\"center\">Priv. List</div></th>
						<th scope=\"col\"><div align=\"center\">Priv. View</div></th>
						<th scope=\"col\"><div align=\"center\">Priv. Execute</div></th>						
					</tr>
				</thead>	
				
				<tbody>";
				
				$no = 0;	
				while ($rowModule = mysql_fetch_assoc($resultModule)) 
				{
					
					$no++;
			
					$strResult .= "
						<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
							<td><div align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"\" value=\"\" id=\"module_id\" onclick=\"this.value=check_uncheck_all(this.form.frm_priv_" . $rowModule['module_id'] . ")\"></div></td>
							<td id=\"r" . $rowModule['user_id'] . "\"><div align=\"right\">" . $no . ".</div></td>
							<td><div align=\"left\">" . stripslashes($rowModule['module_display']) . "</div></td>
							<td><div align=\"right\">" . $rowModule['module_id'] . "<input type=\"hidden\" name=\"frm_module_id[]\" value=\"" . $rowModule['module_id'] . "\" /></div></td>
							<td><div align=\"center\">"; if ($rowModule['module_file_name_add']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_add[" . $rowModule['module_id'] . "]\" id=\"frm_priv_" . $rowModule['module_id'] . "\" value=\"yes\""; if ($rowModule['priv_add'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
							<td><div align=\"center\">"; if ($rowModule['module_file_name_edit']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_edit[" . $rowModule['module_id'] . "]\" id=\"frm_priv_" . $rowModule['module_id'] . "\" value=\"yes\""; if ($rowModule['priv_edit'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
							<td><div align=\"center\">"; if ($rowModule['module_file_name_delete']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_delete[" . $rowModule['module_id'] . "]\" id=\"frm_priv_" . $rowModule['module_id'] . "\" value=\"yes\""; if ($rowModule['priv_delete'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
							<td><div align=\"center\">"; if ($rowModule['module_file_name_list']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_list[" . $rowModule['module_id'] . "]\" id=\"frm_priv_" . $rowModule['module_id'] . "\" value=\"yes\""; if ($rowModule['priv_list'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
							<td><div align=\"center\">"; if ($rowModule['module_file_name_view']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_view[" . $rowModule['module_id'] . "]\" id=\"frm_priv_" . $rowModule['module_id'] . "\" value=\"yes\""; if ($rowModule['priv_view'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
							<td><div align=\"center\">"; if ($rowModule['module_file_name_execute']) { $strResult .= "<input type=\"checkbox\" name=\"frm_priv_execute[" . $rowModule['module_id'] . "]\" id=\"frm_priv_" . $rowModule['module_id'] . "\" value=\"yes\""; if ($rowModule['priv_execute'] == 'yes') { $strResult .= " checked=\"checked\""; } $strResult .= " />"; } else { $strResult .= "&nbsp;"; } $strResult .= "</div></td>
						</tr>";
				}
				
				$strResult .= "
				</tbody>
				<tfoot>
					<tr>
						<th scope=\"row\" colspan=\"2\">Total: " . $rowTotalModule[0] . "</th>					
						<td colspan=\"8\">&nbsp;</td>
					</tr>
				</tfoot>
				</table>
				<br /><br />";
				
				if ($_REQUEST['frm_user_id'])
				{					
					$strResult .= "
					<div align=\"center\">
					<input class=\"btn\" type=\"submit\" name=\"submit\" value=\"Update User Privileges\" onclick=\"return validateUserEdit(this.form)\" /> <input type=\"button\" value=\"Cancel\" onclick=\"history.go(-1)\" />
					</div>
					";
				}
				
			}
			
		}
		
		
		?>

		<?php echo $strResult; ?>
					
		</form>
		<a class="btn" href="#content"><i class="icon-arrow-up"></i> Back to top</a>
		
		<?php
		
		// The Log
		$strLog = "View the Privileges List";
			
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
		
	} // listPrivileges()
	
	
	// LOGS
	function listLog () 
	{
		
		global $TABLE_MAX_ROW_PER_PAGE;
				
		/* If page number not set, set it to 1 */
		if (!$_REQUEST['page_num']) { $_REQUEST['page_num'] = 1; }			

		// Setting queries and pages	
		$offset = ($_REQUEST['page_num'] - 1) * $TABLE_MAX_ROW_PER_PAGE;
	
		$this->conn = DB::dbConnect();		
		$strSearchText = stripslashes($_REQUEST['frm_search_text']);
		
		if ($_REQUEST['frm_search_text'])
		{

			$query = "SELECT * FROM `logs` 
					  WHERE `log_user` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `log_action` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `log_from` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  ORDER BY `log_id` DESC
					  LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			$queryTotal = "SELECT COUNT(*) FROM `logs` 
						   WHERE `log_user` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  	   OR `log_action` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  	   OR `log_from` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'";
		
		}
		
		else 
		
		{
			$query = "SELECT * FROM `logs` ORDER BY `log_id` DESC LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			$queryTotal = "SELECT COUNT(*) FROM `logs`";
		
		}
		
		$result = mysql_query($query, $this->conn);
		
		$resultTotal = mysql_query($queryTotal, $this->conn);
		$rowTotal = mysql_fetch_row($resultTotal);
		
		$totalPage = ceil ($rowTotal[0]/$TABLE_MAX_ROW_PER_PAGE);
		
		$strFileName = "Logs List (" . date("Y-m-d-h-i-s") . ")";
		
		$strResult = "";
		
		?>

		
		<?php if (basename($_SERVER['SCRIPT_FILENAME']) !== 'log_list_excel.php') { ?>

			<form name="search_log" method="post" action="log_list.php">
				<input type="hidden" name="frm_search_referer" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
				<input type="text" name="frm_search_text" size="40" maxlength="128" value="<?php if ($_REQUEST['frm_search_text']) { echo stripslashes($_REQUEST['frm_search_text']); }  ?>" />
				<input class="btn" type="submit" name="frm_search_submit" value="Search Logs" onclick="return validateSearch(this.form)" /><br />
			</form>
			<!-- 								
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" />
				<input type="hidden" name="pdf_file" value="<?php echo $strFileName; ?>".pdf" />				
				<input type="hidden" name="xls_file" value="<?php echo $strFileName; ?>".xls" />
			
				<div align="left">				
				<input name="download_pdf" type="image" src="<?php echo $STR_URL; ?>img/pdf_icon.png" title="Download PDF" />
				<a href="log_list_excel.php?xls_file=<?php echo urlencode($strFileName . ".xls"); ?>"><img src="<?php echo $STR_URL; ?>img/excel_icon.png" border="0" title="Download Excel" /></a>
				</div>
				<br />
			 -->	
		<?php } ?>
		
		
		<?php
		
			$strResult .= "
			<div align=\"center\"><h2>Logs</h2></div>
			<div align=\"right\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', $_REQUEST['frm_search_text']))) . "</div>
			<section id=\"table_log_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Logsƒ\">
			<caption>Logs List</caption>
			<thead>
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>
					<th scope=\"col\"><div align=\"center\">Log ID</div></th>
					<th scope=\"col\"><div align=\"center\">Datetime</div></th>
					<th scope=\"col\"><div align=\"center\">Activity</div></th>
					<th scope=\"col\"><div align=\"center\">Username</div></th>					
					<th scope=\"col\" width=\"20%\"><div align=\"center\">Delete</div></th>
				</tr>
			</thead>	
			
			<tbody>";
			
		?>
			
		<?php
			
			if ($rowTotal[0] > 0) 
			{
			
				$no = $offset;		
				while ($row = mysql_fetch_assoc($result)) 
				{
					$no++;
		?>	
					
		<?php
		
				$strResult .= "
					<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
						<td id=\"r" . $row['item_id'] . "\"><div align=\"right\">" . $no . ".</div></td>
						<td><div align=\"right\">" . $row['log_id'] . "</div></td>
						<td><div align=\"left\">" . stripslashes($row['log_time']) . "</div></td>
						<td><div align=\"left\">" . stripslashes(htmlspecialchars($row['log_action'])) . "</div></td>
						<td><div align=\"left\">" . stripslashes(htmlspecialchars($row['log_user'])) . "</div></td>						
						<td><div align=\"center\"><a class=\"btn\" href=\"log_delete.php?log_id=" . $row['log_id'] . "&action=delete\" title=\"Delete Log\" onclick=\"return confirmDeleteLog(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a></div></td>
					</tr>";
		
		?>
									
		<?php
				}
			
			} 
			
			else 			
			{
				$strResult .= "<tr><td colspan=\"6\"><div align=\"center\">Found no data</div></td></tr>";			
			}
		?>
		
		<?php
		
		$strResult .= "
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"4\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', $_REQUEST['frm_search_text']))) . "</td>
				</tr>
			</tfoot>
			</table>
			</section>
			<a class=\"btn\" href=\"#content\"><i class=\"icon-arrow-up\"></i> Back to top</a>
			";
		?>

		<?php echo $strResult; ?>
		
		<?php if (basename($_SERVER['SCRIPT_FILENAME']) !== 'log_list_excel.php') { ?>
		
		
			<input type="hidden" name="html" value="<?php echo htmlentities(stripslashes($strResult)); ?>" />
		</form>
		
		
		<?php } ?>
		
		<?php
		
		// The Log
		$strLog = "View the Log List";
			
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
		
	} // listLog()
	
	
	function listModules() 
	{
		
		global $TABLE_MAX_ROW_PER_PAGE;
				
		/* If page number not set, set it to 1 */
		if (!$_REQUEST['page_num']) { $_REQUEST['page_num'] = 1; }			

		// Setting queries and pages	
		$offset = ($_REQUEST['page_num'] - 1) * $TABLE_MAX_ROW_PER_PAGE;
	
		$this->conn = DB::dbConnect();		
		$strSearchText = stripslashes($_REQUEST['frm_search_text']);
		
		if ($_REQUEST['frm_search_text'])
		{

			$query = "SELECT * FROM `modules` 
					  WHERE `module_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `module_display` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `module_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  ORDER BY `module_display` ASC
					  LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			$queryTotal = "SELECT COUNT(*) FROM `modules` 
					  WHERE `module_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `module_display` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  OR `module_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'";
		
		}
		
		else 
		
		{
			$query = "SELECT * FROM `modules` ORDER BY `module_display` ASC LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			$queryTotal = "SELECT COUNT(*) FROM `modules`";
		
		}
		
		$result = mysql_query($query, $this->conn);
		
		$resultTotal = mysql_query($queryTotal, $this->conn);
		$rowTotal = mysql_fetch_row($resultTotal);
		
		$totalPage = ceil ($rowTotal[0]/$TABLE_MAX_ROW_PER_PAGE);
		
		
		$strResult = "";
		
		?>

		
		
			<form name="search_module" method="post" action="module_list.php">
				<input type="hidden" name="frm_search_referer" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
				<input type="text" name="frm_search_text" size="40" maxlength="128" value="<?php if ($_REQUEST['frm_search_text']) { echo stripslashes($_REQUEST['frm_search_text']); }  ?>" />
				<input class="btn" type="submit" name="frm_search_submit" value="Search Module" onclick="return validateSearch(this.form)" /><br />
			</form>
											
				<br />
				<div align="right">
				<a class="btn" href="module_add.php" title="New Module"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Module</a> 
				</div>
			
		
		<?php
		
			$strResult .= "			
			<div align=\"center\"><h2>Modules List</h2></div>
			<section id=\"table_module_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Modules List\">
			<caption>Modules List</caption>
			<thead>
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>										
					<th scope=\"col\"><div align=\"center\">Module</div></th>
					<th scope=\"col\"><div align=\"center\">Code</div></th>
					<th scope=\"col\"><div align=\"center\">Description</div></th>					
					<th scope=\"col\" width=\"20%\"><div align=\"center\">Edit/Delete</div></th>
				</tr>
			</thead>	
			
			<tbody>";
			
		?>
			
		<?php
			
			if ($rowTotal[0] > 0) 
			{
			
				$no = $offset;		
				while ($row = mysql_fetch_assoc($result)) 
				{
					$no++;
		
				$strResult .= "
					<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
						<td id=\"r" . $row['module_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
						<td><div align=\"left\"><a href=\"module_view.php?module_id=" . $row['module_id'] . "\"><strong>" . stripslashes($row['module_display']) . "</strong></a></div></td>
						<td><div align=\"left\">" . stripslashes($row['module_name']) . "</div></td>
						<td><div align=\"left\">" . stripslashes($row['module_description']) . "</div></td>						
						<td><div align=\"center\"><a class=\"btn\" href=\"module_edit.php?module_id=" . $row['module_id'] . "\" title=\"Edit Module\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp; <a class=\"btn\" href=\"module_delete.php?module_id=" . $row['module_id'] . "&action=delete\" title=\"Delete Module\" onclick=\"return confirmDeleteModule(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a> </div></td>
					</tr>";
		
				}
			
			} 
			
			else 
			
			{
				$strResult .= "<tr><td colspan=\"5\"><div align=\"center\">Found no data</div></td></tr>";
			
			}
		
		
		$strResult .= "
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"3\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', $_REQUEST['frm_search_text']))) . "</td>
				</tr>
			</tfoot>
			</table>
			</section>
			<a class=\"btn\" href=\"#content\"><i class=\"icon-arrow-up\"></i> Back to top</a>
			";
		
		// The Log
		$strLog = "View the Modules List";
			
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
		
	} // listModules()
	
	
	function viewModule() 
	{
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `modules` WHERE `module_id` = '" . $_REQUEST['module_id'] . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
						
			?>

			<h2>Module &raquo; <?php echo stripslashes($row['module_name']); ?></h2>
			
			<table cellpadding="5" cellspacing="1" border="0">
			  <tr>
			  	<td colspan="2">
			  	<div align="left">
			  	<?php if ($_SESSION['user']['type'] == 'admin') { ?><a class="btn" href="module_add.php" title="New Module"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Module</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="module_edit.php?module_id=<?php echo $row['module_id']; ?>" title="Edit Modul"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="module_delete.php?module_id=<?php echo $row['module_id']; ?>&action=delete" title="Delete Modul" onclick="return confirmDeleteModule(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>&nbsp;&nbsp;&nbsp; <a class="btn" href="module_list.php" title="Modules List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a><?php } else { echo '&nbsp;'; } ?>
				 </div>			  	
			  	</td>
			  </tr>			  			  
			  <tr>
			  	<td width="15%"><div align="right" ><strong>Name Modul</strong>:</div></td>
			  	<td><strong><?php echo stripslashes($row['module_name']); ?></strong></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Tampilan Modul</strong>:</div></td>
			  	<td><?php echo stripslashes($row['module_display']); ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Description</strong>:</div></td>
			  	<td><?php echo stripslashes($row['module_description']); ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Parent</strong>:</div></td>
			  	<td><?php if ($row['module_parent_id']) { echo stripslashes(DB::dbIDToField('modules', 'module_id', $row['module_parent_id'], 'module_name')); } else { echo "Top parent"; } ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Tabel Modul</strong>:</div></td>
			  	<td><?php if ($row['module_use_table']) { echo stripslashes($row['module_use_table']); } else { echo "-"; } ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Field Datetime di Tabel</strong>:</div></td>
			  	<td><?php if ($row['module_datetime_table_field_name']) { echo stripslashes($row['module_datetime_table_field_name']); } else { echo "-"; } ?></td>
			  </tr>
			  
			  <tr>
			  	<td><div align="right" ><strong>Name File List</strong>:</div></td>
			  	<td><?php if ($row['module_file_name_list']) { echo $row['module_file_name_list']; } else { echo "-"; } ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Name File Add</strong>:</div></td>
			  	<td><?php if ($row['module_file_name_add']) { echo $row['module_file_name_add']; } else { echo "-"; } ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Name File Edit</strong>:</div></td>
			  	<td><?php if ($row['module_file_name_edit']) { echo $row['module_file_name_edit']; } else { echo "-"; } ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Name File Delete</strong>:</div></td>
			  	<td><?php if ($row['module_file_name_delete']) { echo $row['module_file_name_delete']; } else { echo "-"; } ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Name File View</strong>:</div></td>
			  	<td><?php if ($row['module_file_name_view']) { echo $row['module_file_name_view']; } else { echo "-"; } ?></td>
			  </tr>
			  <tr>
			  	<td><div align="right" ><strong>Name File Execute</strong>:</div></td>
			  	<td><?php if ($row['module_file_name_execute']) { echo $row['module_file_name_execute']; } else { echo "-"; } ?></td>
			  </tr>
			</table>

			
			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['module_create_date']); ?> by <strong><?php echo stripslashes($row['module_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['module_modify_date']); ?> by <strong><?php echo stripslashes($row['module_modified_by']); ?></strong></li>
			</ul>
			<?php
		}
		
		else 
		{
			echo "<div align=\"center\"><p><strong>Found no data!</strong></p></div>";
		}
		
		?>
		<div align="center">
		<form name="myform" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "module_list.php"; } else { echo "module_list.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
			<input class="btn" type="submit" value="Back">
		</form>
		</div>		
		<?php  
		
	} // viewModule()
	
	function validateFormModuleAdd()
	{
		if ($_REQUEST['frm_action'] !== 'add' || 
			!$_REQUEST['frm_module_name'] || 
			!$_REQUEST['frm_module_display'] || 
			!$_REQUEST['frm_module_activate'] ||
			$_REQUEST['frm_submit'] !== 'Submit'
			) 
			{
					
				$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);
			
			?>				
				<br /> 
		 		<div align="center">
				<form method="post" action="module_add.php">
				<input type="hidden" name="frm_module_name" value="<?php echo strtolower($_REQUEST['frm_module_name']); ?>">
				<input type="hidden" name="frm_module_display" value="<?php echo $_REQUEST['frm_module_display']; ?>">
				<input type="hidden" name="frm_module_description" value="<?php echo $_REQUEST['frm_module_description']; ?>">
				<input type="hidden" name="frm_module_parent_id" value="<?php echo $_REQUEST['frm_module_parent_id']; ?>">
				<input type="hidden" name="frm_module_use_table" value="<?php echo strtolower($_REQUEST['frm_module_use_table']); ?>">
				<input type="hidden" name="frm_module_datetime_table_field_name" value="<?php echo strtolower($_REQUEST['frm_module_datetime_table_field_name']); ?>">
				<input type="hidden" name="frm_module_file_name_list" value="<?php echo strtolower($_REQUEST['frm_module_file_name_list']); ?>">
				<input type="hidden" name="frm_module_file_name_add" value="<?php echo strtolower($_REQUEST['frm_module_file_name_add']); ?>">
				<input type="hidden" name="frm_module_file_name_edit" value="<?php echo strtolower($_REQUEST['frm_module_file_name_edit']); ?>">
				<input type="hidden" name="frm_module_file_name_delete" value="<?php echo strtolower($_REQUEST['frm_module_file_name_delete']); ?>">
				<input type="hidden" name="frm_module_file_name_view" value="<?php echo strtolower($_REQUEST['frm_module_file_name_view']); ?>">
				<input type="hidden" name="frm_module_file_name_execute" value="<?php echo strtolower($_REQUEST['frm_module_file_name_execute']); ?>">
				<input type="hidden" name="frm_module_activate" value="<?php echo $_REQUEST['frm_module_activate']; ?>">
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
			<?php
					
				return 0;
					
			} 
		
			else 
			{
				return 1;
			}
		
	} // validateFormModuleAdd()
	
	
	function validateFormModuleEdit()
	{
		if ($_REQUEST['frm_action'] !== 'edit' || 
			!$_REQUEST['frm_module_name'] || 
			!$_REQUEST['frm_module_display'] || 
			!$_REQUEST['frm_module_activate'] || 
			$_REQUEST['frm_submit'] !== 'Submit'
			) 
			{
					
				$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);
			
			?>				
				<br /> 
		 		<div align="center">
				<form method="post" action="module_edit.php">
				<input type="hidden" name="frm_module_name" value="<?php echo strtolower($_REQUEST['frm_module_name']); ?>">
				<input type="hidden" name="frm_module_display" value="<?php echo $_REQUEST['frm_module_display']; ?>">
				<input type="hidden" name="frm_module_description" value="<?php echo $_REQUEST['frm_module_description']; ?>">
				<input type="hidden" name="frm_module_parent_id" value="<?php echo $_REQUEST['frm_module_parent_id']; ?>">
				<input type="hidden" name="frm_module_use_table" value="<?php echo strtolower($_REQUEST['frm_module_use_table']); ?>">
				<input type="hidden" name="frm_module_datetime_table_field_name" value="<?php echo strtolower($_REQUEST['frm_module_datetime_table_field_name']); ?>">
				<input type="hidden" name="frm_module_file_name_list" value="<?php echo strtolower($_REQUEST['frm_module_file_name_list']); ?>">
				<input type="hidden" name="frm_module_file_name_add" value="<?php echo strtolower($_REQUEST['frm_module_file_name_add']); ?>">
				<input type="hidden" name="frm_module_file_name_edit" value="<?php echo strtolower($_REQUEST['frm_module_file_name_edit']); ?>">
				<input type="hidden" name="frm_module_file_name_delete" value="<?php echo strtolower($_REQUEST['frm_module_file_name_delete']); ?>">
				<input type="hidden" name="frm_module_file_name_view" value="<?php echo strtolower($_REQUEST['frm_module_file_name_view']); ?>">
				<input type="hidden" name="frm_module_file_name_execute" value="<?php echo strtolower($_REQUEST['frm_module_file_name_execute']); ?>">
				<input type="hidden" name="frm_module_activate" value="<?php echo $_REQUEST['frm_module_activate']; ?>">
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
			<?php
					
				return 0;
					
			} 
		
			else 
			{
				return 1;
			}
		
	} // validateFormModuleEdit()
	
	function validateFormUserGroupAdd()
	{
		if ($_REQUEST['frm_action'] !== 'add' || 
			!$_REQUEST['frm_user_group_name'] || 
			!$_REQUEST['frm_user_group_activate'] || 
			$_REQUEST['frm_submit'] !== 'Submit'
			) 
			{
					
				$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);
			
			?>				
				<br /> 
		 		<div align="center">
				<form method="post" action="user_group_add.php">				
				<input type="hidden" name="frm_user_group_name" value="<?php echo $_REQUEST['frm_user_group_name']; ?>">
				<input type="hidden" name="frm_user_group_description" value="<?php echo $_REQUEST['frm_user_group_description']; ?>">
				<input type="hidden" name="frm_user_group_activate" value="<?php echo $_REQUEST['frm_user_group_activate']; ?>">
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
			<?php
					
				return 0;
					
			} 
		
			else 
			{
				return 1;
			}
		
	} // validateFormUserGroupAdd()
	
	
	function validateFormUserGroupEdit()
	{
		if ($_REQUEST['frm_action'] !== 'edit' || 
			!$_REQUEST['frm_user_group_id'] || 			
			!$_REQUEST['frm_user_group_name'] || 
			!$_REQUEST['frm_user_group_activate'] || 
			$_REQUEST['frm_submit'] !== 'Submit'
			) 
			{
					
				$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);
			
			?>				
				<br /> 
		 		<div align="center">
				<form method="post" action="user_group_edit.php">
				<input type="hidden" name="user_group_id" value="<?php echo $_REQUEST['frm_user_group_id']; ?>">				
				<input type="hidden" name="frm_user_group_name" value="<?php echo $_REQUEST['frm_user_group_name']; ?>">
				<input type="hidden" name="frm_user_group_description" value="<?php echo $_REQUEST['frm_user_group_description']; ?>">
				<input type="hidden" name="frm_user_group_activate" value="<?php echo $_REQUEST['frm_user_group_activate']; ?>">				
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
			<?php
					
				return 0;
					
			} 
		
			else 
			{
				return 1;
			}
		
	} // validateFormUserGroupEdit()
	

	function getFileSize($strFile, $strUnit='auto') {
		
		$strValue = false;
		
		if (file($strFile)) 
		{
			$intFileSize = filesize($strFile);
		
			if ($strUnit == 'GB' || $strUnit == 'auto' && $intFileSize >= pow(1024,3)) 
			{
				$strValue = round(($intFileSize / pow(1024,3)),2) . 'GB';
			} 
			
			elseif ($strUnit = 'MB' || $strUnit == 'auto' && $intFileSize >= pow(1024,2)) 
			{
				$strValue = round(($intFileSize / pow(1024,2)),2) . 'MB';
			} 
			
			elseif ($strUnit = 'KB' || $strUnit == 'auto' && $intFileSize >= 1024) 
			{
				$strValue = round(($intFileSize / 1024),2) . 'KB';
			} 
			
			else 
			{				
				$strValue = $intFileSize . 'Bytes';
			}
		
		}
		
		return $strValue;
	}
	
	
	function listConfigurationSettings()
	{

		$arrConfig = array('site_name', 'site_url', 'site_footer_text', 'mbs_p4l_on_behalf_name', 'mbs_p4l_on_behalf_position');
		
		$this->conn = DB::dbConnect();		
		// `setting_id`, `setting_name`, `setting_text`, `setting_value`
		
		// site_name, site_url, site_footer_text, 
		
		$strResult = "";
		
		// javascript to pop up message
		$strResult .= "
		
					";
		
		$strResult .= "			
			<h2>Configuration</h2>
			<section id=\"table_config_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Configuration\">
			<caption>Configuration</caption>
			<thead>
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>															
					<th scope=\"col\"><div align=\"center\">Setting</div></th>
					<th scope=\"col\"><div align=\"center\">Value</div></th>
					<th scope=\"col\"><div align=\"center\">Edit</div></th>		
				</tr>
			</thead>	
			
			<tbody>";
		
		for ($i = 0; $i < count($arrConfig); $i++)
		{
			// variables
			$intNo = $i + 1;
			$strConfigText = DB::dbIDToField('settings', 'setting_name', $arrConfig[$i], 'setting_text');
			$strConfigValue = DB::dbIDToField('settings', 'setting_name', $arrConfig[$i], 'setting_value');
				
		$strResult .= "
				<tr>
					<td><div align=\"right\">" . $intNo . ".</div></td>
					<td><div align=\"left\">" . stripslashes($strConfigText) . "</div></td>
					<td><div align=\"left\">" . stripslashes($strConfigValue) . "</div></td>
					<td><div align=\"center\"><a class=\"btn ajax cboxElement\" href=\"" . $STR_URL . "setting_edit.php?sn=" . $arrConfig[$i] . "&pop=yes\"><img src=\"" . $STR_URL . "img/edit_icon.png\" /> Edit</a></div></td>
				</tr>
				";
		
		}
		
		$strResult .= "		
			</tbody>
			
			<tfoot>
				<tr>					
					<td colspan=\"4\">&nbsp;</td>
				</tr>
			</tfoot>
			</table>
			</section>
			";
		
		// The Log
		$strLog = "View the Configuration List";
			
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
		
	} // listConfigurationSettings()
	
	
	function validateFormSettingEdit()
	{
		if ($_REQUEST['frm_action'] !== 'edit' || 
			!$_REQUEST['frm_setting_value'] || 
			!$_REQUEST['frm_submit']
			) 
			{
					
				$this->showAlert("Sorry, the form was not filled properly. Please make sure that all required fields with * mark was filled! ", FALSE);
			
			?>				
				<br /> 
		 		<div align="center">
				<form method="post" action="setting_edit.php">
				<input type="hidden" name="setting_id" value="<?php echo $_REQUEST['frm_setting_id']; ?>">				
				<input type="hidden" name="frm_setting_name" value="<?php echo $_REQUEST['frm_setting_name']; ?>">
				<input type="hidden" name="frm_setting_value" value="<?php echo $_REQUEST['frm_setting_value']; ?>">								
				<input type="submit" name="correct" value="Fix it">
				</form>
				</div>
			<?php
					
				return 0;
					
			} 
		
			else 
			{
				return 1;
			}
		
	} // validateFormSettingEdit()

	function listDocumentation() 
	{
	
		global $STR_URL, $STR_PATH;
		?>
		
		
		<h2>Documentation</h2>
		
		<?php include($STR_PATH . 'docs/home.html'); ?>
		<?php include($STR_PATH . 'docs/bookings.html'); ?>
		<?php include($STR_PATH . 'docs/master_data.html'); ?>
		<?php include($STR_PATH . 'docs/suppliers.html'); ?>
		
		<!--&copy; 2009-2013 - Erick Wellem Web Developments.-->
		<?php
		
		// The Log
		$strLog = "View the Documentation";
			
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
		
	} // listDocumentation()
	
	function viewCredit()
	{
		?>
		<h2>Credit</h2>
		<img src="<?php echo $STR_URL; ?>img/brandxindo_logo.jpeg" alt="Brand-X" title="Brand-X" style="width:60px;" /><br />
		<strong>Pharmacy4Less Marketing Booking System</strong><br />
		&copy; 2012-<?php echo date("Y"); ?> Brand-X. All rights reserved.
		
		<!--&copy; 2009-2011 - Erick Wellem Web Developments.-->
		<?php	
		
	} // viewCredit()
	
	
	function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
		
	} // formatSizeUnits()
	
	
	function getFileType($strMimeType)
	{
		switch ($strMimeType)
		{
			case 'image/jpeg': $strResult = "JPEG"; break;
			case 'image/jpg': $strResult = "JPG"; break;
			case 'image/gif': $strResult = "GIF"; break;
			case 'image/png': $strResult = "PNG"; break;
			case 'application/pdf': $strResult = "PDF"; break;
			case 'application/msword': $strResult = "DOC"; break;
			case 'application/vnd.ms-excel': $strResult = "XLS"; break;
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': $strResult = "XLSX"; break;
			case 'application/vnd.ms-powerpoint': $strResult = "PPT"; break;
			case 'text/plain': $strResult = "TXT"; break;
			case 'video/quicktime': $strResult = "MOV"; break;
			case 'video/mpeg': $strResult = "MPEG"; break;
			case 'video/3gpp': $strResult = "3GP"; break;
			case 'video/avi': $strResult = "AVI"; break;
			case 'audio/mpeg3': $strResult = "MP3"; break;
			case 'audio/wav': $strResult = "WAV"; break;
			default: $strResult = ""; break;	
		}
		
		return $strResult;
	}
	
		function getFileIcon($strFileType)
	{
		
		switch ($strFileType)
		{
			case '.jpg': $strFileIcon = "img/jpg_icon.png"; break;
			case '.jpeg': $strFileIcon = "img/jpg_icon.png"; break;
			case '.png': $strFileIcon = "img/png_icon.png"; break;
			case '.gif': $strFileIcon = "img/gif_icon.png"; break;
			case '.xls': $strFileIcon = "img/excel_icon.png"; break;
			case '.xlsx': $strFileIcon = "img/excel_icon.png"; break;
			case '.doc': $strFileIcon = "img/word_icon.png"; break;
			case '.docx': $strFileIcon = "img/word_icon.png"; break;
			case '.ppt': $strFileIcon = "img/powerpoint_icon.png"; break;
			case '.pptx': $strFileIcon = "img/powerpoint_icon.png"; break;
			case '.pdf': $strFileIcon = "img/pdf_icon.png"; break;
			case '.txt': $strFileIcon = "img/text_icon.png"; break;
			case '.wav': $strFileIcon = "img/audio_icon.png"; break;
			case '.mp3': $strFileIcon = "img/audio_icon.png"; break;
			case '.mpg': $strFileIcon = "img/video_icon.png"; break;
			case '.mpeg': $strFileIcon = "img/video_icon.png"; break;
			case '.avi': $strFileIcon = "img/video_icon.png"; break;
			case '.3gp': $strFileIcon = "img/video_icon.png"; break;
			case '.mov': $strFileIcon = "img/video_icon.png"; break;
			default: $strFileIcon = "img/file_icon.png"; break;
		}
		
		return $strFileIcon;
	} // getFileIcon()
	
	
	function relativeDateTime($secs) 
	{
		$secs = intval($secs);
		
		$second = 1;
		$minute = 60;
		$hour = 60*60;
		$day = 60*60*24;
		$week = 60*60*24*7;
		$month = 60*60*24*7*30;
		$year = 60*60*24*7*30*365;
		
		if ($secs <= 0) { $output = "just now"; } 
		elseif ($secs > $second && $secs < $minute) { $output = round($secs/$second)." second"; } 
		elseif ($secs >= $minute && $secs < $hour) { $output = round($secs/$minute)." minute"; } 
		elseif ($secs >= $hour && $secs < $day) { $output = round($secs/$hour)." hour"; } 
		elseif ($secs >= $day && $secs < $week) { $output = round($secs/$day)." day"; } 
		elseif ($secs >= $week && $secs < $month) { $output = round($secs/$week)." week"; } 
		elseif ($secs >= $month && $secs < $year) { $output = round($secs/$month)." month"; } 
		elseif ($secs >= $year && $secs < $year*10) { $output = round($secs/$year)." year"; } 
		else { $output = " more than a decade ago"; }
		
		if ($output <> "just now"){
			$output = (substr($output,0,2)<>"1 ") ? $output."s" : $output;
			$output .= " ago";
		}
		
		return $output;

	} // relativeDateTime()


	function getTimeDifference($dateTimeString) 
	{
		
	    if (!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/", $dateTimeString)) 
	    {
	        return false; 
	    }
	    
	    // Implode the datetime data obtained from MySQL database
	    $year = substr($dateTimeString, 0, 4);
	    $month = substr($dateTimeString, 5, 2);
	    $dateday = substr($dateTimeString, 8, 2);
	    $hour = substr($dateTimeString, 11, 2);
	    $minute = substr($dateTimeString, 14, 2);
	    $second = substr($dateTimeString, 17, 2);
	    
	    $intResult = time() - mktime($hour, $minute, $second, $month, $dateday, $year);
	    
	    return $intResult; 

	} // getTimeDifference()

	function convertNoOfDays($intNumber)
	{
		if ($intNumber == 30) { $strResult = "a month"; }
		elseif ($intNumber < 30 && $intNumber >= 24) { $strResult = "4 weeks"; }
		elseif ($intNumber < 24 && $intNumber >= 18) { $strResult = "3 weeks"; }
		elseif ($intNumber < 18 && $intNumber >= 12) { $strResult = "a couple of weeks"; }
		elseif ($intNumber < 12 && $intNumber >= 7) { $strResult = "next week"; }
		elseif ($intNumber < 7 && $intNumber >= 2) { $strResult = $intNumber . "days"; }
		elseif ($intNumber == 1) { $strResult = "tomorrow"; }
		elseif ($intNumber == 0) { $strResult = "today"; }
		
		return $strResult;
		
	} // convertNoOfDays()


	function downloadFile($file, $name, $mime_type='')
	{
		 /*
		 This function takes a path to a file to output ($file),  the filename that the browser will see ($name) and  the MIME type of the file ($mime_type, optional).
		 */
		 
		 //Check the file premission
		 if (!is_readable($file)) 
		 {
		 	die('File not found or inaccessible!');
		 }
		 
		 $size = filesize($file);
		 $name = rawurldecode($name);
		 
		 /* Figure out the MIME type | Check in array */
		 $known_mime_types = array(
		 	"pdf" => "application/pdf",
		 	"txt" => "text/plain",
		 	"html" => "text/html",
		 	"htm" => "text/html",
			"exe" => "application/octet-stream",
			"zip" => "application/zip",
			"doc" => "application/msword",
			"docx" => "application/msword",
			"xls" => "application/vnd.ms-excel",
			"xlsx" => "application/vnd.ms-excel",
			"ppt" => "application/vnd.ms-powerpoint",
			"pptx" => "application/vnd.ms-powerpoint",
			"gif" => "image/gif",
			"png" => "image/png",
			"jpeg"=> "image/jpg",
			"jpg" =>  "image/jpg",
			"mp3" =>  "audio/mpeg3",
			"wav" =>  "audio/wav",
			"mpg" =>  "video/mpeg3",
			"mov" =>  "video/quicktime",
			"3gp" =>  "video/3gpp",
			"mpeg" =>  "video/mpeg",
			"avi" =>  "video/avi",
			"rar" =>  "application/rar"
			);
		 
		 if ($mime_type=='')
		 {
			 $file_extension = strtolower(substr(strrchr($file,"."),1));
			 
			 if (array_key_exists($file_extension, $known_mime_types))
			 {
				$mime_type=$known_mime_types[$file_extension];
			 } 
			 
			 else 
			 {
				$mime_type="application/force-download";
			 }
			 
		 }
		 
		 //turn off output buffering to decrease cpu usage
		 @ob_end_clean(); 
		 
		 // required for IE, otherwise Content-Disposition may be ignored
		 if(ini_get('zlib.output_compression'))
		 {
		 	ini_set('zlib.output_compression', 'Off');
		 }
		 
		 header('Content-Type: ' . $mime_type);
		 header('Content-Disposition: attachment; filename="'.$name.'"');
		 header("Content-Transfer-Encoding: binary");
		 header('Accept-Ranges: bytes');
		 
		 /* The three lines below basically make the 
		    download non-cacheable */
		 header("Cache-control: private");
		 header('Pragma: private');
		 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		 
		 // multipart-download and download resuming support
		 if (isset($_SERVER['HTTP_RANGE']))
		 {
			list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
			list($range) = explode(",",$range,2);
			list($range, $range_end) = explode("-", $range);
			
			$range=intval($range);
			
			if (!$range_end) 
			{
				$range_end=$size-1;
			} 
			
			else 
			{
				$range_end=intval($range_end);
			}
			
			$new_length = $range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
		 } 
		 
		 else 
		 {
			$new_length=$size;
			header("Content-Length: ".$size);
		 }
		 
		 /* Will output the file itself */
		 $chunksize = 1*(1024*1024); //you may want to change this
		 $bytes_send = 0;
		 if ($file = fopen($file, 'r'))
		 {
			if (isset($_SERVER['HTTP_RANGE']))
			fseek($file, $range);
		 
			while (!feof($file) && (!connection_aborted()) && ($bytes_send<$new_length))
			{
				$buffer = fread($file, $chunksize);
				print($buffer); //echo($buffer); // can also possible
				flush();
				$bytes_send += strlen($buffer);
			}
			
		 	fclose($file);
		 } 
		 
		 else
		 {
		 //If no permissiion
		 die('Error - can not open file.');
		 }
		 //die
		die();
	
	} // downloadFile()


/*******************************************
* P4L MBS Functions - February 2013
********************************************/


	function viewActivity() 
	{
		
		global $arrSiteConfig;
		global $STR_URL, $STR_PATH;
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_activities` WHERE `activity_id` = '" . mysql_real_escape_string($_REQUEST['activity_id']) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
						
			?>
			
			<?php if ($_REQUEST['pop'] == "yes") { ?>
			<div align="center">
			<form name="myformTop" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "activity_list.php"; } else { echo "activity_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
				<input type="hidden" name="activity_id" value="<?php echo $_REQUEST['activity_id']; ?>">			
				<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
				<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">								
				<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
			</form>
			</div>
			<?php } ?>
			
			<h2>Activity &raquo; <?php echo stripslashes($row['activity_name']); ?></h2>

			<div class="span12">
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('activities', 'add') !== 0) { ?>
			  	<a class="btn" href="activity.php?action=add" title="New Activity"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Activity</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('activities', 'edit') !== 0) { ?>
				<a class="btn" href="activity.php?action=edit&activity_id=<?php echo $row['activity_id']; ?>" title="Edit Activity"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('activities', 'delete') !== 0) { ?>
				<a id="frm_delete_button_<?php echo $row['activity_id']; ?>" class="btn" href="activity_list.php?activity_id=<?php echo $row['activity_id']; ?>&action=delete" title="Delete Activity" onclick="return confirmDeleteActivity(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>
				<?php } ?>				
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('activities', 'list') !== 0) { ?>
				<a class="btn" href="activity_list.php" title="Activity List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a> 
				<?php } ?>
			</div>
			<br /><br />

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
							if (confirmDeleteActivity())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&activity_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/activity_proc.php',
									type: 'post', 
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
								
							}

							return false;	
										
						}			
						
    				});
 
				});
			</script>

			<fieldset>
				<div>
					<legend style="font-size:1.0em;">Data</legend>
				</div>
				
				<div class="container-fluid">
					<div class="row-fluid">
						
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Name</strong>:</div></div>
							<div class="span8"><strong><?php echo stripslashes($row['activity_name']); ?></strong></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Price</strong>:</div></div>
							<div class="span8">$<?php echo stripslashes($row['activity_price']); ?></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Description</strong>:</div></div>
							<div class="span8"><?php echo stripslashes($row['activity_description']); ?></div>
						</div>

						<?php if ($row['size_id']) { ?>
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Size</strong>:</div></div>
							<div class="span8"><?php echo stripslashes(DB::dbIDToField('mbs_sizes', 'size_id', $row['size_id'], 'size_name')); ?></div>
						</div>
						<?php } ?>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Store Related</strong>:</div></div>
							<div class="span8"><?php if ($row['activity_store_related'] == 'yes') { echo "Yes"; } else { echo "No"; } ?></div>
						</div>

						<?php if ($_SESSION['user']['type'] == 'admin') { ?>
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Active</strong>:</div></div>
							<div class="span8"><?php if ($row['activity_active'] == 'yes') { echo "Yes"; } else { echo "No"; } ?></div>
						</div>
						<?php } ?>

					</div>
				</div>		

				
			</fieldset>
			
			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['activity_created_date']); ?> by <strong><?php echo stripslashes($row['activity_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['activity_modified_date']); ?> by <strong><?php echo stripslashes($row['activity_modified_by']); ?></strong></li>
			</ul>

		<?php if ($_REQUEST['pop'] == "yes") { ?>
		<div align="center" style="margin-top:20px;">
		<form name="myformBottom" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "activity_list.php"; } else { echo "activity_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
			<input type="hidden" name="activity_id" value="<?php echo $_REQUEST['activity_id']; ?>">			
			<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
			<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">									
			<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
		</form>
		</div>
		<?php } ?>
				
		<?php  
		}
		
	} // viewActivity()
	
	
	function listActivity()
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
			$query = "SELECT * FROM `mbs_activities` 
					  		  WHERE (`activity_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			     OR `activity_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')
					  			    ORDER BY "; 
				
			if ($_REQUEST['sortby'])
			{
				$query .= "`" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `activity_id`";
			}								
				
			else 
			{				
				$query .= "`activity_name` ASC, `activity_created_date` DESC";
			}							
					    
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			
			// search query	total ***************************************************************************			
			$queryTotal = "SELECT COUNT(*) FROM `mbs_activities` 
					  			          WHERE (`activity_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			             OR `activity_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')";				
			
		
		}
		
		else 		
		{
			
			// the query ************************************************************************************						
			$query = "SELECT * FROM `mbs_activities` ORDER BY ";							  		

			if ($_REQUEST['sortby'])
			{
				$query .= " `" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `activity_id`";
			}

			else
			{
				$query .= " `activity_name`";
			}								
							  
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			

			// the query total ******************************************************************************
			$queryTotal = "SELECT COUNT(*) FROM `mbs_activities`";
			
		
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
			<form name=\"search_activity_data\" method=\"post\" action=\"" . $STR_URL . "activity_list.php\">
				<input type=\"hidden\" name=\"frm_search_referer\" value=\"" . $_SERVER['PHP_SELF'] . "\" />
				<input type=\"text\" name=\"frm_search_text\" size=\"40\" maxlength=\"128\" value=\""; if ($_REQUEST['frm_search_text']) { $strResult .= stripslashes($_REQUEST['frm_search_text']); }  $strResult .= "\" />				
				<input class=\"btn\" type=\"submit\" name=\"frm_search_submit\" value=\"Search Activity\" onclick=\"return validateSearch(this.form)\" /><br />
			</form>
			";

		// the form
		$strResult .= 
			"
			<form id=\"frm_activity\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" />
			";

				
		$strResult .= "<div align=\"right\">";
		
		// the refresh link
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('activities', 'list') !== 0) 
		{
			$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . ADMIN::getModuleFile('activities', 'list') . "\" title=\"Activity List\"><img src=\"" . $STR_URL . "img/refresh_icon.png\" /> Refresh</a>";		
		}

		$strResult .= "&nbsp;&nbsp;&nbsp;";
		
		// the add link	
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('activities', 'add') !== 0) 
		{
			$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"" . $STR_URL . ADMIN::getModuleFile('activities', 'add') . "?pop=yes\" title=\"New Activity\"><img src=\"" . $STR_URL . "img/add_icon.png\" /> New Activity</a>";
		}

		$strResult .= "<br /><br />";

		$strResult .= "<a class=\"btn\" href=\"activity_list_print.php?action=print\" target=\"_blank\"><img src=\"" . $STR_URL . "img/print_icon.png\" /> Print</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"activity_list_email.php?action=email\" title=\"Send Activity Price List to Email\"><img src=\"" . $STR_URL . "img/email_icon.png\" /> Email</a>";
		
		$strResult .= "	</div>";
		
		
		
		if ($strSortMode == "asc") { $strSortMode = "desc"; } elseif ($strSortMode == "desc") { $strSortMode = "asc"; }
		
		// the table
		$strResult .= 
			"			
			<div align=\"center\"><h2>Activity Price List</h2></div>
			<div align=\"right\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
																						 array('pop', urlencode('yes')),
																						 array('sortby', urlencode($_REQUEST['sortby'])),
																						 array('sortmode', urlencode($_REQUEST['sortmode']))
																						 )) . "</div>

			<section id=\"table_activity_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Activity Price List\">
			<caption>Activity Price List</caption>
			<thead class=\"well\">
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>																				
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=activity_name&sortmode=" . $strSortMode . "\">Name</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=activity_category&sortmode=" . $strSortMode . "\">Category</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=activity_price&sortmode=" . $strSortMode . "\">Price</a></div></th>										
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=activity_store_related&sortmode=" . $strSortMode . "\">Store Related</a></div></th>	
					";
		
		// edit / delete column																				 
		if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('activities', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('activities', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
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
					$strLink = ADMIN::getModuleFile('activities', 'view') . "?activity_id=" . urlencode($row['activity_id']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&page_num=" . $_REQUEST['page_num'] . "&pop=yes";
										
					$strResult .= 
						"
						<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
							<td id=\"r" . $row['activity_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
							<td><div align=\"left\">"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { $strResult .= "<a class=\"ajax callbacks cboxElement\" href=\"" . $STR_URL . $strLink . "\" title=\"" . html_entity_decode(strtoupper($row['activity_name'])) . "\">"; } $strResult .= "<strong>" . html_entity_decode(stripslashes($row['activity_name'])) . "</strong>"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { "</a>"; } $strResult .= "</div></td>
							<td><div align=\"left\">" . html_entity_decode(stripslashes(ucwords($row['activity_category']))) . "</div></td>
							<td><div align=\"right\"><strong>$" . html_entity_decode(stripslashes($row['activity_price'])) . "</strong></div></td>							
							<td><div align=\"center\">" . html_entity_decode(stripslashes($row['activity_store_related'])) . "</div></td>
						";								
					
					// action column
					if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('activities', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('activities', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
					{	
					
						$strResult .= "<td><div align=\"center\">";
						
						// edit
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('activities', 'edit') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{	
							$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"" . $STR_URL . "activity.php?activity_id=" . html_entity_decode($row['activity_id']) . "&action=edit&pop=yes\" title=\"Edit Activity\"><img src=\"" . $STR_URL . "img/edit_icon.png\" /> Edit</a>";
						}
						
						$strResult .= "&nbsp;&nbsp;";
						
						// delete
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('activities', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{
							$strResult .= "<a id=\"frm_delete_button_" . $row['activity_id'] . "\" class=\"btn\" href=\"" . $STR_URL . "activity_list.php?activity_id=" . $row['activity_id'] . "&action=delete\" title=\"Delete Activity\"><img src=\"" . $STR_URL . "img/delete_icon.png\" /> Delete</a> ";
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
				$strResult .= "<tr><td colspan=\"6\"><div align=\"center\">Found no data</div></td></tr>";			
			}
		
		$strResult .= 
			"
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"4\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
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
							if (confirmDeleteActivity())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&activity_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/activity_proc.php',
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
		$strLog = "View the Activity List";
			
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
		
		
	} // listActivity()
			
	

	function listSize()
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
			$query = "SELECT * FROM `mbs_sizes` 
					  		  WHERE (`size_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			     OR `size_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')
					  			    ORDER BY "; 
				
			if ($_REQUEST['sortby'])
			{
				$query .= "`" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `size_id`";
			}								
				
			else 
			{				
				$query .= "`size_name` ASC, `size_created_date` DESC";
			}							
					    
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			
			// search query	total ***************************************************************************			
			$queryTotal = "SELECT COUNT(*) FROM `mbs_sizes` 
					  			          WHERE (`size_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			             OR `size_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')";				
			
		
		}
		
		else 		
		{
			
			// the query ************************************************************************************						
			$query = "SELECT * FROM `mbs_sizes` ORDER BY ";							  		

			if ($_REQUEST['sortby'])
			{
				$query .= " `" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `size_id`";
			}

			else
			{
				$query .= " `size_name`";
			}								
							  
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			

			// the query total ******************************************************************************
			$queryTotal = "SELECT COUNT(*) FROM `mbs_sizes`";
			
		
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
			<form name=\"search_size_data\" method=\"post\" action=\"" . $STR_URL . "size_list.php\">
				<input type=\"hidden\" name=\"frm_search_referer\" value=\"" . $_SERVER['PHP_SELF'] . "\" />
				<input type=\"text\" name=\"frm_search_text\" size=\"40\" maxlength=\"128\" value=\""; if ($_REQUEST['frm_search_text']) { $strResult .= stripslashes($_REQUEST['frm_search_text']); }  $strResult .= "\" />				
				<input class=\"btn\" type=\"submit\" name=\"frm_search_submit\" value=\"Search Size\" onclick=\"return validateSearch(this.form)\" /><br />
			</form>
			";

		// the form
		$strResult .= 
			"
			<form id=\"frm_size\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" />
			";

				
		$strResult .= "<div align=\"right\">";
		
		// the refresh link
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('sizes', 'list') !== 0) 
		{
			$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . ADMIN::getModuleFile('sizes', 'list') . "\" title=\"Size List\"><img src=\"" . $STR_URL . "img/refresh_icon.png\" /> Refresh</a>";		
		}

		$strResult .= "&nbsp;&nbsp;&nbsp;";
		
		// the add link	
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('sizes', 'add') !== 0) 
		{
			$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . ADMIN::getModuleFile('sizes', 'add') . "?action=add\" title=\"New Size\"><img src=\"" . $STR_URL . "img/add_icon.png\" /> New Size</a>";
		}

		$strResult .= "<br /><br />";

		$strResult .= "<a class=\"btn\" href=\"size_list_print.php?action=print\" target=\"_blank\"><img src=\"" . $STR_URL . "img/print_icon.png\" /> Print</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"size_list_email.php?action=email\" title=\"Send Size List to Email\"><img src=\"" . $STR_URL . "img/email_icon.png\" /> Email</a>";

		
		$strResult .= "	</div>";
		
		
		
		if ($strSortMode == "asc") { $strSortMode = "desc"; } elseif ($strSortMode == "desc") { $strSortMode = "asc"; }
		
		// the table
		$strResult .= 
			"			
			<div align=\"center\"><h2>Size List</h2></div>
			<div align=\"right\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
																						 array('pop', urlencode('yes')),
																						 array('sortby', urlencode($_REQUEST['sortby'])),
																						 array('sortmode', urlencode($_REQUEST['sortmode']))
																						 )) . "</div>

			<section id=\"table_size_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Size List\">
			<caption>Size List</caption>
			<thead class=\"well\">
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>																				
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=size_name&sortmode=" . $strSortMode . "\">Name</a></div></th>					
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=size_description&sortmode=" . $strSortMode . "\">Description</a></div></th>	
					";
		
		// edit / delete column																				 
		if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('sizes', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('sizes', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
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
					$strLink = ADMIN::getModuleFile('sizes', 'view') . "?size_id=" . urlencode($row['size_id']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&page_num=" . $_REQUEST['page_num'] . "&pop=yes";
										
					$strResult .= 
						"
						<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
							<td id=\"r" . $row['size_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
							<td><div align=\"left\">"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { $strResult .= "<a class=\"ajax callbacks cboxElement\" href=\"" . $STR_URL . $strLink . "\" title=\"" . html_entity_decode(strtoupper($row['size_name'])) . "\">"; } $strResult .= "<strong>" . stripslashes(htmlspecialchars($row['size_name'])) . "</strong>"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { "</a>"; } $strResult .= "</div></td>
							<td><div align=\"left\">" . stripslashes(htmlspecialchars($row['size_description'])) . "</div></td>
						";								
					
					// action column
					if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('sizes', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('sizes', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
					{	
					
						$strResult .= "<td><div align=\"center\">";
						
						// edit
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('sizes', 'edit') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{	
							$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"" . $STR_URL . "size.php?size_id=" . html_entity_decode($row['size_id']) . "&action=edit&pop=yes\" title=\"Edit Size\"><img src=\"" . $STR_URL . "img/edit_icon.png\" /> Edit</a>";
						}
						
						$strResult .= "&nbsp;&nbsp;";
						
						// delete
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('sizes', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{
							$strResult .= "<a id=\"frm_delete_button_" . $row['size_id'] . "\" class=\"btn\" href=\"" . $STR_URL . "size_list.php?size_id=" . $row['size_id'] . "&action=delete\" title=\"Delete Size\"><img src=\"" . $STR_URL . "img/delete_icon.png\" /> Delete</a> ";
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
				$strResult .= "<tr><td colspan=\"4\"><div align=\"center\">Found no data</div></td></tr>";			
			}
		
		$strResult .= 
			"
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"2\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
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
							if (confirmDeleteSize())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&size_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/size_proc.php',
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
		$strLog = "View the Size List";
			
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
		
		
	} // listSize()


	function viewSize() 
	{
		
		global $arrSiteConfig;
		global $STR_URL, $STR_PATH;
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_sizes` WHERE `size_id` = '" . mysql_real_escape_string($_REQUEST['size_id']) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
						
			?>
			
			<?php if ($_REQUEST['pop'] == "yes") { ?>
			<div align="center">
			<form name="myformTop" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "size_list.php"; } else { echo "size_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
				<input type="hidden" name="size_id" value="<?php echo $_REQUEST['size_id']; ?>">			
				<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
				<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">								
				<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
			</form>
			</div>
			<?php } ?>
			
			<h2>Size &raquo; <?php echo stripslashes(htmlspecialchars($row['size_name'])); ?></h2>

			<div class="span12">
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('sizes', 'add') !== 0) { ?>
			  	<a class="btn" href="size.php?action=add" title="New Size"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Size</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('sizes', 'edit') !== 0) { ?>
				<a class="btn" href="size.php?action=edit&size_id=<?php echo $row['size_id']; ?>" title="Edit Size"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('sizes', 'delete') !== 0) { ?>
				<a id="frm_delete_button_<?php echo $row['size_id']; ?>" class="btn" href="size_list.php?size_id=<?php echo $row['size_id']; ?>&action=delete" title="Delete Size" onclick="return confirmDeleteSize(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>
				<?php } ?>				
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('sizes', 'list') !== 0) { ?>
				<a class="btn" href="size_list.php" title="Size List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a> 
				<?php } ?>
			</div>
			<br /><br />

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
							if (confirmDeleteSize())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&size_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/size_proc.php',
									type: 'post', 
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
								
							}

							return false;	
										
						}			
						
    				});
 
				});
			</script>

			<fieldset>
				<div>
					<legend style="font-size:1.0em;">Data</legend>
				</div>

				<div class="container-fluid">
					<div class="row-fluid">
						
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Name</strong>:</div></div>
							<div class="span8"><strong><?php echo stripslashes(htmlspecialchars($row['size_name'])); ?></strong></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Description</strong>:</div></div>
							<div class="span8"><?php echo stripslashes(htmlspecialchars($row['size_description'])); ?></div>
						</div>

						<?php if ($_SESSION['user']['type'] == 'admin') { ?>
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Active</strong>:</div></div>
							<div class="span8"><?php if ($row['size_active'] == 'yes') { echo "Yes"; } else { echo "No"; } ?></div>
						</div>
						<?php } ?>
							
					</div>	
				</div>	
				
			</fieldset>	

			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['size_created_date']); ?> by <strong><?php echo stripslashes($row['size_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['size_modified_date']); ?> by <strong><?php echo stripslashes($row['size_modified_by']); ?></strong></li>
			</ul>	
			
		<?php if ($_REQUEST['pop'] == "yes") { ?>
		<div align="center" style="margin-top:20px;">
		<form name="myformBottom" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "size_list.php"; } else { echo "size_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
			<input type="hidden" name="size_id" value="<?php echo $_REQUEST['size_id']; ?>">			
			<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
			<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">									
			<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
		</form>
		</div>
		<?php } ?>
				
		<?php  
		}
		
	} // viewSize()
	

	function listStore()
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
			$query = "SELECT * FROM `mbs_stores` 
					  		  WHERE (`store_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  		  		 OR `store_contact` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 					  		  		 
					  			     OR `store_address` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')
					  			    ORDER BY "; 
				
			if ($_REQUEST['sortby'])
			{
				$query .= "`" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `store_id`";
			}								
				
			else 
			{				
				$query .= "`store_name` ASC, `store_created_date` DESC";
			}							
					    
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			
			// search query	total ***************************************************************************			
			$queryTotal = "SELECT COUNT(*) FROM `mbs_stores` 
					  			          WHERE (`store_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			          	     OR `store_contact` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 	
					  			                 OR `store_address` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')";				
			
		
		}
		
		else 		
		{
			
			// the query ************************************************************************************						
			$query = "SELECT * FROM `mbs_stores` ORDER BY ";							  		

			if ($_REQUEST['sortby'])
			{
				$query .= " `" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `store_id`";
			}

			else
			{
				$query .= " `store_name`";
			}								
							  
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			

			// the query total ******************************************************************************
			$queryTotal = "SELECT COUNT(*) FROM `mbs_stores`";
			
		
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
			<form name=\"search_store_data\" method=\"post\" action=\"" . $STR_URL . "store_list.php\">
				<input type=\"hidden\" name=\"frm_search_referer\" value=\"" . $_SERVER['PHP_SELF'] . "\" />
				<input type=\"text\" name=\"frm_search_text\" store=\"40\" maxlength=\"128\" value=\""; if ($_REQUEST['frm_search_text']) { $strResult .= stripslashes($_REQUEST['frm_search_text']); }  $strResult .= "\" />				
				<input class=\"btn\" type=\"submit\" name=\"frm_search_submit\" value=\"Search Store\" onclick=\"return validateSearch(this.form)\" /><br />
			</form>
			";

		// the form
		$strResult .= 
			"
			<form id=\"frm_store\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" />
			";

				
		$strResult .= "<div align=\"right\">";
		
		// the refresh link
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('stores', 'list') !== 0) 
		{
			$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . ADMIN::getModuleFile('stores', 'list') . "\" title=\"Store List\"><img src=\"" . $STR_URL . "img/refresh_icon.png\" /> Refresh</a>";		
		}

		$strResult .= "&nbsp;&nbsp;&nbsp;";
		
		// the add link	
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('stores', 'add') !== 0) 
		{
			$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . ADMIN::getModuleFile('stores', 'add') . "?action=add\" title=\"New Store\"><img src=\"" . $STR_URL . "img/add_icon.png\" /> New Store</a>";
		}
		

		$strResult .= "<br /><br />";

		$strResult .= "<a class=\"btn\" href=\"store_list_print.php?action=print\" target=\"_blank\"><img src=\"" . $STR_URL . "img/print_icon.png\" /> Print</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"store_list_email.php?action=email\" title=\"Send Store List to Email\"><img src=\"" . $STR_URL . "img/email_icon.png\" /> Email</a>";

		$strResult .= "	</div>";
		
		
		
		if ($strSortMode == "asc") { $strSortMode = "desc"; } elseif ($strSortMode == "desc") { $strSortMode = "asc"; }
		
		// the table
		$strResult .= 
			"			
			<div align=\"center\"><h2>Store List</h2></div>
			<div align=\"right\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
																						 array('pop', urlencode('yes')),
																						 array('sortby', urlencode($_REQUEST['sortby'])),
																						 array('sortmode', urlencode($_REQUEST['sortmode']))
																						 )) . "</div>

			<section id=\"table_store_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Store List\">
			<caption>Store List</caption>
			<thead class=\"well\">
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>																				
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=store_name&sortmode=" . $strSortMode . "\">Name</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=store_api_acc&sortmode=" . $strSortMode . "\">API ACC</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=store_address&sortmode=" . $strSortMode . "\">Address</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=store_phone&sortmode=" . $strSortMode . "\">Phone</a></div></th>	
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=store_fax&sortmode=" . $strSortMode . "\">Fax</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=store_email&sortmode=" . $strSortMode . "\">Email</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=store_contact&sortmode=" . $strSortMode . "\">Contact</a></div></th>
					";
		
		// edit / delete column																				 
		if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('stores', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('stores', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
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
					$strLink = ADMIN::getModuleFile('stores', 'view') . "?store_id=" . urlencode($row['store_id']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&page_num=" . $_REQUEST['page_num'] . "&pop=yes";
										
					$strResult .= 
						"
						<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
							<td id=\"r" . $row['store_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
							<td><div align=\"left\">"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { $strResult .= "<a class=\"ajax callbacks cboxElement\" href=\"" . $STR_URL . $strLink . "\" title=\"" . html_entity_decode(strtoupper($row['store_name'])) . "\">"; } $strResult .= "<strong>" . html_entity_decode(stripslashes($row['store_name'])) . "</strong>"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { "</a>"; } $strResult .= "</div></td>
							<td><div align=\"center\">#" . html_entity_decode(stripslashes($row['store_api_acc'])) . "</div></td>
							<td><div align=\"left\">" . html_entity_decode(stripslashes($row['store_address'])) . "</div></td>
							<td><div align=\"left\">" . html_entity_decode(stripslashes($row['store_phone'])) . "</div></td>
							<td><div align=\"left\">" . html_entity_decode(stripslashes($row['store_fax'])) . "</div></td>
							<td><div align=\"left\">" . html_entity_decode(stripslashes($row['store_email'])) . "</div></td>
							<td><div align=\"left\">" . html_entity_decode(stripslashes($row['store_contact'])) . "</div></td>
						";								
					
					// action column
					if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('stores', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('stores', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
					{	
					
						$strResult .= "<td><div align=\"center\">";
						
						// edit
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('stores', 'edit') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{	
							$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"" . $STR_URL . "store.php?store_id=" . html_entity_decode($row['store_id']) . "&action=edit&pop=yes\" title=\"Edit Store\"><img src=\"" . $STR_URL . "img/edit_icon.png\" /> Edit</a>";
						}
						
						$strResult .= "&nbsp;&nbsp;";
						
						// delete
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('stores', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{
							$strResult .= "<a id=\"frm_delete_button_" . $row['store_id'] . "\" class=\"btn\" href=\"" . $STR_URL . "store_list.php?store_id=" . $row['store_id'] . "&action=delete\" title=\"Delete Store\"><img src=\"" . $STR_URL . "img/delete_icon.png\" /> Delete</a> ";
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
				$strResult .= "<tr><td colspan=\"9\"><div align=\"center\">Found no data</div></td></tr>";			
			}
		
		$strResult .= 
			"
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"7\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
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
							if (confirmDeleteStore())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&store_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/store_proc.php',
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
		$strLog = "View the Store List";
			
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
		
		
	} // listStore()


	function viewStore() 
	{
		
		global $arrSiteConfig;
		global $STR_URL, $STR_PATH;
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_stores` WHERE `store_id` = '" . mysql_real_escape_string($_REQUEST['store_id']) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
						
			?>
			
			<?php if ($_REQUEST['pop'] == "yes") { ?>
			<div align="center">
			<form name="myformTop" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "store_list.php"; } else { echo "store_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
				<input type="hidden" name="store_id" value="<?php echo $_REQUEST['store_id']; ?>">			
				<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
				<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">								
				<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
			</form>
			</div>
			<?php } ?>
			
			<h2>Store &raquo; <?php echo stripslashes($row['store_name']); ?></h2>

			<div class="span12">
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('stores', 'add') !== 0) { ?>
			  	<a class="btn" href="store.php?action=add" title="New Store"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Store</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('stores', 'edit') !== 0) { ?>
				<a class="btn" href="store.php?action=edit&store_id=<?php echo $row['store_id']; ?>" title="Edit Store"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('stores', 'delete') !== 0) { ?>
				<a id="frm_delete_button_<?php echo $row['store_id']; ?>" class="btn" href="store_list.php?store_id=<?php echo $row['store_id']; ?>&action=delete" title="Delete Store" onclick="return confirmDeleteStore(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>
				<?php } ?>				
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('stores', 'list') !== 0) { ?>
				<a class="btn" href="store_list.php" title="Store List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a> 
				<?php } ?>
			</div>
			<br /><br />

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
							if (confirmDeleteStore())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&store_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/store_proc.php',
									type: 'post', 
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
								
							}

							return false;	
										
						}			
						
    				});
 
				});
			</script>

			<fieldset>
				<div>
					<legend style="font-size:1.0em;">Data</legend>
				</div>

				<div class="container-fluid">
					<div class="row-fluid">
						
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Name</strong>:</div></div>
							<div class="span8"><strong><?php echo stripslashes($row['store_name']); ?></strong></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>API ACC</strong>:</div></div>
							<div class="span8"><strong>#<?php echo stripslashes($row['store_api_acc']); ?></strong></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Address</strong>:</div></div>
							<div class="span8"><?php echo stripslashes($row['store_address']); ?></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Phone</strong>:</div></div>
							<div class="span8"><?php echo stripslashes($row['store_phone']); ?></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Fax</strong>:</div></div>
							<div class="span8"><?php echo stripslashes($row['store_fax']); ?></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Email</strong>:</div></div>
							<div class="span8"><?php echo stripslashes($row['store_email']); ?></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Contact</strong>:</div></div>
							<div class="span8"><?php echo stripslashes($row['store_contact']); ?></div>
						</div>

						<?php if ($_SESSION['user']['type'] == 'admin') { ?>
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Active</strong>:</div></div>
							<div class="span8"><?php if ($row['store_active'] == 'yes') { echo "Yes"; } else { echo "No"; } ?></div>
						</div>				 
				  		<?php } ?>

					</div>	
				</div>		
				
				
			</fieldset>
			
			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['store_created_date']); ?> by <strong><?php echo stripslashes($row['store_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['store_modified_date']); ?> by <strong><?php echo stripslashes($row['store_modified_by']); ?></strong></li>
			</ul>	
			
		<?php if ($_REQUEST['pop'] == "yes") { ?>
		<div align="center" style="margin-top:20px;">
		<form name="myformBottom" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "store_list.php"; } else { echo "store_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
			<input type="hidden" name="store_id" value="<?php echo $_REQUEST['store_id']; ?>">			
			<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
			<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">									
			<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
		</form>
		</div>
		<?php } ?>
				
		<?php  
		}
		
	} // viewStore()


	function listSupplier()
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
			$query = "SELECT * FROM `mbs_suppliers` 
					  		  WHERE (`supplier_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  		  		 OR `supplier_email` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 					  		  		 
					  			     OR `supplier_postal_address` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')
					  			    ORDER BY "; 
				
			if ($_REQUEST['sortby'])
			{
				$query .= "`" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `supplier_id`";
			}								
				
			else 
			{				
				$query .= "`supplier_name` ASC, `supplier_created_date` DESC";
			}							
					    
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			
			// search query	total ***************************************************************************			
			$queryTotal = "SELECT COUNT(*) FROM `mbs_suppliers` 
					  			          WHERE (`supplier_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			          		 OR `supplier_email` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 		
					  			                 OR `supplier_postal_address` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')";				
			
		
		}
		
		else 		
		{
			
			// the query ************************************************************************************						
			$query = "SELECT * FROM `mbs_suppliers` ORDER BY ";							  		

			if ($_REQUEST['sortby'])
			{
				$query .= " `" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `supplier_id`";
			}

			else
			{
				$query .= " `supplier_name`";
			}								
							  
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			

			// the query total ******************************************************************************
			$queryTotal = "SELECT COUNT(*) FROM `mbs_suppliers`";
			
		
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
			<form name=\"search_supplier_data\" method=\"post\" action=\"" . $STR_URL . "supplier_list.php\">
				<input type=\"hidden\" name=\"frm_search_referer\" value=\"" . $_SERVER['PHP_SELF'] . "\" />
				<input type=\"text\" name=\"frm_search_text\" size=\"40\" maxlength=\"128\" placeholder=\"Enter text to search\" value=\""; if ($_REQUEST['frm_search_text']) { $strResult .= stripslashes($_REQUEST['frm_search_text']); }  $strResult .= "\" />				
				<input class=\"btn btn-popover\" type=\"submit\" rel=\"popover\" data-content=\"Search Supplier's name, address and email based on keywords\" data-original-title=\"Search Supplier\" id=\"frm_search_submit\" name=\"frm_search_submit\" value=\"Search Supplier\" onclick=\"return validateSearch(this.form)\" /><br />
			</form>
			";

		// the form
		$strResult .= 
			"
			<form id=\"frm_supplier\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" />
			";

				
		$strResult .= "<div align=\"right\">";
		
		// the refresh link
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('suppliers', 'list') !== 0) 
		{
			$strResult .= "<a class=\"btn btn-popover\" href=\"" . $STR_URL . ADMIN::getModuleFile('suppliers', 'list') . "\" rel=\"popover\" data-content=\"Refresh the Supplier List to the latest update\" data-original-title=\"Refresh Supplier List\"><img src=\"" . $STR_URL . "img/refresh_icon.png\" /> Refresh</a>";		
		}

		$strResult .= "&nbsp;&nbsp;&nbsp;";
		
		// the add link	
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('suppliers', 'add') !== 0) 
		{
			$strResult .= "<a class=\"btn btn-popover\" href=\"" . $STR_URL . ADMIN::getModuleFile('suppliers', 'add') . "?action=add\" rel=\"popover\" data-content=\"Add new Supplier to the database\" data-original-title=\"New Supplier\"><img src=\"" . $STR_URL . "img/add_icon.png\" /> New Supplier</a>";
		}

		$strResult .= "<br /><br />";
		
		$strResult .= "<a class=\"btn btn-popover\" href=\"supplier_list_print.php?action=print\" target=\"_blank\" rel=\"popover\" data-content=\"Print the Supplier List from the browser. A new tab and a Print dialog will be popped up\" data-original-title=\"Print Supplier List\"><img src=\"" . $STR_URL . "img/print_icon.png\" /> Print</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn ajax callbacks cboxElement btn-popover\" href=\"supplier_list_email.php?action=email\" rel=\"popover\" data-content=\"Send the Supplier List to a certain email\" data-original-title=\"Send Supplier List to Email\" title=\"Send Supplier List to Email\"><img src=\"" . $STR_URL . "img/email_icon.png\" /> Email</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn btn-popover\" href=\"supplier_list_csv.php?action=export-csv\" rel=\"popover\" data-content=\"Export the Supplier List data to CSV format so it can be edited in Microsoft Excel\" data-original-title=\"Export Supplier List to CSV\"><img src=\"" . $STR_URL . "img/csv_icon.png\" /> Export to .CSV</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn ajax callbacks cboxElement btn-popover\" href=\"supplier_list_upload.php?action=import-csv\" rel=\"popover\" data-content=\"Import Supplier List data from CSV format. Please use the valid format from the Supplier List export\" data-original-title=\"Import Supplier List from CSV\"><img src=\"" . $STR_URL . "img/csv_icon.png\" /> Import from .CSV</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn btn-popover\" href=\"documentation_list.php#suppliers\" rel=\"popover\" data-content=\"Look up for the Documentation about Supplier module\" data-original-title=\"Help\" title=\"Help\"><i class=\"icon-info-sign\"></i> Help</a>";

		$strResult .= "	</div>";
		
		
		
		if ($strSortMode == "asc") { $strSortMode = "desc"; } elseif ($strSortMode == "desc") { $strSortMode = "asc"; }
		
		// the table
		$strResult .= 
			"			
			<div align=\"center\"><h2>Supplier List</h2></div>
			<div align=\"right\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
																						 array('pop', urlencode('yes')),
																						 array('sortby', urlencode($_REQUEST['sortby'])),
																						 array('sortmode', urlencode($_REQUEST['sortmode']))
																						 )) . "</div>

			<section id=\"table_supplier_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Supplier List\">
			<caption>Supplier List</caption>
			<thead class=\"well\">
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>																				
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=supplier_name&sortmode=" . $strSortMode . "\">Name</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=supplier_postal_address&sortmode=" . $strSortMode . "\">Address</a></div></th>					
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=supplier_phone_number&sortmode=" . $strSortMode . "\">Contact Number</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=supplier_email&sortmode=" . $strSortMode . "\">Email</a></div></th>	
					";
		
		// edit / delete column																				 
		if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('suppliers', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('suppliers', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
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
					$strLink = ADMIN::getModuleFile('suppliers', 'view') . "?supplier_id=" . urlencode($row['supplier_id']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&page_num=" . $_REQUEST['page_num'] . "";
										
					$strResult .= 
						"
						<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
							<td id=\"r" . $row['supplier_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
							<td><div align=\"left\">"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { $strResult .= "<a class=\"\" href=\"" . $STR_URL . $strLink . "\" title=\"" . html_entity_decode(strtoupper($row['supplier_name'])) . "\">"; } $strResult .= "<strong>" . html_entity_decode(stripslashes($row['supplier_name'])) . "</strong>"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { "</a>"; } $strResult .= "</div></td>
							<td><div align=\"left\">" . html_entity_decode(stripslashes($row['supplier_postal_address'])) . "</div></td>
							<td><div align=\"center\">" . html_entity_decode(stripslashes($row['supplier_phone_number'])) . "</div></td>
							<td><div align=\"center\">" . html_entity_decode(stripslashes($row['supplier_email'])) . "</div></td>
						";								
					
					// action column
					if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('suppliers', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('suppliers', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
					{	
					
						$strResult .= "<td><div align=\"center\">";
						
						// edit
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('suppliers', 'edit') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{	
							$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . "supplier.php?supplier_id=" . html_entity_decode($row['supplier_id']) . "&action=edit\" title=\"Edit Supplier\"><img src=\"" . $STR_URL . "img/edit_icon.png\" /> Edit</a>";
						}
						
						$strResult .= "&nbsp;&nbsp;";
						
						// delete
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('suppliers', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{
							$strResult .= "<a id=\"frm_delete_button_" . $row['supplier_id'] . "\" class=\"btn\" href=\"" . $STR_URL . "supplier_list.php?supplier_id=" . $row['supplier_id'] . "&action=delete\" title=\"Delete Supplier\"><img src=\"" . $STR_URL . "img/delete_icon.png\" /> Delete</a> ";
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
				$strResult .= "<tr><td colspan=\"6\"><div align=\"center\">Found no data</div></td></tr>";			
			}
		
		$strResult .= 
			"
			</tbody>
			<tfoot>
				<tr>
					<th scope=\"row\" colspan=\"2\">Total: " . $rowTotal[0] . "</th>					
					<td colspan=\"4\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
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
							if (confirmDeleteSupplier())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&supplier_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/supplier_proc.php',
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

			<script>
				$(function () { 
					$('.btn-popover').popover({ 
						trigger: 'hover',
						placement: 'top'
					});
				});
			</script>
			";		

		// The Log	
		$strLog = "View the Supplier List";
			
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
		
		
	} // listSupplier()


	function viewSupplier() 
	{
		
		global $arrSiteConfig;
		global $STR_URL, $STR_PATH;
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_suppliers` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
			
			// get account contacts
			$strQuery2 = "SELECT * FROM `mbs_suppliers_account_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' ORDER BY `supplier_account_id`";
			$result2 = mysql_query($strQuery2);
			
			if ($result2)
			{
				$arrAccounts = array();
				while ($row2 = mysql_fetch_assoc($result2))
				{
					$arrAccounts[] = $row2;
				}
			}


			// get marketing contacts
			$strQuery3 = "SELECT * FROM `mbs_suppliers_marketing_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' ORDER BY `supplier_contact_id`";
			$result3 = mysql_query($strQuery3);
			
			if ($result3)
			{
				$arrContacts = array();
				while ($row3 = mysql_fetch_assoc($result3))
				{
					$arrContacts[] = $row3;
				}
			}


			// get territory contacts
			$strQuery4 = "SELECT * FROM `mbs_suppliers_territory_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($_REQUEST['supplier_id']) . "' ORDER BY `supplier_territory_id`";
			$result4 = mysql_query($strQuery4);
			
			if ($result4)
			{
				$arrTerritory = array();
				while ($row4 = mysql_fetch_assoc($result4))
				{
					$arrTerritory[] = $row4;
				}
			}			
			?>
			
			<?php if ($_REQUEST['pop'] == "yes") { ?>
			<div align="center">
			<form name="myformTop" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "supplier_list.php"; } else { echo "supplier_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
				<input type="hidden" name="supplier_id" value="<?php echo $_REQUEST['supplier_id']; ?>">			
				<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
				<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">								
				<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
			</form>
			</div>
			<?php } ?>
			
			
			<h2>Supplier &raquo; <?php echo stripslashes($row['supplier_name']); ?></h2>
			
			
			<div class="span12">
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('suppliers', 'add') !== 0) { ?>
			  	<a class="btn" href="supplier.php?action=add" title="New Supplier"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Supplier</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('suppliers', 'edit') !== 0) { ?>
				<a class="btn" href="supplier.php?action=edit&supplier_id=<?php echo $row['supplier_id']; ?>" title="Edit Supplier"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('suppliers', 'delete') !== 0) { ?>
				<a id="frm_delete_button_<?php echo $row['supplier_id']; ?>" class="btn" href="supplier_list.php?supplier_id=<?php echo $row['supplier_id']; ?>&action=delete" title="Delete Supplier" onclick="return confirmDeleteSupplier(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>
				<?php } ?>				
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('suppliers', 'list') !== 0) { ?>
				<a class="btn" href="supplier_list.php" title="Supplier List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a> 
				<?php } ?>
				&nbsp;&nbsp;&nbsp;
				<a id="frm_print_button_<?php echo $row['supplier_id']; ?>" class="btn" href="supplier_view_print.php?action=print&supplier_id=<?php echo $row['supplier_id']; ?>" title="Print Supplier Data" target="_blank"><img src="<?php echo $STR_URL; ?>img/print_icon.png" /> Print</a>
				&nbsp;&nbsp;&nbsp;
				<a id="frm_email_button_<?php echo $row['supplier_id']; ?>" class="btn ajax callbacks cboxElement" href="supplier_view_email.php?action=email&supplier_id=<?php echo $row['supplier_id']; ?>" title="Email Supplier Data"><img src="<?php echo $STR_URL; ?>img/email_icon.png" /> Email</a>
			</div>
			<br /><br />
			

			<script>
				$(document).ready(function () {
					var strID;
					var intID;
					var deleteConf;	
			
					$('#frm_delete_button_<?php echo $row['supplier_id']; ?>').click(function(event) {
        				strID = event.target.id;        				        				
						intID = strID.replace('frm_delete_button_', '');						

						if (intID && intID !== '')
						{							
							if (confirmDeleteSupplier())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&supplier_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/supplier_proc.php',
									type: 'post', 
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
								
							}

							return false;	
										
						}			
						
    				});
 
				});
			</script>

			<?php if ($_REQUEST['pop'] == "yes") { ?>
			<!-- JQuery Colorbox -->
		    <script>
		    	$(document).ready(function() {
		    		$(".ajax").colorbox();
		    		$(".callbacks").colorbox({ 
		    			onCleanup:function() {    				
		    				location.reload();
		    			}
		    		});	
		    	});
		    </script> 
		    <?php } ?>

			

			<div class="container-fluid">
				<div class="row-fluid">				      	
					      	
					<fieldset>
						<div>
							<legend>Data</legend>		
						</div>

						<!-- Supplier Data -->
						<div class="row-fluid">
							<div class="span2">
								<p style="text-align:right;">Name:</p> 
							</div>

							<div class="span4">
								<p><strong><?php echo stripslashes($row['supplier_name']); ?></strong></p>
							</div>

						</div>	

						<div class="row-fluid">
							<div class="span2">
								<p style="text-align:right;">Postal Address:</p> 
							</div>

							<div class="span4">
								<p><?php echo stripslashes($row['supplier_postal_address']); ?></p>
							</div>								
						</div>	

						<div class="row-fluid">
							<div class="span2">
								<p style="text-align:right;">Contact Number:</p> 
							</div>

							<div class="span4">
								<p><?php echo stripslashes($row['supplier_phone_number']); ?></p>
							</div>								
						</div>	

						<div class="row-fluid">
							<div class="span2">
								<p style="text-align:right;">Email:</p> 
							</div>

							<div class="span4">
								<p><a href="mailto:<?php echo stripslashes($row['supplier_email']); ?>"><?php echo stripslashes($row['supplier_email']); ?></a></p>
							</div>								
						</div>

						<div class="row-fluid">
							<div class="span2">
								<p style="text-align:right;">Ref. No:</p> 
							</div>

							<div class="span4">
								<p><?php echo stripslashes($row['supplier_po_ref_number']); ?></p>
							</div>								
						</div>

						<div class="row-fluid">
							<div class="span2">
								<p style="text-align:right;">Active:</p> 
							</div>

							<div class="span4">
								<p><?php echo stripslashes($row['supplier_active']); ?></p>
							</div>								
						</div>

						<?php if ($row['supplier_name']) { ?> 
						<div class="row-fluid">
							<div class="span2">
								<p style="text-align:right;"></p> 
							</div>

							<div class="span4">
								<p><a id="frm_vcf_button_<?php echo $row['supplier_id']; ?>" class="btn" href="supplier_view_vcard.php?action=vcard&supplier_id=<?php echo $row['supplier_id']; ?>" title="Download vCard"><img src="<?php echo $STR_URL; ?>img/vcf_icon.png" style="width:16px;" /> Download vCard</a></p>
							</div>								
						</div>
						<?php } ?>	
						<!-- Supplier Data -->


						<!-- Marketing Contact -->
						<div class="row-fluid">
							<fieldset>
								<div>
									<legend>Marketing Contact</legend>	
								</div>

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Name:</p>	
									</div>

									<div class="span4">
										<p><strong><?php echo stripslashes($arrContacts[0]['supplier_contact_name']); ?></strong></p>
									</div>
								</div>	

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Title/Position:</p>	
									</div>

									<div class="span4">
										<p><?php echo stripslashes($arrContacts[0]['supplier_contact_position']); ?></p>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Postal Address (Billing Address):</p>	
									</div>

									<div class="span4">
										<p><?php echo stripslashes($arrContacts[0]['supplier_contact_postal_address']); ?></p>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Contact Number:</p>	
									</div>

									<div class="span4">
										<p><?php echo stripslashes($arrContacts[0]['supplier_contact_phone_number']); ?></p>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Mobile Number:</p>	
									</div>

									<div class="span4">
										<p><?php echo stripslashes($arrContacts[0]['supplier_contact_mobile_number']); ?></p>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Email:</p>	
									</div>

									<div class="span4">
										<p><a href="mailto:<?php echo stripslashes($arrContacts[0]['supplier_contact_email']); ?>"><?php echo stripslashes($arrContacts[0]['supplier_contact_email']); ?></a></p>
									</div>
								</div>

								<?php if ($arrContacts[0]['supplier_contact_name']) { ?> 
								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;"></p> 
									</div>

									<div class="span4">
										<p><a id="frm_vcf_button_<?php echo stripslashes($arrContacts[0]['supplier_contact_id']); ?>" class="btn" href="supplier_view_vcard.php?action=vcard&supplier_id=<?php echo $row['supplier_id']; ?>&supplier_contact_id=<?php echo stripslashes($arrContacts[0]['supplier_contact_id']); ?>" title="Download vCard"><img src="<?php echo $STR_URL; ?>img/vcf_icon.png" style="width:16px;" /> Download vCard</a></p>
									</div>								
								</div>
								<?php } ?>

							</fieldset>
						</div>	
						<!-- Marketing Contact -->	

						<!-- Account Contact -->
						<div class="row-fluid">
							<fieldset>
								<div>
									<legend>Account Contact</legend>	
								</div>

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Name:</p>	
									</div>

									<div class="span4">
										<p><strong><?php echo stripslashes($arrAccounts[0]['supplier_account_name']); ?></strong></p>
									</div>
								</div>	

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Postal Address:</p>	
									</div>

									<div class="span4">
										<p><?php echo stripslashes($arrAccounts[0]['supplier_account_postal_address']); ?></p>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Contact Number:</p>	
									</div>

									<div class="span4">
										<p><?php echo stripslashes($arrAccounts[0]['supplier_account_phone_number']); ?></p>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;">Email:</p>	
									</div>

									<div class="span4">
										<p><a href="mailto:<?php echo stripslashes($arrAccounts[0]['supplier_account_email']); ?>"><?php echo stripslashes($arrAccounts[0]['supplier_account_email']); ?></a></p>
									</div>
								</div>

								<?php if ($arrAccounts[0]['supplier_account_name']) { ?>
								<div class="row-fluid">
									<div class="span2">
										<p style="text-align:right;"></p> 
									</div>

									<div class="span4">
										<p><a id="frm_vcf_button_<?php echo stripslashes($arrAccounts[0]['supplier_account_id']); ?>" class="btn" href="supplier_view_vcard.php?action=vcard&supplier_id=<?php echo $row['supplier_id']; ?>&supplier_account_id=<?php echo stripslashes($arrAccounts[0]['supplier_account_id']); ?>" title="Download vCard"><img src="<?php echo $STR_URL; ?>img/vcf_icon.png" style="width:16px;" /> Download vCard</a></p>
									</div>								
								</div>
								<?php } ?>
										
							</fieldset>
						</div>


						<!-- Territory Contact -->
						<div class="row-fluid">
							<fieldset>
								<div>
									<legend>Territory Contact</legend>	
								</div>

								<?php for ($i = 0; $i < 6; $i++) { ?>
									<?php if ($arrTerritory[$i]['supplier_territory_name']) { ?>
										
										<!--#<?php echo $i+1; ?> -->
										<div class="row-fluid" style="border-bottom:1px solid #ddd; margin-bottom:10px;">

											<div class="row-fluid">
												<div class="span2">
													<p style="text-align:right;">Area:</p>	
												</div>

												<div class="span4">
													<!--<p><strong><?php echo DB::dbIDToField('mbs_territories', 'territory_id', stripslashes($arrTerritory[$i]['territory_id']), 'territory_name'); ?></strong></p>-->
													<p><strong><?php echo stripslashes($arrTerritory[$i]['territory_name']); ?></strong></p>
												</div>
											</div>

											<div class="row-fluid">
												<div class="span2">
													<p style="text-align:right;">Name:</p>	
												</div>

												<div class="span4">
													<p><strong><?php echo stripslashes($arrTerritory[$i]['supplier_territory_name']); ?></strong></p>
												</div>
											</div>	

											<div class="row-fluid">
												<div class="span2">
													<p style="text-align:right;">Contact Number:</p>	
												</div>

												<div class="span4">
													<p><?php echo stripslashes($arrTerritory[$i]['supplier_territory_phone_number']); ?></p>
												</div>
											</div>

											<?php if ($arrTerritory[$i]['supplier_territory_name']) { ?> 
											<div class="row-fluid">
												<div class="span2">
													<p style="text-align:right;"></p> 
												</div>

												<div class="span4">
													<p><a id="frm_vcf_button_<?php echo stripslashes($arrTerritory[$i]['supplier_territory_id']); ?>" class="btn" href="supplier_view_vcard.php?action=vcard&supplier_id=<?php echo $row['supplier_id']; ?>&supplier_territory_id=<?php echo stripslashes($arrTerritory[$i]['supplier_territory_id']); ?>" title="Download vCard"><img src="<?php echo $STR_URL; ?>img/vcf_icon.png" style="width:16px;" /> Download vCard</a></p>
												</div>								
											</div>
											<?php } ?>
										
										</div>
										<!--#<?php echo $i+1; ?> -->
									<?php } ?>
								<?php } ?>


							</fieldset>
						</div>


					</fieldset>  		
				</div>
			</div>
			
		    
			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['supplier_created_date']); ?> by <strong><?php echo stripslashes($row['supplier_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['supplier_modified_date']); ?> by <strong><?php echo stripslashes($row['supplier_modified_by']); ?></strong></li>
			</ul>

			
			<?php if ($_REQUEST['pop'] == "yes") { ?>
			<div align="center" style="margin-top:20px;">
			<form name="myformBottom" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "supplier_list.php"; } else { echo "supplier_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
				<input type="hidden" name="supplier_id" value="<?php echo $_REQUEST['supplier_id']; ?>">			
				<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
				<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">									
				<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
			</form>
			</div>
			<?php } ?>
					
			<?php  

			// The Log	
			$strLog = "View Supplier named \"" . $row['supplier_name'] . "\"";
				
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

		}
		
	} // viewSupplier()


	function listProduct()
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
			$query = "SELECT * FROM `mbs_products` 
					  		  WHERE (`product_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'
					  		  		 OR `product_code` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			     OR `product_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')
					  			     ORDER BY "; 
				
			if ($_REQUEST['sortby'])
			{
				$query .= "`" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `product_id`";
			}								
				
			else 
			{				
				$query .= "`product_code` ASC, `product_name` ASC, `product_created_date` DESC";
			}							
					    
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			
			// search query	total ***************************************************************************			
			$queryTotal = "SELECT COUNT(*) FROM `mbs_products` 
					  			          WHERE (`product_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			          	     OR `product_code` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'
					  			                 OR `product_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')";				
			
		
		}
		
		else 		
		{
			
			// the query ************************************************************************************						
			$query = "SELECT * FROM `mbs_products` ORDER BY ";							  		

			if ($_REQUEST['sortby'])
			{
				$query .= " `" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", `product_id`";
			}

			else
			{
				$query .= " `product_code`, `product_name`";
			}								
							  
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			

			// the query total ******************************************************************************
			$queryTotal = "SELECT COUNT(*) FROM `mbs_products`";
			
		
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
			<form name=\"search_product_data\" method=\"post\" action=\"" . $STR_URL . "product_list.php\">
				<input type=\"hidden\" name=\"frm_search_referer\" value=\"" . $_SERVER['PHP_SELF'] . "\" />
				<input type=\"text\" name=\"frm_search_text\" size=\"40\" maxlength=\"128\" value=\""; if ($_REQUEST['frm_search_text']) { $strResult .= stripslashes($_REQUEST['frm_search_text']); }  $strResult .= "\" />				
				<input class=\"btn\" type=\"submit\" name=\"frm_search_submit\" value=\"Search Product\" onclick=\"return validateSearch(this.form)\" /><br />
			</form>
			";

		// the form
		$strResult .= 
			"
			<form id=\"frm_product\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" />
			";

				
		$strResult .= "<div align=\"right\">";
		
		// the refresh link
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('products', 'list') !== 0) 
		{
			$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . ADMIN::getModuleFile('products', 'list') . "\" title=\"Product List\"><img src=\"" . $STR_URL . "img/refresh_icon.png\" /> Refresh</a>";		
		}

		$strResult .= "&nbsp;&nbsp;&nbsp;";
		
		// the add link	
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('products', 'add') !== 0) 
		{
			$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"" . $STR_URL . ADMIN::getModuleFile('products', 'add') . "?pop=yes\" title=\"New Product\"><img src=\"" . $STR_URL . "img/add_icon.png\" /> New Product</a>";
		}
		
		$strResult .= "	</div>";
		
		
		
		if ($strSortMode == "asc") { $strSortMode = "desc"; } elseif ($strSortMode == "desc") { $strSortMode = "asc"; }
		
		// the table
		$strResult .= 
			"			
			<div align=\"center\"><h2>Product List</h2></div>
			<div align=\"right\">" . HTML::showPaging($rowTotal[0], $totalPage, 4, array(array('frm_search_text', urlencode($_REQUEST['frm_search_text'])), 
																						 array('pop', urlencode('yes')),
																						 array('sortby', urlencode($_REQUEST['sortby'])),
																						 array('sortmode', urlencode($_REQUEST['sortmode']))
																						 )) . "</div>

			<section id=\"table_product_list\">
			<table class=\"table table-bordered table-hover\" summary=\"Product List\">
			<caption>Product List</caption>
			<thead class=\"well\">
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>																				
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=product_name&sortmode=" . $strSortMode . "\">Code/Name</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=product_normal_retail_price&sortmode=" . $strSortMode . "\">Normal Retail Price</a></div></th>
					<th scope=\"col\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=product_promo_price&sortmode=" . $strSortMode . "\">Promo Price</a></div></th>															
					";
		
		// edit / delete column																				 
		if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('products', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('products', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
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
					$strLink = ADMIN::getModuleFile('products', 'view') . "?product_id=" . urlencode($row['product_id']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&page_num=" . $_REQUEST['page_num'] . "&pop=yes";
										
					$strResult .= 
						"
						<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
							<td id=\"r" . $row['product_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
							<td><div align=\"left\">"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { $strResult .= "<a class=\"ajax callbacks cboxElement\" href=\"" . $STR_URL . $strLink . "\" title=\"" . html_entity_decode(strtoupper($row['product_name'])) . "\">"; } $strResult .= "<strong>" . html_entity_decode(stripslashes($row['product_code'])) . " / " . html_entity_decode(stripslashes($row['product_name'])) . "</strong>"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { "</a>"; } $strResult .= "</div></td>
							<td><div align=\"right\"><strong>$" . html_entity_decode(stripslashes($row['product_normal_retail_price'])) . "</strong></div></td>
							<td><div align=\"right\"><strong>$" . html_entity_decode(stripslashes($row['product_promo_price'])) . "</strong></div></td>							
						";								
					
					// action column
					if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('products', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('products', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
					{	
					
						$strResult .= "<td><div align=\"center\">";
						
						// edit
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('products', 'edit') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{	
							$strResult .= "<a class=\"btn ajax callbacks cboxElement\" href=\"" . $STR_URL . "product.php?product_id=" . html_entity_decode($row['product_id']) . "&action=edit&pop=yes\" title=\"Edit Product\"><img src=\"" . $STR_URL . "img/edit_icon.png\" /> Edit</a>";
						}
						
						$strResult .= "&nbsp;&nbsp;";
						
						// delete
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('products', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{
							$strResult .= "<a id=\"frm_delete_button_" . $row['product_id'] . "\" class=\"btn\" href=\"" . $STR_URL . "product_list.php?product_id=" . $row['product_id'] . "&action=delete\" title=\"Delete Product\"><img src=\"" . $STR_URL . "img/delete_icon.png\" /> Delete</a> ";
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
							if (confirmDeleteProduct())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&product_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/product_proc.php',
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
		$strLog = "View the Product List";
			
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
		
		
	} // listProduct()


	function viewProduct() 
	{
		
		global $arrSiteConfig;
		global $STR_URL, $STR_PATH;
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_products` WHERE `product_id` = '" . mysql_real_escape_string($_REQUEST['product_id']) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
						
			?>
			
			<?php if ($_REQUEST['pop'] == "yes") { ?>
			<div align="center">
			<form name="myformTop" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "product_list.php"; } else { echo "product_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
				<input type="hidden" name="product_id" value="<?php echo $_REQUEST['product_id']; ?>">			
				<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
				<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">								
				<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
			</form>
			</div>
			<?php } ?>
			
			<h2>Product &raquo; <?php echo stripslashes($row['product_name']); ?></h2>

			<div class="span12">
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('products', 'add') !== 0) { ?>
			  	<a class="btn" href="product.php?action=add" title="New Product"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Product</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('products', 'edit') !== 0) { ?>
				<a class="btn" href="product.php?action=edit&product_id=<?php echo $row['product_id']; ?>" title="Edit Product"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>
				<?php } ?>
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('products', 'delete') !== 0) { ?>
				<a id="frm_delete_button_<?php echo $row['product_id']; ?>" class="btn" href="product_list.php?product_id=<?php echo $row['product_id']; ?>&action=delete" title="Delete Product" onclick="return confirmDeleteProduct(this.form)"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>
				<?php } ?>				
				&nbsp;&nbsp;&nbsp; 
				<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('products', 'list') !== 0) { ?>
				<a class="btn" href="product_list.php" title="Product List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a> 
				<?php } ?>
			</div>
			<br /><br />

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
							if (confirmDeleteProduct())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&product_id=' + intID;							
		      				   
								var request = $.ajax({							    
									url: 'ajax/product_proc.php',
									type: 'post', 
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
								
							}

							return false;	
										
						}			
						
    				});
 
				});
			</script>

			<fieldset>
				<div>
					<legend style="font-size:1.0em;">Data</legend>
				</div>

				<div class="container-fluid">
					<div class="row-fluid">
						
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Code</strong>:</div></div>
							<div class="span8"><strong><?php echo stripslashes($row['product_code']); ?></strong></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Name</strong>:</div></div>
							<div class="span8"><strong><?php echo stripslashes($row['product_name']); ?></strong></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Normal Retail Price</strong>:</div></div>
							<div class="span8">$<?php echo stripslashes($row['product_normal_retail_price']); ?></div>
						</div>

						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Promo Price</strong>:</div></div>
							<div class="span8">$<?php echo stripslashes($row['product_promo_price']); ?></div>
						</div>

						<?php if ($row['product_size']) { ?>	
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Size</strong>:</div></div>
							<div class="span8"><?php echo stripslashes($row['product_size']); ?></div>
						</div>
						<?php } ?>

						<?php if ($_SESSION['user']['type'] == 'admin') { ?>
						<div class="row-fluid">
							<div class="span4"><div align="right"><strong>Active</strong>:</div></div>
							<div class="span8"><?php if ($row['product_active'] == 'yes') { echo "Yes"; } else { echo "No"; } ?></div>
						</div>
						<?php } ?>

					</div>
				</div>		
										
			</fieldset>
			
			<ul>				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['product_created_date']); ?> by <strong><?php echo stripslashes($row['product_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['product_modified_date']); ?> by <strong><?php echo stripslashes($row['product_modified_by']); ?></strong></li>
			</ul>

		<?php if ($_REQUEST['pop'] == "yes") { ?>
		<div align="center" style="margin-top:20px;">
		<form name="myformBottom" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "product_list.php"; } else { echo "product_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
			<input type="hidden" name="product_id" value="<?php echo $_REQUEST['product_id']; ?>">			
			<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
			<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">									
			<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
		</form>
		</div>
		<?php } ?>
				
		<?php  
		}
		
	} // viewProduct()


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
			$query = "SELECT * FROM `mbs_bookings` t1, `mbs_bookings_activities` t2 
					  		  WHERE t1.`booking_id` = t2.`booking_id` 
					  		  AND (t1.`booking_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'
					  		  	   OR t1.`booking_code` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			   OR t1.`booking_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'
					  			   OR t2.`booking_activity_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')
					  			   ORDER BY "; 
				
			if ($_REQUEST['sortby'])
			{
				$query .= "`" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", t1.`booking_id`";
			}								
				
			else 
			{				
				$query .= "t1.`booking_code` ASC, t1.`booking_name` ASC, t1.`booking_created_date` DESC";
			}							
					    
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			
			
			// search query	total ***************************************************************************			
			$queryTotal = "SELECT COUNT(*) FROM `mbs_bookings` t1, `mbs_bookings_activities` t2 
					  		  WHERE t1.`booking_id` = t2.`booking_id` 
					  		  AND (t1.`booking_name` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'
					  		  	   OR t1.`booking_code` LIKE '%" . mysql_real_escape_string($strSearchText) . "%' 
					  			   OR t1.`booking_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%'
					  			   OR t2.`booking_activity_description` LIKE '%" . mysql_real_escape_string($strSearchText) . "%')";				
			
		
		}
		
		else 		
		{
			
			// the query ************************************************************************************						
			$query = "SELECT * FROM `mbs_bookings` t1, `mbs_bookings_activities` t2 WHERE t1.`booking_id` = t2.`booking_id` ORDER BY ";							  		

			if ($_REQUEST['sortby'])
			{
				$query .= " `" . mysql_real_escape_string($_REQUEST['sortby']) . "` " . $strSortMode . ", t1.`booking_id`";
			}

			else
			{
				$query .= " t1.`booking_date` DESC, t1.`booking_code`";
			}								
							  
			$query .= " LIMIT " . $offset . "," . $TABLE_MAX_ROW_PER_PAGE;
			

			// the query total ******************************************************************************
			$queryTotal = "SELECT COUNT(*) FROM `mbs_bookings` t1, `mbs_bookings_activities` t2 WHERE t1.`booking_id` = t2.`booking_id`";
			
		
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
				<input type=\"text\" name=\"frm_search_text\" placeholder=\"Enter text to search\" size=\"40\" maxlength=\"128\" value=\""; if ($_REQUEST['frm_search_text']) { $strResult .= stripslashes($_REQUEST['frm_search_text']); }  $strResult .= "\" />				
				<input class=\"btn btn-popover\" type=\"submit\" rel=\"popover\" data-content=\"Search Booking's Name/Code based on keywords\" data-original-title=\"Search Bookings\" type=\"submit\" name=\"frm_search_submit\" value=\"Search Bookings\" onclick=\"return validateSearch(this.form)\" /><br />
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
			$strResult .= "<a class=\"btn btn-popover\" href=\"" . $STR_URL . ADMIN::getModuleFile('bookings', 'list') . "\" rel=\"popover\" data-content=\"Refresh the Booking List to the latest update\" data-original-title=\"Refresh Booking List\" title=\"Booking List\"><img src=\"" . $STR_URL . "img/refresh_icon.png\" /> Refresh</a>";		
		}

		$strResult .= "&nbsp;&nbsp;&nbsp;";
		
		// the add link	
		if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModulePrivilege('bookings', 'add') !== 0) 
		{
			$strResult .= "<a class=\"btn btn-popover\" href=\"" . $STR_URL . ADMIN::getModuleFile('bookings', 'add') . "?action=add\" rel=\"popover\" data-content=\"Insert new Booking to the database\" data-original-title=\"New Booking\" title=\"New Booking\"><img src=\"" . $STR_URL . "img/add_icon.png\" /> New Booking</a>";
		}

		$strResult .= "<br /><br />";

		$strResult .= "<a class=\"btn btn-popover\" href=\"booking_list_print.php?action=print\" target=\"_blank\" rel=\"popover\" data-content=\"Print the Booking List from the browser. A new tab and a Print dialog will be popped up\" data-original-title=\"Print Booking List\"><img src=\"" . $STR_URL . "img/print_icon.png\" /> Print</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn btn-popover ajax callbacks cboxElement\" href=\"booking_list_email.php?action=email\" rel=\"popover\" data-content=\"Send the Booking List to a certain email\" data-original-title=\"Send Booking List to Email\"><img src=\"" . $STR_URL . "img/email_icon.png\" /> Email</a>";
		$strResult .= "&nbsp;&nbsp;&nbsp;";
		$strResult .= "<a class=\"btn btn-popover\" href=\"documentation_list.php#bookings\" rel=\"popover\" data-content=\"Look up for the Documentation about Booking module\" data-original-title=\"Help\" title=\"Help\"><i class=\"icon-info-sign\"></i> Help</a>";

		
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
			<thead class=\"well\">
				<tr>					
					<th scope=\"col\" width=\"5%\"><div align=\"center\">No</div></th>																				
					<th scope=\"col\" width=\"10%\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=booking_date&sortmode=" . $strSortMode . "\">Date</a></div></th>
					<th scope=\"col\" width=\"30%\"><div align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?page_num=" . intval($_REQUEST['page_num']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&sortby=booking_code&sortmode=" . $strSortMode . "\">Code/Name</a></div></th>
					<th scope=\"col\"><div align=\"center\">Activities</div></th>
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
					$strLink = ADMIN::getModuleFile('bookings', 'view') . "?booking_id=" . urlencode($row['booking_id']) . "&frm_search_text=" . urlencode($_REQUEST['frm_search_text']) . "&page_num=" . $_REQUEST['page_num'] . "";
										
					$strResult .= 
						"
						<tr "; if ($no%2 == 0) { $strResult .= "class=\"odd\""; } $strResult .= ">
							<td id=\"r" . $row['booking_id'] . "\"><div align=\"right\">" . $no . ".</div></td>						
							<td><div align=\"center\">" . HTML::convertDateTime($row['booking_date']) . "</div></td>
							<td><div align=\"left\">"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { $strResult .= "<a href=\"" . $STR_URL . $strLink . "\" title=\"" . html_entity_decode(strtoupper($row['booking_code'])) . "\">"; } $strResult .= "<strong>" . stripslashes(htmlspecialchars($row['booking_code'])) . " / " . stripslashes(htmlspecialchars($row['booking_name'])) . "</strong>"; if ($_SESSION['user']['type'] == 'admin' || $strPrivView == "yes") { "</a>"; } $strResult .= "</div></td>
							<td><div align=\"left\">" . stripslashes($row['booking_activity_description']) . "</div></td>
														
						";								
					
					// action column
					if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('bookings', 'edit') !== 0 && $_SESSION['user']['type'] == 'user') || (ADMIN::getModulePrivilege('bookings', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) 
					{	
					
						$strResult .= "<td><div align=\"center\">";
						
						// edit
						if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('bookings', 'edit') !== 0 && $_SESSION['user']['type'] == 'user')) 
						{	
							$strResult .= "<a class=\"btn\" href=\"" . $STR_URL . "booking.php?booking_id=" . html_entity_decode($row['booking_id']) . "&action=edit\" title=\"Edit Booking\"><img src=\"" . $STR_URL . "img/edit_icon.png\" /> Edit</a>";
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
			<script>
				$(function () { 
					$('.btn-popover').popover({ 
						trigger: 'hover',
						placement: 'top'
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

	
	function viewBooking()
	{
		global $arrSiteConfig;
		global $STR_URL, $STR_PATH;
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_bookings` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);

			// get some variables
			$intBookingYear = substr($row['booking_date'], 0, 4);
			$strFilePath = $STR_PATH . $row['booking_file_path'] . $row['booking_file_name'];
						
			// get supplier data
			$strQuerySupplier = "SELECT * FROM `mbs_suppliers` WHERE `supplier_id` = '" . mysql_real_escape_string($row['supplier_id']) . "'";
			$resultSupplier = mysql_query($strQuerySupplier);
			
			if ($resultSupplier)
			{				
				$rowSupplier = mysql_fetch_assoc($resultSupplier);	

				// get marketing contact
				$strQueryContact = "SELECT * FROM `mbs_suppliers_marketing_contacts` WHERE `supplier_id` = '" . mysql_real_escape_string($rowSupplier['supplier_id']) . "'";
				$resultContact = mysql_query($strQueryContact);
			
				if ($resultContact)
				{
					$rowContact = mysql_fetch_assoc($resultContact);				
				}			
			}

						
			?>
			
			<?php if ($_REQUEST['pop'] == "yes") { ?>
			<div align="center">
			<form name="myformTop" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "booking_list.php"; } else { echo "booking_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
				<input type="hidden" name="booking_id" value="<?php echo $_REQUEST['booking_id']; ?>">			
				<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
				<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">								
				<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
			</form>
			</div>
			<?php } ?>

			<div class="container-fluid">
				<div class="row-fluid">			
					<div class="span12" style="text-align:center;margin-top:20px;">
						<h2>Booking &raquo; <?php echo stripslashes(htmlspecialchars($row['booking_name'])); ?></h2>
					</div>
				</div>
			</div>
				
			<div class="container-fluid">
				<div class="row-fluid">
 
					<div class="span12" style="text-align:center;margin-top:20px;">
						<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('bookings', 'add') !== 0) { ?>
					  	<a class="btn btn-popover" href="booking.php?action=add" rel="popover" data-content="Insert new Booking to the database" data-original-title="New Booking" title="New Booking"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Booking</a>
						<?php } ?>
						&nbsp;&nbsp;&nbsp; 
						<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('bookings', 'edit') !== 0) { ?>
						<a class="btn btn-popover" href="booking.php?booking_id=<?php echo $row['booking_id']; ?>&action=edit" rel="popover" data-content="Edit Booking including the Promotional Activities included" data-original-title="Edit Booking" title="Edit Booking"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</a>
						<?php } ?>
						&nbsp;&nbsp;&nbsp; 
						<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('bookings', 'delete') !== 0) { ?>
						<a id="frm_delete_button_<?php echo $row['booking_id']; ?>" class="btn btn-popover" href="booking_list.php?booking_id=<?php echo $row['booking_id']; ?>&action=delete" rel="popover" data-content="Delete Booking from the database" data-original-title="Delete Booking" title="Delete Booking" /><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Delete</a>
						<?php } ?>				
						&nbsp;&nbsp;&nbsp; 
						<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('bookings', 'list') !== 0) { ?>
						<a class="btn btn-popover" href="booking_list.php" rel="popover" data-content="Refresh the Booking List to the latest update" data-original-title="Booking List" title="Booking List"><img src="<?php echo $STR_URL; ?>img/list_icon.png" /> List</a> 
						<?php } ?>
						&nbsp;&nbsp;&nbsp;
						<a class="btn btn-popover" href="documentation_list.php#bookings" rel="popover" data-content="Look up for the Documentation about Booking module" data-original-title="Help" title="Help"><i class="icon-info-sign"></i> Help</a>

					</div>
				</div>
			</div>	

			<div class="container-fluid">
				<div class="row-fluid">

					<div class="span12" style="text-align:center;margin-top:20px;">
						<?php if ($_SESSION['user']['type'] == 'admin' || ADMIN::getModuleFile('bookings', 'add') !== 0) { ?>
					  	<!--<a class="btn" href="booking.php?booking_id=<?php echo $row['booking_id']; ?>&action=edit" title="New Promo Activity"><img src="<?php echo $STR_URL; ?>img/add_icon.png" /> New Promo Activity</a>-->
						<?php } ?>
						&nbsp;&nbsp;&nbsp; 						
						<a class="btn btn-popover ajax callbacks cboxElement" href="booking_view_upload.php?action=upload&booking_id=<?php echo $row['booking_id']; ?>" rel="popover" data-content="Upload the scanned Booking document to server. Please upload in JPG, GIF, PNG or PDF format!" data-original-title="Upload Booking" title="Upload Booking"><img src="<?php echo $STR_URL; ?>img/upload_icon.png" /> Attach</a>
						<?php if ($row['booking_file_name'] && file_exists($strFilePath)) { ?>
						&nbsp;&nbsp;&nbsp; 						
						<a class="btn btn-popover" href="booking_view_download.php?action=download&booking_id=<?php echo $row['booking_id']; ?>" rel="popover" data-content="Download attached scanned Booking document from server" data-original-title="Download Booking" title="Download Booking"><img src="<?php echo $STR_URL; ?>img/download_icon.png" /> Download</a>
						<?php } ?>
						&nbsp;&nbsp;&nbsp; 						
						<a class="btn btn-popover" href="booking_view_print.php?action=print&booking_id=<?php echo $row['booking_id']; ?>" target="_blank" rel="popover" data-content="Print the Booking from the browser. A new tab and a Print dialog will be popped up" data-original-title="Print Booking" title="Print Booking"><img src="<?php echo $STR_URL; ?>img/print_icon.png" /> Print</a>
						&nbsp;&nbsp;&nbsp;
						<a class="btn btn-popover ajax callbacks cboxElement" href="booking_view_email.php?action=email&booking_id=<?php echo $row['booking_id']; ?>" rel="popover" data-content="Send the Booking to a certain email" data-original-title="Email Booking" title="Email Booking"><img src="<?php echo $STR_URL; ?>img/email_icon.png" /> Email</a>
					</div>	

				</div>
			</div>	


			<fieldset>

			<div class="container-fluid">
				<div class="row-fluid">			
					<div class="span12" style="text-align:center;margin-top:20px;">
						<h3>Promotional Activity <?php echo $intBookingYear; ?></h3>
					</div>
				</div>
			</div>


			<div class="container-fluid">
				<div class="row-fluid">			
					<div class="span5">
						<p><strong>Supplier Name: <?php echo htmlspecialchars($rowSupplier['supplier_name']); ?></strong></p>
					</div>
					<div class="span3 offset4">
						<p><strong>Date: <?php echo HTML::convertDateTime($row['booking_date']); ?></strong></p>
					</div>
				</div>
			</div>

			<script>
				$(document).ready(function () {
										
					$('#frm_delete_button_<?php echo $row['booking_id']; ?>').click(function () {
						
						if (confirmDeleteBooking())
						{
							var dataString = 'action=delete&booking_id=<?php echo $row['booking_id']; ?>';
		      				   
							var request = $.ajax({							    
											url: 'ajax/booking_proc.php',
											type: 'post', 
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
							
						}
						
						return false;	

					});
				});	
			</script>


			<?php
				// Get the booking activity
				$queryBookingActivity = "SELECT * FROM `mbs_bookings_activities` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "' ORDER BY `booking_activity_month`";
				$resultBookingActivity = mysql_query($queryBookingActivity);

				$arrBookingActivityData = array();
				while ($rowBookingActivity = mysql_fetch_assoc($resultBookingActivity))
				{
					$arrBookingActivityData[] = $rowBookingActivity;
				}

				// Get the booking activity amount
				$queryBookingActivityAmount = "SELECT COUNT(*) FROM `mbs_bookings_activities` WHERE `booking_id` = '" . mysql_real_escape_string($_REQUEST['booking_id']) . "'";
				$resultBookingActivityAmount = mysql_query($queryBookingActivityAmount);
				$rowBookingActivityAmount = mysql_fetch_row($resultBookingActivityAmount);				
				$intBookingActivityAmount = $rowBookingActivityAmount[0];
				
			?>

			<?php if ($intBookingActivityAmount > 0) { ?>
			<script>

				$(document).ready(function() { 

					<?php for ($i = 0; $i < count($arrBookingActivityData); $i++) { ?>	
						$('#frm_activity_edit_<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>').click(function() {
							window.location = "<?php echo $STR_URL; ?>booking.php?booking_id=<?php echo $row['booking_id']; ?>&action=edit&booking_activity_id=<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>&child_action=edit-activity";
						});

						$('#frm_activity_delete_<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>').click(function() {
        				
							if (confirmDeleteBookingActivity())
							{
								$(this).closest('tr').remove();	

								var dataString = 'action=delete&booking_id=<?php echo $row['booking_id']; ?>&booking_activity_id=<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>';	
		      				   
								var request = $.ajax({							    
									url: 'ajax/booking_proc.php',
									type: 'post', 
									data: dataString,
									success: function(msg) {
										
										$.gritter.add({				
											title: 'Info',				
											text: '<p>' + msg + '</p>',				
											image: '<?php echo $STR_URL; ?>img/accepted.png',				
											sticky: false,				
											time: '3000'
										});

										$('#frm_preview').load('ajax/booking_activity_preview.php?booking_id=<?php echo $row['booking_id']; ?>');

									}
										    
								});		

							}	
							return false;							
										
						});	

					<?php } ?>		
						
    			});
 

			</script>

			<script>
				$(function () { 
					$('.btn-popover').popover({ 
						trigger: 'hover',
						placement: 'top'
					});
				});
			</script>
			<?php } ?>

		<div id="frm_preview">
			<table class="table table-bordered table-hover">			  		  
				<thead class="well">
				<tr>
					<th style="text-align:center;"><strong>Month/Year</strong></th>
				  	<th style="text-align:center;"><strong>Promotional Agreement</strong></th>
				  	<th style="text-align:center;"><strong>Price</strong></th>
				  	<th style="text-align:center;"><strong>Action</strong></th>
				</tr>			  
				</thead>

				<tbody>
				<?php if ($intBookingActivityAmount > 0) { ?>
				<?php for ($i = 0; $i < count($arrBookingActivityData); $i++) { ?>
				<?php if ($arrBookingActivityData[$i]['store_id']) { $arrStoreID = explode(',', $arrBookingActivityData[$i]['store_id']); $intStoreCount = count($arrStoreID); } ?>
				<?php if ($arrBookingActivityData[$i]['store_id']) { $strPrice = $arrBookingActivityData[$i]['booking_activity_price']*$intStoreCount; } else { $strPrice = $arrBookingActivityData[$i]['booking_activity_price']; } ?>
				<tr id="id<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>">
				  	<td><?php echo HTML::getMonthName($arrBookingActivityData[$i]['booking_activity_month']); ?> <?php echo stripslashes($arrBookingActivityData[$i]['booking_activity_year']); ?></td>
				  	<td><?php echo stripslashes($arrBookingActivityData[$i]['booking_activity_description']); ?></td>
				  	<td style="width:10%;"><div style="text-align:right;">$<?php echo number_format($strPrice, 2); ?></div></td>
				  	<?php if ($_SESSION['user']['type'] == 'admin') { ?>
				  	<td style="width:20%;"><div align="center">
				  		<?php if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('bookings', 'edit') !== 0 && $_SESSION['user']['type'] == 'user')) {	?>
				  		<!--<button class="btn" type="button" id="frm_activity_edit_<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>"><img src="<?php echo $STR_URL; ?>img/edit_icon.png" /> Edit</button>-->
						<?php } ?>
						&nbsp;&nbsp;&nbsp;
						<?php if ($_SESSION['user']['type'] == 'admin' || (ADMIN::getModulePrivilege('bookings', 'delete') !== 0 && $_SESSION['user']['type'] == 'user')) { ?>
						<button class="btn" type="button" id="frm_activity_delete_<?php echo $arrBookingActivityData[$i]['booking_activity_id']; ?>"><img src="<?php echo $STR_URL; ?>img/delete_icon.png" /> Remove</button>
						<?php } ?>						
					</div></td>
				  	<?php } ?>
				</tr>
				<?php $intTotalAmount += $strPrice; ?>
				<?php } ?>	
				<?php } else { ?>
				<tr>
					<td colspan="4"><div align="center">No Promo Activity yet. Please <a class="btn" href="booking.php?booking_id=<?php echo $row['booking_id']; ?>&action=edit">add</a></div></td>
				</tr>	
				<?php } ?>
				<tr>
					<td colspan="2"><div style="text-align:right;"><strong>Total</strong></div></td>
					<td><div style="text-align:right;"><strong>$<?php echo number_format($intTotalAmount, 2); ?></strong></div></td>
					<td></td>
				</tr>	

				</tbody>
			</table>
		</div>

			<div class="container-fluid">
				<div class="row-fluid">				      	
					      						
						<div class="row-fluid">
							<div class="span2 offset6">
								<p style="text-align:right;">Purchases in <?php echo intval($intBookingYear) - 1; ?>:</p> 
							</div>

							<div class="span4" style="border-bottom:1px solid #ddd;">
								<p><?php echo $rowSupplier['supplier_last_year_purchase']; ?></p>
							</div>

						</div>	

						<div class="row-fluid">
							<div class="span2 offset6">
								<p style="text-align:right;"><?php echo intval($intBookingYear); ?> Target:</p> 
							</div>

							<div class="span4" style="border-bottom:1px solid #ddd;">
								<p><?php echo stripslashes(htmlspecialchars($rowSupplier['supplier_target'])); ?></p>
							</div>								
						</div>	

						<div class="row-fluid">
							<div class="span2 offset6">
								<p style="text-align:right;">Growth Incentives:</p> 
							</div>

							<div class="span4" style="border-bottom:1px solid #ddd;">
								<p><?php echo stripslashes(htmlspecialchars($rowSupplier['supplier_growth_incentives'])); ?></p>
							</div>								
						</div>	

						<div class="row-fluid">
							<div class="span2 offset6">
								<p style="text-align:right;">Co-op Budget:</p> 
							</div>

							<div class="span4" style="border-bottom:1px solid #ddd;">
								<p><?php echo stripslashes(htmlspecialchars($rowSupplier['supplier_budget'])); ?></p>
							</div>								
						</div>

				</div>
			</div>		

			<div class="container-fluid" style="margin-top:80px;">
				<div class="row-fluid">	
				
				<div class="span2"><p>Signed:</p></div>
				<div class="span4" style="border-bottom:1px solid #ddd;"></div>	
				<div class="span2"></div>				
				<div class="span4" style="border-bottom:1px solid #ddd;"></div>	

				</div>
			</div>

			<div class="container-fluid">
				<div class="row-fluid">	
				
				<div class="span2"></div>
				<div class="span4" style="text-align:center;"><p style="color:#999;">For &amp; on behalf of supplier</p></div>	
				<div class="span2"></div>				
				<div class="span4" style="text-align:center;"><p style="color:#999;">For &amp; on behalf of Pharmacy 4 Less</p></div>	

				</div>
			</div>


			<div class="container-fluid" style="margin-top:40px;">
				<div class="row-fluid">	
				
				<div class="span2" style="text-align:right;"><p>Name :</p></div>
				<div class="span2" style="border-bottom:1px solid #ddd;"><p><?php echo htmlspecialchars($rowContact['supplier_contact_name']); ?></p></div>	
				<div class="span2"></div>				
				<div class="span2" style="text-align:right;"><p>Name :</p></div>	
				<div class="span4" style="border-bottom:1px solid #ddd;"><p><?php echo stripslashes(htmlspecialchars($arrSiteConfig['mbs_p4l_on_behalf_name'])); ?></p></div>	

				</div>
			</div>

			<div class="container-fluid">
				<div class="row-fluid">	
				
				<div class="span2" style="text-align:right;"><p>Title :</p></div>
				<div class="span2" style="border-bottom:1px solid #ddd;"><p><?php echo htmlspecialchars($rowContact['supplier_contact_position']); ?></p></div>	
				<div class="span2"></div>				
				<div class="span2" style="text-align:right;"><p>Title :</p></div>	
				<div class="span4" style="border-bottom:1px solid #ddd;"><p><?php echo stripslashes(htmlspecialchars($arrSiteConfig['mbs_p4l_on_behalf_position'])); ?></p></div>	

				</div>
			</div>	

			<div class="container-fluid">
				<div class="row-fluid">	
				
				<div class="span2" style="text-align:right;"><p>Date :</p></div>
				<div class="span2" style="border-bottom:1px solid #ddd;"><p><?php echo HTML::convertDateTime($row['booking_date']); ?></p></div>	
				<div class="span2"></div>				
				<div class="span2" style="text-align:right;"><p>Date :</p></div>	
				<div class="span4" style="border-bottom:1px solid #ddd;"><p><?php echo HTML::convertDateTime($row['booking_date']); ?></p></div>	

				</div>
			</div>	

			<div class="container-fluid">
				<div class="row-fluid">	
				
				<div class="span2" style="text-align:right;"><p>Phone :</p></div>
				<div class="span2" style="border-bottom:1px solid #ddd;"><p><?php echo htmlspecialchars($rowContact['supplier_contact_phone_number']); ?></p></div>	
				<div class="span2"></div>				
				<div class="span2" style="text-align:right;"></div>	
				<div class="span4"></div>	

				</div>
			</div>	

			<div class="container-fluid">
				<div class="row-fluid">	
				
				<div class="span2" style="text-align:right;"><p>Mobile :</p></div>
				<div class="span2" style="border-bottom:1px solid #ddd;"><p><?php echo htmlspecialchars($rowContact['supplier_contact_mobile_number']); ?></p></div>	
				<div class="span2"></div>				
				<div class="span2" style="text-align:right;"></div>	
				<div class="span4"></div>	

				</div>
			</div>

			<div class="container-fluid">
				<div class="row-fluid">	
				
				<div class="span2" style="text-align:right;"><p>Billing Address :</p></div>
				<div class="span2" style="border-bottom:1px solid #ddd;"><p><?php echo htmlspecialchars($rowContact['supplier_contact_postal_address']); ?></p></div>	
				<div class="span2"></div>				
				<div class="span2" style="text-align:right;"></div>	
				<div class="span4"><?php if ($row['booking_file_name'] && file_exists($strFilePath)) { ?><strong>Attachment</strong> <img src="<?php echo $STR_URL; ?>img/attachment_icon.png" title="Attachment" /><p><?php echo $row['booking_file_name']; ?> <em>(<?php echo HTML::getFileSize($strFilePath); ?>)</em></p><?php } ?></div>

				</div>
			</div>

			</fieldset>

			<ul style="margin-top:40px;">				
				<li><strong>Created on:</strong> <?php echo HTML::convertDateTime($row['booking_created_date']); ?> by <strong><?php echo stripslashes($row['booking_created_by']); ?></strong></li>
				<li><strong>Last modified on:</strong> <?php echo HTML::convertDateTime($row['booking_modified_date']); ?> by <strong><?php echo stripslashes($row['booking_modified_by']); ?></strong></li>
			</ul>
			
		<?php if ($_REQUEST['pop'] == "yes") { ?>
		<div align="center" style="margin-top:20px;">
		<form name="myformBottom" action="<?php if (preg_match("/_exec/", $_SERVER['HTTP_REFERER'])) { if ($_SESSION['user']['type'] == 'admin') { echo "booking_list.php"; } else { echo "booking_search.php"; } } else { echo $_SERVER['HTTP_REFERER']; } ?>">
			<input type="hidden" name="booking_id" value="<?php echo $_REQUEST['booking_id']; ?>">			
			<input type="hidden" name="page_num" value="<?php echo $_REQUEST['page_num']; ?>">
			<input type="hidden" name="frm_search_text" value="<?php echo $_REQUEST['frm_search_text']; ?>">									
			<input class="btn" type="submit" value="Close" onclick="this.value='Loading...'">
		</form>
		</div>
		<?php } ?>
				
		<?php  
		
		// The Log	
		$strLog = "View Booking named \"" . $row['booking_name'] . "\"";
			
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

		}

	} // viewBooking()

	
	function generateBookingCode()
	{
		DB::dbConnect();

		$query = "SHOW TABLE STATUS LIKE 'mbs_bookings'";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);

		$intIncrement = $row['Auto_increment'];
		$intIncrementLength = strlen($intIncrement);

		$strResult = "P4L";

		$intCount = (12 - intval(strlen($strResult)) - intval($intIncrementLength));

		for ($i = 0; $i < $intCount; $i++)
		{
			$strResult .= "0";
		}

		$strResult .= $intIncrement;

		return $strResult;

	} // generateBookingCode()




} // end of class HTML

?>