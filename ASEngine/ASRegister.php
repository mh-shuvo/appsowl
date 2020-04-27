<?php
	
	/**
		* User registration class.
		*
	*/
	class ASRegister
	{
		/**
			* @var ASEmail Instance of ASEmail class
		*/
		private $mailer;
		
		/**
			* @var ASDatabase Instance of ASDatabase class
		*/
		private $db = null;
		
		/**
			* @var ASValidator
		*/
		private $validator;
		
		/**
			* @var ASLogin
		*/
		private $login;
		
		/**
			* @var ASPasswordHasher
		*/
		private $hasher;
		
		/**
			* Class constructor
			* @param ASDatabase $db
			* @param ASEmail $mailer
			* @param ASValidator $validator
			* @param ASLogin $login
			* @param ASPasswordHasher $hasher
		*/
		public function __construct(
        ASDatabase $db,
        ASEmail $mailer,
        ASValidator $validator,
        ASLogin $login,
        ASPasswordHasher $hasher
		) {
			$this->db = $db;
			$this->mailer = $mailer;
			$this->validator = $validator;
			$this->login = $login;
			$this->hasher = $hasher;
		}
		
		/**
			* Register user.
			* @param array $data User details provided during the registration process.
			* @throws Exception
		*/
		public function register($data)
		{
			if ($errors = $this->validateUser($data)) {
				respond(array(
                "status" => "error",
                "errors" => $errors
				), 422);
			}
			
			$key = $this->generateKey();
			$smskey = rand(100000 , 999999);
			
			$current_country_code = str_replace("+", "", $data['country_code']);
			$currentCountrys = $this->db->select("SELECT * FROM `as_country` WHERE `phonecode` = :id", array( 'id' => $current_country_code));
			$currentCountry = count($currentCountrys) > 0 ? $currentCountrys[0] : null;
			
			MAIL_CONFIRMATION_REQUIRED ? $confirmed = 'N' : $confirmed = 'Y';
			
			$this->db->insert('as_users', array(
            "email" => $data['reg_email'],
            "username" => strip_tags($data['reg_email']),
            "password" => $this->hashPassword($data['password']),
            "confirmed" => $confirmed,
            "confirmation_key" => $key,
            "user_role" => '3',
            "sms_verify_key" => $smskey,
            "register_date" => date("Y-m-d")
			));
			
			$userId = $this->db->lastInsertId();
			
			if (ASSession::get("user_id")) {
				$agentid = ASSession::get("user_id");
				}else{
				$agentid = "null";
			}
			
			$this->db->insert('as_user_details', array(
            'user_id' => $userId,
            'phone' => str_replace($data['country_code'], "", $data['reg_phone']),
            'first_name' => $data['reg_first_name'],
            'last_name' => $data['reg_last_name'],
            'store_id' => 1,
            'agent_id' => $agentid,
            'country' => $currentCountry['name'],
            'country_code' => $data['country_code'],
			));
			
			$smsbody = "Welcome to ".WEBSITE_NAME.". Your Verify key is : ".$smskey;
			app('admin')->sendsms(str_replace("+", "", $data['reg_phone']),$smsbody);
			
			if (MAIL_CONFIRMATION_REQUIRED) {
				$this->mailer->confirmationEmail($data['reg_email'], $key);
				$msg = trans('success_registration_with_confirm');
				} else {
				$msg = trans('success_registration_no_confirm');
			}
			
			if (ASSession::get("user_id")) {
				respond(array(
				"status" => "success",
				"page" => "User create successfully."
				));
				}else{
				ASSession::set("user_id", $userId);
				ASSession::regenerate();
				
				if (LOGIN_FINGERPRINT == true) {
					ASSession::set("login_fingerprint", $this->generateLoginString());
				}
				
				respond(array(
				"status" => "success",
				"page" => "verify" 
				));
			}
		}
		
		private function generateLoginString()
		{
			$fingerprint = sprintf(
			"%s|%s",
			$_SERVER['REMOTE_ADDR'],
			$_SERVER['HTTP_USER_AGENT']
			);
			
			return hash('sha512', $fingerprint);
		}
		
		public function GetUserSubmit($data,$userId,$country_code,$domain_id,$newdb)
		{
			
			if($data['user_id'] == "null"){
				if ($errors = $this->validateAddedUser($data,$country_code)) {
					respond(array(
					"status" => "error",
					"errors" => $errors
					), 422);
				}
				
				$username = str_replace($country_code, "", $data['user_mobile']);
				
				$this->db->insert("as_users",  array(
				"email" => $data['user_email'],
				"username" => strip_tags($username),
				"password" => $this->hashPassword($data['user_password']),
				"confirmed" => "Y",
				"confirmation_key" => "",
				"sms_verify_key" => "",
				"sms_confirmed" => "Y",
				"register_date"=> date("Y-m-d H:i:s")
				));
				
				$last_userId = $this->db->lastInsertId();
				
				$this->db->insert("as_user_details",  array(
				"user_id"=> $last_userId,
				"domain_id" => $domain_id,
				"added_by" => $userId,
				"first_name"=> $data['first_name'],
				"last_name"=> $data['last_name'],
				"phone"=> $data['user_mobile'],
				"address"=> $data['user_address'],
				"pos_sale"=> $data['pos_sale'],
				"country_code"=> $country_code,
				"pos_category"=> $data['pos_category'],
				"pos_unit"=> $data['pos_unit'],
				"pos_product"=> $data['pos_product'],
				"pos_supplier"=> $data['pos_supplier'],
				"pos_stock"=> $data['pos_stock'],
				"pos_customer"=> $data['pos_customer'],
				"pos_return"=> $data['pos_return'],
				"pos_damage"=> $data['pos_damage'],
				"pos_report"=> $data['pos_report']
				));
				
				
				
				respond(array(
				"status" => "success",
				"message" => "This ".$data['first_name']." ".$data['first_name']." added successfully"
				));
				
				}else{
				// Manage User Update
				$this->db->update(
				'as_users',array(
				"email" => $data['user_email']
				),
				"`user_id` = :userid",
				array("userid" => $data['user_id']));
				
				$this->db->update(
				'as_user_details',
				array(
				"first_name"=> $data['first_name'],
				"last_name"=> $data['last_name'],
				"phone"=> $data['user_mobile'],
				"address"=> $data['user_address'],
				"pos_sale"=> $data['pos_sale'],
				"country_code"=> $country_code,
				"pos_category"=> $data['pos_category'],
				"pos_unit"=> $data['pos_unit'],
				"pos_product"=> $data['pos_product'],
				"pos_supplier"=> $data['pos_supplier'],
				"pos_stock"=> $data['pos_stock'],
				"pos_customer"=> $data['pos_customer'],
				"pos_return"=> $data['pos_return'],
				"pos_damage"=> $data['pos_damage'],
				"pos_report"=> $data['pos_report']
				),
				"`user_id` = :userid",
				array("userid" => $data['user_id'])
				);
				
				respond(array(
				"status" => "success",
				"message" => "This ".$data['first_name']." ".$data['first_name']." update successfully"
				));
			}
		}
		
		public function validateAddedUser($data,$country_code)
		{
			$errors = array();
			
			if ($this->validator->isEmpty($data['user_mobile'])) {
				$errors['user_mobile'] = trans('phone_required');
			}
			
			if ($this->validator->isEmpty($data['user_password'])) {
				$errors['user_password'] = trans('password_required');
			}
			
			if ($data['user_password'] !== $data['confirm_password']) {
				$errors['confirm_password'] = trans('passwords_dont_match');
			}
			
			if (! isset($errors['user_mobile']) && $this->validator->phoneExist(str_replace($country_code, "", $data['user_mobile']))) {
				$errors['user_mobile'] = trans('phone_taken');
			}
			
			return $errors;
		}
		
		public function AgentRegistration($data){
			
			$existAgent=app('admin')->getwhereid('as_users','username',$data['agent_phone']);
			$message='';
			if($existAgent==null){
				$username = str_replace($data['country_code'], "", $data['agent_phone']);
				$this->db->insert('as_users', array(
				"email" => $data['agent_email'],
				"username" => strip_tags($username),
				"password" => $this->hashPassword(hash('sha512', $data['agent_password'])),
				"user_role" => '2',
				"confirmed" => 'Y',
				"banned" => 'Y',
				"register_date" => date("Y-m-d")
				));
				
				$userId = $this->db->lastInsertId();
				
				$this->db->insert('as_user_details', array(
				'user_id' => $userId,
				'phone' => $data['agent_phone'],
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				'address' => $data['agent_present_address'],
				'permanent_address' => $data['agent_permanent_address'],
				'profile_image' => $data['agent_image'],
				'nid_card' => $data['agent_nid'],
				// 'attach' => $data['agent_attach'],
				"zone" => $data['agent_zone'],
				"area" => $data['agent_area'],
				));
				$message="Your Account Successfully Created";
				$status="success";
			}
			else{
				$message="Your Phone already used";
				$status="error";
			}
			respond(array(
			"status" => $status,
			"message" => $message,
			));
		}
		
		/**
			* Get user by email.
			* @param $email string User's email
			* @return mixed User info if user with provided email exist, empty array otherwise.
		*/
		public function getConfirmSmsVerify($userId,$data){
			$result = $this->db->select(
			"SELECT * FROM `as_users`
			WHERE `sms_verify_key` = :v AND `user_id` = :id",
			array("v" => $data['verification_number'], "id" => $userId)
			);
			
			if (count($result) !== 1) {
				respond(array(
				'status' => 'error',
				'errors' => array(
				'verification_number' => trans('wrong_verification_number')
				)
				), 422);
				}else{
				
				$this->db->update(
				"as_users",
				array("sms_confirmed" => "Y"),
				"user_id = :u",
				array("u" => $userId)
				);
				
				respond(array(
				"status" => "success",
				"massage" => "verified" 
				));
				
			}
			
			return $result;
		}
		
		public function GetResendSms($phone_number,$userId)
		{
			$result = $this->db->select(
			"SELECT * FROM `as_user_details`
			WHERE `user_id` = :id",
			array("id" => $userId)
			);
			$result = count($result) > 0 ? $result[0] : null;
			
			
			$this->db->update(
			'as_user_details',
			array(
			"phone" => $phone_number
			),
			"`user_id` = :userid",
			array("userid" => $userId)
			);
			
			$smskey = rand(100000 , 999999);
			
			$this->db->update(
			'as_users',
			array(
			"sms_verify_key" => $smskey,
			),
			"`user_id` = :userid",
			array("userid" => $userId)
			);
			
			$phone_number = $result['country_code'].$phone_number;
			$smsbody = "Welcome to ".WEBSITE_NAME.". Your Verify key is : ".$smskey;

			app('admin')->sendsms($phone_number,$smsbody);
		}
		
		public function getByEmail($email)
		{
			$result = $this->db->select(
			"SELECT * FROM `as_users` WHERE `email` = :e",
			array('e' => $email)
			);
			
			if (count($result) > 0) {
				return $result[0];
			}
			
			return $result;
			}
			
		
		/**
			* Check if user has already logged in via specific provider and return user's data if he does.
			* @param $provider string oAuth provider (Facebook, Twitter or Gmail)
			* @param $id string Identifier provided by provider
			* @return array|mixed User info if user has already logged in via specific provider, empty array otherwise.
		*/
		public function getBySocial($provider, $id)
		{
			$result = $this->db->select(
			'SELECT as_users.*
			FROM as_social_logins, as_users 
			WHERE as_social_logins.provider = :p AND as_social_logins.provider_id = :id
			AND as_users.user_id = as_social_logins.user_id',
			array('p' => $provider, 'id' => $id)
			);
			
			if (count($result) > 0) {
				return $result[0];
			}
			
			return $result;
		}
		
		/**
			* Check if user is already registered via some social network.
			* @param $provider string Name of the provider ( twitter, facebook or google )
			* @param $id string Provider identifier
			* @return bool TRUE if user exist in database (already registred), FALSE otherwise
		*/
		public function registeredViaSocial($provider, $id)
		{
			$result = $this->getBySocial($provider, $id);
			
			if (count($result) === 0) {
				return false;
			}
			
			return true;
		}
		
		/**
			* Connect user's social account with his account at this system.
			* @param $userId int User Id on this system
			* @param $provider string oAuth provider (Facebook, Twitter or Gmail)
			* @param $providerId string Identifier provided by provider.
		*/
		public function addSocialAccount($userId, $provider, $providerId)
		{
			$this->db->insert('as_social_logins', array(
			'user_id' => $userId,
			'provider' => $provider,
			'provider_id' => $providerId,
			'created_at' => date('Y-m-d H:i:s')
			));
		}
		
		/**
			* Send forgot password email.
			* @param string $email Provided email.
			* @return bool|mixed|string
			* @throws Exception
		*/
		public function forgotPassword($email)
		{
			if ($error = $this->validateForgotPasswordEmail($email)) {
				respond(array(
				'errors' => array('email' => $error)
				), 422);
			}
			
			//ok, no validation errors, we can proceed
			
			//generate password reset key
			$key = $this->generateKey();
			
			//write key to db
			$this->db->update(
			'as_users',
			array(
			"password_reset_key" => $key,
			"password_reset_confirmed" => 'N',
			"password_reset_timestamp" => date('Y-m-d H:i:s')
			),
			"`email` = :email",
			array("email" => $email)
			);
			
			$this->login->increaseLoginAttempts();
			
			//send email
			$this->mailer->passwordResetEmail($email, $key);
			
			respond(array(
			'status' => 'success'
			));
		}
		
		/**
			* @param $email
			* @return mixed|null|string
		*/
		private function validateForgotPasswordEmail($email)
		{
			if ($email == "") {
				return trans('email_required');
			}
			
			if (! $this->validator->emailValid($email)) {
				return trans('email_wrong_format');
			}
			
			if (! $this->validator->emailExist($email)) {
				return trans('email_not_exist');
			}
			
			if ($this->login->isBruteForce()) {
				return trans('brute_force');
			}
			
			return null;
		}
		
		
		/**
			* Reset user's password if password reset request has been made.
			* @param string $newPass New password.
			* @param string $passwordResetKey Password reset key sent to user
			* in password reset email.
		*/
		public function resetPassword($newPass, $passwordResetKey)
		{
			if ($error = $this->validatePasswordReset($newPass, $passwordResetKey)) {
				respond(array(
				'errors' => array('new_password' => $error)
				), 422);
			}
			
			$pass = $this->hashPassword($newPass);
			
			$this->db->update(
			'as_users',
			array("password" => $pass, 'password_reset_confirmed' => 'Y', 'password_reset_key' => ''),
			"`password_reset_key` = :prk ",
			array("prk" => $passwordResetKey)
			);
			
			respond(array('status' => 'success'));
		}
		
		/**
			* @param $newPassword
			* @param $passwordResetKey
			* @return mixed|null|string
		*/
		private function validatePasswordReset($newPassword, $passwordResetKey)
		{
			if ($this->validator->isEmpty($newPassword)) {
				return trans('field_required');
			}
			
			if (! $this->validator->prKeyValid($passwordResetKey)) {
				return trans('invalid_password_reset_key');
			}
			
			return null;
		}
		
		/**
			* Hash a given password.
			*
			* @param string $password Un-hashed password.
			* @return string Hashed password.
		*/
		public function hashPassword($password)
		{
			return $this->hasher->hashPassword($password);
		}
		
		/**
			* Generate two random numbers and store them into the session.
			* Numbers are used during the registration to prevent bots to create fake accounts.
		*/
		public function botProtection()
		{
			ASSession::set("bot_first_number", rand(1, 9));
			ASSession::set("bot_second_number", rand(1, 9));
		}
		
		/**
			* Validate user provided fields.
			* @param $data array User provided fields and id's of those fields that will be
			* used for displaying error messages on client side.
			* @param bool $botProtection Should bot protection be validated or not
			* @return array Array with errors if there are some, empty array otherwise.
		*/
		public function validateUser($data)
		{
			$errors = array();
			
			if ($this->validator->isEmpty($data['reg_phone'])) {
				$errors['reg_phone'] = trans('phone_required');
			}
			
			//check if email is not empty
			if ($this->validator->isEmpty($data['reg_email'])) {
				$errors['reg_email'] = trans('email_required');
			}
			
			if ($this->validator->isEmpty($data['reg_first_name'])) {
				$errors['reg_first_name'] = trans('first_name_required');
			}
			
			if ($this->validator->isEmpty($data['reg_last_name'])) {
				$errors['reg_last_name'] = trans('last_name_required');
			}
			
			if ($this->validator->isEmpty($data['password'])) {
				$errors['password'] = trans('password_required');
			}
			
			if ($data['password'] !== $data['password_confirmation']) {
				$errors['password_confirmation'] = trans('passwords_dont_match');
			}
			
			if (! isset($errors['reg_phone']) && $this->validator->phoneExist(str_replace($data['country_code'], "", $data['reg_phone']))) {
				$errors['reg_phone'] = trans('phone_taken');
			}
			
			//check if email format is correct
			if (! isset($errors['reg_email']) && ! $this->validator->emailValid($data['reg_email'])) {
				$errors['reg_email'] = trans('email_wrong_format');
			}
			
			if (! isset($errors['reg_email']) && $this->validator->emailExist($data['reg_email'])) {
				$errors['reg_email'] = trans('email_taken');
			}
			
			return $errors;
		}
		
		
		/**
			* Generates random password
			* @param int $length Length of generated password
			* @return string Generated password
		*/
		public function randomPassword($length = 7)
		{
			return str_random($length);
		}
		
		/**
			* Generate random token that will be used for social authentication
			* @return string Generated token.
		*/
		public function socialToken()
		{
			return str_random(40);
		}
		
		/**
			* Generate key used for confirmation and password reset.
			* @return string Generated key.
		*/
		private function generateKey()
		{
			return md5(time() .  str_random(40) . time());
		}
		
		public function ManageUserSubmit($data,$added_by){
			$user_id=null;
			if(isset($data['user_id'])){
				$user_id=$data['user_id'];
			}

			if(!empty($data['user_password'])){
				
				if ($data['user_password'] !== $data['confirm_password']) {
					$errors['confirm_password'] = trans('passwords_dont_match');
					
					respond(array(
					"status" => "error",
					"errors" => $errors
					), 422);
				}
				}elseif(!isset($data['user_id'])){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'user_password' => trans('password_required'),
				'confirm_password' => trans('confirm_password_required')
				)
				), 422);
			}
			
			$current_country_code = str_replace("+", "", $data['country_code']);
			$currentCountry = app('root')->select("SELECT * FROM `as_country` WHERE `phonecode` = :id", array( 'id' => $current_country_code))['0'];
			$username = str_replace('+'.$current_country_code, "", $data['user_mobile']);
			
			@$exist_username = app('root')->select("SELECT * FROM `as_users` WHERE `username` = :id", array( 'id' => $username))['0'];
			if(isset($exist_username) && $exist_username!=null){
				if($data['user_id']!=$exist_username['user_id']){
					$errors['user_mobile'] = trans('mobile_number_already_exists');
					
					respond(array(
					"status" => "error",
					"errors" => $errors
					), 422);
				}
				
			}
			
			
			$as_user = array(
			"email" => $data['user_email'],
			"username" => strip_tags($username),
			"sms_confirmed" => 'Y',
			"confirmed" => 'Y',
			"register_date" => date("Y-m-d H:i:s"),
			"user_role" => '1'
			);
			
			$as_user_details = array(
			"domain_id" => app('current_user')->domain_id,
			"added_by" => $added_by,
			"first_name" => $data['first_name'],
			"last_name" => $data['last_name'],
			"phone" => $data['user_mobile'],
			"address" => $data['user_address'],
			"country" => $currentCountry['name'],
			"country_code" => $data['country_code']
			);
			
			// $loadPlugins = app('admin')->loadAddon('multiple_store_warehouse','create_user_store_insert');
			// if($loadPlugins['load_status']){
				// require dirname(__FILE__) .'/../'.$loadPlugins['location'];
			// }
			if(app('admin')->checkAddon('multiple_store_warehouse')){
				if(isset($data['store_id'])){
					$as_user_details['store_id']=$data['store_id'];
				}
			}
			
			if(isset($data['user_id'])){
				$as_user_details += [ "user_id" => $user_id];
				$as_user_details += [ "user_id" => $user_id];
				}
			if(isset($data['user_id'])){
				$this->db->update("as_users" ,$as_user, "`user_id` = :id ", array("id"=> $user_id));
				$this->db->update("as_user_details" ,$as_user_details, "`user_id` = :id ", array("id"=> $user_id));
				}else{
				$this->db->insert("as_users", $as_user );
				$user_id = $this->db->lastInsertId();
				$as_user_details += [ "user_id" => $user_id];
				$this->db->insert("as_user_details", $as_user_details );
			}
			
			if(!empty($data['user_password'])){
				
				
				$this->db->update("as_users" ,array(
				"password" => $this->hashPassword(hash('sha512',$data['user_password'])),
				), "`user_id` = :id ", array("id"=> $user_id));
				
			}
			$result = app('db')->select("SELECT * FROM `pos_user_permission`");
			foreach ($result as $permission) {
				$permissions=[];
				$view_data='';
				$edit_data='';
				$delete_data='';
				$off_data='';
				if(isset($data["".$permission['permission_section']."_view"])){
					$view_data = $data["".$permission['permission_section']."_view"];
				}
				if(isset($data["".$permission['permission_section']."_edit"])){
					$edit_data = $data["".$permission['permission_section']."_edit"];
				}
				if(isset($data["".$permission['permission_section']."_delete"])){
					$delete_data = $data["".$permission['permission_section']."_delete"];
				}
				if(isset($data["".$permission['permission_section']."_off"])){
					$off_data = $data["".$permission['permission_section']."_off"];
				}
				// echo $permission['permission_section'];
				// echo 'view-'.$view_data;
				// echo 'edit-'.$edit_data;
				// echo 'delete-'.$delete_data;
				// echo 'off-'.$off_data;
				// exit();
				$view_result_data=explode(',',$permission['permission_view']);
				$edit_result_data=explode(',',$permission['permission_edit']);
				$delete_result_data=explode(',',$permission['permission_delete']);
				$off_result_data=explode(',',$permission['permission_off']);
				
				if($view_data!=''){
					if(in_array($user_id,$view_result_data)){
						$permissions += ['permission_view' => implode(',',$view_result_data)];
					}
					else{
						array_push($view_result_data,$user_id);
						$permissions += ['permission_view' => implode(',',$view_result_data)];
					}
				}
				else{
					if(in_array($user_id,$view_result_data)){
						if (($key = array_search($user_id, $view_result_data)) !== false) {
							unset($view_result_data[$key]);
						}
						 $permissions += ['permission_view' => implode(',',$view_result_data)];
					}
					else{
					$permissions += ['permission_view' => implode(',',$view_result_data)];
					}
				}
				
				if($edit_data!=''){
					if(in_array($user_id,$edit_result_data)){
						$permissions += ['permission_edit' => implode(',',$edit_result_data)];
					}
					else{
						array_push($edit_result_data,$user_id);
						$permissions += ['permission_edit' => implode(',',$edit_result_data)];
					}
				}
				else{
					if(in_array($user_id,$edit_result_data)){
						if (($key = array_search($user_id, $edit_result_data)) !== false) {
							unset($edit_result_data[$key]);
						}
						 $permissions += ['permission_edit' => implode(',',$edit_result_data)];
					}
					else{
						$permissions += ['permission_edit' => implode(',',$edit_result_data)];
					}
				}
				if($delete_data!=''){
					if(in_array($user_id,$delete_result_data)){
						$permissions += ['permission_delete' => implode(',',$delete_result_data)];
					}
					else{
						array_push($delete_result_data,$user_id);
						$permissions += ['permission_delete' => implode(',',$delete_result_data)];
					}
				}
				else{
					if(in_array($user_id,$delete_result_data)){
						if (($key = array_search($user_id, $delete_result_data)) !== false) {
							unset($delete_result_data[$key]);
						}
						 $permissions += ['permission_delete' => implode(',',$delete_result_data)];
					}
					else{
						$permissions += ['permission_delete' => implode(',',$delete_result_data)];
					}
				}
				if($off_data!=''){
					
					if(in_array($user_id,$off_result_data)){
						$permissions += ['permission_off' => implode(',',$off_result_data)];
					}
					else{
						array_push($off_result_data,$user_id);
						$permissions += ['permission_off' => implode(',',$off_result_data)];
					}
				}
				else{
					if(in_array($user_id,$off_result_data)){
						if (($key = array_search($user_id,$off_result_data)) !== false) {
							unset($off_result_data[$key]);
						}
						 $permissions += ['permission_off' => implode(',',$off_result_data)];
					}
					else{
						$permissions += ['permission_off' => implode(',',$off_result_data)];
					}
					
				}
				
				app('db')->update("pos_user_permission" ,$permissions, "`permission_id` = :id ", array("id" => $permission['permission_id']));
			}
			
			respond(array(
			"status" => "success"
			));
			
		}
		
		
		public function AccountUpdate($data,$user_id,$storeId){
			
			$current_country_code = str_replace("+", "", $data['country_code']);
			$currentCountry = $this->db->select('SELECT * FROM `as_country` WHERE `phonecode`='.$current_country_code);
			// $currentCountry = app('root')->select('as_country','phonecode',$current_country_code);
			$username = str_replace('+'.$current_country_code, "", $data['user_mobile']);
			
			$exist_username = $this->db->select("SELECT * FROM `as_users` WHERE `username`= :id ", array("id" => $username));
			// $exist_username = app('admin')->getwhereid('as_users','username',$username);
			
			if(count($exist_username)> 0 && $exist_username['user_id'] != $user_id){
				$errors['user_mobile'] = trans('mobile_number_already_exists');
				respond(array(
				"status" => "error",
				"errors" => $errors
				), 422);
			}
			$as_user = array(
			"user_id" => $user_id,
			"email" => $data['email'],
			"username" => strip_tags($username)
			);
			
			$as_user_details = array(
			"user_id" => $user_id,
			"domain_id" => app('current_user')->domain_id,
			"first_name" => $data['first_name'],
			"last_name" => $data['last_name'],
			"phone" => $data['user_mobile'],
			"address" => $data['address'],
			"country" => $currentCountry['name'],
			"country_code" => $data['country_code']
			);
			
			$this->db->update("as_users" ,$as_user, "`user_id` = :id ", array("id"=> $user_id));
			$this->db->update("as_user_details" ,$as_user_details, "`user_id` = :id ", array("id"=> $user_id));
			
			respond(array(
			"status" => "success"
			));
		}
		
		public function PasswordUpdate($data,$user_id,$storeId){
			
			if(!empty($data['old_password']) && $this->hashPassword(hash('sha512',$data['old_password']))!=app('current_user')->password){
				
				$errors['old_password'] = trans('password_dont_match');
				respond(array(
				"status" => "error",
				"errors" => $errors
				), 422);				
			}
			
			
			if ($data['new_password'] !== $data['confirm_password']) {
				$errors['confirm_password'] = trans('passwords_dont_match');
				
				respond(array(
				"status" => "error",
				"errors" => $errors
				), 422);
			}
			
			if ($this->hashPassword(hash('sha512',$data['new_password'])) == app('current_user')->password) {
				
				$errors['new_password'] = trans("can't_use_old_password_as_new_password");
				
				respond(array(
				"status" => "error",
				"errors" => $errors
				), 422);
			}
			
			$this->db->update("as_users" ,array(
			"password" => $this->hashPassword(hash('sha512',$data['new_password'])),
			), "`user_id` = :id ", array("id"=> $user_id));
			
			respond(array(
			"status" => "success"
			));
		}
	}
