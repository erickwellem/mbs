<?php
include('../config.php');
require_once('../lib/db.php');

$db = new DB();

$db->dbConnect();

if ($_REQUEST['term'] && strlen($_REQUEST['term']) > 1)
{
	$query = "SELECT `supplier_id` AS `id`, 				     
				     `supplier_po_ref_number` AS `value`, 
				     CONCAT(`supplier_po_ref_number`, ' - ', `supplier_name`) AS `label` 
		 	  FROM `mbs_suppliers` 
		 	  WHERE `supplier_po_ref_number` LIKE '%" . mysql_real_escape_string($_REQUEST['term']) . "%' 
		 	  ORDER BY `supplier_id`";

	$result = mysql_query($query);
	#echo $query . "<br /><br />";
	if ($result)
	{
		$i = 0;	
		while ($row = mysql_fetch_assoc($result))
		{
			foreach ($row as $key => $value) {
				$data[$i][$key] = $value;
			}
			$i++;
		}
		
		#print_r($data);
		print json_encode($data);
	}
}

else
{
	echo "No results found";
}

?>