<?php
	
	/**
		* Redirect to provided url
		* @param $url
	*/
	function redirect($url)
	{
		$isExternal = stripos($url, "http://") !== false || stripos($url, "https://") !== false;
		
		if (! $isExternal) {
			$url = rtrim(SCRIPT_URL, '/') . '/' . ltrim($url, '/');
		}
		
		if (! headers_sent()) {
			header('Location: '.$url, true, 302);
			} else {
			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$url.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			echo '</noscript>';
		}
		exit;
	}
	
	/**
		* Get page where user should be redirected, based on user's role.
		* If there is no specific page set for provided role, redirect to default page.
		*
		* @return string Page where user should be redirected.
	*/
	function get_redirect_page()
	{
		if (app('login')->isLoggedIn()) {
			$role = app('user')->getRole(ASSession::get("user_id"));
			} else {
			$role = 'default';
		}
		
		$redirect = unserialize(SUCCESS_LOGIN_REDIRECT);
		
		if (! isset($redirect['default'])) {
			$redirect['default'] = '/';
		}
		
		return isset($redirect[$role]) ? $redirect[$role] : $redirect['default'];
	}
	
	
	/**
		* Escape HTML entities in a string.
		*
		* @param  string  $value
		* @return string  Escaped string
	*/
	if (! function_exists('e')) 
	{
		function e($value)
		{
			return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
		}
	}
	
	/**
		* Generates random string.
		*
		* @param int $length
		* @return string
	*/
	if (! function_exists('str_random')) 
	{
		function str_random($length = 16)
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			
			return $randomString;
		}
	}
	
	/**
		* Get translation for specific term represented by $key param.
		*
		* @param $key
		* @param array $bindings
		* @return mixed|string
	*/
	function trans($key, $bindings = array())
	{
		if(empty(ASLang::get($key, $bindings))){
			return $key;
			}else{
			return ASLang::get($key, $bindings);
		}
		
		// return
	}
	
	/**
		* Send an HTTP response.
		*
		* @param array $data
		* @param $statusCode
	*/
	function respond(array $data, $statusCode = 200)
	{
		$response = new ASResponse();
		
		$response->send($data, $statusCode);
	}
	
	/**
		* Get container instance or resolve some class/service
		* out of the container.
		* @param null $service
		* @return mixed
	*/
	function app($service = null)
	{
		$c = ASContainer::getInstance();
		
		if (is_null($service)) {
			return $c;
		}
		
		return $c[$service];
	}
	
	function getdatetime($datetime,$type = 0) {
		
		$date = new DateTime($datetime);
		if($type == 1){
			$dati = $date->format('Y-m-d');
			}elseif($type == 2){
			$dati = $date->format('d-M-Y h:i:s A ');
			}elseif($type == 3){
			$dati = $date->format('d F Y');
			}elseif($type == 4){
			$dati = $date->format('D d F Y');
			}elseif($type == 5){
			$dati = $date->format('F Y');
			}elseif($type == 6){
			$dati = $date->format('d M Y H:i:s A');
			}elseif($type == 7){
			$dati = $date->format('m/d/Y');
			}
			else if($type == 8){
				$dati = $date->format('H:i:s');
			}else{
			$dati = $date->format('Y-m-d H:i:s');
		}
		return $dati;
		
	}
	
	function gettoken($length = 8) {
		@$getuniquetime =  time(true);
		$key = '';
		$keys = array_merge(range(0, 9), range(0, 9));
		
		for ($i = 0; $i < 5; $i++) {
			$key .= $keys[array_rand($keys)];
		}
		$key = substr($getuniquetime.$key, -10);
		return $key;
	}
	
	function randomtoken($length) {
		$key = '';
		$keys = array_merge(range(0, 9), range('A', 'Z'));
		
		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}
		
		return $key;
	}
	
	function sendpostdata($url,$post){
		$key = md5(date('ymdhi'));
		$ch = curl_init($url.'/api.php?key='.$key);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_VERBOSE, true);	  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($post))                                                                       
		);  
		$result = curl_exec($ch);
		curl_close($ch); 
		return $result;
	}
	
	function getCss($cssUrl,$minify = true,$unminify = true)
	{
		if(MINIFY_ENABLE && $unminify){
			if($minify){
				echo  '<style>'.minify_css(file_get_contents($cssUrl)).'</style>'; 
				}else{
				echo  '<style>'.file_get_contents($cssUrl).'</style>'; 
			}
			}else{
			echo '<link rel="stylesheet" type="text/css" href="'.$cssUrl.'">';
		}
	}
	
	function getJs($jsUrl,$minify = true,$unminify = true)
	{
		if(MINIFY_ENABLE && $unminify){
			if($minify){
				echo  '<script>'.minify_js(file_get_contents($jsUrl)).'</script>'; 
				}else{
				echo  '<script>'.file_get_contents($jsUrl).'</script>'; 
			}
			}else{
			echo '<script src="'.$jsUrl.'"></script>';
		}
	}
	
	function getActiveMenu($uri,$menuSection){
		$geturi = $uri == $menuSection ? 'active' : '';
		echo $geturi;
	}
	
	function testLoad(){
		global $GetProduct;
		var_dump($GetProduct);
		// print_r($GLOBALS['GetProduct']);
		
	}
	
	
	
