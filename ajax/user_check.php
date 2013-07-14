<?php
include('../config.php');
require_once('../lib/db.php');

$db = new DB();

$db->dbConnect();
$query = "SELECT COUNT(*) FROM `users` WHERE `user_login_name` = '" . strtolower(trim($_REQUEST['frm_user_login_name'])) . "'";
$result = mysql_query($query);

if ($result)
{
	$row = mysql_fetch_row($result);
	
	if ($row[0] > 0)
	{
		echo 'no';
	}
	else 
	{
		echo 'yes';
	}
}
	
?>