<?php
	
	class ASPayment
	{
		
		private $db = null;
		
		private $users;
		
		public function __construct(ASDatabase $db, ASUser $users)
		{
			$this->db = $db;
			$this->users = $users;
		}
		
		public function getPaymentCallBack(){
			$jsonData = $_POST['merchant_txn_data'];
			$jsonData = stripslashes(html_entity_decode($jsonData));
			$merchant_txn_data = json_decode($jsonData,true);
			
			$token = $merchant_txn_data['token'];
			$txn_status = $merchant_txn_data['txn_status'];
			$OrderId = $merchant_txn_data['merchant_order_id'];
			if($txn_status == '1000'){
				$result = $this->db->select("SELECT `payment_token`,`payment_id`,`user_id` FROM `as_payment` WHERE `payment_token` = :sid AND `payment_id` = :oid",array(
				"sid" => $token,
				"oid" => $OrderId
				));
				if (count($result) == 1) {
					$params = array(
					"wmx_id" => MERCHANT_ID,
					"token" => $token,
					"access_app_key" => APP_KEY,
					"authorization" => $this->get_auth(),
					);
					
					$response = $this->curl_request_post(PAYMENT_CHECKURL,json_encode($params));
					$response = json_decode($response);
					
					if($response->txn_status == '1000'){
						$this->db->update("as_payment" ,  array( 
						'payment_ref_id'		=>	$response->ref_id,
						'payment_load_date'		=>	date('Y-m-d'),
						'payment_amount'		=>	$response->merchant_amount_bdt,
						'payment_currency'		=>	$response->merchant_currency,
						'payment_charge'		=>	$response->wmx_charge_bdt,
						'payment_discount'		=>	$response->discount_bdt,
						'payment_total_amount'	=>	$response->bank_amount_bdt,
						'payment_request_ip'	=>	$response->request_ip,
						'payment_txn_status'	=>	$response->txn_status,
						'payment_txn_details'	=>	$response->txn_details,
						'payment_card_details'	=>	$response->card_details,
						'payment_txn_msg'	=>	"Transaction Successful",
						'payment_card'		=>	$response->payment_card,
						'card_code'		=>	$response->card_code,
						'payment_method'		=>	$response->payment_method,
						'payment_time'		=>	$response->txn_time,
						'payment_status'		=>	'paid',
						), " `payment_id` = :sid", array(
						"sid" => $OrderId
						));
					}
					redirect('/fund?pid='.$OrderId.'/');
					
				}
				}elseif($txn_status == '1001'){
				$this->db->update("as_payment" ,  array( 
				'payment_txn_msg'	=>	"Transaction Rejected",
				'payment_status'		=>	'due',
				), " `payment_id` = :sid", array(
				"sid" => $OrderId
				));
				redirect('/fund?pid='.$OrderId.'/');
				}elseif($txn_status == '1009'){
				$this->db->update("as_payment" ,  array( 
				'payment_txn_msg'	=>	"Transaction Cancelled",
				'payment_status'		=>	'cancel',
				), " `payment_id` = :sid", array(
				"sid" => $OrderId
				));
				redirect('/fund?pid='.$OrderId.'/');
				}else{
				redirect('/fund');
			}
			
		}
		
		public function PaymentTokenGenerate($data){
			$emi_period = array();
			
			$city = "";
			if(isset($data['customer_city'])){
				$city = $data['customer_city'];
			}
			$country = "";
			if(isset($data['customer_country'])){
				$country = $data['customer_country'];
			}
			$postcode = "";
			if(isset($data['customer_postcode'])){
				$postcode = $data['customer_postcode'];
			}
			
			$shipping_name = "";
			if(isset($data['shipping_name'])){
				$shipping_name = $data['shipping_name'];
			}
			
			$shipping_address = "";
			if(isset($data['shipping_add'])){
				$shipping_address = $data['shipping_add'];
			}
			$shipping_city = "";
			if(isset($data['shipping_city'])){
				$shipping_city = $data['shipping_city'];
			}
			
			$shipping_country = "";
			if(isset($data['shipping_country'])){
				$shipping_country = $data['shipping_country'];
			}
			
			$shipping_postcode = "";
			if(isset($data['shipping_postCode'])){
				$shipping_postcode = $data['shipping_postCode'];
			}
			
			$defoult_currency = PAYMENT_DEFAULT_CURRENCY;
			if(isset($data['currency'])){
				$defoult_currency = $data['currency'];
			}
			
			$cart_info = null;
			if(isset($data['cart_info'])){
				$cart_info = $data['cart_info'];
			}
			
			$cart_info = MERCHANT_ID.','.APP_NAME.$cart_info; 
			
			$params = array(
			"wmx_id" => MERCHANT_ID,
			"merchant_order_id" => $data['merchant_order_id'],
			"merchant_ref_id" => $data['merchant_ref_id'],
			"app_name" => APP_NAME,
			"cart_info" => $cart_info,
			
			"customer_name" => $data['customer_name'],
			"customer_email" => $data['customer_email'],
			"customer_add" => $data['customer_add'],
			"customer_city" => $city,
			"customer_country" => $country,
			"customer_postcode" => $postcode,
			"customer_phone" => $data['customer_phone'],
			
			"shipping_name" => $shipping_name,
			"shipping_add" => $shipping_address,
			"shipping_city" => $shipping_city,
			"shipping_country" => $shipping_country,
			"shipping_postCode" => $shipping_postcode,
			
			"product_desc" => $data['product_desc'],
			"extra_json" => json_encode($emi_period),
			"amount" => $data['amount'],
			"currency" => $defoult_currency,
			"options" => $this->get_options_value(),
			"callback_url" => CALLBACK_URL,
			"access_app_key" => APP_KEY,
			"authorization" => $this->get_auth(),
			);
			
			$getServerDetails = $this->curl_request_get(GATEWAY_URL);
			$getServerDetails = json_decode($getServerDetails);
			$response_url  = $getServerDetails->url;
			$response = $this->curl_request_post($response_url,json_encode($params));
			
			$response_d = json_decode($response);
			if($response_d->statusCode === '1000'){
				return array("statusCode" => $response_d->statusCode,"statusMsg" => $response_d->statusMsg,"payment_token" => $response_d->token, "bank_payment_url" => $getServerDetails->bank_payment_url."/".$response_d->token);
				}else{
				return array("statusCode" => $response_d->statusCode,"statusMsg" => $response_d->statusMsg);
			}
		}
		
		private function get_options_value() {
			$options  = base64_encode('s='.PAYMENT_FROM_URL.',i='.$_SERVER['SERVER_ADDR']);
			return $options;
		}
		
		private function get_auth() {
			
			$encodeValue = base64_encode(ACCESS_USERNAME.':'.ACCESS_PASSWORD);
			$auth = 'Basic '.$encodeValue;
			return $auth;
		}
		
		
		private function curl_request_post($url,$params) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_VERBOSE, true);	  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($params))                                                                       
			);  
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}
		
		private function curl_request_get($url) {
			
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_HEADER, false); 
			curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'GET');
			$output = curl_exec($ch);
			curl_close($ch);
			return $output;
		}
		
	}
