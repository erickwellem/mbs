<?php
	/* @Author: Erick Wellem - me @ erickwellem.com - October 2009 */
	
	// include file - no need to change anything here - EW
	$dirPos = './';
	include($dirPos . 'config.php');
	
	// Class Library -- NO NEED TO CHANGE unless you know what to do
	require_once('lib/admin.php');
	require_once('lib/db.php');
	require_once('lib/html.php');
	
	// Includes
	$HEADER_INCLUDE = 'inc/header-default.php';
	$FOOTER_INCLUDE = 'inc/footer-default.php';
	
	// start the session
	session_start();
	
	$db = new DB();
	$admin = new ADMIN();
	$html = new HTML();
	
	// get the configuration
	$arrSiteConfig = $db->getSiteConfig();
	
	// get the privileges and modules
	$arrPrivileges = $admin->getPrivileges();
	
	// get site language
	if ($arrSiteConfig['site_language'] == 'id')
	{
		require_once('lang/id/id.php');	
	}
	
	else
	
	{
		require_once('lang/en/en.php');	
	}

?>