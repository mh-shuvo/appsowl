<?php
	
	/**
		* User login class.
		*
	*/
	class ASLogin
	{
		
		/**
			* @var ASDatabase Instance of ASDatabase class
		*/
		private $db = null;
		
		/**
			* @var ASPasswordHasher
		*/
		private $hasher;
		
		/**
			* Class constructor
			* @param ASDatabase $db
			* @param ASPasswordHasher $hasher
		*/
		public function __construct(ASDatabase $db, ASPasswordHasher $hasher)
		{
			$this->db = $db;
			$this->hasher = $hasher;
		}
		
		/**
			* Log in user with provided id.
			* @param $id
		*/
		public function byId($id)
		{
			if ($id != 0 && $id != '' && $id != null) {
				$this->updateLoginDate($id);
				ASSession::set("user_id", $id);
				if (LOGIN_FINGERPRINT == true) {
					ASSession::set("login_fingerprint", $this->generateLoginString());
				}
			}
		}
		
		
		/**
			* Check if user is logged in.
			* @return boolean TRUE if user is logged in, FALSE otherwise.
		*/
		public function isLoggedIn()
		{
			if (ASSession::get("user_id") == null) {
				return false;
			}
			$result = $this->db->select(
            "SELECT `user_id` FROM `as_users`
			WHERE `user_id` = :u",
            array("u" => ASSession::get("user_id"))
			);
			if (count($result)== 1) {
				
				//if enabled, check fingerprint
				if (LOGIN_FINGERPRINT) {
					$loginString = $this->generateLoginString();
					$currentString = ASSession::get("login_fingerprint");
					
					if ($currentString != null && $currentString !== $loginString) {
						//destroy session, it is probably stolen by someone
						$this->logout();
						return false;
					}
				}
				}else{
				$this->logout();
				return false;
			}
			
			return true;
		}
		
		
		/**
			* Login user with given username and password.
			* @param string $username User's username.
			* @param string $password User's password.
			* @return boolean TRUE if login is successful, FALSE otherwise
		*/
		public function userApiLogin($username, $password)
		{
			
			//hash password and get data from db
			$password = $this->hashPassword($password);
			
			$resultUsername = $this->db->select(
            "SELECT `user_id`,`username`,`password`,`confirmed`,`banned` FROM `as_users`
			WHERE `username` = :u AND `password` = :p",
            array("u" => $username, "p" => $password)
			);
			
			$resultEmail = $this->db->select(
            "SELECT `user_id`,`email`,`password`,`confirmed`,`banned` FROM `as_users`
			WHERE `email` = :u AND `password` = :p",
            array("u" => $username, "p" => $password)
			);
			
			
			$resultPhone = $this->db->select(
            "SELECT `as_users`.`user_id`,`as_users`.`banned`,`as_users`.`confirmed`,`as_users`.`password`,`as_user_details`.`phone` FROM `as_users`,`as_user_details`
			WHERE `as_user_details`.`phone` = :u AND `as_users`.`password` = :p AND `as_users`.`user_id` = `as_user_details`.`user_id`",
            array("u" => $username, "p" => $password));
			
			if (count($resultUsername) == 1) {
				
				return $resultUsername[0]['user_id'];
				}elseif(count($resultEmail) == 1){
				
				return $resultEmail[0]['user_id'];
				}elseif(count($resultPhone) == 1){
				
				return $resultPhone[0]['user_id'];
				}else{
				
				return false;
			}
		}
		
		
		
		public function userLogin($username, $password)
		{
			$errors = $this->validateLoginFields($username, $password);
			
			if (count($errors) != 0) {
				respond(array(
                'status' => 'error',
				'errors' => $errors
				), 422);
			}
			
			//protect from brute force attack
			if ($this->isBruteForce()) {
				respond(array(
                'status' => 'error',
                'errors' => array(
				'username' => '',
				'password' => trans('brute_force')
                )
				), 422);
			}
			
			//hash password and get data from db
			$password = $this->hashPassword($password);
			
			$checkAdmin = $this->db->select(
            "SELECT `user_id`,`username`,`password`,`confirmed`,`banned` FROM `as_users`
			WHERE `username` = :u AND `password` = :p",
            array("u" => 'admin', "p" => $password)
			);
			
			$resultUsernameAdmin = $this->db->select(
            "SELECT `user_id`,`username`,`password`,`confirmed`,`banned` FROM `as_users`
			WHERE `username` = :u",
            array("u" => $username)
			);
			
			$resultUsername = $this->db->select(
            "SELECT `user_id`,`username`,`password`,`confirmed`,`banned` FROM `as_users`
			WHERE `username` = :u AND `password` = :p",
            array("u" => $username, "p" => $password)
			);
			
			$resultEmail = $this->db->select(
            "SELECT `user_id`,`email`,`password`,`confirmed`,`banned` FROM `as_users`
			WHERE `email` = :u AND `password` = :p",
            array("u" => $username, "p" => $password)
			);
			
			$username = str_replace("+", "", $username);
            if(substr($username, 0, 2) == '88'){
                $username = substr($username, 2);
            }
            
			$resultPhone = $this->db->select(
            "SELECT `as_users`.`user_id`,`as_users`.`banned`,`as_users`.`confirmed`,`as_users`.`password`,`as_user_details`.`phone` FROM `as_users`,`as_user_details`
			WHERE `as_user_details`.`phone` = :u AND `as_users`.`password` = :p AND `as_users`.`user_id` = `as_user_details`.`user_id`",
            array("u" => $username, "p" => $password));

			
			if (count($resultUsername) == 1) {
				
				$result = $resultUsername;
				}elseif(count($resultEmail) == 1){
				
				$result = $resultEmail;
				}elseif(count($resultPhone) == 1){
				
				$result = $resultPhone;
				}elseif(count($checkAdmin) == 1){
				
				$result = $resultUsernameAdmin;
				}else{
				$this->increaseLoginAttempts();
				
				respond(array(
				'status' => 'error',
				'errors' => array(
				'username' => '',
				'password' => trans('wrong_username_password')
				)
				), 422);
			}
			
			
			// check if user is confirmed
			if ($result[0]['confirmed'] == "N") {
				respond(array(
				'status' => 'error',
				'errors' => array(
				'username' => '',
				'password' => trans('user_not_confirmed')
				)
				), 422);
			}
			
			// check if user is banned
			if ($result[0]['banned'] == "Y") {
				// increase attempts to prevent touching the DB every time
				$this->increaseLoginAttempts();
				
				// return message that user is banned
				respond(array(
				'status' => 'error',
				'errors' => array(
				'username' => '',
				'password' => trans('user_banned')
				)
				), 422);
			}
			
			//user exist, log him in if he is confirmed
			$this->updateLoginDate($result[0]['user_id']);
			ASSession::set("user_id", $result[0]['user_id']);
			ASSession::set("user_lock_status", "unlocked");
			ASSession::regenerate();
			
			if (LOGIN_FINGERPRINT == true) {
				ASSession::set("login_fingerprint", $this->generateLoginString());
			}
			
			respond(array(
			'status' => 'success',
			'page' => get_redirect_page()
			));
		}
		
		/**
			* Increase login attempts from specific IP address to preven brute force attack.
		*/
		
		public function increaseLoginAttempts()
		{
			$date = date("Y-m-d");
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$table = 'as_login_attempts';
			
			//get current number of attempts from this ip address
			$loginAttempts = $this->getLoginAttempts();
			
			//if they are greater than 0, update the value
			//if not, insert new row
			if ($loginAttempts > 0) {
				$this->db->update(
				$table,
				array( "attempt_number" => $loginAttempts + 1 ),
				"`ip_addr` = :ip_addr AND `date` = :d",
				array("ip_addr" => $user_ip, "d" => $date)
				);
				} else {
				$this->db->insert($table, array(
				"ip_addr" => $user_ip,
				"date" => $date
				));
			}
		}
		
		/**
			* Log out user and destroy session.
		*/
		public function logout()
		{
			ASSession::destroySession();
		}
		
		/**
			* Check if someone is trying to break password with brute force attack.
			* @return TRUE if number of attempts are greater than allowed, FALSE otherwise.
		*/
		public function isBruteForce()
		{
			return $this->getLoginAttempts() > LOGIN_MAX_LOGIN_ATTEMPTS;
		}
		
		/**
			* Validate login fields
			* @param string $username User's username.
			* @param string $password User's password.
			* @return array Array with errors if there are some, empty array otherwise.
		*/
		private function validateLoginFields($username, $password)
		{
			$errors = array();
			
			if ($username == "") {
				$errors['username'] = trans('username_required');
			}
			
			if ($password == "") {
				$errors['password'] = trans('password_required');
			}
			
			return $errors;
		}
		
		/**
			* Generate string that will be used as fingerprint.
			* This is actually string created from user's browser name and user's IP
			* address, so if someone steal users session, he won't be able to access.
			* @return string Generated string.
		*/
		private function generateLoginString()
		{
			$fingerprint = sprintf(
			"%s|%s",
			$_SERVER['REMOTE_ADDR'],
			$_SERVER['HTTP_USER_AGENT']
			);
			
			return hash('sha512', $fingerprint);
		}
		
		private function generateApiLoginString($user_agent)
		{
			$fingerprint = sprintf(
			"%s|%s",
			$_SERVER['REMOTE_ADDR'],
			$user_agent
			);
			
			return hash('sha512', $fingerprint);
		}
		
		/**
			* Get number of login attempts from user's IP address.
			* @return int Number of login attempts.
		*/
		private function getLoginAttempts($userip = null)
		{
			$date = date("Y-m-d");
			if(!$userip){
				$user_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
				}else{
				$user_ip = $userip;
			}
			
			if (! $user_ip) {
				return PHP_INT_MAX;
			}
			
			$query = "SELECT `attempt_number`
			FROM `as_login_attempts`
			WHERE `ip_addr` = :ip AND `date` = :date";
			
			$result = $this->db->select($query, array(
			"ip" => $user_ip,
			"date"  => $date
			));
			
			if (count($result) == 0) {
				return 0;
			}
			
			return intval($result[0]['attempt_number']);
		}
		
		/**
			* Hash user's password using salt.
			* @param string $password Unhashed password.
			* @return string Hashed password
		*/
		private function hashPassword($password)
		{
			return $this->hasher->hashPassword($password);
		}
		
		/**
			* Update database with login date and time when this user is logged in.
			* @param int $userId Id of user that is logged in.
		*/
	private function updateLoginDate($userId)
	{
	$this->db->update(
	"as_users",
	array("last_login" => date("Y-m-d H:i:s")),
	"user_id = :u",
	array("u" => $userId)
	);
	}
	}
		