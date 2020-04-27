<?php
	
	class ASView
	{
		private $route;
		
		private $uri;
		
		private $currentUser;
		
		public function __construct($route,$uri = null){
			$this->route = $route;
			$this->currentUser = app('current_user');
			$this->uri = $uri;
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
		
		public function getLogout(){	
			ASSession::destroySession();
			redirect('/');
		}
		
		public function view(){
			$this->getPageStart();
			if(file_exists(dirname(__FILE__) .'/../modules/'.$this->uri.'.php')){
				include dirname(__FILE__) .'/../modules/'.$this->uri.'.php';
				}else{
				include dirname(__FILE__) .'/../'. PAGE_404;
			}
			$this->getPageEnd();
		}
		
		public function loadplugin(){
			
			$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../');
			$filesystem = new League\Flysystem\Filesystem($adapter);
			$SubDomainName = str_replace(".", "", $_SERVER['SERVER_NAME']);
			$jsonPluginLocation = 'user-config/'.$SubDomainName.'.json';
			$jsonPlugin = $filesystem->read($jsonPluginLocation);
			$jsonPluginData = json_decode($jsonPlugin);
			$pluginSexists = $filesystem->has($jsonPluginLocation);
			if($pluginSexists){
				$pluginName = $this->uri;
				if(isset($jsonPluginData->plugins->$pluginName)){
					$fileLocation = 'plugins/'.$jsonPluginData->plugins->$pluginName->plugin_page_name.'/'.$jsonPluginData->plugins->$pluginName->plugin_page_file.'.php';
					$fileSexists = $filesystem->has($fileLocation);
					if($fileSexists){
						$this->getPageStart();
						include dirname(__FILE__) .'/../'.$fileLocation;
						$this->getPageEnd();
						}else{
						echo "Plugin File Missing";
						return false;
					}	
				}	
			}
		}
		
		public function loadpluginextra(){
			preg_match('#\((.*?)\)#', $this->uri, $match);
			$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../');
			$filesystem = new League\Flysystem\Filesystem($adapter);
			$SubDomainName = str_replace(".", "", $_SERVER['SERVER_NAME']);
			$jsonPluginLocation = 'user-config/'.$SubDomainName.'.json';
			$jsonPlugin = $filesystem->read($jsonPluginLocation);
			$jsonPluginData = json_decode($jsonPlugin);
			$pluginSexists = $filesystem->has($jsonPluginLocation);
			if($pluginSexists){
				$pluginName = str_replace($match[0], "", $this->uri);
				if(isset($jsonPluginData->plugins->$pluginName) && $jsonPluginData->plugins->$pluginName->subscribe_status != 'cancel'){
					if(in_array($match[1], explode(',',$jsonPluginData->plugins->$pluginName->plugin_extra_page_file))) {
						$fileLocation = 'plugins/'.$jsonPluginData->plugins->$pluginName->plugin_page_name.'/'.$match[1].'.php';
						$fileSexists = $filesystem->has($fileLocation);
						if($fileSexists){
							$this->getPageStart();
							include dirname(__FILE__) .'/../'.$fileLocation;
							$this->getPageEnd();
							}else{
							echo "Plugin File Missing";
							return false;
						}	
					}	
				}	
			}
		}
		
		public function post(){
			$this->GetCSRF();
			$action = $_POST['action'];
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$current_url = urlencode($url = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			include dirname(__FILE__) .'/'.$this->uri.'.php';
		}
		
	}
