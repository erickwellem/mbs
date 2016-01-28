<?php
/**************************************************************************************************
 * EW Web Apps Process File
 * @Author		: Erick Wellem (me@erickwellem.com)
 * 				  October 2009
 *				  This version: February 2013
 * 		
 * @Desc: Process file using Ajax
 **************************************************************************************************/

include('../config.php');
#require_once('../lib/db.php');

if ($_REQUEST['year'] && $_REQUEST['month'])
{
	if (mktime(0,0,0,intval($_REQUEST['month']), 1, intval($_REQUEST['year'])) < time() + (3600*24*30*1))
	{
		echo '<div class="alert alert-error span4"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-info-sign"></i> <strong>Warning!</strong> The Month and Year chosen was not in the minimum booking time required or is in the past</div>';
	}
}

?>