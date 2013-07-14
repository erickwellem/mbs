<?php
session_start();
include('../config.php');
require_once('../lib/db.php');

#print_r($_POST);

if ($_REQUEST['frm_document_id'] && $_REQUEST['frm_comment_body'])
{
	$db = new DB();
	
	$db->dbConnect();
	$query = "INSERT INTO `comextra_documents_comments` (`comment_id` ,
														`document_id` ,
														`user_id` ,
														`comment_body` ,
														`comment_from` ,
														`comment_created_date` ,
														`comment_created_by` ,
														`comment_modifed_date` ,
														`comment_modifed_by`
														)
														VALUES (NULL , 
																'" . intval($_REQUEST['frm_document_id']) . "', 
																'" . $_SESSION['user']['id'] . "', 
														 		'" . addslashes($_REQUEST['frm_comment_body']) . "',  
																'" . $_SERVER['REMOTE_ADDR'] . " (" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")', 
														 		NOW( ) ,  
														 		'" . $_SESSION['user']['login_name'] . "', 
														 		NOW( ) ,  
														 		'" . $_SESSION['user']['login_name'] . "')";
	$result = mysql_query($query);
	
	#echo $query;
	if ($result)
	{
			$strLog = "Comment for Document \"" . stripslashes($db->dbFieldToID('comextra_documents', 'document_id', intval($_REQUEST['frm_document_id']), 'document_code')) . " - " . stripslashes($db->dbFieldToID('comextra_documents', 'document_id', intval($_REQUEST['frm_document_id']), 'document_name')) . "\" is successfully posted!";
			
			$queryLog = "INSERT INTO `logs` (`log_id`, 
										     `log_user`, 
										     `log_action`, 
										     `log_time`, 
										     `log_from`, 
										     `log_logout`)

						VALUES (NULL, 
								'" . $_SESSION['user']['login_name'] . "',
							    '" . addslashes($strLog) . "',
								NOW( ),
								'" . $_SESSION['user']['ip_address'] . "', 
								NULL)";
			
			$resultLog = mysql_query($queryLog);
		
			echo 'Comment is successfully posted!';
	}
	
}

else 
{
	echo "Failed!";
}
?>