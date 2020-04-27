<?php
	include dirname(__FILE__) . '/ASEngine/AS.php';
	header('Content-Type: application/json');
	$DemoMode = false;
	$data = json_decode(file_get_contents('php://input'), true);
	
	if(isset($_GET['action'])){
		$data = $_GET;
	}
	
	if($DemoMode){
		if(isset($data['key']) && app('admin')->getapikeycheck($data['key'])){
			if(!app('admin')->getapikeycheck($data['key'],"active")){
				$myObj = new stdClass;
				$myObj->api_status = "inactive";
				$myObj->api_msg = "Authorized API Key Not Active";
				echo json_encode($myObj);
				return;
			}
			}elseif($data['action'] != 'GetLoginAuth'){
			$myObj = new stdClass;
			$myObj->api_status = "not_found";
			$myObj->api_msg = "Authorized API Key Not Found";
			echo json_encode($myObj);
			return;
		}
	}
	
	$currentUser = app('current_user');
	
	switch ($data['action']) {
		
		case 'GetLoginAuth':
		$userid = app('login')->userApiLogin($data['username'], $data['password']);
		$myObj = new stdClass;
		if($userid){
			$userdetails = app('admin')->getuserdetails($userid);
			$domaindetails = app('admin')->getwhereid("as_sub_domain","domain_id",$userdetails['domain_id']);
			$ApiCheckStatus = app('admin')->getapikeycheck($data['key']);
			if(!$ApiCheckStatus){
				app('admin')->GetApiKeyUpdate($data['key'],'inactive',$data['pc_name'],$data['last_ip'],$userid,$userdetails['domain_id']);
				$myObj->api_status = 'inactive';
				$myObj->api_msg = "Authorized API Key Not Active";
				}elseif($ApiCheckStatus['api_status'] != 'active'){
				$myObj->api_status = $ApiCheckStatus['api_status'];
				$myObj->api_msg = "Authorized API Key Not Active";
				}else{
				$myObj->username = $userdetails['username'];
				$myObj->password = $data['password'];
				$myObj->user_id = $userdetails['user_id'];
				$myObj->first_name = $userdetails['first_name'];
				$myObj->last_name = $userdetails['last_name'];
				$myObj->email = $userdetails['email'];
				$myObj->phone = $userdetails['phone'];
				$myObj->store_id = $userdetails['store_id'];
				$myObj->domain_name = "https://".$domaindetails['sub_domain'].'.'.$domaindetails['root_domain']."/api.php";
				$myObj->status = "active";
				$myObj->api_status = $ApiCheckStatus['api_status'];
				$myObj->api_msg = "Authorized API Key Valid";
			}
			echo json_encode($myObj);
			}else{
			$myObj->status = "invalide";
			echo json_encode($myObj);
		}
		
		break;
		
		case 'GetApiKeyActive':
		$myObj = new stdClass;
		if(app('admin')->getapikeycheck($data['api_key'],"active")){
			$myObj->status = 'active';
			}else{
			$myObj->status = 'inactive';
		}
		echo json_encode($myObj);
		break;
		
		case 'GetSettingUpdate':
		
		$myObj = new stdClass;
		
		$myObj->status = 'inactive';
		jsonEncode($data,$myObj);
		
		// $possetting = app('pos')->GetApiPosSetting();
		// $GetApiProductCategory = app('pos')->GetApiProductCategory();
		// $GetApiProductUnit = app('pos')->GetApiProductUnit();
		// $GetPosProducts = app('admin')->getall('pos_product');
		// $GetApiCustomer = app('pos')->GetApiCustomerData();
		// $myObj = new stdClass;
		// $myObj->PosSetting = $possetting;
		// $myObj->ProductCategory = $GetApiProductCategory;
		// $myObj->ProductUnit = $GetApiProductUnit;
		// $myObj->Products = $GetPosProducts;
		// $myObj->Customers = $GetApiCustomer;
		// jsonEncode($apikey,$myObj);
		break;
		
		
		case 'GetStockUpdate':
		$customerid = array();	
		foreach($data['customer'] as $customer_data){
			if(!app('admin')->GetApiCrossCheck('pos_customer','customer_id',$customer_data['customer_id'])){
				if(!app('admin')->GetApiCrossCheck('pos_customer','customer_phone',$customer_data['customer_phone'])){
					app('pos')->GetUpdatePosCustomer($customer_data);
				}
			}
			$customerid[] =$customer_data['customer_id'];
		}
		
		$salesid = array();
		foreach($data['sales'] as $sales_data){
			if(!app('admin')->GetApiCrossCheck('pos_sales','sales_id',$sales_data['sales_id'])){
				$salesid[] = app('pos')->GetUpdatePosSales($sales_data);
			}
		}
		$stockid = array();	
		foreach($data['stock'] as $stock_data){
			if(!app('admin')->GetApiCrossCheck('pos_stock','stock_id',$stock_data['stock_id'])){
				$stockid[] = app('pos')->GetUpdatePosStock($stock_data);
			}
		}
		
		$GetPosProducts = app('admin')->getall('pos_product');
		$posproductstock = array();
		foreach ($GetPosProducts as $GetPosProduct) {
			$posproductstock[$GetPosProduct['product_id']] = app('pos')->GetApiProductStock($GetPosProduct['product_id'],$data['store_id']);
		}
		
		$myObj = new stdClass;
		$myObj->sales_ids = $salesid;
		$myObj->stock_ids = $stockid;
		$myObj->customer_ids = $customerid;
		$myObj->product_stock = $posproductstock;
		jsonEncode($apikey,$myObj);
		break;
		
		
		
		
		
		case 'GetProductAndStock':
		$GetPosProducts = app('admin')->getall('pos_product');
		$i = 0;
		foreach ($GetPosProducts as $GetPosProduct) {
			$GetApiProductStock = app('pos')->GetApiProductStock($GetPosProduct['product_id'],$data['store_id']);
			$myObj[$i] = new stdClass;
			$myObj[$i]->product_id = $GetPosProduct['product_id'];
			$myObj[$i]->product_name = $GetPosProduct['product_name'];
			$myObj[$i]->sell_price = $GetPosProduct['sell_price'];
			$myObj[$i]->product_vat = $GetPosProduct['product_vat'];
			$myObj[$i]->size = $GetPosProduct['size'];
			$myObj[$i]->unit = $GetPosProduct['unit'];
			$myObj[$i]->category_id = $GetPosProduct['category_id'];
			$myObj[$i]->supplier_id = $GetPosProduct['supplier_id'];
			$myObj[$i]->stock = $GetApiProductStock;
			$myObj[$i]->store_id = $data['store_id'];
			$myObj[$i]->warehouse_id = 1;
			$i++;	
		}
		jsonEncode($apikey,$myObj);
		break;
		
		case 'GetProductUnit':
		$GetApiProductUnit = app('pos')->GetApiProductUnit();
		jsonEncode($apikey,$GetApiProductUnit);
		break;
		
		case 'GetProductCategory':
		$GetApiProductCategory = app('pos')->GetApiProductCategory();
		jsonEncode($apikey,$GetApiProductCategory);
		break;
		
		case 'GetCustomerSync':
		$GetApiCustomer = app('pos')->GetApiCustomerData();
		jsonEncode($apikey,$GetApiCustomer);
		break;
		
		case 'GetSettingSync':
		$possetting = app('pos')->GetApiPosSetting();
		jsonEncode($apikey,$possetting);
		break;
		
		case 'geterror':
		$myObj = new stdClass;
		$myObj->status = "invalide_key";
		echo json_encode($myObj);
		break;
		
		default:
        break;
	}
	
	function jsonEncode($data,$token,$userId = null,$encodeMode= true){
		$firebase = new \Firebase\JWT\JWT;
		$key = app('admin')->getapikeycheck($data['key'],"active",$userId);
		if($key){
			if($encodeMode){
				echo $firebase->encode($token, $key['api_key']);
				}else{
				echo json_encode($myObj);
			}
			return true;
			}else{
			$myObj = new stdClass;
			$myObj->api_status = "not_found";
			$myObj->api_msg = "Authorized API Key Not Found";
			echo json_encode($myObj);
			return false;
		}
	}
?>		