<?php

namespace App\Helpers;
use Image;
use Parser;

class Upload
{
    public static function document($document,$filename,$extension)
    {
        if(substr($extension, 0, 5) == 'image') {
            Image::make($document)->resize(600, 500)->save(storage_path('/app/public/uploads/document/'.$filename ));
        }else{
            $document->storeAs('uploads/document',$filename);
        }
        
    }

    public static function send_sms($reciver,$text)
    {
        if (\strpos($reciver, '+88') !== false) {
          $number = $reciver;
        }else{
          $number = '+88'.$reciver;
        }

     	$postData = array(
     		'user' => '01718156972',
     		'password' => 'ABCDabcd1234',
     		'SMSText' => $text,
     		'sender' => 'APPSOWL',
     		'GSM' => $number,
     		'type' => 'longSMS'
     	);

     	$url = 'http://api.zaman-it.com/api/v3/sendsms/plain?';

     	$ch = curl_init();
     	curl_setopt_array($ch, array(
     		CURLOPT_URL => $url,
     		CURLOPT_RETURNTRANSFER => true,
     		CURLOPT_POST => true,
     		CURLOPT_POSTFIELDS => $postData
     	));

     	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     	$output = curl_exec($ch);
        curl_close($ch);

        $response_array = Parser::xml($output);
        $sms_status = $response_array['result']['status'];
        $messageid = $response_array['result']['messageid'];
        $destination = $response_array['result']['destination'];

        if($sms_status=='0'){
     		$arr = array('msg' => "Message Send Successfull",'number' => $destination,'messageid' => $messageid,'status' => 'success');
     	}else{
     		$arr = array('msg' => 'Error in sending sms.','number' => 'N/A', 'status' => 'error');
     	}
     	return $arr;         
    }
}