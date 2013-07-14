<?php
include ('inc/_include.php');
include 'config.php';
if ($_REQUEST['frm_submit']) 
{

	if ($_REQUEST['frm_user_login'] && $_REQUEST['frm_user_password']) 
	{
		
		$strUserType = $admin->getUserType(strtolower($_REQUEST['frm_user_login']));
			
		if ($admin->checkUser($_REQUEST['frm_user_login']) > 0 && 
		    $admin->checkPassword($_REQUEST['frm_user_login'], $_REQUEST['frm_user_password']) > 0) 
		    {		    

			$user = $admin->getUserData($_REQUEST['frm_user_login']);
					
			if ($admin->checkUserStatus($_REQUEST['frm_user_login']) > 0) 
			{
					
				$id = $admin->getUserData($_REQUEST['frm_user_login']);
				
				/* registering sessions */
				$_SESSION['user']['session_id'] = session_id();
				$_SESSION['user']['log_id'] = $admin->getLogID();
				$_SESSION['user']['type'] = $strUserType;
				$_SESSION['user']['login_name'] = strtolower($_REQUEST['frm_user_login']);
        		$_SESSION['user']['login_time'] = date("Y-m-d H:i:s");
				$_SESSION['user']['id'] = $id['user_id'];		
				$_SESSION['user']['ip_address'] = $_SERVER['REMOTE_ADDR'] . " (" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")";
				$_SESSION['user']['user_group_id'] = $id['user_group_id'];
				
				if ($admin->saveUserLogin($_SESSION['user']['login_name']) > 0) 
				{
					$admin->sendSecurityNotification();
					header("Location: ./home.php");					
					exit;
				} 
				
				else 				
				{
					$html->showHeader ();					
					$html->showAlert ("Sorry, cannot save your login session!");					
					$html->showFooter ();
				}
							
			} 
			
			else 
			
			{
				$html->showHeader ();				
				$html->showAlert ("Sorry, your account has expired!");								
				$html->showFooter ();			
			} // if(checkUserStatus($_REQUEST['frm_user_login']) > 0)

		} 
		
		else 
		
		{
			$html->showHeader ();
			$html->showAlert ("Sorry, your username or password is incorrect!");			
			$html->showFooter (FALSE, TRUE);
		} // if (checkUser($_REQUEST['frm_user_login']) > 0 && checkPassword($_REQUEST['frm_user_login'], $_REQUEST['frm_user_password']) > 0)

	} 
	
	else 
	
	{
		$html->showHeader ();		
		$html->showAlert ("Sorry your username or password is not entered!");		
		$html->showFooter ();
	
	} // if ($_REQUEST['frm_user_login'] && $_REQUEST['frm_user_password']) 

} 

else 

{
	header("Location: index.php");
	exit;
}
?>