<?php defined('_AZ') or die('Restricted access');  
	
	switch ($loadPlugins['load_addon']) {
		
		case 'GetSalesProductListSearch':
		if($_POST['serial_no_check'] == 'no_serial'){
			$getProductVariation = app('db')->table('pos_variations')->where('sub_product_id',$_POST['product_code'])->where('is_delete','false')->get(1);
			$getSerial = app('db')->table('pos_product_serial')->where('product_serial_no',$_POST['product_code'])->where('product_serial_status',"received")->where('is_delete','false')->get();
			$getProductSerial = count($getSerial) > 0 ? $getSerial[0] : null;
			}else{
			$getProductVariation = null;
			$getSerial = null;
			$getProductSerial = app('db')->table('pos_product_serial')->where('product_serial_no',$_POST['serial_no_check'])->where('sub_product_id',$_POST['product_code'])->where('is_delete','false')->get(1);
		}
		
		if($getProductVariation){
			$getProduct = app('db')->table('pos_product')->where('product_id',$getProductVariation['product_id'])->where('is_delete','false')->get(1);
			$getUnit = app('db')->table('pos_unit')->where('unit_id',$getProduct['unit_id'])->where('is_delete','false')->get(1);
			$GetProductAvaliableStock = app('pos')->GetProductAvaliableStock($this->currentUser->store_id,$getProductVariation['sub_product_id']);
			respond(array(
			"status" => "success",
			"product_status" => "found",
			"product_name" => $getProduct['product_name'],
			"product_type" => $getProduct['product_type'],
			"product_search_type" => "product_code",
			"product_vat" => $getProduct['product_vat'],
			"product_vat_type" => $getProduct['product_vat_type'],
			"unit_name" => $getUnit['unit_name'],
			"product_serial" => $getProduct['product_serial'],
			"product_stock" => $getProduct['product_stock'],
			"alert_quantity" => $getProduct['alert_quantity'],
			"variation_name" => $getProductVariation['variation_name'],
			"product_id" => $getProductVariation['product_id'],
			"sub_product_id" => $getProductVariation['sub_product_id'],
			"purchase_price" => $getProductVariation['purchase_price'],
			"profit_percent" => $getProductVariation['profit_percent'],
			"sell_price" => $getProductVariation['sell_price'],
			"variation_id" => $getProductVariation['variation_id'],
			"product_current_stock" => $GetProductAvaliableStock['product_stock']
			));
			}elseif($getProductSerial){
			if($getSerial != null && count($getSerial) > 1){
				respond(array(
				"status" => "success",
				"product_status" => "multi_product_serial"
				));
				}else{
				$getProductVariation = app('db')->table('pos_variations')->where('sub_product_id',$getProductSerial['sub_product_id'])->where('is_delete','false')->get(1);
				$getProduct = app('db')->table('pos_product')->where('product_id',$getProductVariation['product_id'])->where('is_delete','false')->get(1);
				$getUnit = app('db')->table('pos_unit')->where('unit_id',$getProduct['unit_id'])->where('is_delete','false')->get(1);
				$GetProductAvaliableStock = app('pos')->GetProductAvaliableStock($this->currentUser->store_id,$getProductVariation['sub_product_id']);
				respond(array(
				"status" => "success",
				"product_status" => "found",
				"product_name" => $getProduct['product_name'],
				"product_type" => $getProduct['product_type'],
				"product_search_type" => "product_serial",
				"product_vat" => $getProduct['product_vat'],
				"product_vat_type" => $getProduct['product_vat_type'],
				"unit_name" => $getUnit['unit_name'],
				"product_serial" => $getProduct['product_serial'],
				"product_serial_no" => $getProductSerial['product_serial_no'],
				"product_stock" => $getProduct['product_stock'],
				"alert_quantity" => $getProduct['alert_quantity'],
				"sub_product_id" => $getProductVariation['sub_product_id'],
				"variation_name" => $getProductVariation['variation_name'],
				"product_id" => $getProductVariation['product_id'],
				"purchase_price" => $getProductVariation['purchase_price'],
				"profit_percent" => $getProductVariation['profit_percent'],
				"sell_price" => $getProductVariation['sell_price'],
				"variation_id" => $getProductVariation['variation_id'],
				"product_current_stock" => $GetProductAvaliableStock['product_stock']
				));
			}
			}else{
			respond(array(
			"status" => "success",
			"product_status" => "not_found"
			));
		}
		break;
		
		case 'GetStockReportData':
		if($GetFilterDatas['product_serial']=='enable'){
			$view_serial = '<a href="javascript:void(0)" class="view_serial" product_id="'.$GetFilterDatas['product_id'].'" ><i class="fa fa-eye"></i></a>';
			$FilterData[$x]['product_name'] = $FilterData[$x]['product_name'].' '.$view_serial;
		}
		break;
		
		case 'GetStockReportFilter':
		$rowSelect[] = 'pos_product.product_serial';
		break;
		
		case 'addSalesSerialDataInput':
		if (isset($data['product_serial_id'][$product_id])) {
			
			for ($serials=0; $serials < count($data['product_serial_id'][$product_id]) ; $serials++) { 
				$this->db->update("pos_product_serial" ,  array( 
				"sales_id" => $data['sales_id'],
				"sell_stock_id" => $product_stock_id,
				"customer_id" => $data['customer_code'],
				"product_serial_status" => 'sell',
				"product_serial_stock_type" => 'out',
				"sold_at" => date("Y-m-d H:i:s")
				), "`product_serial_no`= :id AND `sub_product_id`= :pid", array('id' => $data['product_serial_id'][$product_id][$serials], 'pid' => $sub_product_id));
			}
		}
		
		break;
		
		case 'addPurchaseSerialDataInput':
		if (isset($data['product_serial_id'][$product_id])) {
			
			for ($serials=0; $serials < count($data['product_serial_id'][$product_id]) ; $serials++) { 
				$this->db->insert("pos_product_serial",  array(
				"product_id" => $product_id,
				"sub_product_id" => $sub_product_id,
				"purchase_id" => $data['purchase_id'],
				"supplier_id" => $data['purchase_supplier'],
				"stock_id" => $product_stock_id,
				"product_serial_no" => $data['product_serial_id'][$product_id][$serials],
				"product_serial_category" => 'purchase',
				"product_serial_status" => 'received',
				"product_serial_stock_type" => 'in',
				"created_at" => date("Y-m-d H:i:s")
				));
			}
		}
		
		break;
		
		case 'addProdductEnableSerialInsert':
		
		$product_serial_status = 'disable';
		
		if(isset($data['product_serial_num'])){
			$product_serial_status = 'enable';
		}
		
		$pos_product['product_serial'] = $product_serial_status;
		
		break;
		
		default:
		break;
	}
	
	
?>																				