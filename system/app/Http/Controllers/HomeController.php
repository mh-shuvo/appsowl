<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\Cache;
	use Carbon;
	use Hash;
	use Session;
	use DB;
	use Config;
	use App\Model\user;
	use App\Model\subscribePayment;
	use App\Model\pos_requirements;
	use ActivityLog;
	use Upload;
	use App\Jobs\Sendsms;
	
	use App\Model\activitylog as aclog;
	
	class HomeController extends Controller
	{
		public function test(){
			return view('SuperAdmin.support_replay');
		}
		
		public function dashboard(){
			
			$current_date = Carbon::now()->format('Y-m-d');
			
			$data=array(
			'today_user'=>0,
			'total_user'=>0,
			'total_active_user'=>0,
			'total_inactive_user'=>0,
			'today_agent'=>0,
			'total_agent'=>0,
			'total_active_agent'=>0,
			'total_inactive_agent'=>0,
			'total_bill'=>0,
			'total_paid_bill'=>0,
			'total_unpaid_bill'=>0,
			'total_support'=>0,
			'total_pending_support'=>0,
			'total_complete_support'=>0,
			);
			/*
				User Data 
			*/
			$today_user=user::whereDate('register_date',$current_date)->where('user_role','=','3')->count();
			$data['today_user']=$today_user;
			$total_user=user::where('user_role','=','3')->count();
			$data['total_user']=$total_user;
			$total_active_user=user::where('banned','=','N')->where('user_role','=','3')->count();
			$data['total_active_user']=$total_active_user;
			$total_inactive_user=user::where('banned','=','Y')->where('user_role','=','3')->count();
			$data['total_inactive_user']=$total_inactive_user;
			/*
				Agent Data 
			*/
			$today_agent=user::whereDate('register_date',$current_date)->where('user_role','=','2')->count();
			$data['today_agent']=$today_agent;
			$total_agent=user::where('user_role','=','2')->count();
			$data['total_agent']=$total_agent;
			$total_active_agent=user::where('user_role','=','2')->where('banned','=','N')->count();
			$data['total_active_agent']=$total_active_agent;
			$total_inactive_agent=user::where('user_role','=','2')->where('banned','=','Y')->count();
			$data['total_inactive_agent']=$total_inactive_agent;
			
			return view('SuperAdmin.dashboard',compact('data'));
		}  
		
		public function login(){
			if (Session::get('admin_data')[0] ['admin_id']){
				return redirect('/home');
				}else{
				return view('SuperAdmin.login');
			}
		}
		
		public function login_submit(Request $login){
			$user_admin_data = user::with('userDetails')->where(['username' => $login->input('username'), 'user_role' => '4'])->first();
			
			if (isset($user_admin_data)) {
				
				if ($this->hashPassword(hash('sha512',$login->input('password'))) == $user_admin_data->password){
					if ($user_admin_data->banned=='N') {
						$session_data = [
						'admin_id' => $user_admin_data->user_id,
						'email' => $user_admin_data->email,
						'username' => $user_admin_data->username,
						'register_date' => $user_admin_data->register_date,
						'permission' => $user_admin_data->permission,
						'first_name' => $user_admin_data->userDetails->first_name,
						'last_name' => $user_admin_data->userDetails->last_name,
						];
						
						Session::push('admin_data', $session_data);
						
						$redirect = array('url' => url('/home'), 'status' => 'true');
						return Response()->json($redirect);
						
						}else{
						$error_message = array('msg' => 'Sorry, Your account is temporary blocked !!', 'status' => 'false');
						return Response()->json($error_message);
					}
					
					}else{
					$error_message = array('msg' => 'Password do not match!', 'status' => 'false');
					return Response()->json($error_message);
				}
				
				}else{
				$error_message = array('msg' => 'Username or Password do not match!', 'status' => 'false');
				return Response()->json($error_message);
			}			
		}
		
		public function logout(Request $logout){
			Session::flush();
			return redirect('/');
		}
		
		private function hashPassword($password){
			$salt = "$2a$" . config("app.password_bcrypt_cost") . "$" . config("app.password_salt");
			if (config("app.password_encryption") == "bcrypt") {
				return crypt($password, $salt);
			}
			
			$newPassword = $password;
			for ($i = 0; $i < config("app.password_sha512_iterations"); $i++) {
				$newPassword = hash('sha512', $salt.$newPassword.$salt);
			}
			
			return $newPassword;

		}
		
		public function change_password(Request $change_password){
			$user_data = user::find(session('admin_data')[0]['admin_id']);
			
			if ($this->hashPassword(hash('sha512',$change_password->input('current_password'))) !== $user_data->password){
				$response = array('msg' => 'The password you have entered does not match your current one.', 'status' => 'error');
				return Response()->json($response);
				}else{
				$new_password = $this->hashPassword(hash('sha512',$change_password->input('new_password')));
				
				$user_data->password = $new_password;
				$user_data->save();
				
				$arr = array('msg' => 'Password changed successfully.', 'status' => 'success');
				return Response()->json($arr);
			}
		}
		
		public function download_file($filename,$for){
			
			if(Storage::exists('/uploads/document/'.$filename)){
				$file = Storage::get('/app/public/uploads/document/'.$filename);
				$extention = substr($filename, strpos($filename, ".") + 1);
				$headers = array(
				'Content-Type: application/'.$extention,
				);
				return Response()->download($file,$for.'.'.$extention);
				
				//          $file = '/app/public/uploads/document/'.$filename;
				//          $extention = substr($filename, strpos($filename, ".") + 1);
				// $headers = array(
				//              'Content-Type: application/'.$extention,
				//            );
				//          return Storage::download($file,$for.'.'.$extention,$headers);
				}else{
				abort(404);
			}
		}
		
		public function activity_log(){
			return view('SuperAdmin.activity_log');
		}
		
		public function sms_send(){
			return view('SuperAdmin.sms_send');
		}
		
		public function sms_log(){
			return view('SuperAdmin.sms_log');
		}
		
		public function sms_send_submit(Request $sms){
			
			if (strpos($sms->message, 'user-id=') !== false) {
				$string = ' ' . $sms->message;
				$ini = strpos($string, "=");
				if ($ini == 0) return '';
				$ini += strlen("=");
				$len = strpos($string, ",", $ini) - $ini;
				$user_id = substr($string, $ini, $len);
				$split = explode(",",$sms->message);					
				
				$user_data = pos_requirements::where('user_id','=',$user_id)->first();
				if ($user_data) {
					$name = $user_data->userDetails['first_name'].' '.$user_data->userDetails['last_name'];
					$company_name = $user_data->company_name;
					$phone = $user_data->company_phone;
					$address = $user_data->company_address;
					
					$text = "Name: ".$name."\nBussness name: ".$company_name."\nPhone: ".$phone."\nAddress: ".$address."\n".$split[1];
					}else{
					$text = $split[1];
				}
				}else{
				$text = $sms->message;
			}
			
			if ($sms->bulk=='3') {
				if (empty($sms->number)) {
					$arr = array('msg' => 'Need mobile number or click All user/All agent.', 'error' => 'true');
					return Response()->json($arr);
					} else {
					$this->dispatch(new Sendsms($sms->number, $text));
				}
				
				}elseif ($sms->bulk=='1'){
				$all_users = user::where('user_role', '=', '1')->get();
				foreach ($all_users as $all_user) {
					$number = $all_user->userDetails['country_code'].$all_user->userDetails['phone'];
					$this->dispatch(new Sendsms($number, $text));					
				}
				}elseif ($sms->bulk=='2'){
				$all_agents = user::where('user_role', '=', '2')->get();
				foreach ($all_agents as $all_agent) {
					$number = $all_agent->userDetails['country_code'].$all_agent->userDetails['phone'];
					$this->dispatch(new Sendsms($number, $text));					
				}
				}else{
				if (isset($sms->user_id)) {
					$user_data = user::find($sms->user_id);
					$country_code = $user_data->userDetails['country_code'];
					$number = $user_data->userDetails['country_code'].$user_data->userDetails['phone'];				
					$this->dispatch(new Sendsms($number, $text));
					} elseif(isset($sms->number)) {
					$this->dispatch(new Sendsms($sms->number, $text));
				}
			}
			$sms_send = array('msg' => "Sms Send",'status' => 'success');
			
			return Response()->json($sms_send);
		}
		
		public function email_varification($token){
			echo $token;
		}
	}						