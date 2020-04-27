<?php
	include dirname(__FILE__) . '/ASEngine/AS.php';
// 	echo app('admin')->sendsms('+8801911423670','Testss');
// 	$variations = app('db')->table('pos_variations')->get();
	
// 	foreach($variations as $variation){
	    
// 	    $product_sales_value = $variation['sell_price'];
// 		$product_purchase_value = $variation['purchase_price'];
// 		$total_profit_amount = $product_sales_value - $product_purchase_value;
// 		$total_percent = $total_profit_amount / $product_purchase_value * 100;
// 		echo number_format($total_percent,2).' / ';
	    
// 	    echo $variation['sub_product_id'].'<br>';
	    
// 	    	app('db')->update("pos_variations" ,  array(
//         			"profit_percent"=> number_format($total_percent,2),
//         			),
//         			"`sub_product_id` = :id ",
//         			array("id"=> $variation['sub_product_id'])
//         			);
	
	    
// 	}
	
// 	app('db')->update("pos_contact" ,  array(
//         			"user_id"=> '1001',
//         			),
//         			"`is_delete` = :id ",
//         			array("id"=> 'false')
//         			);
	
	// app('pos')->getIncomeStatementData();

// 	app('pos')->getFinancialStatementData(["from_data" => '2019-10-10',"to_data" => "2019-10-16","store_id" => '1'],true);
	
	// $data ='
	// {
	// "chart_no":{
	// "1236547989",
	// "1236547989"
	// },
	// "chart_name":{
	// "account_total_purchase",
	// "account_total_purchases"
	// }
	// }
	
	// ';
	// $data = array(
	// "chart_no" => 
		// "1236547989",
		// "1236547989"
	
	// ),
	// "chart_name" => 
		// "account_total_purchase",
		// "account_total_purchase"
	
	// );
	
	// echo json_encode($data);
	// $data = json_decode($data, JSON_FORCE_OBJECT);
	// $data = json_encode($data,true);
	// print_r($data);
	
	// ksort($data);
	
	// $fieldNames = implode('`, `', array_keys($data));
	// $fieldValues = ':' . implode(', :', array_keys($data));
	
	// print_r($fieldNames);
	// print_r($fieldValues);
	
	
	// app('db')->insert('accounts_chart',$data);
	// use NumberToWords\NumberToWords;
	// $numberToWords = new NumberToWords();
	// $numberTransformer = $numberToWords->getNumberTransformer('en');
	// echo $numberTransformer->toWords(501200);
	
	
	
	
	// $adapter = new League\Flysystem\Adapter\Local(__DIR__.'/');
	// $filesystem = new League\Flysystem\Filesystem($adapter);
	// use Nette\Utils\Json;
	
	// $Arrays = Nette\Utils\Arrays;
	// $array = ['foo','yahoo','what' => ['new-one' => 'lock']];
	// $array = ['foo','yahoo','lock'];
	// $a = Arrays::pick($array, null);
	// $a = 'bar'
	// $b = Arrays::pick($array, 'not-exists', 'foobar');
	// $b = 'foobar'
	// $c = Arrays::pick($array, 'not-exists');
	// throws Nette\InvalidArgumentException
	
	// if($value){
	// print_r($value);
	// }
	
	// $array = new stdClass;
	// $array->name = 'rishad';
	// $array->details = 'appsowl';
	
	// try {
	// $json = Json::encode($array);
	// } catch (Nette\Utils\JsonException $e) {
	// Exception handling
	// }
	
	// $jsonLocation = '/addons/test.json';
	
	// $exists = $filesystem->has($jsonLocation);
	// if($exists){
	// $filesystem->update($jsonLocation, $json);
	// }else{
	// $filesystem->write($jsonLocation, $json);
	// }
	
	// $contents = $filesystem->read($jsonLocation);

// try {
// $contents = Json::decode($contents);
// }catch(Nette\Utils\JsonException $e){
// }

// print_r($contents);	
// foreach( $contents as $value);
// echo $contents->name;
// app('admin')->userPluginsJsonUpdate(1);
// }
// print_r($value);

// app('admin')->PosInstallation(2);
// $domain_name = "test";
// $friendly_key = "b4eb5_6cbf9_a39a3edd71b3de97622cddad80f5410c";
// $friendly_cert = "b4eb5_6cbf9_a39a3edd71b3de97622cddad80f5410c";
// $getdomaindetails = app('admin')->getwhereid("as_sub_domain","sub_domain",$domain_name);
// app('admin')->getApiDeleteAuto("as_subscribe_log","user_id",$getdomaindetails['user_id']);
// app('admin')->getApiDeleteAuto("as_subscribe","user_id",$getdomaindetails['user_id']);
// app('admin')->getApiDeleteAuto("as_payment","user_id",$getdomaindetails['user_id']);
// app('admin')->getApiDeleteAuto("as_sub_domain","user_id",$getdomaindetails['user_id']);
// try {
// $cPanel = new ASCpanel(CPANEL_USERNAME,CPANEL_PASSWORD,CPANEL_DOMAIN);
// $cPanel->api2->SubDomain->delsubdomain(['domain'=> $domain_name]);
// $response = $cPanel->uapi->SSL->delete_ssl(['domain'=> $domain_name]);
// $response = $cPanel->uapi->SSL->delete_key(['id'=> $friendly_key]);
// $response = $cPanel->uapi->SSL->delete_cert(['id'=> $friendly_cert]);
// $cPanel->uapi->Mysql->delete_database(['name' => CPANEL_PREFIX.$domain_name]);
// $cPanel->uapi->Mysql->delete_user(['name' => CPANEL_PREFIX.$domain_name]);
// print_r($response);
// }catch(Exception $e) {
// echo 'Message: ' .$e->getMessage();
// }
