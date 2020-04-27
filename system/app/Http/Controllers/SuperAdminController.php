<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Mail;
	use Hash;
	use App\Mail\VerificationEmail;
	use App\Model\user;
	use App\Model\userDetails;
	use App\Model\voucher;
	use App\Model\invoices;
	use App\Model\agent_payment;
	use App\Model\sub_domain;
	use App\Model\subscribe;
	use App\Model\pos_requirements;
	use App\Model\agent_commission;
	use App\Model\payment;
	use App\Model\withdraw;
	use App\Model\software_variation;
	use Carbon;
	use ActivityLog;
	use Upload;
	
	class SuperAdminController extends Controller
	{
		public function __construct()
		{
			
		}
	    
		public function user_manage(){
			return view('SuperAdmin.user_manage');
		}
		
		public function user_view($id){
			
			$totalBalance = $this->userAvailableBalance($id);
			$user_data = user::find($id);
			
			$plugins=user::whereHas('subscribtion', function ($query) {
				$query->where('subscribe_type','software');
			})->count();
			
			$balance = $totalBalance ['current_balance'];
			
			return view('SuperAdmin.user_details',compact('user_data','plugins','balance'));			
		}
		
		public function userAvailableBalance($userId){
			
			$fundAmount = payment::where('payment_status', "paid")->where('user_id', $userId)->where('payment_load_date', '<=', date('Y-m-d'))->sum('payment_amount');
			$withdrawalAmount = withdraw::where('withdrawal_status','!=', "cancel")->where('user_id', $userId)->sum('withdrawal_amount');
			$invoiceAmount = invoices::where('invoice_status','!=', "cancel")->where('user_id', $userId)->sum('invoice_amount');
			$voucherAmount = voucher::where('voucher_status', "active")->where('voucher_available','!=', "reject")->where('user_id', $userId)->sum('voucher_amount');
			
			$currentBalance = $fundAmount + $voucherAmount - $withdrawalAmount - $invoiceAmount;
			return ['current_balance' => $currentBalance, 'total_fund' => $fundAmount, 'total_withdrawal' => $withdrawalAmount,'total_invoice' => $invoiceAmount, 'total_voucher' => $voucherAmount];
			
			
		}
		
		public function user_status_change(Request $status){
			if($status->hasFile('document')){
				$document = $status->file('document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$find = user::find($status->user_id);
			if ($find->banned =="N"){
				$from = "Active";
				user::where('user_id', '=', $status->user_id)->update(array('banned' => 'Y'));
				$to = "Deactive";
				}elseif($find->banned=="Y"){
				$from = "Deactive";
				user::where('user_id', '=', $status->user_id)->update(array('banned' => 'N'));
			$to = "Active";
			}
			ActivityLog::addToLog($find->userDetails['first_name'].' '.$find->userDetails['last_name'].' status changed from '.$from.' to '.$to,$status->resone,$filename);
			$arr = array('msg' => 'Successfully status changed.', 'user' => $find->username);
			return Response()->json($arr);			
		}
		
		public function payment_details($payment_id){
			$payment_details = payment::find($payment_id);
			return Response($payment_details);
		}
		
		public function subscribe_change(Request $subscribe){	
			
			if($subscribe->hasFile('document')){
				$document = $subscribe->file('document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$subscribe_details = subscribe::find($subscribe->subscribe_id);
			$subscribe_details->subscribe_status = $subscribe->todo;
			$subscribe_details->save();
			
			if(!empty($subscribe_details->plugins_id)){
				$name  = $subscribe_details->plugin->plugins_name;
				}elseif(empty($subscribe_details->plugins_id)){
				$name  = $subscribe_details->software->software_title;
			}
			ActivityLog::addToLog($subscribe_details->user->userDetails['first_name'].' '.$subscribe_details->user->userDetails['last_name'].' '.$name.' status changed to '.$subscribe->todo,$subscribe->resone,$filename);
			
			$arr = array('msg' => 'Successfully status changed.', 'status' => true);
			return Response()->json($arr);
		}
		
		public function add_fund_submit(Request $add_fund){
			
			if($add_fund->hasFile('document')){
				$document = $add_fund->file('document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$payment = new payment;
			$payment->payment_type = 'office';
			$payment->payment_load_date = $add_fund->payment_load_date;
			$payment->user_id = $add_fund->user_id;
			$payment->office_payment_by = session('admin_data')[0]['admin_id'];
			$payment->payment_amount = $add_fund->payment_amount;
			$payment->payment_charge = $add_fund->payment_charge;
			$payment->payment_note = $add_fund->payment_note;
			$payment->payment_status = 'paid';
			$payment->save();
			
			ActivityLog::addToLog($add_fund->payment_amount.' fund added to '.$add_fund->user_id,$add_fund->payment_note,$filename);
			
			$arr = array('msg' => 'Successfully fund added.');
			return Response()->json($arr);
		}
		
		public function agent_manage(){
			return view('SuperAdmin.agent_manage');
		}
		
		public function withdraw_manage(){
			return view('SuperAdmin.withdraw_manage');
		}
		
		public function withdraw_details($withdrawal_id){
			$withdraw_details = withdraw::find($withdrawal_id);
			$withdraw_details['withdraw_by'] = $withdraw_details->userDetails['first_name'].' '.$withdraw_details->userDetails['last_name'];
			$withdraw_details['approved_by'] = $withdraw_details->approve_by['first_name'].' '.$withdraw_details->approve_by['last_name'];
			return Response($withdraw_details);
		}
		
		public function withdraw_status_change($withdrawal_id,$status){
			withdraw::where('withdrawal_id', '=', $withdrawal_id)->update(array('withdrawal_status' => $status));
			ActivityLog::addToLog($withdrawal_id.' status changed to '.$status);
			$arr = array('msg' => 'Successfully status changed.');
			return Response()->json($arr);
		}
		
		public function withdrawal_pay(Request $withdraw){
			
			if($withdraw->hasFile('document')){
				$document = $withdraw->file('document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$withdrawal = withdraw::find($withdraw->withdrawal_id);
			$withdrawal->withdrawal_status = 'paid';
			$withdrawal->withdrawal_approve_by = session('admin_data')[0]['admin_id'];
			$withdrawal->withdrawal_transaction_id = $withdraw->transaction_id;
			$withdrawal->approve_note = $withdraw->note;
			$withdrawal->withdrawal_document = $filename;
			$withdrawal->save();
			
			ActivityLog::addToLog('withdrawal id '.$withdraw->withdrawal_id.' withdrawal paid by '.$withdraw->amount,$withdraw->note,$filename);
			
			$arr = array('msg' => 'Successfully withdrawal paid.', 'status' => true);
			return Response()->json($arr);
		}
		
		public function subdomain_manage(){
			return view('SuperAdmin.subdomain_manage');
		}
		
		public function agentdetails($agent_id){
			$user_data = user::find($agent_id);
			return view('SuperAdmin.agent_details',compact('user_data'));
		}
		
		public function commission_submit(Request $submit){
			
			if($submit->hasFile('document')){
				$document = $submit->file('document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$commission = new agent_commission;
			$commission->agent_id = $submit->user_id;
			$commission->previous_rate = $submit->old_commission;
			$commission->new_rate = $submit->commission;
			$commission->commission_note = $submit->commission_note;
			$commission->document = $filename;
			$commission->save();
			
			$user_info = user::find($submit->user_id);
			
			ActivityLog::addToLog($user_info->userDetails['first_name'].' '.$user_info->userDetails['last_name'].' commission changed from '.$submit->old_commission.' to '.$submit->commission);
			
			$arr = array('msg' => 'Successfully commission changed.', 'status' => true);
			return Response()->json($arr);
		}
		
		public function agent_pay(Request $agent_pay){
			
			if($agent_pay->hasFile('payment_document')){
				$document = $agent_pay->file('payment_document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$agent_payment = agent_payment::find($agent_pay->payment_id);
			$agent_payment->pay_document = $filename;
			$agent_payment->pay_note = $agent_pay->payment_note;
			$agent_payment->pay_date = Carbon::now();
			$agent_payment->payment_status = 'paid';
			$agent_payment->save();
			
			ActivityLog::addToLog($agent_payment->agent->userDetails['first_name'].' '.$agent_payment->agent->userDetails['last_name'].' agent bill paid by '.$agent_pay->amount,$agent_pay->payment_note,$filename);
			
			$arr = array('msg' => 'Payment action successful.', 'status' => true);
			return Response()->json($arr);
		}
		
		public function super_admin_manage(){
			return view('SuperAdmin.super_admin_manage');
		}
		
		public function super_admin_submit(Request $superadmin){
			if(!empty($superadmin->account) && !empty($superadmin->maintainer)){
				$comma = ',';
				}else{
				$comma = false;
			}
			
			$permission = $superadmin->account.$comma.$superadmin->maintainer;
			
			$user = new user;
			
			$pass = '$2a$13$UPj8EVJWvP73FZ9Ih0xzUebANa.Jwzy83Q4Nij.mDbivHo2pDGRE.';
			$user->username = $superadmin->mobile;
			$user->email = $superadmin->email;
			$user->password = $pass;
			$user->user_role = "4";
			$user->permission = $permission;
			$user->email_varification_token = str_random(32);
			
			$user->save();
			
			if ($user->save()) {
				$userDetails = new userDetails;
				$userDetails->user_id = $user->user_id;
				$userDetails->first_name = $superadmin->first_name;
				$userDetails->last_name = $superadmin->last_name;
				$userDetails->dob = $superadmin->dob;
				$userDetails->phone = $superadmin->mobile;
				$userDetails->save();
			}
			
			Mail::to($superadmin->email)->queue(new VerificationEmail($user));
			// $user->notify(new VerificationEmail($user));
			
			$arr = array('msg' => 'Successfully super admin added.', 'status' => true);
			return Response()->json($arr);
		}		
		
		public function subscribelist(){
			return view('SuperAdmin.subscribelist');
		}
		
		public function subscribe_edit(Request $subscribe_edit){
			
			if($subscribe_edit->hasFile('document')){
				$document = $subscribe_edit->file('document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$subscribe = subscribe::find($subscribe_edit->subscribe_id);
			$subscribe->subscribe_amount = $subscribe_edit->subscribe_amount;
			$subscribe->subscribe_activation_date = $subscribe_edit->subscribe_activation_date;
			$subscribe->save();
			
			ActivityLog::addToLog($subscribe->subscribe_id.' Edited.',$subscribe_edit->resone,$filename);
			
			$arr = array('msg' => 'Successfully edited.');
			return Response()->json($arr);
			
		}
		
		public function subscribe_details($subscribe_id){
			$check_result = invoices::where('subscribe_id','=',$subscribe_id)->get();
			return view('SuperAdmin.subscribedetails',compact('check_result','subscribe_id'));
		}
		
		// public function manualactive($subscribe_id){
		// 	$subscribePayment = subscribePayment::where('subscribe_id','=',$subscribe_id);
		// 	$subscribePayment->payment_method = "Office Cash";
		// 	$subscribePayment->subscribe_payment_status = "paid";
		// 	$subscribePayment->save();
		
		// 	if ($subscribePayment->agent_id!=null) {
		// 		$check_payment = agent_payment::where('subscribe_payment_id','=',$subscribePayment->subscribe_payment_id)->first();
		// 		if ($check_payment==null) {
		// 			$agent_commission = $subscribePayment->payment_amount * AGENT_COMMISSION / 100;
		
		// 			$payment_details = "You have received ".$agent_commission." TK Commission for Software Subscription From user id :".$subscribePayment->user_id;
		
		// 			$agent_payment = new agent_payment;
		
		// 	        $agent_payment->user_id = $subscribePayment->user_id;
		// 	        $agent_payment->agent_id = $subscribePayment->agent_id;
		// 	        $agent_payment->subscribe_id = $subscribePayment->subscribe_id;
		// 	        $agent_payment->subscribe_payment_id = $subscribePayment->subscribe_payment_id;
		// 	        $agent_payment->payment_method = $subscribePayment->payment_method;
		// 	        $agent_payment->payment_status = "due";
		
		// 	        $agent_payment->save();
		
		// 	    } else {
		
		// 			$agent_payment = agent_payment::where('subscribe_payment_id','=',$subscribePayment->subscribe_payment_id);
		// 			$agent_payment->payment_method = $subscribePayment->payment_method;
		// 			$agent_payment->payment_date = Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
		// 			$agent_payment->payment_amount = $agent_commission;
		// 			$agent_payment->payment_details = $payment_details;
		// 			$agent_payment->payment_status = $subscribePayment->subscribe_payment_status;
		// 			$agent_payment->save();
		
		// 		}
		
		// 	}			
		
		// 	$subscribe = subscribe::find($subscribe_id);
		// 	$subscribe->subscribe_status = "active";
		// 	$subscribe->save();
		
		// 	return redirect()->back();
		// }
		
		public function pos_requirements(){
			return view('SuperAdmin.pos_requirements');
		}
		
		public function voucher(){
			return view('SuperAdmin.voucher_manage');
		}
		
		public function promocode_submit(Request $voucher){	
			
			if($voucher->hasFile('document')){
				$document = $voucher->file('document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$vouchers = new voucher;
			$vouchers->voucher_title = $voucher->title;
			$vouchers->voucher_code = $voucher->code;
			$vouchers->voucher_amount = $voucher->amount;
			$vouchers->voucher_price = $voucher->price;
			$vouchers->generated_by = session('admin_data')[0]['admin_id'];
			$vouchers->voucher_note = $voucher->note;
			$vouchers->voucher_document = $filename;
			$vouchers->save();
			
			ActivityLog::addToLog($vouchers->voucher_id.' voucher added by '.session('admin_data')[0]['first_name'].' '.session('admin_data')[0]['last_name']);
			
			$arr = array('msg' => 'Successfully voucher added.');
			return Response()->json($arr);
		} 
		
		public function voucher_status(Request $voucher){
			if($voucher->hasFile('document')){
				$document = $voucher->file('document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				
				Upload::document($document,$filename,$extension);
				}else{
				$filename = false;
			}
			
			$vouchers = voucher::find($voucher->voucher_id);
			if($vouchers->voucher_status == "inactive"){
				$previous = "inactive";
				$vouchers->voucher_status = "active";
				} elseif($vouchers->voucher_status == "active") {
				$previous = "active";
				$vouchers->voucher_status = "inactive";
			}
			$vouchers->save();
			ActivityLog::addToLog($vouchers->voucher_id.' voucher status changed from '.$previous.' to '.$vouchers->voucher_status.'.',$voucher->resone,$filename);
			
			$arr = array('msg' => 'Status changed successful.', 'status' => true);
			return Response()->json($arr);
		}
		
		public function software_list(){
			return view('SuperAdmin.software_list');
		}
		
		public function software_variations($software_id){
			return view('SuperAdmin.software_variations',compact('software_id'));
		}
		
		public function variation_edit(Request $variation){
			$variation_data = software_variation::find($variation->variation_id);
			$variation_data->software_variation_name = $variation->title;
			$variation_data->software_variation_price = $variation->price;
			$variation_data->software_subscribe_fee = $variation->sub_price;
			$variation_data->software_subscribe_in_advance = $variation->in_advance;
			$variation_data->save();
			ActivityLog::addToLog('Variation ID '.$variation->variation_id.' Edited ');
			
			$arr = array('msg' => 'Edited successful.');
			return Response()->json($arr);
		}  
	}
