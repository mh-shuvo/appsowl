<?php 
	
	defined('_AZ') or die('Restricted access'); 
	
	switch ($action) 
	{
		case "PosInstallation":
		app('admin')->PosInstallation($this->currentUser->id);
		break;
		
		case "getSoftwareVariationPrice":
		app('admin')->getSoftwareVariationPrice($_POST);
		break;
		
		case "GetAddFund":
		if(isset($_POST['payment_amount']) && $_POST['payment_amount'] < '499'){
			respond(array(
			'status' => 'error',
			'errors' => array(
			'payment_amount' => trans('minimum_500_tk_required')
			)
			), 422);
		}
		$OrderId = app('admin')->GetSubscribePaymentRequest($this->currentUser->id, $_POST);
		$getRequirements = app('admin')->getwhereid("as_pos_requirements","user_id",$this->currentUser->id);
		$RefId = uniqid().$OrderId;
		$data = [];
		$data['customer_name'] = $this->currentUser->first_name.' '.$this->currentUser->last_name;
		if($getRequirements){
			$data['customer_email'] = $getRequirements['company_email'];
			$data['customer_add'] = $getRequirements['company_address'];
			$data['customer_phone'] = $getRequirements['company_phone'];
			$data['customer_city'] = $getRequirements['company_city'];
			$data['customer_country'] = $getRequirements['company_country'];
			$data['customer_postcode'] = $getRequirements['company_postcode'];
			}else{
			$data['customer_email'] = $this->currentUser->email;
			$data['customer_add'] = $this->currentUser->address;
			$data['customer_phone'] = $this->currentUser->phone;
			$data['customer_city'] = $this->currentUser->zone;
			$data['customer_country'] = $this->currentUser->country_name;
			$data['customer_postcode'] = $this->currentUser->postcode;
		}
		
		$data['merchant_order_id'] = $OrderId;
		$data['merchant_ref_id']   = $RefId;
		$data['product_desc']      = 'Add Fund';
		$data['amount']            = $_POST['payment_amount'];
		$paymentStatus             = app('payment')->PaymentTokenGenerate($data);
		app('admin')->GetSubscribePaymentRequestTokenUpdate($paymentStatus['payment_token'],$OrderId,$RefId);
		
		if($paymentStatus['statusCode'] == '1000'){
			respond(array(
			"status" => "success",
			"page" => $paymentStatus['bank_payment_url']
			));
			}else{
			respond(array(
			"status" => "error",
			"message" => $paymentStatus['statusMsg']
			));
		}
		break;
		
		case "GetContactData":
		respond(array(
		"status" => "success",
		"message" => "Asajax"
		));
		break;
		
		case 'checkLogin':
        app('login')->userLogin($_POST['username'], $_POST['password']);
        break;
		
		case "registerUser":
		app('register')->register($_POST['user']);
		break;
		
		case "getDomainRegister":
		app('admin')->getDomainRegister($this->currentUser->id,$_POST['data']);
		break;
		
		case "GetDomainCheck":
		app('admin')->GetDomainCheck($_POST);
		break;
		
		case "smsVerification":
		app('register')->getConfirmSmsVerify($this->currentUser->id,$_POST['data']);
		break;
		
		case "GetAgentIdCard":
		
		// respond(array(
		// 'status' => 'success',
		// 'id'	=> $_POST['id'],
		// ));
		$getuserdetails = app('admin')->getuserdetails($_POST['id']);
	?>
	<div id="pos-print">
		<div class="ibox-content">
			<div class="id-card-holder" style="border:1px solid black;">
				<div class="id-card">
					<div class="header">
						<img src="images/logo.jpg">
					</div>
					<div class="photo">
						<img src="images/agent/01559617392.jpg">
					</div>
					<h2><?php echo $getuserdetails['first_name'].' '.$getuserdetails['last_name']; ?></h2>
					<h4><?php echo $getuserdetails['user_role']==2 ? 'Agent Manager' : 'No Designation';?></h4>
					<div class="qr-code">
						<img src="images/qr.png">
					</div>
					<h3>www.appsowl.com</h3>
					<hr>
					<p><strong>Powered By: "Software Galaxy"</strong> House-6, Road-10, Nikunja-2 </p>
					<p>Dhaka-1230, Bangladesh</p>
					<p>Ph: 01687-802090 | E-mail: softwaregalaxyltd@gmail.com</p>
					
				</div>
			</div>
		</div>
	</div>
	<?php
		break;
		
		case "agent_registration":
		// print_r($_FILES);
		// exit();
		$username = str_replace($_POST['country_code'], "",$_POST['agent_phone']);
		$image_type = pathinfo($_FILES['agent_image']['name'], PATHINFO_EXTENSION);;
		$nid_type = pathinfo($_FILES['agent_nid']['name'], PATHINFO_EXTENSION);;
		// $agent_type = pathinfo($_FILES['agent_attach']['name'], PATHINFO_EXTENSION);;
		
		if(isset($_FILES['agent_image']) && $_FILES['agent_image']['size'] != 0){
			$_POST['agent_image'] = $username.'.'.$image_type;
			$upload_handler = new UploadHandler(array(
			'filename' => $username,
			'max_file_size' => 1000000, //1MB file size
			'accept_file_types' => '/\.(jpe?g)$/i',
			'width' => 250, 
			'height' => 250, 
			'print_response' => false,
			'param_name' => 'agent_image',
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/agent/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->agent_image[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'agent_image' => $error_massage->response->agent_image[0]->error
				)
				), 422);
			}
		}
		if(isset($_FILES['agent_nid']) && $_FILES['agent_nid']['size'] != 0){
			$_POST['agent_nid'] = $username.'_nid.'.$nid_type;
			$upload_handler = new UploadHandler(array(
			'filename' => $username.'_nid',
			'max_file_size' => 1000000, //1MB file size
			'accept_file_types' => '/\.(jpe?g|pdf)$/i', 
			'print_response' => false,
			'param_name' => 'agent_nid',
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/agent/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->agent_nid[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'agent_nid' => $error_massage->response->agent_nid[0]->error
				)
				), 422);
			}
		}
		// if(isset($_FILES['agent_attach']) && $_FILES['agent_attach']['size'] != 0){
		// $_POST['agent_attach'] = $username.'_attach.'.$agent_type;
		// $upload_handler = new UploadHandler(array(
		// 'filename' => $username.'_attach',
		// 'max_file_size' => 1000000, //1MB file size
		// 'accept_file_types' => '/\.(jpe?g|pdf)$/i', 
		// 'print_response' => false,
		// 'param_name' => 'agent_attach',
		// 'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
		// 'upload_dir' => dirname(__FILE__).'/../images/agent/',
		// ));
		// $error_massage = json_decode(json_encode($upload_handler));
		// if(!empty($error_massage->response->agent_attach[0]->error)){
		// respond(array(
		// 'status' => 'error',
		// 'errors' => array(
		// 'agent_nid' => $error_massage->response->agent_attach[0]->error
		// )
		// ), 422);
		// }
		// }
		app('register')->AgentRegistration($_POST);
		break;
		
		case "resetPassword":
		app('register')->resetPassword($_POST['new_password'], $_POST['key']);
		break;
		
		case "forgotPassword":
		app('register')->forgotPassword($_POST['email']);
		break;
		
		case "postComment":
		app('comment')->insertComment(ASSession::get("user_id"), $_POST['comment']);
		break;
		
		case "Agent_withdrawSubmit":
		app('admin')->Agent_withdraw($_POST['data'],$this->currentUser->id);
		break;
		
		case "updatePassword":
		app('user')->updatePassword(
		ASSession::get("user_id"),
		array(
		"old_password" => $_POST['old_password'],
		"new_password" => $_POST['new_password'],
		"new_password_confirmation" => $_POST['new_password_confirmation']
		)
		);
		break;
		case "withdrawalPassword":
		
		app('user')->withdrawalPassword(
		ASSession::get("user_id"),
		array(
		"new_password" => $_POST['new_password'],
		"new_password_confirmation" => $_POST['new_password_confirmation']
		)
		);
		break;
		
		case "updateWithdrawalPassword":
		app('user')->updateWithdrawalPassword(
		ASSession::get("user_id"),
		array(
		"old_password" => $_POST['old_password'],
		"new_password" => $_POST['new_password'],
		"new_password_confirmation" => $_POST['new_password_confirmation']
		)
		);
		break;
		
		case "updateAdminPassword":
		app('user')->updateAdminPassword(
		ASSession::get("user_id"),
		array(
		"old_password" => $_POST['old_password'],
		"new_password" => $_POST['new_password'],
		"new_password_confirmation" => $_POST['new_password_confirmation']
		)
		);
		break;
		
		case "updateUserPassword":
		app('user')->updateAdminPassword(
		ASSession::get("user_id"),
		array(
		"old_password" => $_POST['old_password'],
		"new_password" => $_POST['new_password'],
		"new_password_confirmation" => $_POST['new_password_confirmation']
		)
		);
		break;
		
		case "updateAdminUserPassword":
		app('user')->updateAdminUserPassword(
		$_POST['user_id'],
		array(
		"new_password" => $_POST['new_password'],
		"new_password_confirmation" => $_POST['new_password_confirmation']
		)
		);
		break;
		
		case "updateDetails":
		app('user')->updateDetails(ASSession::get("user_id"), $_POST['details']);
		break;
		
		case "changeRole":
		onlyAdmin();
		
		$result = app('user')->changeRole($_POST['userId'], $_POST['role']);
		respond(array('role' => ucfirst($result)));
		break;
		
		case "deleteUser":
		onlyAdmin();
		
		$userId = (int) $_POST['userId'];
		$users = app('user');
		
		if (! $users->isAdmin($userId)) {
			$users->deleteUser($userId);
			respond(array('status' => 'success'));
		}
		respond(array('error' => 'Forbidden.'), 403);
		break;
		
		case "getUserDetails":
		onlyAdmin();
		
		respond(
		app('user')->getAll($_POST['userId'])
		);
		break;
		case 'account_update':
		app('admin')->account_update($_POST['data'],$this->currentUser->id);
		break;
		
		case 'admin_account_update':
		$newdb = app('admin')->newdb($this->currentUser->domain_id);
		$extra_data = $_POST['data'];
		$extra_data['newdb'] = $newdb;
		app('admin')->admin_account_update($extra_data,$this->currentUser->id);
		break;
		
		case "addRole":
		onlyAdmin();
		
		app('role')->add($_POST['role']);
		break;
		
		case "deleteRole":
		onlyAdmin();
		
		app('role')->delete($_POST['roleId']);
		break;
		
		
		case "addUser":
		onlyAdmin();
		
		respond(
		app('user')->add($_POST['user'])
		);
		break;
		
		case "updateUser":
		onlyAdmin();
		
		app('user')->updateUser($_POST['user']['user_id'], $_POST['user']);
		break;
		
		case "banUser":
		onlyAdmin();
		
		app('user')->updateInfo($_POST['userId'], array('banned' => 'Y'));
		respond(array('status' => 'success'));
		break;
		
		case "unbanUser":
		onlyAdmin();
		
		app('user')->updateInfo($_POST['userId'], array('banned' => 'N'));
		respond(array('status' => 'success'));
		break;
		
		case "getUser":
		onlyAdmin();
		
		respond(
		app('user')->getAll($_POST['userId'])
		);
		break;
		
		case "GetPosSubscribe":
		app('admin')->GetSoftwareSubscribe($_POST,$this->currentUser->id,$this->currentUser->agent_id,$this->currentUser);
		break;
		
		case "GetPosRequirementSetup":
		
		$db_name = CPANEL_PREFIX.$_POST['domain_name'];
		$db_username = CPANEL_PREFIX.$_POST['domain_name'];
		$db_password = randomtoken(12);
		$domain_id = app('admin')->GetSubdomainInsert($this->currentUser->id,$_POST['domain_name'],CPANEL_HOST,$db_name,$db_username,$db_password,0);
		app('admin')->InsertUpdatePosRequirements($_POST,$this->currentUser->id);
		
		respond(array(
		"status" => "success",
		"massage" => "Requirement Submit Successfully"
		));
		
		
		
		break;	
		
		case "GetPosInstallBack":
		
		break;
		
		case "UserSubmit":
		$getsubdomain = app('admin')->getwhereid('as_sub_domain','domain_id',$this->currentUser->domain_id);
		$newdb = app('admin')->newdb($this->currentUser->domain_id);
		app('register')->GetUserSubmit($_POST['data'],$this->currentUser->id,$this->currentUser->country_code,$this->currentUser->domain_id,$newdb);
		break;
		
	case "GetResendSms":
	app('register')->GetResendSms($_POST['phone_number'],$this->currentUser->id);
	break;
	
	case "GetDeleteUser":
	app('admin')->GetDeleteUser($_POST['id'],$this->currentUser->domain_id);
	break;
	
	case 'usersubscrib':
	$subscribed = app('admin')->getwhere('as_subscribe','user_id',$_POST['id']);
	if ($subscribed) 
	{
	?>
	<table class='table table-striped table-bordered text-center' id="usersublist">
	<thead>
	<th class="text-center">Software</th>
	<th class="text-center">Subscribed Date</th>
	<th class="text-center">Renew Date</th>
	<th class="text-center">Status</th>
	<th class="text-center">View</th>
	</thead>
	<tbody>
	<?php foreach ($subscribed as $value) {
	$getsoftdetails = app('admin')->getwhereid('as_software','software_id',$value['software_id']);
	
	$getsoftsubdetails = app('admin')->getlastrowwhere('as_subscribe_payment','subscribe_id',$value['subscribe_id'],'subscribe_payment_id');
	$subscribe_payment = app('admin')->getsumtotalbywhereand('as_subscribe_payment','subscribe_month','subscribe_id',$value['subscribe_id'],'subscribe_payment_status','paid');
	if($subscribe_payment){
	$date = new DateTime($value['subscribe_date']);
	$date->modify('+'.$subscribe_payment.'month');
	$subscribe_end_date = $date->format('Y-m-d H:i:s');
	}
	?>
	<tr>
	<td class="text-center"><?php echo $getsoftdetails['software_title']; ?></td>
	<td class="text-center"><?php echo getdatetime($value['subscribe_date'],3); ?></td>
	<td class="text-center"><?php 
	if(isset($subscribe_end_date)){
	echo getdatetime($subscribe_end_date,3); 
	}else{
	echo "Not Paid Yet"; 
	}
	
	?></td>
	<td>
	<?php if ($value['subscribe_status']=="active") { ?>
	<span class="label label-primary">Active</span>
	<?php } else{ ?>
	<span class="label label-danger"><?php echo $value['subscribe_status']; ?></span>
	<?php } ?>
	</td>
	<td><a href='agent/subscribe-history/<?php echo $value['subscribe_id'];?>' class='btn btn-primary btn-xs'><?php echo trans('view'); ?></a></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<?php 
	}
	break;
	case "GetecSetting":
	app('pos')->GetecSetting($_POST['data']);
	break;
	case "SendVerifyNumber":
	app('admin')->SendVerifyNumber($_POST);
	break;
	case "GetSmskeyVerify":
	app('admin')->GetSmskeyVerify($_POST);
	break;
	case "ForgotPasswordChange":
	app('admin')->ForgotPasswordChange($_POST);
	break;
	
	case "ModuleStatusChange":
	app('admin')->ModuleStatusChange($_POST,$this->currentUser->id,$this->currentUser->agent_id);
	break;
	
	case "getManualPayment":
	app('admin')->GetManualPayment($_POST,$this->currentUser->id,$this->currentUser->agent_id);
	break;
	
	case "getSubscribeCancel":
	app('admin')->GetSubscribeCancel($_POST,$this->currentUser->id);
	break;
	
	case "GetVoucherSubmit":
	app('admin')->GetVoucherSubmit($_POST,ASSession::get("user_id"));
	break;
	case "GetWithdrawalSubmit":
	app('admin')->GetWithdrawalSubmit($_POST,ASSession::get("user_id"));
	break;
	case "WithdrawalCancel":
	app('admin')->GetWithdrawalCancel($_POST);
	break;
	case "GetNotification":
	app('admin')->GetNotification($_POST);
	break;
	
	case "UpdateNotificationReadStatus":
	app('admin')->UpdateNotificationReadStatus($_POST);
	break;
	
	case "GetTutorialByPageName":
	app('admin')->GetTutorialByPageName($_POST);
	break;
	case "AddTicketData":
	if(isset($_FILES['ticket_document']) && $_FILES['ticket_document']['size'] != 0){
	if (isset($_POST['ticket_document'])) {
	$path = dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/'.$_POST['ticket_document'];
	if (file_exists($path)) {
	unlink($path);
	}
	}
	$extension = pathinfo($_FILES['ticket_document']['name'], PATHINFO_EXTENSION);
	$date = new DateTime();
	$image_name =  $date->getTimestamp();
	$_POST['ticket_document_name'] = $image_name.'.'.$extension;
	$upload_handler = new UploadHandler(array(
	'max_file_size' => 100000,
	'print_response' => false,
	'param_name' => 'ticket_document',
	'filename' => $image_name,
	'image_file_types' => '/\.(gif|jpe?g|png)$/i',
	'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/',
	));
	$error_massage = json_decode(json_encode($upload_handler));
	if(!empty($error_massage->response->ticket_document[0]->error)){
	respond(array(
	'status' => 'error',
	'errors' => array(
	'ticket_document' => $error_massage->response->ticket_document[0]->error
	)
	), 422);
	}
	}
	app('admin')->AddTicketData($_POST,$this->currentUser->id);
	break;
	case "AddTicketChat":
	if(isset($_FILES['chat_document']) && $_FILES['chat_document']['size'] != 0){
	if (isset($_POST['chat_document'])) {
	$path = dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/'.$_POST['chat_document'];
	if (file_exists($path)) {
	unlink($path);
	}
	}
	$extension = pathinfo($_FILES['chat_document']['name'], PATHINFO_EXTENSION);
	$date = new DateTime();
	$image_name =  $date->getTimestamp();
	$_POST['chat_document_name'] = $image_name.'.'.$extension;
	$upload_handler = new UploadHandler(array(
	'max_file_size' => 100000,
	'print_response' => false,
	'param_name' => 'chat_document',
	'filename' => $image_name,
	'image_file_types' => '/\.(gif|jpe?g|png)$/i',
	'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/',
	));
	$error_massage = json_decode(json_encode($upload_handler));
	if(!empty($error_massage->response->chat_document[0]->error)){
	respond(array(
	'status' => 'error',
	'errors' => array(
	'chat_document' => $error_massage->response->chat_document[0]->error
	)
	), 422);
	}
	}
	app('admin')->AddTicketChat($_POST,$this->currentUser->id);
	break;
	
	case "ChangeStoreByStoreId":
	app('admin')->ChangeStoreByStoreId($_POST,$this->currentUser->id);
	break;
	
	default:
	break;
	}
	
	function onlyAdmin()
	{
	if (! (app('login')->isLoggedIn() && app('current_user')->is_admin)) {
	respond(array('error' => 'Forbidden.'), 403);
	}
	}
		