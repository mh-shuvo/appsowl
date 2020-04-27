<?php defined('_AZ') or die('Restricted access');  
	
	switch ($addonName) {
		
		case 'manage_user':
		echo $data['User']->username;
		// echo "Table Connected";
		break;
		
		default:
		break;
	}
	
	
?>	