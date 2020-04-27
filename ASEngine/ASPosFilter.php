<?php defined('_AZ') or die('Restricted access');
	
	// $searchSelect = array();
	// $rowSelect = array();
	// $selectWhere = array();
	// $jointWhere = array();
	// $selectNotWhere = array();
	// $tableSelect = array();
	// $GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
	
	switch ($action) {
		
		case "GetAccountUserData":	
		
		$data = $_POST;
		
		$whereStore = '';
		if(isset($data['business_location']) && $data['business_location'] != '0'){
			$store =  $data['business_location'];
			$whereStore = " AND `accounts_transactions`.`store_id` = '$store'";
		}
		
		
		$selectRaw = ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` != 'due' AND `accounts_transactions`.`cr_account_status` != 'advance' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`payer_name` = `pos_contact`.`contact_id` $whereStore) AS `expense_amount` ";
		$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`dr_account_status` != 'advance' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`payer_name` = `pos_contact`.`contact_id` $whereStore) AS `expense_amount_paid` ";
		$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` = 'due' AND `accounts_transactions`.`dr_account_status` = 'paid' THEN amount END), 0) - COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'due' AND `accounts_transactions`.`cr_account_status` = 'paid' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`payer_name` = `pos_contact`.`contact_id` $whereStore) AS `expense_amount_due` ";
		$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'advance' AND `accounts_transactions`.`cr_account_status` = 'paid' THEN amount END), 0) - COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` = 'advance' AND `accounts_transactions`.`dr_account_status` = 'paid' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`payer_name` = `pos_contact`.`contact_id` $whereStore) AS `expense_amount_advance` ";
		
		$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` != 'advance' AND `accounts_transactions`.`cr_account_status` != 'due' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`payer_name` = `pos_contact`.`contact_id` $whereStore) AS `income_amount` ";
		$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`dr_account_status` != 'due' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`payer_name` = `pos_contact`.`contact_id` $whereStore) AS `income_amount_paid` ";
		$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'due' AND `accounts_transactions`.`cr_account_status` = 'paid' THEN amount END), 0) - COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`cr_account_status` = 'due' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`payer_name` = `pos_contact`.`contact_id` $whereStore) AS `income_amount_due` ";
		$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`cr_account_status` = 'advance' THEN amount END), 0) - COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'advance' AND `accounts_transactions`.`cr_account_status` = 'paid' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`payer_name` = `pos_contact`.`contact_id` $whereStore) AS `income_amount_advance` ";
		
		$searchSelect = array("pos_contact.name","pos_contact.contact_id");
		$rowSelect = array(
		"pos_contact.contact_id",
		"pos_contact.contact_type",
		"pos_contact.name",
		"pos_contact.address",
		"pos_contact.phone",
		"pos_contact.email",
		"pos_contact.user_id",
		"pos_contact.contact_status",
		"pos_contact.business_name",
		"pos_contact.website_name",
		);
		
		$jointWhere = array();
		$tableSelect = array("pos_contact");
		$selectWhere = array("pos_contact.is_delete" => "false");
		$selectNotWhere = array("pos_contact.contact_status" => 'inactive');
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere,$selectRaw);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $x+1;
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetProductData":
		$searchSelect = array("pos_product.product_name","pos_product.product_id");
		$rowSelect = array(
		"pos_product.product_name",
		"pos_product.product_id",
		"pos_product.product_vat",
		"pos_product.product_vat_type",
		"pos_product.product_type",
		"pos_variations.sub_product_id",
		"pos_variations.variation_name",
		"pos_variations.sell_price",
		);
		
		$jointWhere = array(
		"pos_variations.product_id" => "pos_product.product_id"
		);
		$tableSelect = array("pos_variations","pos_product");
		$selectWhere = array("pos_variations.is_delete" => "false");
		$selectNotWhere = array();
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			if($GetFilterData['Data'][$x]['product_type'] == 'variable'){
				$GetFilterData['Data'][$x]['product_name'] = $GetFilterData['Data'][$x]['product_name'].'['.$GetFilterData['Data'][$x]['variation_name'].']';
			}
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetIncomeReportData":
		$searchSelect = array(
		"accounts_transactions.payer_name",
		);
		$rowSelect = array(
		"accounts_transactions.amount",
		"accounts_transactions.id",
		"accounts_transactions.cr_account",
		"accounts_transactions.date",
		"accounts_transactions.account_status",
		"accounts_transactions.payment_method",
		"accounts_transactions.created_at",
		"accounts_transactions.user_id",
		"accounts_transactions.note",
		"pos_contact.name",
		);
		$selectWhere = array(
		"accounts_transactions.account_type" => "account_income",
		"accounts_transactions.is_delete" => "false",
		);
		$jointWhere = array(
		"accounts_transactions.payer_name" => "pos_contact.contact_id"
		);
		$selectNotWhere = array();
		$tableSelect = array("accounts_transactions","pos_contact");
		
		if(isset($_POST['account_name']) && $_POST['account_name'] != '0'){
			$selectWhere['accounts_transactions.cr_account'] = $_POST['account_name'];
		}
		
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$selectWhere['accounts_transactions.store_id'] = $_POST['store_id'];
		}
		
		if(isset($_POST['account_for']) && $_POST['account_for'] != '0'){
			$selectWhere['accounts_transactions.payer_name'] = $_POST['account_for'];
		}
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {			
			$GetFilterData['Data'][$x]['cr_account'] = trans($GetFilterData['Data'][$x]['cr_account']);
			$GetFilterData['Data'][$x]['payer_name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['date'] = getdatetime($GetFilterData['Data'][$x]['date'],3);
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetExpenseReportData":
		$searchSelect = array(
		"accounts_transactions.payer_name",
		);
		$rowSelect = array(
		"accounts_transactions.id",
		"accounts_transactions.amount",
		"accounts_transactions.dr_account",
		"accounts_transactions.date",
		"accounts_transactions.account_status",
		"accounts_transactions.payment_method",
		"accounts_transactions.created_at",
		"accounts_transactions.user_id",
		"accounts_transactions.note",
		"pos_contact.name",
		);
		$selectWhere = array(
		"accounts_transactions.account_type" => "account_expense",
		"accounts_transactions.is_delete" => "false"
		);
		$jointWhere = array(
		"accounts_transactions.payer_name" => "pos_contact.contact_id"
		);
		$selectNotWhere = array();
		$tableSelect = array("accounts_transactions","pos_contact");
		
		if(isset($_POST['account_name']) && $_POST['account_name'] != '0'){
			$selectWhere['accounts_transactions.dr_account'] = $_POST['account_name'];
		}
		
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$selectWhere['accounts_transactions.store_id'] = $_POST['store_id'];
		}
		
		if(isset($_POST['account_for']) && $_POST['account_for'] != '0'){
			$selectWhere['accounts_transactions.payer_name'] = $_POST['account_for'];
		}
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {			
			$GetFilterData['Data'][$x]['dr_account'] = trans($GetFilterData['Data'][$x]['dr_account']);
			$GetFilterData['Data'][$x]['payer_name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['date'] = getdatetime($GetFilterData['Data'][$x]['date'],3);
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetAccountChartReportData":
		
		$searchSelect = array(
		"accounts_transactions.payer_name",
		);
		$rowSelect = array(
		"accounts_transactions.id",
		"accounts_transactions.amount",
		"accounts_transactions.dr_account",
		"accounts_transactions.date",
		"accounts_transactions.account_status",
		"accounts_transactions.payment_method",
		"accounts_transactions.created_at",
		"accounts_transactions.user_id",
		"accounts_transactions.note",
		"pos_contact.name",
		);
		$selectWhere = array(
		// "accounts_transactions.account_type" => "account_expense",
		"accounts_transactions.dr_account" => $_POST['account_name'],
		"accounts_transactions.is_delete" => "false"
		);
		$jointWhere = array(
		"accounts_transactions.payer_name" => "pos_contact.contact_id"
		);
		$selectNotWhere = array();
		$tableSelect = array("accounts_transactions","pos_contact");
		
		if(isset($_POST['account_name']) && $_POST['account_name'] != '0'){
			$selectWhere['accounts_transactions.dr_account'] = $_POST['account_name'];
		}
		
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$selectWhere['accounts_transactions.store_id'] = $_POST['store_id'];
		}
		
		if(isset($_POST['account_for']) && $_POST['account_for'] != '0'){
			$selectWhere['accounts_transactions.payer_name'] = $_POST['account_for'];
		}
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {			
			$GetFilterData['Data'][$x]['dr_account'] = trans($GetFilterData['Data'][$x]['dr_account']);
			$GetFilterData['Data'][$x]['payer_name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['date'] = getdatetime($GetFilterData['Data'][$x]['date'],3);
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetPurchaseListData":
		
		$searchSelect = array(
		"pos_purchase.purchase_id",
		"pos_contact.name"
		);
		
		$rowSelect = array(
		"pos_purchase.id",
		"pos_purchase.purchase_id",
		"pos_purchase.supplier_id",
		"pos_purchase.purchase_status",
		"pos_purchase.purchase_payment_status",
		"pos_purchase.purchase_total",
		"pos_contact.contact_id",
		"pos_contact.name"
		);
		
		$selectWhere = array("pos_purchase.is_delete" => "false");
		$jointWhere = array(
		"pos_purchase.supplier_id" => "pos_contact.contact_id"
		);
		
		$selectNotWhere = array("pos_purchase.purchase_status" => "cancel");
		$tableSelect = array("pos_purchase","pos_contact");
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			
			$GetFilterData['Data'][$x]['id'] = $x+1;
			
			$GetFilterData['Data'][$x]['pos_contact/name'] = $GetFilterData['Data'][$x]['name'];
			
			$getPaymentInfo = app('pos')->GetPurchaseByCustomerOrder(false,$GetFilterData['Data'][$x]['purchase_id']);
			if($getPaymentInfo['total_due']!=null){
				$GetFilterData['Data'][$x]['total_due'] = $getPaymentInfo['total_due'];
			}
			else{
				$GetFilterData['Data'][$x]['total_due']='N/A';
			}
			if($getPaymentInfo['total_return']!=null){
				$GetFilterData['Data'][$x]['total_return'] = $getPaymentInfo['total_return'];
			}
			else{
				$GetFilterData['Data'][$x]['total_return']='N/A';
			}
			if($getPaymentInfo['total_return_due']!=null){
				$GetFilterData['Data'][$x]['total_return_due'] = $getPaymentInfo['total_return_due'];
			}
			else{
				$GetFilterData['Data'][$x]['total_return_due']="N/A";
			}
			$GetFilterData['Data'][$x]['net_purchase'] = $getPaymentInfo['net_purchase'];
		}
		
		$response = array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']);
		echo json_encode($response);
		break;
		
		case "GetProductListData":
		$searchSelect = array("pos_product.product_name","pos_product.product_id");
		$rowSelect = array(
		"pos_product.id",
		"pos_product.product_id",
		"pos_product.created_at",
		"pos_product.product_image",
		"pos_product.product_vat_type",
		"pos_product.product_vat",
		"pos_product.product_name",
		"pos_product.category_id",
		"pos_product.brand_id",
		"pos_product.unit_id",
		"pos_product.user_id",
		"pos_product.product_type",
		"pos_product.product_featured",
		"pos_category.category_id",
		"pos_category.category_name",
		"pos_unit.unit_id",
		"pos_unit.unit_name",
		"pos_brands.brand_id",
		"pos_brands.brand_name",
		);
		$jointWhere = array(
		"pos_product.category_id" => "pos_category.category_id",
		"pos_product.brand_id" => "pos_brands.brand_id",
		"pos_product.unit_id" => "pos_unit.unit_id",
		);
		$tableSelect = array("pos_product","pos_category","pos_unit","pos_brands");
		$selectWhere = array("pos_product.is_delete" => "false");
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $x+1;
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['pos_category/category_name'] = $GetFilterData['Data'][$x]['category_name'];
			$GetFilterData['Data'][$x]['pos_unit/unit_name'] = $GetFilterData['Data'][$x]['unit_name'];
			$GetFilterData['Data'][$x]['pos_brands/brand_name'] = $GetFilterData['Data'][$x]['brand_name'];
			
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],4).'<br>'.getdatetime($GetFilterData['Data'][$x]['created_at'],8);
			
			$exists_product=app('db')->table('pos_stock')->where('product_id',$GetFilterData['Data'][$x]['product_id'])->where('is_delete','false')->get(1);
			
			if($exists_product){
				$GetFilterData['Data'][$x]['exists'] = "exists";
				}else{
				$GetFilterData['Data'][$x]['exists'] = "not_exists";
			}
			$imageLocation = "images/stores/".$_SERVER['SERVER_NAME'].'/'.$GetFilterData['Data'][$x]['product_image'];
			if(!empty($GetFilterData['Data'][$x]['product_image']) && file_exists($imageLocation) ){
				$GetFilterData['Data'][$x]['product_image'] = "images/stores/".$_SERVER['SERVER_NAME'].'/'.$GetFilterData['Data'][$x]['product_image'];
				}else{
				$GetFilterData['Data'][$x]['product_image'] = "assets/img/no-image.png";
			}
			
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetCustomerData":
		
		$searchSelect = array("pos_contact.name","pos_contact.contact_id");
		$rowSelect = array(
		"pos_contact.contact_id",
		"pos_contact.contact_type",
		"pos_contact.name",
		"pos_contact.address",
		"pos_contact.phone",
		"pos_contact.email",
		"pos_contact.user_id",
		"pos_contact.contact_status",
		);
		$jointWhere = array();
		$tableSelect = array("pos_contact");
		$selectWhere = array("pos_contact.contact_type" => "customer","pos_contact.is_delete" => "false");
		$selectNotWhere = array("pos_contact.contact_status" => 'inactive');
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $x+1;
			$GetContactInfo = app('pos')->GetSalesByCustomerOrder($GetFilterData['Data'][$x]['contact_id']);
			$GetFilterData['Data'][$x]['total_sales'] = $GetContactInfo['total_sales'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			
			if(app('admin')->checkAddon('due_sale')){
				$GetFilterData['Data'][$x]['due_sale'] = $GetContactInfo['total_due'];
			}
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		case "GetTrialBalanceData":
		
		$startDate = $_POST['from_data'];
		$endDate = $_POST['to_data'];
		$whereBeteween = " AND date(`accounts_transactions`.`date`) BETWEEN '$startDate' AND '$endDate' ";
		
		$whereStore = '';
		
		$CapitalAmount = app('pos')->getCapitalAmount($_POST,false);
		$GetClosingStock = app('pos')->getStockAmount($_POST,false);
		
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$store =  $_POST['store_id'];
			$whereStore = " AND `accounts_transactions`.`store_id` = '$store'";
		}
		
		$GetStockSales = app('db')->table('pos_sales')->sum('sales_total')->sum('sales_vat')->sum('sales_subtotal')->sum('sales_discount')->sum('shipping_charge')->where('sales_status','!=',"cancel")->where('is_delete',"false")->between('created_at',$startDate,$endDate,true);
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$GetStockSales = $GetStockSales->where('store_id',$_POST['store_id']);
		}
		$GetStockSales = $GetStockSales->get(1);
		
		$GetStockSalesReturn = app('db')->table('pos_return')->sum('return_total')->sum('return_vat')->sum('return_discount')->sum('return_subtotal')->sum('return_sales_purchase_total')->where('return_type',"sales")->where('return_status','!=',"cancel")->where('is_delete',"false")->between('created_at',$startDate,$endDate,true);
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$GetStockSalesReturn = $GetStockSalesReturn->where('store_id',$_POST['store_id']);
		}
		$GetStockSalesReturn = $GetStockSalesReturn->get(1);
		
		$GetStockPurchase = app('db')->table('pos_purchase')->sum('purchase_total')->sum('purchase_discount')->sum('purchase_shipping_charge')->sum('purchase_subtotal')->where('purchase_status','!=',"cancel")->where('is_delete',"false")->between('purchase_date',$startDate,$endDate,true);
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$GetStockPurchase = $GetStockPurchase->where('store_id',$_POST['store_id']);
		}
		$GetStockPurchase = $GetStockPurchase->get(1);
		
		$GetStockPurchaseReturn = app('db')->table('pos_return')->sum('return_total')->sum('return_vat')->sum('return_discount')->sum('return_subtotal')->where('return_type',"purchase")->where('return_status','!=',"cancel")->where('is_delete',"false")->between('created_at',$startDate,$endDate,true);
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$GetStockPurchaseReturn = $GetStockPurchaseReturn->where('store_id',$_POST['store_id']);
		}
		$GetStockPurchaseReturn = $GetStockPurchaseReturn->get(1);
		
		$searchSelect = array("chart_name","chart_name_value");
		$rowSelect = array(
		"chart_no",
		"chart_name",
		"chart_name_value",
		"chart_type",
		"account_group",
		"chart_sub_category_name",
		);
		$tableSelect = "accounts_chart";
		$selectWhere = array("is_delete" => "false");
		$selectNotWhere = array();
		
		$selectRawPosTnx = ", COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'credit' AND `payment_method_value` = 'cash' THEN transaction_amount END), 0) AS `pos_txn_cash` ";
		$selectRawPosTnx .= ", COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'credit' AND `payment_method_value` != 'cash' THEN transaction_amount END), 0) AS `pos_txn_bank` ";
		$GetPosTxn = app('db')->table('pos_transactions')->col('transaction_status')->where('transaction_status',"paid")->where('is_delete',"false")->get(1,$selectRawPosTnx);
		
		
		$selectRaw = "";
		$whereRaw = " ";
		$selectRaw .= ", (SELECT COALESCE(SUM((CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`dr_account` = `accounts_chart`.`chart_name_value` THEN amount END)),0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_type` = 'debit'  $whereBeteween $whereStore ) AS `current_debit_balance` ";
		$selectRaw .= ", (SELECT COALESCE(SUM((CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`cr_account` = `accounts_chart`.`chart_name_value` THEN amount END)),0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_type` = 'credit' $whereBeteween $whereStore ) AS `current_credit_balance` ";
		
		$GetFilterData = app('admin')->GetFilterData($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$selectNotWhere,$selectRaw,$whereRaw);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $GetFilterData['Data'][$x]['chart_no'];
			$GetFilterData['Data'][$x]['chart_name'] = trans($GetFilterData['Data'][$x]['chart_name']);
			if($GetFilterData['Data'][$x]['current_debit_balance'] != '0.00'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetFilterData['Data'][$x]['current_debit_balance'];
				}elseif($GetFilterData['Data'][$x]['current_credit_balance'] != '0.00'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetFilterData['Data'][$x]['current_credit_balance'];
				}else{
				$GetFilterData['Data'][$x]['current_balance'] = '0.00';
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_total_sale'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockSales['sales_subtotal'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_total_sale'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockSales['sales_subtotal'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_vat'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockSales['sales_vat'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_sale_discount'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockSales['sales_discount'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_sale_return'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockSalesReturn['return_subtotal'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_sale_return_discount'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockSalesReturn['return_discount'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_total_purchase'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockPurchase['purchase_subtotal'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_purchase_discount'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockPurchase['purchase_discount'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_purchase_return'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockPurchaseReturn['return_subtotal'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_purchase_return_discount'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetStockPurchaseReturn['return_discount'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_cash'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetFilterData['Data'][$x]['current_balance'] + $GetPosTxn['pos_txn_cash'];
			}
			
			if($GetFilterData['Data'][$x]['chart_name_value'] == 'account_bank'){
				$GetFilterData['Data'][$x]['current_balance'] = $GetFilterData['Data'][$x]['current_balance'] + $GetPosTxn['pos_txn_bank'];
			}
			
			if($GetFilterData['Data'][$x]['chart_type'] && $GetFilterData['Data'][$x]['chart_type'] == 'debit'){
				$GetFilterData['Data'][$x]['current_balance_debit'] = $GetFilterData['Data'][$x]['current_balance'];
				$GetFilterData['Data'][$x]['current_balance_credit'] = '-';
				}elseif($GetFilterData['Data'][$x]['chart_type'] && $GetFilterData['Data'][$x]['chart_type'] == 'credit'){
				$GetFilterData['Data'][$x]['current_balance_debit'] = '-';
				$GetFilterData['Data'][$x]['current_balance_credit'] = $GetFilterData['Data'][$x]['current_balance'];
				}else{
				$GetFilterData['Data'][$x]['current_balance_debit'] = '-';
				$GetFilterData['Data'][$x]['current_balance_credit'] = '-';
			}
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case 'GetCustomerDetailsData':
		
		$searchSelect = array("pos_sales.sales_id","pos_sales.created_at");
		$rowSelect = array(
		"pos_sales.sales_id",
		"pos_sales.customer_id",
		"pos_sales.store_id",
		"pos_sales.user_id",
		"pos_sales.sales_total",
		"pos_sales.sales_status",
		"pos_sales.created_at",
		"pos_sales.is_delete",
		);
		$jointWhere = array();
		$tableSelect = array("pos_sales");
		$selectWhere = array("pos_sales.customer_id" => $_POST['contact_id'],"pos_sales.is_delete" => 'false');
		$selectNotWhere = array('pos_sales.sales_status'=> 'cancel');
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],2);
			$GetSalesInfo = app('pos')->GetSalesByCustomerOrder(false,$GetFilterData['Data'][$x]['sales_id']);
			$GetFilterData['Data'][$x]['due'] = $GetSalesInfo['total_due'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		case 'GetAccountTransferData':
		
		$searchSelect = array("dr_account","cr_account","created_at");
		$rowSelect = array(
		"id",
		"user_id",
		"dr_account",
		"cr_account",
		"amount",
		"payment_method",
		"from_payment_method",
		"note",
		"created_at",
		"is_delete",
		);
		$jointWhere = array();
		$tableSelect = "accounts_transactions";
		$selectWhere = array(
		"is_delete" => 'false',
		'cr_account' => 'account_transfer',
		'account_type' => 'account_transfer'
		);
		$selectNotWhere = array();
		$selectRow =", (SELECT (SELECT `pos_payment_method`.`payment_method_name` FROM `pos_payment_method` WHERE `pos_payment_method`.`payment_method_value` = `t2`.`payment_method`) FROM `accounts_transactions` as t2 WHERE `t2`.`is_delete` = 'false' AND `t2`.`id` = `accounts_transactions`.`from_payment_method`) AS from_payment_method_name ";
		$selectRow .=", (SELECT `pos_payment_method`.`payment_method_name` FROM `pos_payment_method` WHERE `pos_payment_method`.`payment_method_value` = `accounts_transactions`.`payment_method`) as 'to_payment_method_name' ";
		$GetFilterData = app('admin')->GetFilterData($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$selectNotWhere,$selectRow);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],2);
			// $GetFilterData['Data'][$x]['dr_account'] = trans($GetFilterData['Data'][$x]['dr_account']);
			// $GetFilterData['Data'][$x]['cr_account'] = trans($GetFilterData['Data'][$x]['cr_account']);
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetCapitalData":
		if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])){
			$postSearch = strtolower($_POST['search']['value']);
			$postSearch = str_replace(" ", "_", $_POST['search']['value']);
			$_POST['search']['value'] = 'account_'.$postSearch;
		}
		
		$capitalType = "account_capital_cash";
		if(isset($_POST['capital_type'])){
			if($_POST['capital_type'] == 'bank'){
				$capitalType = "account_capital_bank";
				}elseif($_POST['capital_type'] == 'assets'){
				$capitalType = "account_capital_assets";
				}elseif($_POST['capital_type'] == 'loan'){
				$capitalType = "account_capital_loans";
			}
		}
		
		$searchSelect = array(
		"accounts_transactions.dr_account",
		"accounts_transactions.cr_account",
		);
		$rowSelect = array(
		"accounts_transactions.id",
		"accounts_transactions.amount",
		"accounts_transactions.dr_account",
		"accounts_transactions.date",
		"accounts_transactions.account_status",
		"accounts_transactions.payment_method",
		"accounts_transactions.created_at",
		"accounts_transactions.user_id",
		"accounts_transactions.note",
		"pos_contact.name",
		"pos_contact.contact_id",
		);
		$selectWhere = array(
		"accounts_transactions.account_type" => $capitalType,
		"accounts_transactions.is_delete" => "false"
		);
		
		
		$jointWhere = array(
		"accounts_transactions.payer_name" => "pos_contact.contact_id"
		);
		$selectNotWhere = array();
		$tableSelect = array("accounts_transactions","pos_contact");
		
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$selectWhere['accounts_transactions.store_id'] = $_POST['store_id'];
		}
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {			
			$GetFilterData['Data'][$x]['dr_account'] = trans($GetFilterData['Data'][$x]['dr_account']);
			$GetFilterData['Data'][$x]['payer_name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['date'] = getdatetime($GetFilterData['Data'][$x]['date'],3);
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetSupplierCustomerReportData":
		$searchSelect = array("pos_contact.name","pos_contact.contact_id");
		$rowSelect = array(
		"pos_contact.id",
		"pos_contact.contact_id",
		"pos_contact.contact_type",
		"pos_contact.name",
		"pos_contact.address",
		"pos_contact.phone",
		"pos_contact.email",
		"pos_contact.user_id",
		"pos_contact.contact_status",
		);
		$jointWhere = array();
		$tableSelect = array("pos_contact");
		$selectWhere = array("pos_contact.is_delete"=>"false");
		$selectNotWhere = array();
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$PurchaseOrder = app('pos')->GetPurchaseByCustomerOrder($GetFilterData['Data'][$x]['contact_id']);
			$SalesOrder = app('pos')->GetSalesByCustomerOrder($GetFilterData['Data'][$x]['contact_id']);
			if($PurchaseOrder['total_purchase']==null){
				$GetFilterData['Data'][$x]['total_purchase'] = 0;
			}
			else{
				$GetFilterData['Data'][$x]['total_purchase'] = $PurchaseOrder['total_purchase'];
			}
			if($SalesOrder['total_sales']==null){
				$GetFilterData['Data'][$x]['total_sales'] = 0;
			}
			else{
				$GetFilterData['Data'][$x]['total_sales'] = $SalesOrder['total_sales'];
			}
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetSupplierData":
		$searchSelect = array("pos_contact.name","pos_contact.contact_id");
		$rowSelect = array(
		"pos_contact.contact_id",
		"pos_contact.contact_type",
		"pos_contact.name",
		"pos_contact.business_name",
		"pos_contact.website_name",
		"pos_contact.address",
		"pos_contact.phone",
		"pos_contact.email",
		"pos_contact.user_id",
		"pos_contact.contact_status",
		);
		$jointWhere = array();
		$tableSelect = array("pos_contact");
		
		$selectWhere = array("pos_contact.contact_type" => "supplier","pos_contact.is_delete" => "false");
		$selectNotWhere = array("pos_contact.contact_status" => "inactive");
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $x+1;
			$GetContactInfo = app('pos')->GetPurchaseByCustomerOrder($GetFilterData['Data'][$x]['contact_id']);
			$GetFilterData['Data'][$x]['sales_total'] = $GetContactInfo['total_purchase'];
			$GetFilterData['Data'][$x]['total_due'] = $GetContactInfo['total_due'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case 'GetContactData':
		
		$searchSelect = array("pos_purchase.purchase_id","pos_purchase.purchase_date");
		$rowSelect = array(
		"pos_purchase.supplier_id",
		"pos_purchase.purchase_id",
		"pos_purchase.purchase_date",
		"pos_purchase.purchase_reference_no",
		"pos_purchase.purchase_status",
		"pos_purchase.purchase_payment_status",
		"pos_purchase.purchase_total",
		"pos_purchase.user_id",
		"pos_purchase.is_delete",
		);
		$jointWhere = array();
		$tableSelect = array("pos_purchase");
		
		$selectWhere = array("pos_purchase.supplier_id" => $_POST['contact_id'],"pos_purchase.is_delete" => 'false');
		$selectNotWhere = array("pos_purchase.purchase_status" => 'cancel');
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetPurchaseInfo = app('pos')->GetPurchaseByCustomerOrder(false,$GetFilterData['Data'][$x]['purchase_id']);
			$GetFilterData['Data'][$x]['payment_due'] = $GetPurchaseInfo['total_due'];
			$GetFilterData['Data'][$x]['purchase_payment_status'] = strtoupper($GetFilterData['Data'][$x]['purchase_payment_status']);
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;	
		
		case "GetUniteData":
		$searchSelect = array("pos_unit.unit_id","pos_unit.unit_name");
		$rowSelect =array(
		"pos_unit.unit_id",
		"pos_unit.unit_name",
		"pos_unit.created_at",
		"pos_unit.updated_at",
		"pos_unit.user_id",
		);
		$jointWhere = array();
		$tableSelect = array("pos_unit");
		$selectWhere = array();
		$selectNotWhere = array();
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);		
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'].' ';
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],6);
			$GetFilterData['Data'][$x]['updated_at'] = getdatetime($GetFilterData['Data'][$x]['updated_at'],6);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetBrandData":
		$searchSelect = array("pos_brands.brand_id","pos_brands.brand_name");
		$rowSelect =array(
		"pos_brands.brand_id",
		"pos_brands.brand_name",
		"pos_brands.created_at",
		"pos_brands.update_at",
		"pos_brands.user_id",
		);
		$jointWhere = array();
		$tableSelect = array("pos_brands");
		$selectWhere = array();
		$selectNotWhere = array();
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);		
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'].' ';
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],6);
			$GetFilterData['Data'][$x]['update_at'] = getdatetime($GetFilterData['Data'][$x]['update_at'],6);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetCategoryData":
		$searchSelect = array("pos_category.category_id","pos_category.category_name");
		$rowSelect =array(
		"pos_category.category_id",
		"pos_category.category_name",
		"pos_category.created_at",
		"pos_category.updated_at",
		"pos_category.user_id",
		);
		$jointWhere = array();
		$tableSelect = array("pos_category");
		$selectWhere = array();
		$selectNotWhere = array();
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);		
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'].' ';
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],6);
			$GetFilterData['Data'][$x]['updated_at'] = getdatetime($GetFilterData['Data'][$x]['updated_at'],6);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetPosSaleList":
		
		$searchSelect = array("pos_sales.sales_id");
		$rowSelect = array(
		"pos_sales.id",
		"pos_sales.sales_id",
		"pos_sales.sales_type",
		"pos_sales.sales_status",
		"pos_sales.sales_pay_change",
		"pos_sales.customer_id",
		"pos_sales.sales_total",
		"pos_sales.sales_vat",
		"pos_sales.user_id",
		"pos_sales.created_at",
		"pos_sales.sales_payment_status",
		);
		$jointWhere = array();
		$tableSelect = array("pos_sales");
		$selectWhere = array("pos_sales.sales_type" => "pos","pos_sales.is_delete" => "false");
		$selectNotWhere = array("pos_sales.sales_status" => "cancel");
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$getTransactionInfo = app('pos')->GetSalesByCustomerOrder(false,$GetFilterData['Data'][$x]['sales_id']);
			$getContactInfo=app('admin')->getwhereid('pos_contact','contact_id',$GetFilterData['Data'][$x]['customer_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['customer_id']=$getContactInfo['name'];
			$GetFilterData['Data'][$x]['sales_vat'] = $GetFilterData['Data'][$x]['sales_total'] - $GetFilterData['Data'][$x]['sales_vat'];
			$GetFilterData['Data'][$x]['total_paid'] = $getTransactionInfo['total_paid'];
			$GetFilterData['Data'][$x]['total_due']=$getTransactionInfo['total_due'];
			$GetFilterData['Data'][$x]['total_return']=$getTransactionInfo['total_return'];
			if($GetFilterData['Data'][$x]['total_due'] == 0){
				$GetFilterData['Data'][$x]['total_due'] = 0;
			}
			if($GetFilterData['Data'][$x]['total_return'] == 0){
				$GetFilterData['Data'][$x]['total_return'] = 0;
			}
			
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetVatData":
		$searchSelect = array("pos_sales.sales_id","pos_sales.customer_id");
		$rowSelect = array(
		"pos_sales.id",
		"pos_sales.sales_id",
		"pos_sales.sales_vat",
		"pos_sales.customer_id",
		"pos_sales.user_id",
		"pos_sales.customer_id",
		"pos_sales.created_at",
		"pos_sales.sales_status",
		);
		$jointWhere = array();
		$tableSelect = array("pos_sales");
		$selectWhere = array("pos_sales.sales_status" => "complete");
		$selectNotWhere = array();
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetCustomerInfo = app('admin')->getwhereid('pos_contact','contact_id',$GetFilterData['Data'][$x]['customer_id']);			
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
			$GetFilterData['Data'][$x]['customer_id'] = $GetCustomerInfo['name'];
			
		}
		
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;;
		
		case "GetPaidVatData":
		
		$searchSelect = array("pos_transactions.transaction_id","pos_transactions.transaction_status");
		$rowSelect = array(
		"pos_transactions.transaction_id",
		"pos_transactions.transaction_amount",
		"pos_transactions.payment_method_value",
		"pos_transactions.user_id",
		"pos_transactions.paid_date",
		"pos_transactions.transaction_note",
		"pos_transactions.transaction_status",
		);
		$jointWhere = array();
		$tableSelect = array("pos_transactions");
		$selectWhere = array("pos_transactions.transaction_type" => "vat");
		$selectNotWhere = array();
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetVariationData":
		$searchSelect = array("pos_variations_category.name","pos_variations_category.variation_category_value");
		$rowSelect = array(
		"pos_variations_category.variation_category_id",
		"pos_variations_category.variation_category_name",
		"pos_variations_category.variation_category_value",
		);
		$jointWhere = array();
		$tableSelect = array("pos_variations_category");
		$selectWhere = array();
		$selectNotWhere = array();
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetStockReport":
		
		$searchSelect = [
		"pos_product.product_id",
		"pos_product.product_name"
		];
		$rowSelect = [
		"pos_unit.unit_id",
		"pos_unit.unit_name",
		"pos_product.product_name",
		"pos_product.product_id",
		"pos_product.brand_id",
		"pos_product.unit_id",
		"pos_product.category_id",
		"pos_product.product_name"
		];
		$selectWhere = [
		"pos_product.brand_id" => $_POST["brand_id"], 
		"pos_product.unit_id" => $_POST["unit_id"], 
		"pos_product.category_id" => $_POST["category_id"],
		"pos_product.is_delete" => 'false',
		];
		$jointWhere = ["pos_product.unit_id" => "pos_unit.unit_id"];
		$selectNotWhere = [];
		$tableSelect = ["pos_product","pos_unit"];
		
		if(!isset($_POST["store_id"])){
			$_POST["store_id"] = null;
		}
		
		if(app('admin')->checkAddon('serial_product')){
			$rowSelect[] = 'pos_product.product_serial';
		}
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$x = 0;
		$FilterData = [];
		
		foreach($GetFilterData['Data'] as $GetFilterDatas){
			
			$getVariables = app('admin')->getwhere("pos_variations","product_id",$GetFilterDatas['product_id']);	
			foreach($getVariables as $getVariable){
				$GetProductStock = app('pos')->GetProductAvaliableStock($_POST["store_id"], $getVariable['sub_product_id']);
				if($GetProductStock['product_purchase']==null){
					$GetProductStock['product_purchase']=0;
				}
				if($GetProductStock['product_sale']==null){
					$GetProductStock['product_sale']=0;
				}
				$FilterData[$x]['product_id'] = $getVariable['sub_product_id'];
				if(!empty($getVariable['variation_name'])){
					$FilterData[$x]['product_name'] = $GetFilterDatas['product_name'].' ['.$getVariable['variation_name'].']';
					}else{
					$FilterData[$x]['product_name'] = $GetFilterDatas['product_name'];
				}
				
				
				if(app('admin')->checkAddon('serial_product')){
					if($GetFilterDatas['product_serial']=='enable'){
						$view_serial = '<a href="javascript:void(0)" class="view_serial" product_id="'.$GetFilterDatas['product_id'].'" ><i class="fa fa-eye"></i></a>';
						$FilterData[$x]['product_name'] = $FilterData[$x]['product_name'].' '.$view_serial;
					}
				}
				
				$FilterData[$x]['current_stock'] = $GetProductStock['product_stock'].' '.$GetFilterDatas['unit_name'].' ';
				$FilterData[$x]['total_purchase'] = $GetProductStock['product_purchase'].' '.$GetFilterDatas['unit_name'].' ';
				$FilterData[$x]['total_sale'] = $GetProductStock['product_sale'].' '.$GetFilterDatas['unit_name'].' ';
				$x++;
			}
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $FilterData));
		break;
		
		case "GetProductSalesReportData":
		if(!isset($_POST["store_id"])){
			$_POST["store_id"] = null;
		}
		$searchSelect = array("pos_stock.sales_id","pos_contact.name","pos_product.product_id","pos_product.product_name");
		$rowSelect =array("pos_unit.unit_id","pos_unit.unit_name","pos_variations.sub_product_id","pos_variations.variation_name","pos_stock.sub_product_id", "pos_product.product_id","pos_product.product_name","pos_contact.contact_id","pos_contact.name","pos_stock.product_id","pos_stock.store_id","pos_stock.stock_category","pos_stock.sales_id","pos_stock.product_subtotal","pos_stock.product_price","pos_stock.product_quantity","pos_stock.product_discount","pos_stock.product_vat_total","pos_stock.created_at","pos_stock.customer_id");
		$selectWhere = array("pos_stock.stock_category" => "sales", "pos_stock.customer_id" => $_POST["customer_id"],"pos_stock.store_id" => $_POST["store_id"],
		"pos_stock.is_delete" => "false"
		);
		$jointWhere = array("pos_stock.sub_product_id" => "pos_variations.sub_product_id","pos_stock.product_id" => "pos_product.product_id","pos_stock.customer_id" => "pos_contact.contact_id","pos_unit.unit_id" => "pos_product.unit_id");
		$selectNotWhere =array();
		$tableSelect = array("pos_stock","pos_product","pos_contact","pos_variations","pos_unit");
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			if(!empty($GetFilterData['Data'][$x]['variation_name'])){
				$GetFilterData['Data'][$x]['pos_product/product_name'] = $GetFilterData['Data'][$x]['product_name'].' ['.$GetFilterData['Data'][$x]['variation_name'].']';
				}else{
				$GetFilterData['Data'][$x]['pos_product/product_name'] = $GetFilterData['Data'][$x]['product_name'];
			}
			$GetFilterData['Data'][$x]['product_quantity'] = $GetFilterData['Data'][$x]['product_quantity'].' '.$GetFilterData['Data'][$x]['unit_name'].' ';
			$GetFilterData['Data'][$x]['pos_contact/name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetPurchaseReportData":
		if(!isset($_POST["store_id"])){
			$_POST["store_id"] = null;
		}
		$searchSelect = array("pos_stock.purchase_id","pos_contact.name","pos_product.product_id","pos_product.product_name");
		$rowSelect = array("pos_variations.sub_product_id","pos_variations.variation_name","pos_stock.sub_product_id", "pos_product.product_id","pos_product.product_name","pos_contact.contact_id","pos_contact.name","pos_stock.product_id","pos_stock.store_id","pos_stock.stock_category","pos_stock.purchase_id","pos_stock.product_subtotal","pos_stock.product_price","pos_stock.product_quantity","pos_stock.created_at","pos_stock.supplier_id");
		$selectWhere = array("pos_stock.stock_category" => "purchase", "pos_stock.supplier_id" => $_POST["supplier_id"],"pos_stock.store_id"=>$_POST["store_id"],
		"pos_stock.is_delete" => "false"
		);
		$jointWhere = array("pos_stock.sub_product_id" => "pos_variations.sub_product_id","pos_stock.product_id" => "pos_product.product_id","pos_stock.supplier_id" => "pos_contact.contact_id");
		$selectNotWhere = array();
		$tableSelect = array("pos_stock","pos_product","pos_contact","pos_variations");
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			if(!empty($GetFilterData['Data'][$x]['variation_name'])){
				$GetFilterData['Data'][$x]['pos_product/product_name'] = $GetFilterData['Data'][$x]['product_name'].' ['.$GetFilterData['Data'][$x]['variation_name'].']';
				}else{
				$GetFilterData['Data'][$x]['pos_product/product_name'] = $GetFilterData['Data'][$x]['product_name'];
				
			}
			$GetFilterData['Data'][$x]['pos_contact/name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetVatReportData":
		if(!isset($_POST["store_id"])){
			$_POST["store_id"] = null;
		}
		$searchSelect = array("pos_stock.sub_product_id","pos_stock.sales_id","pos_stock.customer_id","pos_stock.created_at");
		$rowSelect = array(
		"pos_stock.sub_product_id",
		"pos_stock.sales_id",
		"pos_stock.customer_id",
		"pos_stock.created_at",
		"pos_stock.product_vat",
		"pos_contact.name"
		);
		$selectWhere = array("pos_stock.stock_category" => "sales", "pos_stock.customer_id" => $_POST["customer"], "pos_stock.sub_product_id" => $_POST["product"], "pos_stock.store_id" => $_POST["store_id"], "pos_stock.is_delete" => "false");
		$jointWhere = array("pos_stock.customer_id" => "pos_contact.contact_id");
		$selectNotWhere = array();
		$tableSelect = array("pos_stock","pos_contact");
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {			
			$GetFilterData['Data'][$x]['product_id'] = $GetFilterData['Data'][$x]['sub_product_id'];
			$GetFilterData['Data'][$x]['customer_id'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetUserData":
		
		$searchSelect = [
		"as_users.username",
		"as_users.email",
		"as_user_details.first_name",
		"as_user_details.last_name",
		"as_user_details.phone",
		];
		$rowSelect = [
		"as_users.banned",
		"as_users.username",
		"as_users.email",
		"as_users.user_id",
		"as_users.user_role",
		"as_user_details.user_id",
		"as_user_details.first_name",
		"as_user_details.last_name",
		"as_user_details.phone",
		];
		$selectWhere = [
		"as_user_details.added_by" => $this->currentUser->id, 
		"as_users.user_role" => '1', 
		];
		$jointWhere = ["as_users.user_id" => "as_user_details.user_id"];
		$selectNotWhere = [];
		$tableSelect = ["as_users","as_user_details"];
		
		$GetFilterData = app('root')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetStoreData":
		$searchSelect = [
		"pos_store.store_id",
		"pos_store.store_name",
		];
		$rowSelect = [
		"pos_store.store_id",
		"pos_store.store_name",
		"pos_store.warehouse_id",
		"pos_store.store_location",
		"pos_store.store_status",
		"pos_store.created_at",
		"pos_store.updated_at",
		"pos_store.user_id",
		];
		$selectWhere = [];
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = ["pos_store"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetWarehouseInfo = app('admin')->getwhereid('pos_warehouse','warehouse_id',$GetFilterData['Data'][$x]['warehouse_id']);
			$GetFilterData['Data'][$x]['warehouse'] = $GetWarehouseInfo['warehouse_name'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],6);
			$GetFilterData['Data'][$x]['updated_at'] = getdatetime($GetFilterData['Data'][$x]['updated_at'],6);
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		case "GetWarehouseData":
		$searchSelect = [
		"pos_warehouse.warehouse_id",
		"pos_warehouse.warehouse_name"
		];
		$rowSelect = [
		"pos_warehouse.warehouse_id",
		"pos_warehouse.warehouse_name",
		"pos_warehouse.warehouse_status",
		"pos_warehouse.warehouse_location",
		"pos_warehouse.created_at",
		"pos_warehouse.updated_at",
		"pos_warehouse.user_id",
		];
		$selectWhere = [];
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = ["pos_warehouse"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],6);
			$GetFilterData['Data'][$x]['updated_at'] = getdatetime($GetFilterData['Data'][$x]['updated_at'],6);
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetAccountChartData":
		$searchSelect = [
		"accounts_chart.chart_no",
		"accounts_chart.chart_name"
		];
		$rowSelect = [
		"accounts_chart.id",
		"accounts_chart.chart_no",
		"accounts_chart.chart_name",
		"accounts_chart.chart_status",
		"accounts_chart.chart_type",
		"accounts_chart.user_id",
		];
		$selectWhere = ['accounts_chart.is_delete' => false ];
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = ["accounts_chart"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['added_by'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetIncomeData":
		
		$postSearch = strtolower($_POST['search']['value']);
		$postSearch = str_replace(" ", "_", $_POST['search']['value']);
		$_POST['search']['value'] = 'account_'.$postSearch;
		
		$searchSelect = [
		"chart_name"
		];
		
		$rowSelect = [
		"chart_name_value",
		"chart_name",
		"created_at",
		];
		
		$selectWhere = [
		// "chart_sub_category_name" => 'account_income'
		];
		
		$whereRaw = "";
		// $whereRaw = " AND FIND_IN_SET('account_group', 'account_income') ";
		
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = "accounts_chart";
		
		$startDate = $_POST['from_data'];
		$endDate = $_POST['to_data'];
		$store = $_POST['store_id'];
		$accountfor = $_POST['account_for'];
		$whereStore = '';
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$whereStore = " AND `accounts_transactions`.`store_id` = '$store'";
		}
		
		$whereBeteween = " AND date(`accounts_transactions`.`date`) BETWEEN '$startDate' AND '$endDate'";
		
		$whereContact = '';
		if(isset($_POST['account_for']) && $_POST['account_for'] != '0'){
			$whereContact = " AND `accounts_transactions`.`payer_name` = '$accountfor'";
		}
		
		$amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`dr_account_status` != 'advance' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`cr_account_status` != 'due' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account`";
		$paid_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
		$due_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`dr_account_status` = 'due' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
		$due_paid_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`dr_account_status` != 'due' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`cr_account_status` = 'due' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
		$advance_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`cr_account_status` = 'advance' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
		$advance_paid_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`dr_account_status` = 'advance' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
		
		
		$selectRaw = ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $amount $whereStore $whereBeteween $whereContact ) as `amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $paid_amount $whereStore $whereBeteween $whereContact ) as `paid_amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $due_amount $whereStore $whereBeteween $whereContact ) as `due_amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $due_paid_amount $whereStore $whereBeteween $whereContact ) as `due_paid_amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $advance_amount $whereStore $whereBeteween $whereContact ) as `advance_amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $advance_paid_amount $whereStore $whereBeteween $whereContact ) as `advance_paid_amount`";
		
		
		$GetFilterData = app('admin')->GetFilterData($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$selectNotWhere,$selectRaw,$whereRaw);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['chart_name'] = trans($GetFilterData['Data'][$x]['chart_name']);
			$GetFilterData['Data'][$x]['chart_name_value'] = $GetFilterData['Data'][$x]['chart_name_value'];
			$GetFilterData['Data'][$x]['amount'] = $GetFilterData['Data'][$x]['amount'] ? number_format($GetFilterData['Data'][$x]['amount'],2) : 0;
			$GetFilterData['Data'][$x]['due_amount'] = number_format($GetFilterData['Data'][$x]['due_amount'] - $GetFilterData['Data'][$x]['due_paid_amount']);
			$GetFilterData['Data'][$x]['advance_amount'] = $GetFilterData['Data'][$x]['advance_amount'] - $GetFilterData['Data'][$x]['advance_paid_amount'];
			$GetFilterData['Data'][$x]['paid_amount'] = number_format($GetFilterData['Data'][$x]['paid_amount'] - $GetFilterData['Data'][$x]['advance_amount']);
			$GetFilterData['Data'][$x]['advance_amount'] = number_format($GetFilterData['Data'][$x]['advance_amount']);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetWithdrawalData":
		$searchSelect = [
		"pos_contact.business_name",
		"pos_contact.name"
		];
		$rowSelect = [
		"accounts_transactions.id",
		"pos_contact.name",
		"accounts_transactions.amount",
		"accounts_transactions.payment_method",
		"accounts_transactions.date",
		"accounts_transactions.created_at",
		"accounts_transactions.user_id",
		"pos_store.store_name",
		];
		$selectWhere = [
		'accounts_transactions.is_delete' => false,
		'accounts_transactions.dr_account' => 'account_capital', 
		'accounts_transactions.account_type' => 'account_withdraw' 
		];
		$jointWhere = [
		'accounts_transactions.payer_name' => 'pos_contact.contact_id', 
		'accounts_transactions.store_id' => 'pos_store.store_id', 
		];
		$selectNotWhere = [];
		$tableSelect = ["accounts_transactions","pos_contact","pos_store"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['added_by'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['pos_contact/name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['pos_store/store_name'] = $GetFilterData['Data'][$x]['store_name'];
			$GetFilterData['Data'][$x]['amount'] = number_format($GetFilterData['Data'][$x]['amount']);
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetExpenseData":
		
		$postSearch = strtolower($_POST['search']['value']);
		$postSearch = str_replace(" ", "_", $_POST['search']['value']);
		$_POST['search']['value'] = 'account_'.$postSearch;
		
		$searchSelect = [
		"chart_name"
		];
		
		$rowSelect = [
		"chart_name_value",
		"chart_name",
		"created_at",
		];
		
		$selectWhere = [
		"chart_sub_category_name" => 'account_expense',
		];
		
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = "accounts_chart";
		
		$startDate = $_POST['from_data'];
		$endDate = $_POST['to_data'];
		$store = $_POST['store_id'];
		$accountfor = $_POST['account_for'];
		
		$whereStore = '';
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$whereStore = " AND `accounts_transactions`.`store_id` = '$store'";
		}
		
		$whereBeteween = " AND date(`accounts_transactions`.`date`) BETWEEN '$startDate' AND '$endDate'";
		
		$whereContact = '';
		if(isset($_POST['account_for']) && $_POST['account_for'] != '0'){
			$whereContact = " AND `accounts_transactions`.`payer_name` = '$accountfor'";
		}
		
		$amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`dr_account_status` != 'due' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`cr_account_status` != 'advance' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account`";
		$paid_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`is_delete` = 'false' AND  `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
		$due_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`cr_account_status` = 'due' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
		$due_paid_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`dr_account_status` = 'due' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`cr_account_status` != 'due' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
		$advance_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`dr_account_status` = 'advance' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
		$advance_paid_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`is_delete` = 'false' AND `accounts_transactions`.`cr_account_status` = 'advance' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
		
		
		$selectRaw = ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $amount $whereStore $whereBeteween $whereContact ) as `amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $paid_amount $whereStore $whereBeteween $whereContact ) as `paid_amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $due_amount $whereStore $whereBeteween $whereContact ) as `due_amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $due_paid_amount $whereStore $whereBeteween $whereContact ) as `due_paid_amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $advance_amount $whereStore $whereBeteween $whereContact ) as `advance_amount`";
		$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE $advance_paid_amount $whereStore $whereBeteween $whereContact ) as `advance_paid_amount`";
		
		
		$GetFilterData = app('admin')->GetFilterData($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$selectNotWhere,$selectRaw);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['chart_name'] = trans($GetFilterData['Data'][$x]['chart_name']);
			$GetFilterData['Data'][$x]['chart_name_value'] = $GetFilterData['Data'][$x]['chart_name_value'];
			
			$GetFilterData['Data'][$x]['amount'] = $GetFilterData['Data'][$x]['amount'] ? number_format($GetFilterData['Data'][$x]['amount'],2) : 0;
			$GetFilterData['Data'][$x]['due_amount'] = number_format($GetFilterData['Data'][$x]['due_amount'] - $GetFilterData['Data'][$x]['due_paid_amount']);
			$GetFilterData['Data'][$x]['advance_amount'] = $GetFilterData['Data'][$x]['advance_amount'] - $GetFilterData['Data'][$x]['advance_paid_amount'];
			$GetFilterData['Data'][$x]['paid_amount'] = number_format($GetFilterData['Data'][$x]['paid_amount'] - $GetFilterData['Data'][$x]['advance_amount']);
			$GetFilterData['Data'][$x]['advance_amount'] = number_format($GetFilterData['Data'][$x]['advance_amount']);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetLowStockData":
		$searchSelect = [
		"pos_product.product_id",
		"pos_product.product_name"
		];
		$rowSelect = [
		"pos_product.product_id",
		"pos_product.product_name",
		"pos_product.alert_quantity",
		"pos_product.product_stock",
		"pos_unit.unit_name",
		];
		$selectWhere = [];
		$jointWhere = ["pos_product.unit_id" => "pos_unit.unit_id"];
		$selectNotWhere = [];
		$tableSelect = ["pos_product","pos_unit"];		
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$totalproductin = app('admin')->getsumtotalbywhereand('pos_stock','product_quantity','stock_type','in','product_id',$GetFilterData['Data'][$x]['product_id']);
			$totalproductout = app('admin')->getsumtotalbywhereand('pos_stock','product_quantity','stock_type','out','product_id',$GetFilterData['Data'][$x]['product_id']);
			$availiablestock = $totalproductin - $totalproductout;
			if($availiablestock<=$GetFilterData['Data'][$x]['alert_quantity'] && $GetFilterData['Data'][$x]['product_stock']=='enable'){
				
				$GetFilterData['Data'][$x]['product_id'] = $GetFilterData['Data'][$x]['product_id'];
				$GetFilterData['Data'][$x]['product_name'] = $GetFilterData['Data'][$x]['product_name'];
				$GetFilterData['Data'][$x]['product_quantity'] = $availiablestock.' '.$GetFilterData['Data'][$x]['unit_name'];
				
			}
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "PaymentInfoData":
		
		break;
		case "GetTicketData":
		$searchSelect = [
		"as_ticket.ticket_no",
		"as_ticket.ticket_title",
		];
		$rowSelect = [
		"as_ticket.ticket_no",
		"as_ticket.ticket_title",
		"as_ticket.ticket_details",
		"as_ticket.ticket_document",
		"as_ticket.priority",
		"as_ticket.user_id",
		"as_ticket.status",
		];
		$selectWhere = ["as_ticket.ticket_type" => 'ticket',"as_ticket.user_id" => $this->currentUser->id];
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = ["as_ticket"];
		
		$GetFilterData = app('root')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['added_by'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['priority'] = ucfirst($GetFilterData['Data'][$x]['priority']);
			$GetFilterData['Data'][$x]['ticket_no'] = $x+1;
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case 'GetInvoiceSaleData':
		
		$GetFilterData = app('admin')->GetFilterData($_POST,"pos_sales",array("sales_id","customer_id","store_id","sales_type","user_id","sales_subtotal","sales_total","sales_vat","sales_discount","sales_payment_status","sales_status","created_at"),array("sales_id","customer_id"),array("sales_type" => 'invoice'),array("sales_status" => 'cancel',"sales_status" => 'quote'));
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $x+1;
			$total_paid = app('pos')->GetSalesByCustomerOrder(false,$GetFilterData['Data'][$x]['sales_id']);
			$GetFilterData['Data'][$x]['paid'] = $total_paid['total_paid'];
			$GetFilterData['Data'][$x]['due'] = $total_paid['total_due'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['added_by'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],2);
		}
		
		$response = array(
		"draw" => intval($GetFilterData['draw']),
		"iTotalRecords" => $GetFilterData['iTotalRecords'],
		"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],
		"aaData" => $GetFilterData['Data']
		);
		echo json_encode($response);
		
		break;
		
		case 'GetSalesReturnData':
		$searchSelect = [
		"pos_return.return_id",
		"pos_contact.customer_name"
		];
		$rowSelect = [
		"pos_return.return_id",
		"pos_return.created_at",
		"pos_return.return_total",
		"pos_contact.name",
		"pos_return.sales_id",
		];
		$selectWhere = ["pos_return.return_type" => "sales","pos_return.is_delete" => "false"];
		$jointWhere = ["pos_return.customer_id" => "pos_contact.contact_id"];
		$selectNotWhere = [];
		$tableSelect = ["pos_return","pos_contact"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $x+1;
			$TransactionInfo = app('pos')->GetSalesByCustomerOrder(false,$GetFilterData['Data'][$x]['sales_id']);
			// print_r($TransactionInfo);
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
			$GetFilterData['Data'][$x]['pos_contact/customer_name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['return_total'] = $GetFilterData['Data'][$x]['return_total'];
			$GetFilterData['Data'][$x]['total_paid'] = $TransactionInfo['total_return_paid'];
			$GetFilterData['Data'][$x]['total_due'] = $TransactionInfo['total_return_due'];
			if($GetFilterData['Data'][$x]['total_paid'] == 0){
				$GetFilterData['Data'][$x]['total_paid'] = 0;
			}			
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetPurchaseReturnData":
		
		$searchSelect = [
		"pos_return.return_id",
		"pos_contact.supplier_name",
		];
		$rowSelect = [
		"pos_return.return_id",
		"pos_return.created_at",
		"pos_return.return_total",
		"pos_return.purchase_id",
		"pos_contact.name",
		];
		$selectWhere = ["pos_return.return_type" => "purchase","pos_return.is_delete" => "false"];
		$jointWhere = ["pos_return.supplier_id" => "pos_contact.contact_id"];
		$selectNotWhere = [];
		$tableSelect = ["pos_return","pos_contact"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $x+1;
			$TransactionInfo=app('pos')->GetPurchaseByCustomerOrder(false,$GetFilterData['Data'][$x]['purchase_id']);
			
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],3);
			
			$GetFilterData['Data'][$x]['pos_contact/supplier_name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['return_total'] = $GetFilterData['Data'][$x]['return_total'];
			$GetFilterData['Data'][$x]['total_paid'] = $TransactionInfo['total_return_paid'];
			$GetFilterData['Data'][$x]['total_due'] = $TransactionInfo['total_return']-$TransactionInfo['total_return_paid'];
			
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		
		break;
		
		case "GetPaymentMethodData":
		$searchSelect = [
		"pos_payment_method.payment_method_id",
		"pos_payment_method.payment_method_name",
		];
		$rowSelect = [
		"pos_payment_method.payment_method_id",
		"pos_payment_method.payment_method_name",
		"pos_payment_method.payment_method_type",
		"pos_payment_method.account_number",
		"pos_payment_method.payment_method_status",
		"pos_payment_method.minimum_amount",
		"pos_payment_method.created_at",
		"pos_payment_method.updated_at",
		"pos_payment_method.user_id",
		];
		$selectWhere = [];
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = ["pos_payment_method"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['added_by'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],6);
			$GetFilterData['Data'][$x]['updated_at'] = getdatetime($GetFilterData['Data'][$x]['updated_at'],6);
			$GetFilterData['Data'][$x]['payment_method_type'] = trans($GetFilterData['Data'][$x]['payment_method_type']);
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetTransactionHistory":
		$searchSelect = [
		"pos_transactions.transaction_id",
		"pos_transactions.payment_method_value",
		"pos_transactions.transaction_amount",
		];
		$rowSelect = [
		"pos_transactions.transaction_id",
		"pos_transactions.transaction_type",
		"pos_transactions.created_at",
		"pos_transactions.payment_method_value",
		"pos_transactions.transaction_amount",
		"pos_transactions.transaction_note",
		"pos_transactions.user_id",
		];
		$selectWhere = ["pos_transactions.transaction_type" => "transfer"];
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = ["pos_transactions"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],6);
			$trasferPayment=app('admin')->getwhereid('pos_transactions','matching_id',$GetFilterData['Data'][$x]['transaction_id']);
			$GetFilterData['Data'][$x]['account_no'] = $trasferPayment['transaction_no']==null ? 'N/A' : $trasferPayment['transaction_no'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['added_by'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetStockTransferData":
		
		$searchSelect = [
		"pos_stock_transfer.date",
		"pos_stock_transfer.reference_no",
		];
		$rowSelect = [
		"pos_stock_transfer.id",
		"pos_stock_transfer.stock_transfer_id",
		"pos_stock_transfer.from_store_id",
		"pos_stock_transfer.to_store_id",
		"pos_stock_transfer.date",
		"pos_stock_transfer.reference_no",
		"pos_stock_transfer.store_id",
		"pos_stock_transfer.user_id",
		"pos_stock_transfer.shipping_charge",
		"pos_stock_transfer.created_at",
		"pos_stock_transfer.stock_transfer_note",
		];
		$selectWhere = [
		"pos_stock_transfer.is_delete" => "false"
		];
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = ["pos_stock_transfer"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['date'],4);
			$from_store=app('admin')->getwhereid('pos_store','store_id',$GetFilterData['Data'][$x]['from_store_id']);
			$GetFilterData['Data'][$x]['from_store'] = $from_store['store_name'];
			$to_store=app('admin')->getwhereid('pos_store','store_id',$GetFilterData['Data'][$x]['to_store_id']);
			$GetFilterData['Data'][$x]['to_store'] = $to_store['store_name'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['added_by'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],2);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetStockAdjustmentData":
		
		$searchSelect = [
		"pos_stock_adjustment.date",
		"pos_stock_adjustment.reference_no",
		"pos_transactions.transaction_amount"
		];
		$rowSelect = [
		"pos_store.store_name",
		"pos_store.store_id",
		"pos_stock_adjustment.stock_adjustment_id",
		"pos_stock_adjustment.from_store_id",
		"pos_stock_adjustment.date",
		"pos_stock_adjustment.reference_no",
		"pos_stock_adjustment.store_id",
		"pos_stock_adjustment.user_id",
		"pos_stock_adjustment.shipping_charge",
		"pos_stock_adjustment.created_at",
		"pos_stock_adjustment.stock_adjustment_note",
		"pos_stock_adjustment.type",
		"pos_transactions.transaction_amount",
		"pos_transactions.adjustment_id",
		];
		$selectWhere = [
		"pos_stock_adjustment.is_delete" => "false",
		];
		$jointWhere = [
		"pos_stock_adjustment.stock_adjustment_id" => "pos_transactions.adjustment_id",
		"pos_stock_adjustment.from_store_id" => "pos_store.store_id"
		];
		$selectNotWhere = [];
		$tableSelect = ["pos_stock_adjustment","pos_transactions","pos_store"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);		
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['date'],4);
			$GetFilterData['Data'][$x]['pos_store/store_name'] = $GetFilterData['Data'][$x]['store_name'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],2);
			$GetFilterData['Data'][$x]['pos_transactions/transaction_amount'] = $GetFilterData['Data'][$x]['transaction_amount'];
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case 'GetListQuotationData':
		$searchSelect = [
		"pos_sales.sales_id","pos_sales.customer_id"
		];
		$rowSelect = [
		"pos_sales.sales_id",
		"pos_sales.customer_id",
		"pos_sales.store_id",
		"pos_sales.sales_type",
		"pos_sales.user_id",
		"pos_sales.sales_subtotal",
		"pos_sales.sales_total",
		"pos_sales.sales_vat",
		"pos_sales.sales_discount",
		"pos_sales.sales_payment_status",
		"pos_sales.sales_status",
		"pos_sales.created_at"
		];
		$selectWhere = ["pos_sales.sales_status" => 'quote'];
		$jointWhere = [];
		$selectNotWhere = [];
		$tableSelect = ["pos_sales"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['id'] = $x+1;
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['added_by'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['sale_date'] = getdatetime($GetFilterData['Data'][$x]['created_at'],2);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		
		case "GetPurchasePaymentReportData":
		$searchSelect = [
		"pos_transactions.purchase_id",
		"pos_transactions.transaction_id",
		"pos_contact.contact_id",
		"pos_contact.name"
		];
		$rowSelect = [
		"pos_transactions.transaction_id",
		"pos_transactions.purchase_id",
		"pos_transactions.paid_date",
		"pos_transactions.payment_for",
		"pos_transactions.transaction_type",
		"pos_transactions.transaction_amount",
		"pos_transactions.payment_method_value",
		"pos_contact.name",
		"pos_contact.contact_id",
		"pos_payment_method.payment_method_name",
		"pos_payment_method.payment_method_value"
		];
		$selectWhere = ["pos_transactions.transaction_type" => "purchase", "pos_transactions.payment_for" => $_POST["supplier_id"], "pos_transactions.store_id" => $_POST["store_id"],
		"pos_transactions.is_delete" => "false"
		];
		$jointWhere = ["pos_transactions.payment_method_value" => "pos_payment_method.payment_method_value","pos_transactions.payment_for" => "pos_contact.contact_id"];
		$selectNotWhere = [];
		$tableSelect = ["pos_transactions","pos_contact","pos_payment_method"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['pos_contact/name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['pos_payment_method/payment_method_name'] = $GetFilterData['Data'][$x]['payment_method_name'];
			$GetFilterData['Data'][$x]['paid_date'] = getdatetime($GetFilterData['Data'][$x]['paid_date'],6);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetSalesPaymentReportData":
		$searchSelect = [
		"pos_transactions.sales_id",
		"pos_transactions.transaction_id",
		"pos_contact.contact_id","pos_contact.name"
		];
		$rowSelect = [
		"pos_transactions.transaction_id",
		"pos_transactions.sales_id",
		"pos_transactions.paid_date",
		"pos_transactions.payment_for",
		"pos_transactions.transaction_type",
		"pos_transactions.transaction_amount",
		"pos_transactions.payment_method_value",
		"pos_contact.name","pos_contact.contact_id",
		"pos_payment_method.payment_method_name",
		"pos_payment_method.payment_method_value"
		];
		$selectWhere = [
		"pos_transactions.transaction_type" => "sales", 
		"pos_transactions.payment_for" => $_POST["customer_id"], 
		"pos_transactions.store_id" => $_POST["store_id"],
		"pos_transactions.is_delete" => "false"
		];
		$jointWhere = [
		"pos_transactions.payment_method_value" => "pos_payment_method.payment_method_value",
		"pos_transactions.payment_for" => "pos_contact.contact_id"
		];
		$selectNotWhere = [];
		$tableSelect = ["pos_transactions","pos_contact","pos_payment_method"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['pos_contact/name'] = $GetFilterData['Data'][$x]['name'];
			$GetFilterData['Data'][$x]['pos_payment_method/payment_method_name'] = $GetFilterData['Data'][$x]['payment_method_name'];
			$GetFilterData['Data'][$x]['paid_date'] = getdatetime($GetFilterData['Data'][$x]['paid_date'],6);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetRegisterData":
		$searchSelect = [
		"pos_register_report.register_id",
		"pos_register_report.register_status"
		];
		$rowSelect = [
		"pos_register_report.register_id","pos_register_report.user_id","pos_register_report.store_id","pos_register_report.register_open","pos_register_report.register_close","pos_register_report.register_open_balance","pos_register_report.register_close_balance","pos_register_report.register_status"
		];
		$selectWhere = [
		"pos_register_report.is_delete" => "false"
		];
		$jointWhere = [
		
		];
		$selectNotWhere = [];
		$tableSelect = ["pos_register_report"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_name'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetStockAdjustmentReportData":
		$searchSelect = [
		"pos_stock_adjustment.date",
		"pos_stock_adjustment.reference_no",
		"pos_transactions.transaction_amount"
		];
		$rowSelect = [
		"pos_store.store_name",
		"pos_store.store_id",
		"pos_stock_adjustment.stock_adjustment_id",
		"pos_stock_adjustment.from_store_id",
		"pos_stock_adjustment.date",
		"pos_stock_adjustment.reference_no",
		"pos_stock_adjustment.store_id",
		"pos_stock_adjustment.user_id",
		"pos_stock_adjustment.shipping_charge",
		"pos_stock_adjustment.created_at",
		"pos_stock_adjustment.stock_adjustment_note",
		"pos_stock_adjustment.type",
		"pos_transactions.transaction_amount",
		"pos_transactions.adjustment_id"
		];
		$selectWhere = [
		"pos_stock_adjustment.store_id" => $_POST["store_id"],
		"pos_stock_adjustment.is_delete" => "false",
		"pos_transactions.is_delete" => "false",
		"pos_store.is_delete" => "false"
		];
		$jointWhere = [
		"pos_stock_adjustment.stock_adjustment_id" => "pos_transactions.adjustment_id",
		"pos_stock_adjustment.from_store_id" => "pos_store.store_id"
		];
		$selectNotWhere = [
		];
		$tableSelect = ["pos_stock_adjustment","pos_transactions","pos_store"];
		
		$GetFilterData = app('admin')->GetFilterDataJoint($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$jointWhere,$selectNotWhere);
		$i = 0;
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['date'],4);
			$GetFilterData['Data'][$x]['pos_store/store_name'] = $GetFilterData['Data'][$x]['store_name'];
			$GetuserInfo = app('admin')->getuserdetails($GetFilterData['Data'][$x]['user_id']);
			$GetFilterData['Data'][$x]['user_id'] = $GetuserInfo['first_name'].' '.$GetuserInfo['last_name'];
			$GetFilterData['Data'][$x]['created_at'] = getdatetime($GetFilterData['Data'][$x]['created_at'],2);
			$GetFilterData['Data'][$x]['pos_transactions/transaction_amount'] = $GetFilterData['Data'][$x]['transaction_amount'];
		}
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		case "GetHeadOfAccountData":
		$searchSelect = [
		"date",
		];
		$rowSelect = [
		"payment_method_id",
		"payment_method_name",
		"payment_method_value",
		"minimum_amount",
		"payment_method_status",
		"user_id",
		];
		$selectWhere = [
		"is_delete" => "false"
		];
		
		
		
		$whereStore = '';
		if(isset($_POST['store_id']) && $_POST['store_id'] != '0'){
			$store = $_POST['store_id'];
			$whereStore = " AND `store_id` = '$store' ";
		}
		
		$selectNotWhere = [];
		$tableSelect = "pos_payment_method";
		$selectRaw = "";
		$selectRaw .= ", (SELECT COALESCE(SUM((CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`dr_account` = `pos_payment_method`.`payment_method_type` THEN amount END)),0) - COALESCE(SUM((CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`cr_account` = `pos_payment_method`.`payment_method_type` THEN amount END)),0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND  `accounts_transactions`.`payment_method` = `pos_payment_method`.`payment_method_value` $whereStore ) AS `current_balance` ";
		$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_transactions`.`transaction_flow_type` = 'credit' THEN transaction_amount END), 0) - COALESCE(SUM(CASE WHEN `pos_transactions`.`transaction_flow_type` = 'debit' THEN transaction_amount END), 0) FROM `pos_transactions` WHERE `pos_transactions`.`is_delete` = 'false' AND `pos_transactions`.`payment_method_value` = `pos_payment_method`.`payment_method_value` $whereStore ) AS `current_balance_pos` ";
		
		$GetFilterData = app('admin')->GetFilterData($_POST,$tableSelect,$rowSelect,$searchSelect,$selectWhere,$selectNotWhere,$selectRaw);
		
		$i = 0;
		
		for ($x = 0; $x < count($GetFilterData['Data']); $x++) {
			$GetFilterData['Data'][$x]['current_balance'] = number_format($GetFilterData['Data'][$x]['current_balance'] + $GetFilterData['Data'][$x]['current_balance_pos'] ,2);
		}
		
		echo json_encode(array("draw" => intval($GetFilterData['draw']),"iTotalRecords" => $GetFilterData['iTotalRecords'],"iTotalDisplayRecords" => $GetFilterData['iTotalDisplayRecords'],"aaData" => $GetFilterData['Data']));
		break;
		
		
		
		default:
		break;
	}
	
	function onlyAdmin()
	{
		if (! (app('login')->isLoggedIn() && app('current_user')->is_admin)) {
			respond(array('error' => 'Forbidden.'), 403);
		}
	}
	
	function onlySuperAdmin()
	{
		if (! (app('login')->isLoggedIn() && app('current_user')->is_superadmin)) {
			respond(array('error' => 'Forbidden.'), 403);
		}
	}
	
	function onlyAgent()
	{
		if (! (app('login')->isLoggedIn() && app('current_user')->is_agent)) {
			respond(array('error' => 'Forbidden.'), 403);
		}
	}
