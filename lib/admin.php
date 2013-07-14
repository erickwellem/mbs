<?php
/*************************************************************
 * ADMIN Class
 * @Author		: Erick Wellem (me@erickwellem.com)
 * @Date		: March 8, 2009
 * @Description	: class for admin functions and privileges
 ************************************************************/

class ADMIN 
{
	
	var $conn;
	
	function getUserType($userLoginName) 
	{
		$this->conn = DB::dbConnect();
		$query = "SELECT `user_level` FROM `users` WHERE `user_login_name` = '" . $userLoginName . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_row($result);
			
			return $row[0];
		}
	
	} // getUserType()
	
	
	function checkUser($userLoginName) 
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT COUNT(*) FROM `users` WHERE `user_login_name` = '" . strtolower($userLoginName) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_row($result);
			
			return $row[0];
		}
	
	} // checkUser()
	
	function checkPassword($userLoginName, $password) 
	{
		$this->conn = DB::dbConnect();
		$query = "SELECT `user_password` FROM `users` WHERE `user_login_name` = '" . strtolower($userLoginName) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_row($result);
			
			if ($row[0] == md5($password)) 
			{
				return 1;
			} 
			else 
			{
				return 0;
			}
		}
	
	} // checkPassword()
	
	function getUserData($userLoginName) 
	{
		$this->conn = DB::dbConnect();
		$query = "SELECT * FROM `users` WHERE `user_login_name` = '" . strtolower($userLoginName) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_assoc($result);
			
			return $row;	
		}
	
	} // getUserData()
	
	function checkUserStatus($userLoginName) 
	{
		$this->conn = DB::dbConnect();
		$query = "SELECT COUNT(*) FROM `users` WHERE `user_login_name` = '" . $userLoginName . "' AND `user_subscription_end` >= NOW() LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_row($result);
			
			return $row[0];
		}
	
	} // checkUserStatus()
		
	function saveUserLogin($userLoginName) 
	{
		$this->conn = DB::dbConnect();

		$arrBrowserData = ADMIN::getBrowser();
		$strBrowser = $arrBrowserData['name'] . " " . $arrBrowserData['version'] . " on " . $arrBrowserData['platform'] . " reports: " . $arrBrowserData['userAgent'];

		$query = "INSERT INTO `logs`  
	    	      VALUES (NULL, 
				  		  '" . $userLoginName . "',
						  'Logged in successfully from " . $_SESSION['user']['ip_address'] . " using client browser " . $strBrowser . "', 
			    	      '" . $_SESSION['user']['login_time'] . "', 
						  '" . $_SESSION['user']['ip_address'] . "',
					  	  NULL)";
    	$result = mysql_query($query);
    	
    	if ($result) 
    	{
    		$query = "INSERT INTO `sessions`  
				  	  VALUES ('" . $_SESSION['user']['session_id'] . "', 
				          '" . $userLoginName . "', 
						  '" . time() . "', 
						  '" . $_SESSION['user']['login_time'] . "', 
						  '" . $_SESSION['user']['ip_address'] . "',
						  'http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "')";
						  
			$result = mysql_query ($query);				
				
				return 1;
			
    	} 
    	
    	else 
    	{
    		return 0;
    	}
	
	} // saveUserLogin()
	
	function sendSecurityNotification() 
	{
		
		$arrSiteConfig = DB::getSiteConfig();
		
		$subject =  stripslashes($arrSiteConfig['site_name']) . ' : Successful Login Report';
		$email = "me@erickwellem.com";	
		
		$strMsg = "Login on site: " . stripslashes($arrSiteConfig['site_name']) . "\n\n";
		$strMsg .= "URL       : " . $_SERVER['HTTP_REFERER'] . "\n";
		$strMsg .= "Full Name : " . DB::getUserFullNameByID($_SESSION['user']['id']) . "\n";
		$strMsg .= "Username  : " . strtolower($_REQUEST['frm_user_login']) . "\n";		
		$strMsg .= "Role      : " . $_SESSION['user']['type'] . "\n";
		$strMsg .= "From      : " . $_SERVER['REMOTE_ADDR'] . " (" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")\n";
		$strMsg .= "Datetime  : " . date('d F Y - H:i:s') ."\n";
		$strMsg .= "Client    : " . $_SERVER['HTTP_USER_AGENT'] ."\n";
	
		$message = $strMsg;
		
		// header for plain text email
		$headers = "From: " . strtolower($arrSiteConfig['site_admin_email']) . "\r\n";		
		$headers .= "Return-Path: <me@erickwellem.com>\r\n";
		$headers .= "Content-Type: text/plain; charset=us-ascii\r\n";
	
		// mail the code
		$strMail = @mail ($email, $subject, stripslashes($message), $headers);	
		
		if ($strMail) 
		{
			return TRUE;	
		} 
		
		else 		
		{
			return FALSE;	
		}
	
	} // sendSecurityNotification()
	
	function getLogID() 
	{
        $this->conn = DB::dbConnect();		
		$query = "SELECT MAX(`log_id`) FROM `logs`";
		$result = mysql_query($query);
		
		if ($result) 
		{
			$row = mysql_fetch_row($result);
			
			return $row[0];			
		}
	
	} // getLogID()
	
	
	function checkUserLogin($dirPos='./') 
	{   				
		
	   	if ($dirPos == '../../') { $target = '../';	} elseif ($dirPos == '../') { $target = './'; } else { $target = './'; }
		
	
	    if (!$_SESSION['user']['session_id'] ||
	 	    !$_SESSION['user']['log_id'] ||
		    !$_SESSION['user']['type'] ||
		    !$_SESSION['user']['ip_address'] ||
		    !$_SESSION['user']['login_name'] ||
		    !$_SESSION['user']['login_time'] || 
		    !$_SESSION['user']['id'] ||
		    $this->isSessionExist($_SESSION['user']['session_id'], $_SESSION['user']['login_name']) == FALSE) 
		    { 
				
		    	HTML::showHeader();
		    	HTML::showAlert("Sorry, You are not logged in yet. Please login!");		    	
				HTML::redirectUser($target, 3);
		    	HTML::showFooter();
		    	
		    	exit;	
		
			}
			
			elseif (strpos($_SERVER['SCRIPT_FILENAME'], '_exec') === FALSE && $this->isPrivileged() === FALSE) 			
			{
				
				if ($_SESSION['user']['type'])
				{
					$target = 'home.php';
				}
				
				HTML::showHeader();
				HTML::showAlert("Sorry, you cannot access this page!");		    	
				HTML::redirectUser($target, 3);
		    	HTML::showFooter();
		    	
		    	exit;
			}

			else
			{
				// update session time
				$query = "UPDATE `sessions` SET `session_time` = UNIX_TIMESTAMP(), 
												`session_url` = 'http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "' 
											WHERE `session_user` = '" . $_SESSION['user']['login_name'] . "' 
											AND `session_id` = '" . $_SESSION['user']['session_id'] . "'";
				$result = mysql_query($query);
			}
						
	} // checkUserLogin()
	
	
	function isLoggedIn()
	{
		
		if ($_SESSION['user']['session_id'] && 
	 	    $_SESSION['user']['log_id'] && 
		    $_SESSION['user']['type'] && 
		    $_SESSION['user']['ip_address'] && 
		    $_SESSION['user']['login_name'] && 
		    $_SESSION['user']['login_time'] &&  
		    $_SESSION['user']['id'] && 
		    $_SESSION['user']['user_group_id'] && 
		    $this->isSessionExist($_SESSION['user']['session_id'], $_SESSION['user']['login_name'])) 
		    {
		    	return 1;
		    }
		    
		    else 
		    
		    {
		    	return 0;
		    }
	
	} // isLoggedIn()
	
	
	function getPrivileges()
	{
		$this->conn = DB::dbConnect();
		
		if ($_SESSION['user']['type'] == 'admin')
		{
		
			$query = "SELECT `module_id`, 
							 `module_file_name_list`, 
							 `module_file_name_add`, 
							 `module_file_name_edit`, 
							 `module_file_name_view`, 
							 `module_file_name_delete`, 
							 `module_file_name_execute` 
					  FROM `modules` 
					  ORDER BY `module_id`";
		}
		
		else 		
		{
			
			$query = "SELECT t1.`module_id`, 
							 t1.`module_file_name_list`, 
							 t1.`module_file_name_add`, 
							 t1.`module_file_name_edit`, 
							 t1.`module_file_name_view`, 
							 t1.`module_file_name_delete`, 
							 t1.`module_file_name_execute`, 
							 t2.`priv_list`, 
							 t2.`priv_add`, 
							 t2.`priv_edit`, 
							 t2.`priv_view`, 
							 t2.`priv_delete`, 
							 t2.`priv_execute` 
							 
					  FROM `modules` t1, `privileges` t2 
					  WHERE t2.`module_id` = t1.`module_id` 					  
					  AND t2.`user_id` = '" . $_SESSION['user']['id'] . "'
					  ORDER BY t1.`module_id`";
			
		}
		
		$result = mysql_query($query);
		
		if ($result)
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				if ($row['module_file_name_execute'])
				{
					$data[] = array('module_id' => $row['module_id'], 'file_name' => $row['module_file_name_execute'], 'value' => $row['priv_execute']);
				}
				
				if ($row['module_file_name_list'])
				{
					$data[] = array('module_id' => $row['module_id'], 'file_name' => $row['module_file_name_list'], 'value' => $row['priv_list']);
				}
				
				if ($row['module_file_name_add'])
				{
					$data[] = array('module_id' => $row['module_id'], 'file_name' => $row['module_file_name_add'], 'value' => $row['priv_add']);
				}
				
				if ($row['module_file_name_edit'])
				{
					$data[] = array('module_id' => $row['module_id'], 'file_name' => $row['module_file_name_edit'], 'value' => $row['priv_edit']);
				}
				
				if ($row['module_file_name_delete'])
				{
					$data[] = array('module_id' => $row['module_id'], 'file_name' => $row['module_file_name_delete'], 'value' => $row['priv_delete']);
				}
				
				if ($row['module_file_name_view'])
				{
					$data[] = array('module_id' => $row['module_id'], 'file_name' => $row['module_file_name_view'], 'value' => $row['priv_view']);
				}
				
			}
			
			if ($_SESSION['user']['type'] == 'admin') 
			{
				$dataResult = array();
				
				for ($i = 0; $i < count($data); $i++) 
				{
					$dataResult['admin'][$i] = $data[$i]['file_name'];
				}
				
			}
			
			else 			
			{
				$dataResult = array();
				
				for ($i = 0; $i < count($data); $i++) 
				{
					if ($data[$i]['value'] == 'yes')
					{
						$dataResult['user'][$i] = $data[$i]['file_name'];
					}
					
				}
				
			}
			
			return $dataResult;
		}
	
	} // getPrivileges()

	function isPrivileged() 
	{
		
		global $arrPrivileges;
		
		if ($_SESSION['user']['type']) 
		{
			#echo basename($_SERVER['SCRIPT_FILENAME']);
			foreach ($arrPrivileges as $level => $files)
			{
				if ($level == $_SESSION['user']['type'])
				{
					foreach ($files as $id=>$fileName)
					{
						$data[] = $fileName;
					}
				}
			}
			
			
			return @array_search(basename($_SERVER['SCRIPT_FILENAME']), $data);
			
		}	
	
	} // isPrivileged()
	
	
	function getModulePrivilege($moduleName, $privilege)
	{
		$this->conn = DB::dbConnect();
		
		switch ($privilege) 
		{
			case 'add': $privilege = 'priv_add';
				break;
			case 'edit': $privilege = 'priv_edit';
				break;	
			case 'list': $privilege = 'priv_list';
				break;
			case 'view': $privilege = 'priv_view';
				break;
			case 'delete': $privilege = 'priv_delete';
				break;
			case 'del': $privilege = 'priv_delete';
				break;
			case 'exec': $privilege = 'priv_execute';
				break;
			case 'execute': $privilege = 'priv_execute';
				break;
			default: $privilege = $privilege;
				break;
				
		}
		
		$moduleID = DB::dbFieldToID('modules', 'module_name', $moduleName, 'module_id');
		
		
		if ($_SESSION['user']['type'] == 'admin') 
		{
			return 1;
		}
		
		else 
		{
			
			$query = "SELECT `" . $privilege . "` FROM `privileges` 
					  WHERE `module_id` = '" . $moduleID . "' 
					  AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
			
			$result = mysql_query($query);
			
			if ($result)
			{
				$row = mysql_fetch_row($result);
				
				if ($row[0] == 'yes')
				{
					return 1;
				}
				
				else 
				{
					return 0;
				}
			}
			
			else 
			{
				return 0;
			}
		}
		
	} // getModulePrivilege()
	
	function isSessionExist ($sessionID, $userLoginName) 
	{		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT COUNT(*) 
				  FROM `sessions`   
		          WHERE `session_id` = '" . $sessionID . "' 
		          AND `session_user` = '" . $userLoginName . "'"; 
		
		$result = mysql_query($query);

		if ($result) 
		{
			$row = mysql_fetch_row($result);
				
			if ($row[0] > 0) 
			{
				return TRUE;
			} 
			else 
			{
				return FALSE;
			}
		}
		 
		else 
		{
			return FALSE;
		}
	
	} // isSessionExist()	
	
	function logoutUser($logID) 
	{

	   if ($_SESSION['user']['session_id'] &&
	 	   $_SESSION['user']['login_name'] &&
		   $_SESSION['user']['type'] &&		   
		   $_SESSION['user']['login_time'] &&
		   $_SESSION['user']['ip_address'] &&
		   $_SESSION['user']['log_id'] && 
		   $_SESSION['user']['id'] && 
		   $_SESSION['user']['user_group_id']
		   ) 
		   {
			
		   	$this->saveLoginInfo($logID);    
		   
			$this->conn = DB::dbConnect();
			
			$query = "DELETE FROM `sessions` WHERE `session_id` = '" . $_SESSION['user']['session_id'] . "' AND `session_user` = '" . $_SESSION['user']['login_name'] . "'";
			
			$result = mysql_query($query);
			
				if ($result) 
				{
					unset($_SESSION['user']['session_id']);
					unset($_SESSION['user']['log_id']);
					unset($_SESSION['user']['type']);
					unset($_SESSION['user']['login_name']);									
					unset($_SESSION['user']['login_time']);
					unset($_SESSION['user']['id']);
					unset($_SESSION['user']['ip_address']);
					unset($_SESSION['user']['user_group_id']);
					
					#$_SESSION = array();
					#session_destroy();
					// Added: delete also sessions that's been active over 24 hours
					$queryDel = "DELETE FROM `sessions` WHERE (UNIX_TIMESTAMP()-`session_time`) > 86400";
					$resultDel = mysql_query($queryDel);
					
					return 1;
					
				}
				 
				else 
				{
					return 0;
				}
			
			}
			 
			else 
			{
				return 0; 
			}
	
	} // logoutUser()
	
	
	function saveLoginInfo($logID) 
	{  		   
	
		$this->conn = DB::dbConnect();
		
		$query = "UPDATE `logs`  
		       	  SET `log_logout` = '" . date("Y-m-d H:i:s") . "' 
				  WHERE `log_id` = '" . $logID . "'";			  
		    
		$result = mysql_query($query);
		
		// update user table
		$query2 = "UPDATE `users` SET `user_last_login_time` = '" . $_SESSION['user']['login_time'] . "', 
									  `user_last_login_from` = '" . $_SESSION['user']['ip_address'] . "' 
								WHERE `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
		$result2 = mysql_query($query2);
			
		/* add log table */        
		$result3 = mysql_query("INSERT INTO `logs`  
								(log_user,
								 log_action,
								 log_time,
								 log_from)
	
								 VALUES ('" . $_SESSION['user']['login_name'] . "',
								         'Logout successfully from " . $_SESSION['user']['ip_address'] . "',
										 '" . date('Y-m-d H:i:s') . "',
										 '" . $_SESSION['user']['ip_address'] . "')");
		    
	    if ($result) 
	    {	
			return 1;	
		}
		 
		else 
		{
			return 0;
		}
	
	
	} // saveLoginInfo()
	
   
	function getModuleFile($strModuleName, $strModulePrivilege)
	{
		
			$query = "SELECT ";
			
			switch ($strModulePrivilege) 
			{
				case 'list'    : $query .= "t1.`module_file_name_list`";
					break;
				case 'add'     : $query .= "t1.`module_file_name_add`"; 
					break;	
				case 'edit'    : $query .= "t1.`module_file_name_edit`"; 
					break;
				case 'delete'  : $query .= "t1.`module_file_name_delete`"; 
					break;
				case 'view'    : $query .= "t1.`module_file_name_view`"; 
					break;	
				case 'execute' : $query .= "t1.`module_file_name_execute`"; 
					break;
				default        : $query .= "t1.*"; 
					break;
			}
						
			$query .= " FROM `modules` t1"; 
			
			if ($_SESSION['user']['type'] == 'admin')
			{
				$query .= " WHERE t1.`module_name` = '" . $strModuleName . "'";
			
			}
			
			else 
			{
				$query .= ", `privileges` t2 ";
				$query .= " WHERE t1.`module_id` = t2.`module_id` ";
				$query .= " AND t1.`module_name` = '" . $strModuleName . "'";
				$query .= "AND t2.`user_id` = '" . $_SESSION['user']['id'] . "'";	
				
				switch ($strModulePrivilege) 
				{
					case 'list'    : $query .= "AND t2.`priv_list` = 'yes'";
						break;
					case 'add'     : $query .= "AND t2.`priv_add` = 'yes'"; 
						break;	
					case 'edit'    : $query .= "AND t2.`priv_edit` = 'yes'"; 
						break;
					case 'delete'  : $query .= "AND t2.`priv_delete` = 'yes'"; 
						break;
					case 'view'    : $query .= "AND t2.`priv_view` = 'yes'"; 
						break;	
					case 'execute' : $query .= "AND t2.`priv_execute` = 'yes'"; 
						break;
					default        : $query .= ""; 
						break;
				}
			
			}		
			
			#echo $query . "<br /><br />";
	
			$result = mysql_query($query);
			
			if ($result)
			{
				$row = mysql_fetch_row($result);
				
				if ($row[0]) 
				{
					return $row[0];
				}
				
				else 
				{
					return 0;
				}
			
			}
				
	} // getModuleFile()
	
	
	function isModulePrivilegeExist($intID, $moduleID, $type='user')
	{
		
		if ($type == 'user')
		{
			$query = "SELECT COUNT(*) FROM `privileges` WHERE `user_id` = '" . $intID . "' AND `module_id` = '" . $moduleID . "' AND `user_group_id` = '0'";
		}
		
		elseif ($type == 'group') 
		{
			$query = "SELECT COUNT(*) FROM `privileges` WHERE `user_group_id` = '" . $intID . "' AND `module_id` = '" . $moduleID . "' AND `user_id` = '0'";
		}
		
		$result = mysql_query($query);
		
		if ($result)
		{
			$row = mysql_fetch_row($result);
			
			if ($row[0] > 0) 
			{
				return 1;
			}
			
			else 
			{
				return 0;
			}
		
		}
		
	} // isModulePrivilegeExist()
	
	function getGroupPrivileges($userGroupID)
	{
		
		$query = "SELECT * FROM `privileges` WHERE `user_group_id` = '" . $userGroupID . "'";
		$result = mysql_query($query);
		
		if ($result)
		{
			$data = array();
			while ($row = mysql_fetch_arrow($result))
			{
				$data[] = $row;
			}
			
			return $data;
		}
	
	} // getGroupPrivileges()


	function getBrowser() 
	{ 
	    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version= "";

	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
	        $platform = 'Linux';
	    }
	    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	        $platform = 'Mac';
	    }
	    elseif (preg_match('/windows|win32/i', $u_agent)) {
	        $platform = 'Windows';
	    }
	    
	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
	    { 
	        $bname = 'Internet Explorer'; 
	        $ub = "MSIE"; 
	    } 
	    elseif(preg_match('/Firefox/i',$u_agent)) 
	    { 
	        $bname = 'Mozilla Firefox'; 
	        $ub = "Firefox"; 
	    } 
	    elseif(preg_match('/Chrome/i',$u_agent)) 
	    { 
	        $bname = 'Google Chrome'; 
	        $ub = "Chrome"; 
	    } 
	    elseif(preg_match('/Safari/i',$u_agent)) 
	    { 
	        $bname = 'Apple Safari'; 
	        $ub = "Safari"; 
	    } 
	    elseif(preg_match('/Opera/i',$u_agent)) 
	    { 
	        $bname = 'Opera'; 
	        $ub = "Opera"; 
	    } 
	    elseif(preg_match('/Netscape/i',$u_agent)) 
	    { 
	        $bname = 'Netscape'; 
	        $ub = "Netscape"; 
	    } 
	    
	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
	        // we have no matching number just continue
	    }
	    
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
	            $version= $matches['version'][0];
	        }
	        else {
	            $version= $matches['version'][1];
	        }
	    }
	    else {
	        $version= $matches['version'][0];
	    }
	    
	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}
	    
	    return array(
	        'userAgent' => $u_agent,
	        'name'      => $bname,
	        'version'   => $version,
	        'platform'  => $platform,
	        'pattern'    => $pattern
	    );

	} // getBrowser()


} // end of Class ADMIN

?>