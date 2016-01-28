<?php
/*************************************************************
 * DB Class
 * @Author		: Erick Wellem (me@erickwellem.com)
 * @Date		: March 8, 2009
 * @Description	: class for mysql database connections,
 *				  queries and other database manipulations
 ************************************************************/

class DB 
{
	
	var $conn; 
	
	static function dbConnect() 
	{
		
		global $DB_HOST;
		global $DB_USER;
		global $DB_PASS;
		global $DB_NAME;	
		
		/* connect to mysql  database */
		$conn = mysql_connect ($DB_HOST, $DB_USER, $DB_PASS) 
		    		  or die ("Sorry, connection to the mysql database is failed: " . mysql_error());
	
		/* select database */
		mysql_select_db ($DB_NAME, $conn) or die ("Failed to use database \"" . $DB_NAME . "\"!");	
	
		return $conn;
		
	} // dbConnect()
	
	
	function getSiteConfig ()
	{
		DB::dbConnect();
		
		$query = "SELECT * FROM `settings` ORDER BY `setting_id`";
		$result = mysql_query($query);
		
		if ($result)
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['setting_name']] = $row['setting_value'];
			}
			
			return $data;
		}
		
	} // getSiteConfig()
	
	static function dbFieldToID($tableName, $fieldName, $fieldValue, $IDName) 
	{		
		DB::dbConnect();
		
		$query = "SELECT `" . $IDName . "` FROM `" . $tableName . "` WHERE `" . $fieldName . "` = '" . mysql_real_escape_string($fieldValue) . "' LIMIT 1";
		$result = mysql_query($query);
		//echo $query . "<br />";
		if ($result) 
		{
			$row = mysql_fetch_row($result); 
			return $row[0];
		}
		
	} // dbFieldToID()
	
	static function dbIDToField ($tableName, $IDName, $IDValue, $fieldName) 
	{
		DB::dbConnect();		
		
		$query = "SELECT `" . $fieldName . "` FROM `" . $tableName . "` WHERE `" . $IDName . "` = '" . mysql_real_escape_string($IDValue) . "' LIMIT 1";
		$result = mysql_query($query);
		//echo $query . "<br />";
		if ($result) 
		{
			$row = mysql_fetch_row($result); 
			return $row[0];
		}
		
	} // dbIDToField()


	function getNextAutoIncrement($strTableName)
	{
		DB::dbConnect();

		$query = "SHOW TABLE STATUS WHERE `name` = '" . $strTableName . "'";
		$result = mysql_query($query);

		if ($result)
		{
			$row = mysql_fetch_assoc($result);

			return $row['Auto_increment'];
		}

	} // getNextAutoIncrement($strTableName)
	
	function dbGetAggregateData($type, $tableName, $fieldName, $strCondition)
	{
		// type MAX, MIN, AVG, COUNT SUM etc.
		
		$query = "SELECT " . $type . "(`" . $fieldName . "`) FROM `" . $tableName . "` " . $strCondition;
		$result = mysql_query($query);		
		#echo $query . "<br />";
		if ($result)
		{
			$row = mysql_fetch_row($result);
			
			return $row[0];
		}
	} // dbGetAggregateData()
		
	// -- USERS
	function getUserPhotoByID($userID) 
	{
		
		$query = "SELECT `user_photo` FROM `users` WHERE `user_id` = '" . mysql_real_escape_string($user_id) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{			
			$row = mysql_fetch_row($result);
			
			return $row[0];
		}
	
	} // getUserPhotoByID
	
	function getUserEmailByID($userID) 
	{
		
		$query = "SELECT `user_email` FROM `users` WHERE `user_id` = '" . mysql_real_escape_string($userID) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{			
			$row = mysql_fetch_row($result);
			
			return $row[0];
		}
	
	} // getUserEmailByID
	
	function getUserLoginNameByID($userID) 
	{
		
		$query = "SELECT `user_login_name` FROM `users` WHERE `user_id` = '" . mysql_real_escape_string($userID) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{			
			$row = mysql_fetch_row($result);
			
			return $row[0];
		}
		
	} // getUserLoginNameByID()
	
	function getUserFullNameByID($userID) 
	{
		
		$query = "SELECT `user_full_name` FROM `users` WHERE `user_id` = '" . mysql_real_escape_string($userID) . "' LIMIT 1";
		$result = mysql_query($query);
		
		if ($result) 
		{			
			$row = mysql_fetch_row($result);
			
			return $row[0];
		}
	
	} // getUserFullNameByID()
	
	
	function changePassword() 
	{		
		
		$this->conn = $this->dbConnect();								

		$query = "UPDATE `users` SET `user_password` = '" . md5(trim($_REQUEST['frm_user_new_password'])) . "' ";
		$query .= " WHERE user_id = '" . $_SESSION['user']['id'] . "' LIMIT 1";
		
		$result = mysql_query($query, $this->conn);
		
		#echo $query;
		if ($result) 
		{
			
			$strAlert = "Your password is successfully changed!";
			$strLog = "User \"" . DB::getUserFullNameByID($_SESSION['user']['id']) . " (" . stripslashes($_SESSION['user']['login_name']) . ")\" has successfully changed password.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";			
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);
			echo "\n<div align=\"center\"><a href=\"home.php\"><strong>[ Back ]</strong></a></div>\n";
			
		}
		
	} // changePassword()
	
	
	function insertUser() 
	{		
		
		$this->conn = $this->dbConnect();
		
		if ($_FILES['frm_user_photo']['name']) 
		{
			
			$strImagePath = 'uploads/user/';
			$strNewFileName = 'user_' . date('YmdHis');
			$strNewFileNameOri = 'user_' . date('YmdHis') . '_ori';
		
			switch ($_FILES['frm_user_photo']['type']) 
			{
				case 'image/pjpeg' : $strNewFileName .= '.jpg';
									 $strNewFileNameOri .= '.jpg';	
									 $strImageType = 'jpg';
					break;
				case 'image/jpeg' : $strNewFileName .= '.jpg';
									$strNewFileNameOri .= '.jpg';		
									$strImageType = 'jpg';
					break;
				case 'image/x-png' : $strNewFileName .= '.png';
									 $strNewFileNameOri .= '.png';		
									 $strImageType = 'png';
					break;
				case 'image/png' : $strNewFileName .= '.png';
								   $strNewFileNameOri .= '.png';	 
								   $strImageType = 'png';
					break;
				case 'image/gif' : $strNewFileName .= '.gif';
								   $strNewFileNameOri .= '.gif';	
								   $strImageType = 'gif';
					break;
			}
			
			@copy($_FILES['frm_user_photo']['tmp_name'], $strImagePath . $strNewFileNameOri);
			
		} 
		
		else 
		{
			$strNewFileName = '';
		}


		HTML::resizeImage($strImagePath . $strNewFileNameOri, $strImagePath . $strNewFileName, $strImageType, 150, 150);
		
		
		// subscription start date
		if ($_REQUEST['frm_user_subscription_start'])
		{
			$arrSDates = explode('-', $_REQUEST['frm_user_subscription_start']);
			$strSDate = $arrSDates[2] . '-' . $arrSDates[1] . '-' . $arrSDates[0];
		}

		// subscription end date
		if ($_REQUEST['frm_user_subscription_end'])
		{
			$arrEDates = explode('-', $_REQUEST['frm_user_subscription_end']);
			$strEDate = $arrEDates[2] . '-' . $arrEDates[1] . '-' . $arrEDates[0];
		}


		$query = "INSERT INTO `users` (`user_id`, 
										`user_login_name`, 
										`user_password`, 
										`user_group_id`, 
										`user_level`, 
										`user_full_name`, 
										`user_email`, 
										`user_photo`, 
										`user_subscription_start`, 
										`user_subscription_end`, 
										`user_description`, 
										`user_last_login_time`, 
										`user_last_login_from`, 
										`user_activate`, 
										`user_created_date`, 
										`user_created_by`, 
										`user_modified_date`, 
										`user_modified_by`
									  )
								VALUES (NULL , 
										'" . strtolower($_REQUEST['frm_user_login_name']) . "', 
										'" . md5($_REQUEST['frm_user_password']) . "',
										'" . $_REQUEST['frm_user_group_id'] . "', 
										'" . $_REQUEST['frm_user_level'] . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_user_full_name']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_user_email']) . "', 
										'" . $strNewFileName . "', 
										'" . mysql_real_escape_string($strSDate) . "', 
										'" . mysql_real_escape_string($strEDate) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_user_description']) . "', 
										'0000-00-00 00:00:00', 
										'', 
										'" . mysql_real_escape_string($_REQUEST['frm_user_activate']) . "', 
										NOW( ) , 
										'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', 
										NOW( ) , 
										'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'
										);";
						
		
			$result = mysql_query($query, $this->conn);
			$intUserID = mysql_insert_id();
		
		#echo $query;
		if ($result) 
		{

			if ($_REQUEST['frm_user_level'] == 'user')
			{
				// insert default privileges for standard users
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
																  
															VALUES 
															(NULL, '" . $intUserID . "', '" . $_REQUEST['frm_user_group_id'] . "', '" . DB::dbFieldToID('modules', 'module_name', 'file_index', 'module_id') . "', 'no', 'no', 'no', 'no', 'no', 'yes', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'), 
															(NULL, '" . $intUserID . "', '" . $_REQUEST['frm_user_group_id'] . "', '" . DB::dbFieldToID('modules', 'module_name', 'file_authorize', 'module_id') . "', 'no', 'no', 'no', 'no', 'no', 'yes', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'), 
															(NULL, '" . $intUserID . "', '" . $_REQUEST['frm_user_group_id'] . "', '" . DB::dbFieldToID('modules', 'module_name', 'file_home', 'module_id') . "', 'no', 'no', 'no', 'no', 'no', 'yes', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'), 
															(NULL, '" . $intUserID . "', '" . $_REQUEST['frm_user_group_id'] . "', '" . DB::dbFieldToID('modules', 'module_name', 'file_logout', 'module_id') . "', 'no', 'no', 'no', 'no', 'no', 'yes', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'), 
															(NULL, '" . $intUserID . "', '" . $_REQUEST['frm_user_group_id'] . "', '" . DB::dbFieldToID('modules', 'module_name', 'user_password_change', 'module_id') . "', 'no', 'no', 'no', 'no', 'no', 'yes', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'),
															(NULL, '" . $intUserID . "', '" . $_REQUEST['frm_user_group_id'] . "', '" . DB::dbFieldToID('modules', 'module_name', 'user_profile', 'module_id') . "', 'no', 'no', 'yes', 'no', 'yes', 'no', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', NOW(), '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "')
																  ";
				$resultPrivInsert = mysql_query($queryPrivInsert);
				#echo $queryPrivInsert . "<br /><br />";
			}

			// insert user group privileges
			if ($_REQUEST['frm_user_group_id']) 
			{
				$queryUserGroupPriv = "SELECT * FROM `privileges` WHERE `user_id` = '0' AND `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ORDER BY `module_id`";
				$resultUserGroupPriv = mysql_query($queryUserGroupPriv);
				
				if ($resultUserGroupPriv)
				{
					while ($rowUserGroupPriv = mysql_fetch_assoc($resultUserGroupPriv)) 
					{
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
																  
																	VALUES 
																	(NULL, 
																	'" . $intUserID . "', 
																	'" . $_REQUEST['frm_user_group_id'] . "', 
																	'" . $rowUserGroupPriv['module_id'] . "', 
																	'" . $rowUserGroupPriv['priv_list'] . "', 
																	'" . $rowUserGroupPriv['priv_add'] . "', 
																	'" . $rowUserGroupPriv['priv_edit'] . "', 
																	'" . $rowUserGroupPriv['priv_delete'] . "', 
																	'" . $rowUserGroupPriv['priv_view'] . "', 
																	'" . $rowUserGroupPriv['priv_execute'] . "', 																	
																	NOW(), 
																	'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', 
																	NOW(), 
																	'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "')";
																	
						$resultPrivInsert = mysql_query($queryPrivInsert);
						#echo $queryPrivInsert . "<br><br>";
					}
				}
			}
			
			
			$strAlert = "User <strong>\"" . stripslashes($_REQUEST['frm_user_login_name']) . "\"</strong> is successfully added!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('users', 'view') > 0) { $strAlert .= "<a href=\"user_view.php?user_id=". $intUserID . "\" title=\"View User\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('users', 'edit') > 0) { $strAlert .= "<a href=\"user_edit.php?user_id=" . $intUserID . "\" title=\"Edit User\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('users', 'delete') > 0) { $strAlert .= "<a href=\"user_delete.php?user_id=" . $intUserID . "&action=delete\" title=\"Delete User\" onclick=\"return confirmDeleteUser(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			if (ADMIN::getModulePrivilege('privileges', 'list') > 0) { $strAlert .= "<a href=\"privileges_list.php?user_id=" . $intUserID . "\" title=\"User Privileges\"><img src=\"img/priv_icon.png\" /> Privileges</a>&nbsp;&nbsp;&nbsp;\n<br /><br />"; }
			if (ADMIN::getModulePrivilege('users', 'add') > 0) { $strAlert .= "<a href=\"user_add.php\" title=\"Add User\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('users', 'list') > 0) { $strAlert .= "<a href=\"user_list.php\" title=\"User List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strLog = "User \"" . stripslashes($_REQUEST['frm_user_login_name']) . "\" is successfully added!";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);			
			
		} 
		
		else 
		{			
			HTML::showAlert("Error on the database!", FALSE);			
		}
		
	} // insertUser()
	
	
	function updateUser() 
	{		
		
		$this->conn = $this->dbConnect();
		
		if ($_FILES['frm_user_photo']['name']) 
		{
			
			$strImagePath = 'uploads/user/';
			$strNewFileName = 'user_' . date('YmdHis');
		
			switch ($_FILES['frm_user_photo']['type']) 
			{
				case 'image/pjpeg' : $strNewFileName .= '.jpg';
					break;
				case 'image/jpeg' : $strNewFileName .= '.jpg';
					break;
				case 'image/x-png' : $strNewFileName .= '.png';
					break;
				case 'image/png' : $strNewFileName .= '.png';
					break;
				case 'image/gif' : $strNewFileName .= '.gif';
					break;
			}
			
			@copy($_FILES['frm_user_photo']['tmp_name'], $strImagePath . $strNewFileName);
			
		} 
		
		else 
		{
			$strNewFileName = $_REQUEST['frm_user_photo'];
		}				

		$query = "UPDATE `users` SET `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "', 
									 `user_level` = '" . $_REQUEST['frm_user_level'] . "', 
									 `user_full_name` = '" . mysql_real_escape_string($_REQUEST['frm_user_full_name']) . "', 
									 `user_email` = '" . mysql_real_escape_string($_REQUEST['frm_user_email']) . "', 
									 `user_photo` = '" . $strNewFileName . "', 
									 `user_subscription_start` = '" . $_REQUEST['frm_user_subscription_start_year'] . "-" . $_REQUEST['frm_user_subscription_start_month'] . "-" . $_REQUEST['frm_user_subscription_start_day'] . "', 
									 `user_subscription_end` = '" . $_REQUEST['frm_user_subscription_end_year'] . "-" . $_REQUEST['frm_user_subscription_end_month'] . "-" . $_REQUEST['frm_user_subscription_end_day'] . "', 									 									 
									 `user_description` = '" . mysql_real_escape_string($_REQUEST['frm_user_description']) . "',  
									 `user_modified_date` = NOW( ) , 
									 `user_modified_by` = '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'";
		
		 
		$query .= " WHERE `user_id` = '" . $_REQUEST['frm_user_id'] . "' LIMIT 1";
		
		$result = mysql_query($query, $this->conn);
		$intUserID = $_REQUEST['frm_user_id'];
		
		#echo $query;
		if ($result) 
		{
			// drop the previous privileges
			if ($_REQUEST['frm_user_group_id']) 
			{
				$queryUserGroupPriv = "DELETE FROM `privileges` WHERE `user_id` = '" . $intUserID . "'";
				$resultUserGroupPriv = mysql_query($queryUserGroupPriv);
				#echo $queryUserGroupPriv . "<br /><br />";
			}
			
			// update user group privileges
			if ($_REQUEST['frm_user_group_id']) 
			{
				$queryUserGroupPriv = "SELECT * FROM `privileges` WHERE `user_id` = '0' AND `user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' ORDER BY `module_id`";
				$resultUserGroupPriv = mysql_query($queryUserGroupPriv);
				#echo $queryUserGroupPriv . "<br /><br />";
				
				if ($resultUserGroupPriv)
				{
					while ($rowUserGroupPriv = mysql_fetch_assoc($resultUserGroupPriv)) 
					{
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
																  
																	VALUES 
																	(NULL, 
																	'" . $intUserID . "', 
																	'" . $_REQUEST['frm_user_group_id'] . "', 
																	'" . $rowUserGroupPriv['module_id'] . "', 
																	'" . $rowUserGroupPriv['priv_list'] . "', 
																	'" . $rowUserGroupPriv['priv_add'] . "', 
																	'" . $rowUserGroupPriv['priv_edit'] . "', 
																	'" . $rowUserGroupPriv['priv_delete'] . "', 
																	'" . $rowUserGroupPriv['priv_view'] . "', 
																	'" . $rowUserGroupPriv['priv_execute'] . "', 																	
																	NOW(), 
																	'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', 
																	NOW(), 
																	'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "')";
																	
						$resultPrivInsert = mysql_query($queryPrivInsert);
						#echo $queryPrivInsert . "<br><br>";
					}
				}
			}
			
			$strAlert = "User <strong>\"" . stripslashes($_REQUEST['frm_user_login_name']) . "\"</strong> is successfully updated!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('users', 'view') > 0) { $strAlert .= "<a href=\"user_view.php?user_id=". $intUserID . "\" title=\"View User\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('users', 'edit') > 0) { $strAlert .= "<a href=\"user_edit.php?user_id=" . $intUserID . "\" title=\"Edit User\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('users', 'delete') > 0) { $strAlert .= "<a href=\"user_delete.php?user_id=" . $intUserID . "&action=delete\" title=\"Delete User\" onclick=\"return confirmDeleteUser(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			if (ADMIN::getModulePrivilege('privileges', 'list') > 0) { $strAlert .= "<a href=\"privileges_list.php?user_id=" . $intUserID . "\" title=\"User Privileges\"><img src=\"img/priv_icon.png\" /> Privileges</a>&nbsp;&nbsp;&nbsp;\n<br /><br />"; }
			if (ADMIN::getModulePrivilege('users', 'add') > 0) { $strAlert .= "<a href=\"user_add.php\" title=\"Add User\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('users', 'list') > 0) { $strAlert .= "<a href=\"user_list.php\" title=\"User List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strLog = "User \"" . stripslashes($_REQUEST['frm_user_login_name']) . "\" is successfully updated.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";			
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);

		}
		
	} // updateUser()

	
	function deleteUser() 
	{
		
		$this->conn = $this->dbConnect();
		
		$query = "SELECT * FROM `users` WHERE `user_id` = '" . $_REQUEST['user_id'] . "' LIMIT 1";
		$result = mysql_query($query, $this->conn);
		$row = mysql_fetch_assoc($result);
		
		if ($row['user_photo'] && file_exists('uploads/user/' . $row['user_photo'])) 
		{
			@unlink('uploads/user/' . $row['user_photo']);
		}
		
		// delete user
		$queryDel = "DELETE FROM `users` WHERE `user_id` = '" . $_REQUEST['user_id'] . "' LIMIT 1";
		$resultDel = mysql_query($queryDel, $this->conn);

		// delete privileges
		$queryDelPriv = "DELETE FROM `privileges` WHERE `user_id` = '" . $_REQUEST['user_id'] . "'";
		$resultDelPriv = mysql_query($queryDelPriv, $this->conn);

			
		if ($resultDel) 
		{
			$strAlert = "User <strong>\"" . stripslashes($row['user_full_name']) . "\"</strong>  with username <strong>\"" . stripslashes($row['user_login_name']) . "\"</strong> is successfully deleted!";
			$strAlert .= "<br /><br />\n";			
			if (ADMIN::getModulePrivilege('users', 'add') > 0) { $strAlert .= "<a href=\"user_add.php\" title=\"Add User\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('users', 'list') > 0) { $strAlert .= "<a href=\"user_list.php\" title=\"User List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strLog = "User \"" . stripslashes($row['user_full_name']) . "\" dengan username \"" . stripslashes($row['user_login_name']) . "\" is successfully deleted.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";			
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);
			
		}
	
	} // deleteUser()
	
	
	function userPasswordReset() 
	{		
		
		$this->conn = $this->dbConnect();
				
		$query = "UPDATE `users` SET `user_password` = '" . md5($_REQUEST['frm_user_password']) . "', 									 
									 `user_modified_date` = NOW( ) , 
									 `user_modified_by` = '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'";
				 
		$query .= " WHERE `user_id` = '" . $_REQUEST['frm_user_id'] . "' LIMIT 1";
		
		$result = mysql_query($query, $this->conn);
		$intUserID = $_REQUEST['frm_user_id'];
		$strUserName = DB::dbIDToField('users', 'user_id', $intUserID, 'user_login_name');
		
		#echo $query;
		if ($result) 
		{
			
			$strAlert = "Password for username <strong>\"" . stripslashes($strUserName) . "\"</strong> is successfully reset!";
			$strAlert .= "<br /><br />\n";
			$strAlert .= "<a href=\"user_view.php?user_id=". $intUserID . "\" title=\"View User\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n";
			$strAlert .= "<a href=\"user_edit.php?user_id=" . $intUserID . "\" title=\"Edit User\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n";
			$strAlert .= "<a href=\"user_delete.php?user_id=" . $intUserID . "&action=delete\" title=\"Delete User\" onclick=\"return confirmDeleteUser(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n";
			$strAlert .= "<a href=\"user_add.php\" title=\"Add User\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n";
			$strAlert .= "<a href=\"user_list.php\" title=\"User List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n";
			
			$strLog = "Password for username \"" . stripslashes($_REQUEST['frm_user_login_name']) . "\" is successfully reset.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";			
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);
						
		}
		
	} // userPasswordReset()
	
	
	function userPasswordChange() 
	{		
		
		$this->conn = $this->dbConnect();
				
		$query = "UPDATE `users` SET `user_password` = '" . md5($_REQUEST['frm_user_password']) . "', 
									 `user_modified_date` = NOW( ) , 
									 `user_modified_by` = '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'";
				 
		$query .= " WHERE `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
		
		$result = mysql_query($query, $this->conn);
		$intUserID = $_SESSION['user']['id'];
		$strUserName = DB::dbIDToField('users', 'user_id', $intUserID, 'user_login_name');
		
		#echo $query;
		if ($result) 
		{
			
			$strAlert = "Password for username <strong>\"" . stripslashes($strUserName) . "\"</strong> is successfully changed!";
			$strAlert .= "<br /><br />\n";
			$strAlert .= "<a href=\"user_profile_view.php?user_id=". $intUserID . "\" title=\"View User\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n";
			$strAlert .= "<a href=\"user_profile_edit.php?user_id=" . $intUserID . "\" title=\"Edit User\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n";
			
			
			$strLog = "Password for username \"" . stripslashes($_SESSION['user']['login_name']) . "\" is successfully changed.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";			
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);
						
		}
		
	} // userPasswordChange()
	
	
	function getUserData($strUserType=NULL) 
	{
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `users` ";
		
		if ($strUserType)
		{
			$query .= "WHERE `user_level` = '" . $strUserType . "'";
		}
		
		$query .= "ORDER BY `user_login_name` ASC";
		$result = mysql_query($query);
		
		if ($result) 
		{
			
			$data = array();
			
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['user_id']] = stripslashes($row['user_login_name']);	
				
			}
			
			return $data;
		}
	
	} // getUserData()

		
	function deleteLog() 
	{
		
		$this->conn = $this->dbConnect();
						
		// delete log
		$queryDel = "DELETE FROM `logs` WHERE `log_id` = '" . $_REQUEST['log_id'] . "' LIMIT 1";
		$resultDel = mysql_query($queryDel, $this->conn);
			
		if ($resultDel) 
		{
			$strAlert = "Log ID <strong>\"" . stripslashes($_REQUEST['log_id']) . "\"</strong> is successfully deleted!";
			$strAlert .= "<br /><br />\n";						
			$strAlert .= "<a href=\"log_list.php\" title=\"Logs List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n";
			
			$strLog = "Log ID \"" . stripslashes($_REQUEST['log_id']) . "\" is successfully deleted.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";			
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);
						
		}
		
	} // deleteLog()

	
	
	
	// Profile
	function updateUserProfile() 
	{		
		
		$this->conn = $this->dbConnect();
		
		if ($_FILES['frm_user_photo']['name']) 
		{
			
			$strImagePath = 'uploads/user/';
			$strNewFileName = 'user_' . date('YmdHis');
		
			switch ($_FILES['frm_user_photo']['type']) 
			{
				case 'image/pjpeg' : $strNewFileName .= '.jpg';
					break;
				case 'image/jpeg' : $strNewFileName .= '.jpg';
					break;
				case 'image/x-png' : $strNewFileName .= '.png';
					break;
				case 'image/png' : $strNewFileName .= '.png';
					break;
				case 'image/gif' : $strNewFileName .= '.gif';
					break;
			}
			
			@copy ($_FILES['frm_user_photo']['tmp_name'], $strImagePath . $strNewFileName);
			
		} 
		else 
		{
			$strNewFileName = $_REQUEST['frm_user_photo'];
		}				

		$query = "UPDATE `users` SET `user_full_name` = '" . mysql_real_escape_string($_REQUEST['frm_user_full_name']) . "', 
									 `user_email` = '" . mysql_real_escape_string($_REQUEST['frm_user_email']) . "', 
									 `user_photo` = '" . $strNewFileName . "', 									 
									 `user_description` = '" . mysql_real_escape_string($_REQUEST['frm_user_description']) . "',  
									 `user_modified_date` = NOW( ) , 
									 `user_modified_by` = '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'";
		
		 
		$query .= " WHERE `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
		
		$result = mysql_query($query, $this->conn);
		$intUserID = $_SESSION['user']['id'];
		
		#echo $query;
		if ($result) 
		{
			
			$strAlert = "Profile for user <strong>\"" . stripslashes($_REQUEST['frm_user_login_name']) . "\"</strong> is successfully updated!";
			$strAlert .= "<br /><br />\n";
			$strAlert .= "<a class=\"btn\" href=\"user_profile_view.php?user_id=". $intUserID . "\" title=\"View Profile\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n";
			$strAlert .= "<a class=\"btn\" href=\"user_profile_edit.php?user_id=" . $intUserID . "\" title=\"Edit Profile\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n";
			
			$strLog = "Profile for user \"" . stripslashes($_REQUEST['frm_user_login_name']) . "\" is successfully updated.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";			
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);
			
			
		}
		
	} // updateUserProfile()
	
	
	function getModulesData() 
	{
		
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `modules` ORDER BY `module_name` ASC";
		$result = mysql_query($query);
		
		if ($result) 
		{
			
			$data = array();
			
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['module_id']] = array('module_name'=>stripslashes($row['module_name']), 'module_display'=>stripslashes($row['module_display']));	
				
			}
			
			return $data;
		}
	
	} // getModulesData()
	
	function insertModule()
	{
		
		$this->conn = $this->dbConnect();
				
		$query = "INSERT INTO `modules` (`module_id`, 
										 `module_name`, 
										 `module_display`, 
										 `module_parent`, 
										 `module_parent_id`, 
										 `module_child`, 
										 `module_description`, 
										 `module_file_name_list`, 
										 `module_file_name_add`, 
										 `module_file_name_edit`, 
										 `module_file_name_view`, 
										 `module_file_name_delete`, 
										 `module_file_name_execute`, 
										 `module_use_table`, 
										 `module_datetime_table_field_name`, 
										 `module_activate`, 
										 `module_create_date`, 
										 `module_created_by`, 
										 `module_modify_date`, 
										 `module_modified_by`)
										 
								VALUES (NULL , 
										'" . mysql_real_escape_string($_REQUEST['frm_module_name']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_display']) . "', ";
		
						// module parent id
						if ($_REQUEST['frm_module_parent_id'])
						{
							$query .=  "'no', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_parent_id']) . "', 
										'no', ";
							
							// now update the parent
							$queryParent = "UPDATE `modules` SET `module_child` = 'yes' WHERE `module_id` = '" . $_REQUEST['frm_module_parent_id'] . "' LIMIT 1";
							$resultParent = mysql_query($queryParent);
						
						}
						
						else 
						{
		
							$query .=  "'yes', 
										NULL, 
										'no', ";
						}
										
							$query .=
									   "'" . mysql_real_escape_string($_REQUEST['frm_module_description']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_file_name_list']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_file_name_add']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_file_name_edit']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_file_name_view']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_file_name_delete']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_file_name_execute']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_use_table']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_module_datetime_table_field_name']) . "', 										
										'" . mysql_real_escape_string($_REQUEST['frm_module_activate']) . "', 
										NOW( ) , 
										'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', 
										NOW( ) , 
										'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'
										);";
						
		
			$result = mysql_query($query, $this->conn);
			$intModuleID = mysql_insert_id();
		
		#echo $query;
		if ($result) 
		{

			$strAlert = "Module <strong>\"" . stripslashes($_REQUEST['frm_module_display']) . " (" . stripslashes($_REQUEST['frm_module_name']) . ")\"</strong> is successfully added!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('module', 'view') > 0) { $strAlert .= "<a href=\"module_view.php?module_id=". $intModuleID . "\" title=\"View Modul\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('module', 'edit') > 0) { $strAlert .= "<a href=\"module_edit.php?module_id=" . $intModuleID . "\" title=\"Edit Modul\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('module', 'delete') > 0) { $strAlert .= "<a href=\"module_delete.php?module_id=" . $intModuleID . "&action=delete\" title=\"Delete Modul\" onclick=\"return confirmDeleteModule(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strAlert .= "<br /><br />\n";
			
			if (ADMIN::getModulePrivilege('module', 'add') > 0) { $strAlert .= "<a href=\"module_add.php\" title=\"Add Modul\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('module', 'list') > 0) { $strAlert .= "<a href=\"module_list.php\" title=\"List Modul\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strLog = "Module \"" . stripslashes($_REQUEST['frm_module_display']) . " - " . stripslashes($_REQUEST['frm_module_name']) . "\" is successfully added.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);			
			
		} 
		
		else 
		{			
			HTML::showAlert("Query error on the database: Module!", FALSE);			
		}
		
		
	} // insertModule()
	
	
	function updateModule()
	{
		
		$this->conn = $this->dbConnect();
				
		$query = "UPDATE `modules` SET `module_name` = '" . mysql_real_escape_string($_REQUEST['frm_module_name']) . "',
									   `module_display` = '" . mysql_real_escape_string($_REQUEST['frm_module_display']) . "', 
									   `module_description` = '" . mysql_real_escape_string($_REQUEST['frm_module_description']) . "', 
									   `module_file_name_list` = '" . mysql_real_escape_string($_REQUEST['frm_module_file_name_list']) . "', 
									   `module_file_name_add` = '" . mysql_real_escape_string($_REQUEST['frm_module_file_name_add']) . "', 
									   `module_file_name_edit` = '" . mysql_real_escape_string($_REQUEST['frm_module_file_name_edit']) . "', 
									   `module_file_name_view` = '" . mysql_real_escape_string($_REQUEST['frm_module_file_name_view']) . "', 
									   `module_file_name_delete` = '" . mysql_real_escape_string($_REQUEST['frm_module_file_name_delete']) . "', 
									   `module_file_name_execute` = '" . mysql_real_escape_string($_REQUEST['frm_module_file_name_execute']) . "', 
									   `module_use_table` = '" . mysql_real_escape_string($_REQUEST['frm_module_use_table']) . "', 
									   `module_datetime_table_field_name` = '" . mysql_real_escape_string($_REQUEST['frm_module_datetime_table_field_name']) . "', 
									   `module_activate` = '" . mysql_real_escape_string($_REQUEST['frm_module_activate']) . "', 
									   `module_modify_date` = NOW( ), 
									   `module_modified_by` = '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', 		 
				";
		
		// module parent id
		if ($_REQUEST['frm_module_parent_id'])
		{
			$query .=  "`module_parent` = 'no', 
						`module_parent_id` = '" . mysql_real_escape_string($_REQUEST['frm_module_parent_id']) . "', 
						`module_child` = 'no' ";
							
			// now update the parent
			$queryParent = "UPDATE `modules` SET `module_child` = 'yes' WHERE `module_id` = '" . $_REQUEST['frm_module_parent_id'] . "' LIMIT 1";
			$resultParent = mysql_query($queryParent);
						
		}
					
		else 
		{
			$query .=  "`module_parent` = 'yes', 
						`module_parent_id` = NULL, 
						`module_child` = 'no' ";
		}
										
		$query .= "WHERE `module_id` = '" . $_REQUEST['frm_module_id'] . "' LIMIT 1";
						
		
		$result = mysql_query($query, $this->conn);
		$intModuleID = $_REQUEST['frm_module_id'];
		
		#echo $query;
		if ($result) 
		{

			$strAlert = "Module <strong>\"" . stripslashes($_REQUEST['frm_module_display']) . " (" . stripslashes($_REQUEST['frm_module_name']) . ")\"</strong> is successfully updated!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('module', 'view') > 0) { $strAlert .= "<a href=\"module_view.php?module_id=". $intModuleID . "\" title=\"View Modul\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('module', 'edit') > 0) { $strAlert .= "<a href=\"module_edit.php?module_id=" . $intModuleID . "\" title=\"Edit Modul\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('module', 'delete') > 0) { $strAlert .= "<a href=\"module_delete.php?module_id=" . $intModuleID . "&action=delete\" title=\"Delete Modul\" onclick=\"return confirmDeleteModule(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strAlert .= "<br /><br />\n";
			
			if (ADMIN::getModulePrivilege('module', 'add') > 0) { $strAlert .= "<a href=\"module_add.php\" title=\"Add Modul\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('module', 'list') > 0) { $strAlert .= "<a href=\"module_list.php\" title=\"List Modul\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strLog = "Module \"" . stripslashes($_REQUEST['frm_module_display']) . " - " . stripslashes($_REQUEST['frm_module_name']) . "\" is successfully updated.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);			
			
		} 
		
		else 
		{			
			HTML::showAlert("Query error on the database: Module!", FALSE);			
		}
		
		
	} // updateModule()
	
	
	function deleteModule() 
	{
		
		$this->conn = $this->dbConnect();
						
		// get module data first
		$query = "SELECT * FROM `modules` WHERE `module_id` = '" . $_REQUEST['module_id'] . "' LIMIT 1";
		$result =  mysql_query($query);
		
		if ($result)
		{
			$row = mysql_fetch_assoc($result);
		}
		
		// delete log
		$queryDel = "DELETE FROM `modules` WHERE `module_id` = '" . $_REQUEST['module_id'] . "' LIMIT 1";
		$resultDel = mysql_query($queryDel, $this->conn);
			
		if ($resultDel) 
		{
			$strAlert = "Module <strong>\"" . stripslashes($row['module_name']) . " (" . stripslashes($row['module_display']) . ")\"</strong> is successfully deleted!";
			$strAlert .= "<br /><br />\n";						
			$strAlert .= "<a href=\"module_list.php\" title=\"List Modul\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n";
			
			$strLog = "Module \"" . stripslashes($row['module_name']) . " (" . stripslashes($row['module_display']) . ")\" is successfully deleted.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";			
			
			$resultModule = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);
						
		}
		
	} // deleteModule()
	
	
	function getNumPagesInPDF(array $arguments = array())
	{
			@list($PDFPath) = $arguments;
			$stream = @fopen($PDFPath, "r");
			$PDFContent = @fread ($stream, filesize($PDFPath));

			if(!$stream || !$PDFContent)
			{
				return false;
			}
		   
			$firstValue = 0;
			$secondValue = 0;
			if (preg_match("/\/N\s+([0-9]+)/", $PDFContent, $matches)) 
			{
				$firstValue = $matches[1];
			}
		 
			if (preg_match_all("/\/Count\s+([0-9]+)/s", $PDFContent, $matches))
			{
				$secondValue = max($matches[1]);
			}
			
			return (($secondValue != 0) ? $secondValue : max($firstValue, $secondValue));
		}
	
	
	
	
	function getUserGroupData()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `user_groups` ORDER BY `user_group_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['user_group_id']] = array('user_group_name'=>$row['user_group_name']);
			}
			
			return $data;
		}
		
	} // getUserGroupData()
	
	
	function insertUserGroup()
	{
		
		$this->conn = $this->dbConnect();
				
		$query = "INSERT INTO `user_groups` (`user_group_id`, 
											 `user_group_name`, 											  
											 `user_group_description`, 
											 `user_group_activate`, 
										     `user_group_created_date`, 
											 `user_group_created_by`, 
											 `user_group_modified_date`, 
											 `user_group_modified_by`
									  		 )
								VALUES (NULL , 
										'" . mysql_real_escape_string($_REQUEST['frm_user_group_name']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_user_group_description']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_user_group_activate']) . "', 
										NOW( ) , 
										'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', 
										NOW( ) , 
										'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'
										);";
						
		
			$result = mysql_query($query, $this->conn);
			$intUserGroupID = mysql_insert_id();
		
		#echo $query;
		if ($result) 
		{
			
			// insert default usergroup default privileges
			DB::setDefaultUserGroupPrivileges($intUserGroupID);

			$strAlert = "User Group <strong>\"" . stripslashes($_REQUEST['frm_user_group_name']) . "\"</strong> is successfully added!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('user_groups', 'view') > 0) { $strAlert .= "<a href=\"user_group_view.php?user_group_id=". $intUserGroupID . "\" title=\"View User Group\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('user_groups', 'edit') > 0) { $strAlert .= "<a href=\"user_group_edit.php?user_group_id=" . $intUserGroupID . "\" title=\"Edit User Group\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('user_groups', 'delete') > 0) { $strAlert .= "<a href=\"user_group_delete.php?user_group_id=" . $intUserGroupID . "&action=delete\" title=\"Delete User Group\" onclick=\"return confirmDeleteUserGroup(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strAlert .= "<br /><br />\n";
			
			if (ADMIN::getModulePrivilege('user_groups', 'add') > 0) { $strAlert .= "<a href=\"user_group_add.php\" title=\"Add User Group\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('user_groups', 'list') > 0) { $strAlert .= "<a href=\"user_group_list.php\" title=\"User Group List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strLog = "User Group <strong>\"" . stripslashes($_REQUEST['frm_user_group_name']) . "\"</strong> is successfully added.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);			
			
		} 
		
		else 
		{			
			HTML::showAlert("Query error on the database: User Group!", FALSE);			
		}
		
		
	} // insertUserGroup()
	
	function updateUserGroup()
	{
		
		$this->conn = $this->dbConnect();
				
		$query = "UPDATE `user_groups` SET `user_group_name` = '" . mysql_real_escape_string($_REQUEST['frm_user_group_name']) . "', 											
										   `user_group_description` = '" . mysql_real_escape_string($_REQUEST['frm_user_group_description']) . "', 
										   `user_group_activate` = '" . mysql_real_escape_string($_REQUEST['frm_user_group_activate']) . "', 
										   `user_group_modified_date` = NOW( ), 
										   `user_group_modified_by` = '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'
										WHERE
											`user_group_id` = '" . $_REQUEST['frm_user_group_id'] . "' LIMIT 1";
								
			$result = mysql_query($query, $this->conn);
			$intUserGroupID = $_REQUEST['frm_user_group_id'];
		
		#echo $query;
		if ($result) 
		{			

			$strAlert = "User Group <strong>\"" . stripslashes($_REQUEST['frm_user_group_name']) . "\"</strong> is successfully updated!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('user_groups', 'view') > 0) { $strAlert .= "<a href=\"user_group_view.php?user_group_id=". $intUserGroupID . "\" title=\"View User Group\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('user_groups', 'edit') > 0) { $strAlert .= "<a href=\"user_group_edit.php?user_group_id=" . $intUserGroupID . "\" title=\"Edit User Group\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('user_groups', 'delete') > 0) { $strAlert .= "<a href=\"user_group_delete.php?user_group_id=" . $intUserGroupID . "&action=delete\" title=\"Delete User Group\" onclick=\"return confirmDeleteUserGroup(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strAlert .= "<br /><br />\n";
			
			if (ADMIN::getModulePrivilege('user_groups', 'add') > 0) { $strAlert .= "<a href=\"user_group_add.php\" title=\"Add User Group\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('user_groups', 'list') > 0) { $strAlert .= "<a href=\"user_group_list.php\" title=\"User Group List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strLog = "User Group \"" . stripslashes($_REQUEST['frm_user_group_name']) . "\" is successfully updated!";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);			
			
		} 
		else 
		
		{		
			HTML::showAlert("Query error on the database: UserGroup!", FALSE);			
		}
		
		
	} // updateUserGroup()
	
	
	function deleteUserGroup() 
	{
		
		$this->conn = $this->dbConnect();
		
		$query = "SELECT * FROM `user_groups` WHERE `user_group_id` = '" . $_REQUEST['user_group_id'] . "' LIMIT 1";
		$result = mysql_query($query, $this->conn);
		$row = mysql_fetch_assoc($result);
		
		// check if any user in the user group
		$queryCheck = "SELECT COUNT(*) FROM `users` WHERE `user_group_id` = '" . $_REQUEST['user_group_id'] . "'";
		$resultCheck = mysql_query($queryCheck);
		
		$rowCheck = mysql_fetch_row($resultCheck);
		
		if ($rowCheck[0] == 0)
		{
			// delete user group
			$queryDel = "DELETE FROM `user_groups` WHERE `user_group_id` = '" . $_REQUEST['user_group_id'] . "' LIMIT 1";
			$resultDel = mysql_query($queryDel, $this->conn);
				
			if ($resultDel) 
			{
				
				// delete also usergroup privileges
				$queryPrivDel = "DELETE FROM `privileges` WHERE `user_group_id` = '" . $_REQUEST['user_group_id'] . "' AND `user_id` = '0'";
				$resultPrivDel = mysql_query($queryPrivDel);
				
				$strAlert = "User Group <strong>\"" . stripslashes($row['user_group_name']) . "\"</strong> is successfully deleted!";
				$strAlert .= "<br /><br />\n";			
				
				if (ADMIN::getModulePrivilege('user_groups', 'add') > 0) { $strAlert .= "<a href=\"user_group_add.php\" title=\"Add User Group\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
				if (ADMIN::getModulePrivilege('user_groups', 'list') > 0) { $strAlert .= "<a href=\"user_group_list.php\" title=\"User Group List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
				
				$strLog = "User Group \"" . stripslashes($row['user_group_name']) . "\" is successfully deleted.";
				
				$queryLog = "INSERT INTO `logs` (`log_id`, 
											     `log_user`, 
											     `log_action`, 
											     `log_time`, 
											     `log_from`, 
											     `log_logout`)
	
							VALUES (NULL, 
									'" . $_SESSION['user']['login_name'] . "',
								    '" . mysql_real_escape_string($strLog) . "',
									NOW( ),
									'" . $_SESSION['user']['ip_address'] . "', 
									NULL)";			
				
				$resultLog = mysql_query($queryLog, $this->conn);
				
				HTML::showAlert($strAlert, FALSE);
				
				
			}
		
		}
		
		else 
		{
			$strAlert = "User Group <strong>\"" . stripslashes($row['user_group_name']) . "\"</strong> tidak kosong!";
			$strAlert .= "<br /><br />\n";						
			if (ADMIN::getModulePrivilege('user_groups', 'list') > 0) { $strAlert .= "<a href=\"user_group_list.php\" title=\"User Group List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			HTML::showAlert($strAlert, FALSE);
		}
		
		
		
	}  //deleteUserGroup()
	
	function updateSetting()
	{
		$this->conn = $this->dbConnect();
		
		$query = "UPDATE `settings` SET `setting_value` = '" . $_REQUEST['frm_setting_value'] . "' WHERE `setting_name` = '" . $_REQUEST['frm_setting_name'] . "' LIMIT 1";
		$result = mysql_query($query, $this->conn);
		$row = mysql_fetch_assoc($result);
		
		#echo $query;
		if ($result) 
		{

			$strAlert = "Setting <strong>\"" . stripslashes($_REQUEST['frm_setting_text']) . "\"</strong> is successfully updated!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('settings', 'list') > 0) { $strAlert .= "<a href=\"setting_list.php?sn=" . $_REQUEST['frm_setting_name'] . "\" title=\"List Setting\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('settings', 'edit') > 0) { $strAlert .= "<a href=\"setting_edit.php?sn=" . $_REQUEST['frm_setting_name'] . "\" title=\"Edit Setting\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			
			$strAlert .= "<br /><br />\n";
			
			
			$strLog = "Setting \"" . stripslashes($_REQUEST['frm_setting_text']) . "\" is successfully updated.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);			
			
		} 
		else 
		
		{		
			HTML::showAlert("Query error on the database: Settings!", FALSE);			
		}
		
	}
	
	function setDefaultUserGroupPrivileges($groupID)
	{
		// set privileges array
		$arrDefaultPriv = array('file_authorize'=>array('priv_list'=>'no', 'priv_add'=>'no', 'priv_edit'=>'no', 'priv_delete'=>'no', 'priv_view'=>'no', 'priv_execute'=>'yes'), 
								'file_home'=>array('priv_list'=>'no', 'priv_add'=>'no', 'priv_edit'=>'no', 'priv_delete'=>'no', 'priv_view'=>'no', 'priv_execute'=>'yes'), 
								'file_index'=>array('priv_list'=>'no', 'priv_add'=>'no', 'priv_edit'=>'no', 'priv_delete'=>'no', 'priv_view'=>'no', 'priv_execute'=>'yes'), 					 
								'file_logout'=>array('priv_list'=>'no', 'priv_add'=>'no', 'priv_edit'=>'no', 'priv_delete'=>'no', 'priv_view'=>'no', 'priv_execute'=>'yes'), 
								'user_password_change'=>array('priv_list'=>'no', 'priv_add'=>'no', 'priv_edit'=>'no', 'priv_delete'=>'no', 'priv_view'=>'no', 'priv_execute'=>'yes'), 
								'user_profile'=>array('priv_list'=>'no', 'priv_add'=>'no', 'priv_edit'=>'yes', 'priv_delete'=>'no', 'priv_view'=>'yes', 'priv_execute'=>'no'), 
								'credits'=>array('priv_list'=>'no', 'priv_add'=>'no', 'priv_edit'=>'no', 'priv_delete'=>'no', 'priv_view'=>'yes', 'priv_execute'=>'no')
							   );
		
		
		$this->conn = $this->dbConnect();				
		
		foreach ($arrDefaultPriv as $module=>$priv)
		{
			$moduleID = DB::dbFieldToID('modules', 'module_name', $module, 'module_id');
			
			$query = "INSERT INTO `privileges` (`priv_id`, `user_id`, `user_group_id`, `module_id`, `priv_list`, `priv_add`, `priv_edit`, `priv_delete`, `priv_view`, `priv_execute`, `priv_create_date`, `priv_created_by`, `priv_modify_date`, `priv_modified_by`) 
					  VALUES (NULL, '0', '" . $groupID . "', '" . $moduleID . "', '" . $priv['priv_list'] . "', '" . $priv['priv_add'] . "', '" . $priv['priv_edit'] . "', '" . $priv['priv_delete'] . "', '" . $priv['priv_view'] . "', '" . $priv['priv_execute'] . "', NOW(), '" . $_SESSION['user']['login_name'] . "', NOW(), '" . $_SESSION['user']['login_name'] . "')
		         	";
			$result = mysql_query($query, $this->conn);
			#echo $query . "<br><br>";
		}
		
		
	}
	
	
	/****************************************************************************
	 * P4L MBS Functions
	 ****************************************************************************/
	function getSizeData()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_sizes` ORDER BY `size_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['size_id']] = array('size_name'=>$row['size_name']);
			}
			
			return $data;
		}
		
	} // getSizeData()
	
	function getTerritoryData()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_territories` ORDER BY `territory_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['territory_id']] = array('territory_name'=>$row['territory_name']);
			}
			
			return $data;
		}
		
	} // getTerritoryData()
	
	function getSupplierData()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_suppliers` ORDER BY `supplier_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['supplier_id']] = array('supplier_name'=>$row['supplier_name']);
			}
			
			return $data;
		}
		
	} // getSupplierData()


	function getSupplierRefNo()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT `supplier_id`, `supplier_po_ref_number` FROM `mbs_suppliers` ORDER BY `supplier_id`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['supplier_id']] = $row['supplier_po_ref_number'];
			}
			
			return $data;
		}
		
	} // getSupplierRefNo()

	function getActivityData()
	{
		$this->conn = DB::dbConnect();
		
		$intYear = date("Y");
		
		$query = "SELECT * FROM `mbs_activities` where `year`='$intYear' ORDER BY `activity_store_related` DESC, `activity_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['activity_id']] = array('activity_name'=>$row['activity_name'], 
												   'activity_description'=>$row['activity_description'], 
												   'activity_price'=>$row['activity_price'], 
												   'activity_store_related'=>$row['activity_store_related'], 
												   'size_id'=>$row['size_id']);
			}
			
			return $data;
		}
		
	} // getActivityData()


	function getActivityStoreRelated()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_activities` WHERE `activity_store_related` = 'yes' ORDER BY `activity_id`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[] = $row['activity_id'];
			}
			
			return $data;
		}
		
	} // getActivityStoreRelated()


	function getActivitySizeID()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_activities` WHERE `size_id` <> '0' ORDER BY `activity_id`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[] = $row['activity_id'];
			}
			
			return $data;
		}
		
	} // getActivitySizeID()


	function getActivitySizeIDData()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT `activity_id`, `size_id` FROM `mbs_activities` WHERE `size_id` <> '0' ORDER BY `activity_id`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[] = array('activity_id'=>$row['activity_id'],'size_id'=>$row['size_id']);
			}
			
			return $data;
		}
		
	} // getActivitySizeIDData()


	function getActivityPrice()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT `activity_id`, `activity_price` FROM `mbs_activities` ORDER BY `activity_id`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['activity_id']] = $row['activity_price'];
			}
			
			return $data;
		}
		
	} // getActivityPrice()


	function getActivitiesByBookingID($intBookingID)
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_bookings_activities` WHERE `booking_id` = '" . $intBookingID . "' ORDER BY `booking_activity_id`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[] = $row;
			}
			
			return $data;
		}
		
	} // getActivitiesByBookingID()


	function getActivityProductByID($intActivityID)
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_bookings_products` WHERE `booking_activity_id` = '" . $intActivityID . "' ORDER BY `booking_product_id`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[] = $row;
			}
			
			return $data;
		}
	
	} // getActivityProductByID($intActivityID)


	function getStoreData()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_stores` ORDER BY `store_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['store_id']] = array('store_id'=>$row['store_id'],'store_name'=>$row['store_name']);
			}
			
			return $data;
		}
		
	} // getSizeData()

	function getProductData()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_bookings_products` ORDER BY `booking_product_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['booking_product_id']] = array('booking_product_name'=>$row['booking_product_name'], 
												  		  'booking_product_code'=>$row['booking_product_code'], 
												  		  'booking_product_description'=>$row['booking_product_description'], 												  
												  		  'booking_product_normal_retail_price'=>$row['booking_product_normal_retail_price'], 
												  		  'booking_product_promo_price'=>$row['booking_product_promo_price'], 
												  		  'booking_product_special_offer_details'=>$row['booking_product_special_offer_details']);
			}
			
			return $data;
		}
		
	} // getProductData()


	function getBookingsLatest($intNumber = 5) 
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_bookings` ORDER BY `booking_created_date` DESC LIMIT " . $intNumber;
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['booking_id']] = array('booking_name'=>$row['booking_name'], 
												  'booking_code'=>$row['booking_code'], 
												  'booking_created_date'=>$row['booking_created_date'], 												 
												  'booking_supplier_id'=>$row['supplier_id'], 
												  'booking_supplier_name'=>DB::dbIDToField('mbs_suppliers', 'supplier_id', $row['supplier_id'], 'supplier_name'));
			}
			
			return $data;
		}

	} // getBookingsLatest()


	function getActivitiesDue($intNumber = 5)
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT *, DATEDIFF(`booking_activity_due_date`, CURDATE()) `booking_activity_due_in_days` 
			      FROM `mbs_bookings_activities` 
			      WHERE DATEDIFF(`booking_activity_due_date`, CURDATE()) <= 30 
			      AND DATEDIFF(`booking_activity_due_date`, CURDATE()) >= 0 
			      ORDER BY `booking_activity_due_date` ASC 
			      LIMIT " . $intNumber;
		$result = mysql_query($query, $this->conn);
		

		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$intSupplierID = DB::dbIDToField('mbs_bookings', 'booking_id', $row['booking_id'], 'supplier_id');

				$data[$row['booking_activity_id']] = array('booking_id'=>$row['booking_id'], 
												  	       'activity_id'=>$row['activity_id'], 
												  		   'product_id'=>$row['product_id'],
												  		   'size_id'=>$row['size_id'], 
												  		   'store_id'=>$row['store_id'],
												  		   'activity_name'=>DB::dbIDToField('mbs_activities', 'activity_id', $row['activity_id'], 'activity_name'), 
												  		   'booking_activity_year'=>$row['booking_activity_year'], 
												  		   'booking_activity_month'=>$row['booking_activity_month'], 
												  		   'booking_activity_description'=>$row['booking_activity_description'], 
												  		   'booking_activity_price'=>$row['booking_activity_price'], 
												  		   'booking_activity_due_date'=>$row['booking_activity_due_date'], 
												  		   'booking_activity_due_in_days'=>$row['booking_activity_due_in_days'], 
												  		   'booking_code'=>DB::dbIDToField('mbs_bookings', 'booking_id', $row['booking_id'], 'booking_code'), 
												  		   'booking_name'=>DB::dbIDToField('mbs_bookings', 'booking_id', $row['booking_id'], 'booking_name'), 
												  		   'supplier_id'=>$intSupplierID,	
												  	       'supplier_name'=>DB::dbIDToField('mbs_suppliers', 'supplier_id', $intSupplierID, 'supplier_name'));
			}
			
			return $data;
		}

	} // getActivitiesDue()


	function getBookingTotal($intBookingID)
	{

		$this->conn = DB::dbConnect();
		
		$query = "SELECT SUM(`booking_activity_price_total`) `total` FROM `mbs_bookings_activities` WHERE `booking_id` = '" . $intBookingID . "'";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			
			$row = mysql_fetch_assoc($result);			
			$strTotal = $row['total'];
			
			return $strTotal;
		}
	
	} // getBookingTotal($intBookingID)

	function checkActivityInStoreByDateTime($intActivityID, $intStoreID, $intYear=NULL, $intMonth=NULL)
	{
		$this->conn = DB::dbConnect();
		
		if (!$intYear) { $intYear = date('Y'); }
		if (!$intMonth) { $intYear = date('n'); }

		//-- Special for Gondola End (1 store for 12 months) ID 89
		if ($intActivityID == 89 || $intActivityID == 128 || $intActivityID == 164)
		{

			$strDateTime = $intYear . "-" . $intMonth . "-" . "01";

			$query = "SELECT * 
				      FROM `mbs_bookings_activities` 
				      WHERE `activity_id` = '" . $intActivityID . "' 
				      AND (`store_id` REGEXP '^" . intval($intStoreID) . ",' 
				      OR `store_id` REGEXP '," . intval($intStoreID) . ",' 
				      OR `store_id` REGEXP '," . intval($intStoreID) . "$' 
				      OR `store_id` REGEXP '^" . intval($intStoreID) . "$') 
					  AND `booking_activity_due_date` > '" . $strDateTime . "' ";

			$query .= "";
			#echo $query."<br />";
		}

		else	
		{
			$query = "SELECT * 
				      FROM `mbs_bookings_activities` 
				      WHERE `activity_id` = '" . $intActivityID . "' 
				      AND (`store_id` REGEXP '^" . intval($intStoreID) . ",' 
				      OR `store_id` REGEXP '," . intval($intStoreID) . ",' 
				      OR `store_id` REGEXP '," . intval($intStoreID) . "$' 
				      OR `store_id` REGEXP '^" . intval($intStoreID) . "$') ";

			if ($intYear) { $query .= " AND `booking_activity_year` = '" . $intYear . "' "; }	
			if ($intMonth) { $query .= " AND `booking_activity_month` = '" . $intMonth . "' "; }	      

			$query .= " ";
		}

		#echo $query."<br />";
		$result = mysql_query($query, $this->conn);
		$numRows = mysql_num_rows($result);
		
		return $numRows;

		if ($numRows)
		{
			$row = mysql_fetch_assoc($result);
			//echo $query."<br>";
			return $row;
		}


		

	} // checkActivityInStoreByDateTime($intActivityID, $intStoreID, $intYear=NULL, $intMonth=NULL)

	
	function getActivityInStoreTotalByDateTime($intActivityID, $intYear=NULL, $intMonth=NULL)
	{
		$this->conn = DB::dbConnect();
		
		if (!$intYear) { $intYear = date('Y'); }
		if (!$intMonth) { $intYear = date('n'); }

		//-- Special for Gondola End (1 store for 12 months) ID 89
		if ($intActivityID == 89 || $intActivityID == 128 || $intActivityID == 164)
		{
			$strDateTime = $intYear . "-" . $intMonth . "-" . "01";

			$query = "SELECT * 
				      FROM `mbs_bookings_activities` 
				      WHERE `activity_id` = '" . $intActivityID . "' 
				      AND `booking_activity_due_date` > '" . $strDateTime . "' ";
				            
			#$query .= " LIMIT 1";	
			
		}

		else
		{

			$query = "SELECT * 
				      FROM `mbs_bookings_activities` 
				      WHERE `activity_id` = '" . $intActivityID . "' 
				      ";

			if ($intYear) { $query .= " AND `booking_activity_year` = '" . $intYear . "' "; }	
			if ($intMonth) { $query .= " AND `booking_activity_month` = '" . $intMonth . "' "; }	      

			$query .= " LIMIT 1";
						
		}

		#echo $query."<br />";
		$result = mysql_query($query, $this->conn);	

		if ($result)
		{
			$row = mysql_fetch_assoc($result);

			return $row;
		}

	} // getActivityInStoreTotalByDateTime($intActivityID, $intYear=NULL, $intMonth=NULL)


	function getActivityByDateTime($intActivityID, $intYear=NULL, $intMonth=NULL)
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * 
			      FROM `mbs_bookings_activities` 
			      WHERE `activity_id` = '" . $intActivityID . "' 
			      ";

		if ($intYear) { $query .= " AND `booking_activity_year` = '" . $intYear . "' "; }	
		if ($intMonth) { $query .= " AND `booking_activity_month` = '" . $intMonth . "' "; }	      

		$query .= " LIMIT 1";
		#echo $query."<br />";
		$result = mysql_query($query, $this->conn);	

		if ($result)
		{
			$row = mysql_fetch_assoc($result);

			return $row;
		}

	} // getActivityByDateTime($intActivityID, $intYear=NULL, $intMonth=NULL)

	function getActivitiesByYear($intYear=NULL)
	{
		$this->conn = DB::dbConnect();

		if (!$intYear) { $intYear = date('Y'); }
		
		$query = "SELECT * 
			      FROM `mbs_bookings_activities` 
			      WHERE `booking_activity_year` = '" . $intYear . "' ";
		
		#echo $query."<br />";
		$result = mysql_query($query, $this->conn);	

		if ($result)
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result))
			{
				$data[] = $row;
			}

			return $row;
		}

	} // getActivitiesByYear($intYear=NULL)

	function countActivityPriceByDatetime($intYear=NULL, $intMonth=NULL, $strActivityCat=NULL)
	{
		$this->conn = DB::dbConnect();

		if (!$intYear) { $intYear = date('Y'); }
		if (!$intMonth) { $intYear = date('n'); }
		
		if ($strActivityCat)
		{
			$query = "SELECT SUM(t1.`booking_activity_price_total`) 
					  FROM  `mbs_bookings_activities` t1,
					  		`mbs_activities` t2
					  WHERE t1.`activity_id` = t2.`activity_id` 
					  AND t2.`activity_category` = '" . $strActivityCat . "' 
					  AND t1.`booking_activity_month` = '" . $intMonth . "' 
					  AND  t1.`booking_activity_year` = '" . $intYear . "' ";
		}

		else
		{
			$query = "SELECT SUM(`booking_activity_price_total`) 
					  FROM  `mbs_bookings_activities` 
					  WHERE  `booking_activity_month` = '" . $intMonth . "' 
					  AND  `booking_activity_year` = '" . $intYear . "' ";	
		}

		
		
		#echo $query."<br />";
		$result = mysql_query($query, $this->conn);	

		if ($result)
		{
			$row = mysql_fetch_row($result);

			return $row[0];
		}
	
	} // countActivityPriceByDatetime($intYear=NULL, $intMonth=NULL)

	function countActivityPriceInAYear($intYear=NULL, $strActivityCat=NULL)
	{
		if (!$intYear) { $intYear = date('Y'); }

		$data = array();
		for ($i = 1; $i <= 12; $i++)
		{
			if (DB::countActivityPriceByDatetime($intYear, $i, $strActivityCat))
			{
				$data[$i] = DB::countActivityPriceByDatetime($intYear, $i, $strActivityCat);	
			}

			else
			{
				$data[$i] = 0;
			}
			
		}

		return $data;

	} // countActivityPriceInAYear($intYear=NULL)


	function countActivityByDatetime($intYear=NULL, $intMonth=NULL)
	{
		$this->conn = DB::dbConnect();

		if (!$intYear) { $intYear = date('Y'); }
		if (!$intMonth) { $intYear = date('n'); }
		
		$query = "SELECT COUNT(`booking_activity_id`) 
				  FROM  `mbs_bookings_activities` 
				  WHERE  `booking_activity_month` = '" . $intMonth . "' 
				  AND  `booking_activity_year` = '" . $intYear . "' ";
		
		#echo $query."<br />";
		$result = mysql_query($query, $this->conn);	

		if ($result)
		{
			$row = mysql_fetch_row($result);

			return $row[0];
		}
	
	} // countActivityByDatetime($intYear=NULL, $intMonth=NULL)


	function countActivityInAYear($intYear=NULL)
	{
		if (!$intYear) { $intYear = date('Y'); }

		$data = array();
		for ($i = 1; $i <= 12; $i++)
		{
			if (DB::countActivityByDatetime($intYear, $i))
			{
				$data[$i] = DB::countActivityByDatetime($intYear, $i);	
			}
			else
			{
				$data[$i] = 0;
			}
			
		}

		return $data;

	} // countActivityPriceInAYear($intYear=NULL)


	function getActivitiesInBooking($intBookingID)
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_bookings_activities` WHERE `booking_id` = '" . $intBookingID . "' ORDER BY DATE(CONCAT(`booking_activity_year`, '-', `booking_activity_month`, '-', '01'))";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			
			$data = array();
			while ($row = mysql_fetch_assoc($result))
			{
				$data[] = $row;
			}			
			
			return $data;
		}

	} // getActivitiesInBooking($intBookingID)


	function getProductsInActivity($intBookingActivityID)
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_bookings_products` WHERE `booking_activity_id` = '" . $intBookingActivityID . "' ORDER BY `booking_product_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			
			$data = array();
			while ($row = mysql_fetch_assoc($result))
			{
				$data[] = $row;
			}			
			
			return $data;
		}

	} // getProductsInActivity($intBookingActivityID)
	
	function getDepartmentData()
	{
		$this->conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_departments` ORDER BY `department_name`";
		$result = mysql_query($query, $this->conn);
		
		if ($result) 
		{
			$data = array();
			while ($row = mysql_fetch_assoc($result)) 
			{
				$data[$row['department_id']] = array('department_name'=>$row['department_name']);
			}
			
			return $data;
		}
		
	} // getDepartmentData()
	
	function bookingCopy($intBookingId = NULL){
		if($intBookingId){
			
			$this->conn = DB::dbConnect();
			$html = new HTML();
		
			$query = "SELECT * FROM `mbs_bookings` WHERE `booking_id` = '$intBookingId'";
			$resultSelectBooking = mysql_query($query, $this->conn);
			$row = mysql_fetch_assoc($resultSelectBooking);
			
			$strBookingCode = $html->generateBookingCode();
			$query = "INSERT INTO `mbs_bookings` 
								(`booking_id`, 
								 `supplier_id`, 
								 `booking_code`, 
								 `booking_name`, 
								 `booking_date`, 
								 `booking_supplier_name`, 
								 `booking_supplier_po_ref_number`, 
								 `booking_total`, 
								 `booking_description`, 
								 `booking_file_name`, 
								 `booking_file_path`, 
								 `booking_active`, 
								 `booking_created_date`, 
								 `booking_created_by`, 
								 `booking_modified_date`, 
								 `booking_modified_by`) 
		
						VALUES (NULL, 
								 '" . $row['supplier_id'] . "', 
								 '" . $strBookingCode . "', 
								 'Booking " . $strBookingCode . " For ". $row['booking_supplier_name'] ."', 
								 '" . $row['booking_date'] . "', 
								 '" . $row['booking_supplier_name'] . "', 
								 '" . $row['booking_supplier_po_ref_number'] . "', 
								 '" . $row['booking_total'] . "', 
								 'Booking For ". $row['booking_supplier_name'] ."', 
								 '', 
								 '', 
								 '" . $row['booking_active'] . "', 
								 '" . date('Y-m-d H:i:s') . "', 
								 '" . $_SESSION['user']['login_name'] . "',
								 '" . date('Y-m-d H:i:s') . "', 
								 '" . $_SESSION['user']['login_name'] . "')";
		
			$resultInsertBooking = mysql_query($query, $this->conn);
			$intNewBookingID = mysql_insert_id();
			
			if($resultInsertBooking){
				$query = "SELECT * FROM `mbs_bookings_activities` WHERE `booking_id` = '$intBookingId'";
				$resultSelectActivities = mysql_query($query, $this->conn);
				
				while($row = mysql_fetch_assoc($resultSelectActivities)){
					$intActivityOldID = $row['booking_activity_id'];
					
					$query = "INSERT INTO `mbs_bookings_activities` (
								`booking_activity_id`, 
								`booking_id`, 
								`activity_id`, 
								`size_id`, 
								`store_id`, 																			
								`booking_activity_year`, 
								`booking_activity_month`, 
								`booking_activity_description`, 
								`booking_activity_price`, 
								`booking_activity_price_total`, 
								`booking_activity_due_date`, 
								`booking_activity_created_date`, 
								`booking_activity_created_by`, 
								`booking_activity_modified_date`, 
								`booking_activity_modified_by`) 
								
							  VALUES (NULL,
								'" . $intNewBookingID . "', 
								'" . $row['activity_id'] . "', 
								'" .  $row['size_id'] . "', 
								'" . $row['store_id'] . "', 																			
								'" . $row['booking_activity_year'] . "', 
								'" . $row['booking_activity_month'] . "', 
								'" . $row['booking_activity_description'] . "',
								'" . $row['booking_activity_price'] . "', 
								'" . $row['booking_activity_price_total'] . "', 	
								 " . $row['booking_activity_due_date'] .", 
								'" . date('Y-m-d H:i:s') . "', 
								'" . $_SESSION['user']['login_name'] . "',
								'" . date('Y-m-d H:i:s') . "', 
								'" . $_SESSION['user']['login_name'] . "');";

					$resultInsertActivities = mysql_query($query);
					$intActivityNewID = mysql_insert_id();
					
					if($resultInsertActivities){
						$query = "SELECT * FROM `mbs_bookings_products` WHERE `booking_activity_id` = '$intActivityOldID'";
						$resultSelectProducts = mysql_query($query, $this->conn);
						
						while($row = mysql_fetch_assoc($resultSelectProducts)){
							$query = "INSERT INTO `mbs_bookings_products` (`booking_product_id`, 
										`booking_id`, 
										`booking_activity_id`,
										`booking_department_id`, 
										`booking_product_code`, 
										`booking_product_name`, 																	
										`booking_product_normal_retail_price`, 
										`booking_product_promo_price`, 
										`booking_product_cost_price`, 
										`booking_product_recommended_retail_price`, 
										`booking_product_special_offer_details`, 
										`booking_product_description`, 
										`booking_product_created_date`, 
										`booking_product_created_by`, 
										`booking_product_modified_date`, 
										`booking_product_modified_by`) 
								 VALUES (NULL, 
										'" . $intNewBookingID . "', 
										'" . $intActivityNewID . "', 
										'" . $row['booking_department_id'] . "', 
										'" . $row['booking_product_code'] . "', 
										'" . $row['booking_product_name'] . "', 
										'" . $row['booking_product_normal_retail_price'] . "', 
										'" . $row['booking_product_promo_price'] . "', 
										'" . $row['booking_product_cost_price'] . "', 
										'" . $row['booking_product_recommended_retail_price'] . "', 
										'" . $row['booking_product_special_offer_details'] . "', 
										'" . $row['booking_product_description'] . "',
										'" . date('Y-m-d H:i:s') . "', 
										'" . $_SESSION['user']['login_name'] . "',
										'" . date('Y-m-d H:i:s') . "', 
										'" . $_SESSION['user']['login_name'] . "')";	

							$resultInsertProducts = mysql_query($query);
							$intID = mysql_insert_id();
						}
					}
				}
				return $intNewBookingID;
			}else{
				return FALSE;
			}
		}
	} // bookingCopy()
	
	function getSupplierAccount($intSupplierId){
		$conn = DB::dbConnect();
		
		$query = "SELECT * FROM `mbs_suppliers` 
						INNER JOIN `mbs_suppliers_marketing_contacts` 
							ON mbs_suppliers.supplier_id=mbs_suppliers_marketing_contacts.supplier_id 
					WHERE `mbs_suppliers`.`supplier_id` = '$intSupplierId' LIMIT 1";
		$resultSelectSupplier = mysql_query($query, $conn);
		$row = mysql_fetch_assoc($resultSelectSupplier);
		
		return $row;
		
	} // getSupplierAccount()

	function checkSupplierCatalogueStock($intSupplierId, $text, $strYear, $strMonth){
		$conn = DB::dbConnect();
		
		$maxSpot = 384;
		$spot;
		$theme;

		if(strpos($text, "Catalogue - IN 2 Health")!==false){
			$theme = "Catalogue - IN 2 Health";
		}else if(strpos($text, "Catalogue - Pharmacy4Less")!==false){
			$theme = "Catalogue - Pharmacy4Less";
		}else if(strpos($text, "Catalogue - Roy Young")!==false){
			$theme = "Catalogue - Roy Young";
		}else if(strpos($text, "Themed Catalogue - Pharmacy4Less")!==false){
			$theme = "Themed Catalogue - Pharmacy4Less";
		}else if(strpos($text, "Themed Catalogue - Roy Young")!==false){
			$theme = "Themed Catalogue - Roy Young";
		}

		if(strpos($text, "Double Spot")!==false){
			$spot = 2;
		}else if(strpos($text, "Full Page")!==false){
			$spot = 16;
		}else if(strpos($text, "8 Spots")!==false){
			$spot = 8;
		}else if(strpos($text, "4 Spots")!==false){
			$spot = 4;
		}else if(strpos($text, "Single Spot")!==false){
			$spot = 1;
		}else if(strpos($text, "Themed Supplementary")!==false){
			$spot = 2;
		}

		$query = "SELECT 
					SUM(
						CASE
				    		WHEN LOCATE('Double Spot',activity.activity_name)>0 THEN '2'
							WHEN LOCATE('Full Page',activity.activity_name)>0 THEN '16'
							WHEN LOCATE('8 Spots',activity.activity_name)>0 THEN '8'
							WHEN LOCATE('4 Spots',activity.activity_name)>0 THEN '4'
							WHEN LOCATE('Single Spot',activity.activity_name)>0 THEN '1'
							WHEN LOCATE('Themed Supplementary',activity.activity_name)>0 THEN '2'
				    	END
					) AS stock
				FROM 
					mbs_bookings_activities AS booking 
				INNER JOIN 
					mbs_activities AS activity 
					ON activity.activity_id = booking.activity_id 
				WHERE 
					activity.activity_name like '%".$theme."%'
					AND booking_activity_month = '".$strMonth."' 
					AND booking_activity_year = '".$strYear."'";
		$result = mysql_query($query, $conn);
		$row = mysql_fetch_assoc($result);
		
		if($row['stock'] + $spot <= 384){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function insertEmail()
	{
		
		$this->conn = $this->dbConnect();
				
		$query = "INSERT INTO `mbs_emails` (`email_id`,
											`store_id`, 
											`email_name`, 
											 `email_address`, 											  
										     `email_created_date`, 
											 `email_created_by`, 
											 `email_modified_date`, 
											 `email_modified_by`
									  		 )
								VALUES (NULL , 
										'" . mysql_real_escape_string($_REQUEST['frm_store_id']) . "',
										'" . mysql_real_escape_string($_REQUEST['frm_email_name']) . "', 
										'" . mysql_real_escape_string($_REQUEST['frm_email_address']) . "', 
		
										NOW( ) , 
										'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "', 
										NOW( ) , 
										'" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'
										);";
						
		
			$result = mysql_query($query, $this->conn);
			$intEmailID = mysql_insert_id();
		
		#echo $query;
		if ($result) 
		{
			
	
			$strAlert = "Email Address <strong>\"" . stripslashes($_REQUEST['frm_email_address']) . "\"</strong> is successfully added!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('email', 'view') > 0) { $strAlert .= "<a href=\"email_view.php?email_id=". $intEmailID . "\" title=\"View Email\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('email', 'edit') > 0) { $strAlert .= "<a href=\"email_edit.php?email_id=" . $intEmailID . "\" title=\"Edit Email\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('email', 'delete') > 0) { $strAlert .= "<a href=\"email_delete.php?email_id=" . $intEmailID . "&action=delete\" title=\"Delete Email\" onclick=\"return confirmDeleteEmail(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strAlert .= "<br /><br />\n";
			
			if (ADMIN::getModulePrivilege('email', 'add') > 0) { $strAlert .= "<a href=\"email_add.php\" title=\"Add Email\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('email', 'list') > 0) { $strAlert .= "<a href=\"email_list.php\" title=\"User Email\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			
			
			$strLog = "Email <strong>\"" . stripslashes($_REQUEST['frm_email_address']) . "\"</strong> is successfully added.";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);			
			
		} 
		
		else 
		{			
			HTML::showAlert("Query error on the database: Email!", FALSE);			
		}
		
		
	} // insertEmail()

	function updateEmail()
	{
		
		$this->conn = $this->dbConnect();
				
		$query = "UPDATE `mbs_emails` SET `store_id` = '" . mysql_real_escape_string($_REQUEST['frm_store_id']) . "', 											
										   `email_name` = '" . mysql_real_escape_string($_REQUEST['frm_email_name']) . "', 
										   `email_address` = '" . mysql_real_escape_string($_REQUEST['frm_email_address']) . "', 
										   `email_modified_date` = NOW( ), 
										   `email_modified_by` = '" . mysql_real_escape_string($_SESSION['user']['login_name']) . "'
										WHERE
											`email_id` = '" . $_REQUEST['frm_email_id'] . "' LIMIT 1";
								
			$result = mysql_query($query, $this->conn);
			$intEmailID = $_REQUEST['frm_email_id'];
		
		#echo $query;
		if ($result) 
		{			

			$strAlert = "Email <strong>\"" . stripslashes($_REQUEST['frm_email_address']) . "\"</strong> is successfully updated!";
			$strAlert .= "<br /><br />\n";
			if (ADMIN::getModulePrivilege('emails', 'view') > 0) { $strAlert .= "<a href=\"email_view.php?email_id=". $intEmailID . "\" title=\"View Email\"><img src=\"img/view_icon.png\" /> View</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('emails', 'edit') > 0) { $strAlert .= "<a href=\"email_edit.php?email_id=" . $intEmailID . "\" title=\"Edit Email\"><img src=\"img/edit_icon.png\" /> Edit</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('emails', 'delete') > 0) { $strAlert .= "<a href=\"email_delete.php?email_id=" . $intEmailID . "&action=delete\" title=\"Delete Email\" onclick=\"return confirmDeleteUserGroup(this.form)\"><img src=\"img/delete_icon.png\" /> Delete</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strAlert .= "<br /><br />\n";
			
			if (ADMIN::getModulePrivilege('emails', 'add') > 0) { $strAlert .= "<a href=\"email_add.php\" title=\"Add Email\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
			if (ADMIN::getModulePrivilege('emails', 'list') > 0) { $strAlert .= "<a href=\"email_list.php\" title=\"Email List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			
			$strLog = "Email \"" . stripslashes($_REQUEST['frm_email_address']) . "\" is successfully updated!";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . mysql_real_escape_string($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog, $this->conn);
			
			HTML::showAlert($strAlert, FALSE);			
			
		} 
		else 
		
		{		
			HTML::showAlert("Query error on the database: UserGroup!", FALSE);			
		}
		
		
	} // updateEmail()

	function deleteEmail() 
	{
		
		$this->conn = $this->dbConnect();
		
		$query = "SELECT * FROM `mbs_emails` WHERE `email_id` = '" . $_REQUEST['email_id'] . "' LIMIT 1";
		$result = mysql_query($query, $this->conn);
		$row = mysql_fetch_assoc($result);
		
		
		if ($row)
		{
			// delete user group
			$queryDel = "DELETE FROM `mbs_emails` WHERE `email_id` = '" . $_REQUEST['email_id'] . "' LIMIT 1";
			$resultDel = mysql_query($queryDel, $this->conn);
				
			if ($resultDel) 
			{
				

				$strAlert = "Email <strong>\"" . stripslashes($row['email_address']) . "\"</strong> is successfully deleted!";
				$strAlert .= "<br /><br />\n";			
				
				if (ADMIN::getModulePrivilege('emails', 'add') > 0) { $strAlert .= "<a href=\"email_add.php\" title=\"Add Email\"><img src=\"img/add_icon.png\" /> Add</a>&nbsp;&nbsp;&nbsp;\n"; }
				if (ADMIN::getModulePrivilege('emails', 'list') > 0) { $strAlert .= "<a href=\"email_list.php\" title=\"Email List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
				
				$strLog = "Email \"" . stripslashes($row['email_address']) . "\" is successfully deleted.";
				
				$queryLog = "INSERT INTO `logs` (`log_id`, 
											     `log_user`, 
											     `log_action`, 
											     `log_time`, 
											     `log_from`, 
											     `log_logout`)
	
							VALUES (NULL, 
									'" . $_SESSION['user']['login_name'] . "',
								    '" . mysql_real_escape_string($strLog) . "',
									NOW( ),
									'" . $_SESSION['user']['ip_address'] . "', 
									NULL)";			
				
				$resultLog = mysql_query($queryLog, $this->conn);
				
				HTML::showAlert($strAlert, FALSE);
				
				
			}
		
		}
		
		else 
		{
			$strAlert = "Email <strong>\"" . stripslashes($row['email_address']) . "\"</strong> tidak kosong!";
			$strAlert .= "<br /><br />\n";						
			if (ADMIN::getModulePrivilege('emails', 'list') > 0) { $strAlert .= "<a href=\"email_list.php\" title=\"Email List\"><img src=\"img/list_icon.png\" /> List</a>&nbsp;&nbsp;&nbsp;\n"; }
			HTML::showAlert($strAlert, FALSE);
		}
		
		
		
	}  //deleteEmail()

	function getListEmail($intStoreID){
		$query = "SELECT * FROM mbs_emails WHERE store_id = '$intStoreID'";
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)){
			$array[] = $row['email_address'];
		}
		return $array;
	}

	

} // end of class DB

?>