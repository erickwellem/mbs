<?php
include('../config.php');
require_once('../lib/db.php');

$db = new DB();

$db->dbConnect();
$query = "SELECT COUNT(*) FROM `comextra_documents` WHERE `document_code` = '" . strtoupper(trim($_REQUEST['frm_document_code'])) . "'";
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