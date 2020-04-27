<?php
	
	class ASWebView
	{
		private $route;
		
		public function __construct($route){
			$this->route = $route;
			$this->currentUser = app('current_user');
		}
		
		public function getHome(){
			$this->getPageStart();
			$currentUser = app('current_user');
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$current_url = urlencode($url=$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			if (isset($this->route['page']) && file_exists(dirname(__FILE__) .'/../modules/'.DEFAULT_PAGE.'/'.$this->route['page'].'.php')) {
				include dirname(__FILE__) .'/../modules/'.DEFAULT_PAGE.'/'.$this->route['page'].'.php';
				}elseif(!isset($this->route['page']) && file_exists(dirname(__FILE__) .'/../modules/'.DEFAULT_PAGE.'/home.php')){
				include dirname(__FILE__) .'/../modules/'.DEFAULT_PAGE.'/home.php';
				}else{
				include dirname(__FILE__) .'/../modules/'.DEFAULT_PAGE.'/404.php';
			}
			$this->getPageEnd();
		}
		
		public function getVerify()
		{
			$this->getPageStart();	
			$currentUser = app('current_user');			
			include dirname(__FILE__).'/../modules/web/verify.php';
			$this->getPageEnd();
		}
		public function getPricing()
		{
			$this->getPageStart();	
			$currentUser = app('current_user');			
			include dirname(__FILE__).'/../modules/web/pricing.php';
			$this->getPageEnd();
		}
		public function getAgent()
		{
			$this->getPageStart();	
			$currentUser = app('current_user');			
			include dirname(__FILE__).'/../modules/web/agent.php';
			$this->getPageEnd();
		}
		public function getPosDetails()
		{
			$this->getPageStart();
			$currentUser = app('current_user');			
			include dirname(__FILE__).'/../modules/web/pos-details.php';
			$this->getPageEnd();
		}
		public function getTutorial()
		{
			$this->getPageStart();		
			include dirname(__FILE__).'/../modules/web/tutorial.php';
			$this->getPageEnd();
		}
		public function getBlog()
		{
			$this->getPageStart();
			$currentUser = app('current_user');			
			include dirname(__FILE__).'/../modules/web/blog.php';
			$this->getPageEnd();
		}
		public function getSubscribePosSetting()
		{
			$this->getPageStart();
			$currentUser = app('current_user');			
			include dirname(__FILE__).'/../modules/web/subscribe-pos-setting.php';
			$this->getPageEnd();
		}
		public function getPosSetting()
		{
			$this->getPageStart();
			$currentUser = app('current_user');			
			include dirname(__FILE__).'/../modules/web/pos-setting.php';
			$this->getPageEnd();
		}
		public function getSoftRegistration()
		{
			$this->getPageStart();
			$currentUser = app('current_user');			
			include dirname(__FILE__).'/../modules/web/soft-reg.php';
			$this->getPageEnd();
		}
		
		private function getPageStart(){
			ob_start();
		}
		
		private function getPageEnd(){
			$body = ob_get_contents();
			ob_get_clean();
			$header = $this->getStringBetween($body, '[header]', '[/header]');
			$footer = $this->getStringBetween($body, '[footer]', '[/footer]');
			$body = str_replace('</head>', $header.'</head>', $body);
			$body = str_replace('</body>', $footer.'</body>', $body);
			$body = str_replace('[header]'.$header.'[/header]', ' ', $body);
			$body = str_replace('[footer]'.$footer.'[/footer]', ' ', $body);
			
			if(MINIFY_ENABLE){
				$body = minify_html($body);
			}
			echo $body;
			
		}
		
		public function GetCSRF(){
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && ! ASCsrf::validate($_POST)) {
				die('Invalid CSRF token.');
			}
		}
		
		public function getStringBetween($string, $start, $end){
			$string = ' ' . $string;
			$ini = strpos($string, $start);
			if ($ini == 0) return '';
			$ini += strlen($start);
			$len = strpos($string, $end, $ini) - $ini;
			return substr($string, $ini, $len);
		}
		
		public function getAjax(){	
			$this->GetCSRF();
			$currentUser = app('current_user');
			$action = $_POST['action'];
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$current_url = urlencode($url=$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			include dirname(__FILE__) .'/ASAjax.php';
		}
		
		public function getLogout(){	
			ASSession::destroySession();
			redirect('/');
		}
		
		
		public function getDashBoard()
		{
			$this->getPageStart();
			$currentUser = app('current_user');
			include dirname(__File__).'/../modules/web/dashboard.php';
			
			$this->getPageEnd();
			
		}
		public function getAccountSetting()
		{
			$this->getPageStart();
			$currentUser = app('current_user');
			include dirname(__File__).'/../modules/web/account-setting.php';
			
			$this->getPageEnd();
			
		}
		public function getHistory()
		{
			$this->getPageStart();
			$currentUser = app('current_user');
			include dirname(__File__).'/../modules/web/history.php';
			
			$this->getPageEnd();
			
		}
		public function getPaymentHistory()
		{
			$this->getPageStart();
			$currentUser = app('current_user');
			include dirname(__File__).'/../modules/web/payment-history.php';
			
			$this->getPageEnd();
			
		}
		public function GetAgentInfo()
		{
			$this->getPageStart();
			include dirname(__File__).'/../modules/web/agent-info.php';
			$this->getPageEnd();
		}
		
		
	}
