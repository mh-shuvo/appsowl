<?php
	
	class ASAgentView
	{
		private $route;
		private $currentUser;
		
		public function __construct($route){
			$this->route = $route;
			$this->currentUser = app('current_user');
		}
				
			public function GetCSRF(){
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && ! ASCsrf::validate($_POST)) {
				die('Invalid CSRF token.');
			}
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
		
		public function getStringBetween($string, $start, $end){
			$string = ' ' . $string;
			$ini = strpos($string, $start);
			if ($ini == 0) return '';
			$ini += strlen($start);
			$len = strpos($string, $end, $ini) - $ini;
			return substr($string, $ini, $len);
		}
		
		
		public function getLogout(){	
			ASSession::destroySession();
			redirect('/');
		}
		
		/*==========================================*/
		
		public function GetAgentHome()
		{
			$this->getPageStart();
			include dirname(__File__).'/../modules/agent/home.php';
			$this->getPageEnd();
		}
	
		public function GetNewUser()
		{
			$this->getPageStart();
			include dirname(__File__).'/../modules/agent/new-user.php';
			$this->getPageEnd();
		}
		public function UserInfo()
		{
			$this->getPageStart();
			include dirname(__File__).'/../modules/agent/user-info.php';
			$this->getPageEnd();
		}
		public function SubscriptionHistory()
		{
			$this->getPageStart();
			include dirname(__File__).'/../modules/agent/subscribe-history.php';
			$this->getPageEnd();
		}
		public function Profile()
		{
			$this->getPageStart();
			include dirname(__File__).'/../modules/agent/profile.php';
			$this->getPageEnd();
		}
		public function ReceivedPayment()
		{
			$this->getPageStart();
			include dirname(__File__).'/../modules/agent/received-payment.php';
			$this->getPageEnd();
		}
		public function WithdrawalPayment()
		{
			$this->getPageStart();
			include dirname(__File__).'/../modules/agent/withdrwal-payment.php';
			$this->getPageEnd();
		}
		
	}
