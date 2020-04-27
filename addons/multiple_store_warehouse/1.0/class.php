<?php defined('_AZ') or die('Restricted access');  
	
	switch ($loadPlugins['load_addon']) {
		
		case 'add_purchase_store_insert':
		
		break;
		
		case 'add_purchase_store_validation_check':
		
		break;
		
		case "create_user_store_insert":
		if(isset($data['store_id'])){
			$as_user_details['store_id']=$data['store_id'];
		}
		break;
		
		default:
		break;
	}
	
	
?>	