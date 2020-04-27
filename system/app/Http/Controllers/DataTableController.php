<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DataTables;
use App\Model\user;
use App\Model\voucher;
use App\Model\subscribe;
use App\Model\activitylog;
use App\Model\termsandcondition;
use App\Model\userDetails;
use App\Model\agent_payment;
use App\Model\agent_commission;
use App\Model\sub_domain;
use App\Model\subscribePayment;
use App\Model\pos_requirements;
use App\Model\payment;
use App\Model\withdraw;
use App\Model\notification;
use App\Model\smslog;
use App\Model\software;
use App\Model\software_variation;
use App\Model\support;


class DataTableController extends Controller
{
    public function datatable($table,$user_id=false){

    	switch ($table) {

    		case "user_datatable":

    		$model = user::query()->with('userDetails')->where('user_role','3');

			return DataTables::eloquent($model)
			->orderColumn('user_id', '-user_id $1')
			->addColumn('status', function(user $user) {
				if($user->banned == "N"){
					return "<label class='label label-primary'>Active</label>";
				}elseif($user->banned == "Y"){
					return "<label class='label label-danger'>Deactive</label>";
				}
            })
			->addColumn('action', function(user $user) {
				return '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="'.url('/user-view').'/'.$user->user_id.'" ><i class="fa fa-eye"></i> View</a>
						</li>
						<li>
							<a href="javascript:void(0)" class="send_message" user-id="'.$user->user_id.'" ><i class="fa fa-envelope-open"></i> Send Message</a>
						</li>
						<li>
							<a href="'.url('/support').'/'.$user->user_id.'" ><i class="fa fa-support"></i> Support</a>
						</li>
						<li>
							<a href="javascript:void(0)" class="user_status_change" user_id="'.$user->user_id.'" ><i class="fa fa-ioxhost"></i> Change Status</a>
						</li>
					</ul>
				</div>';
            })
            ->addColumn('name', function(user $user) {
                return $user->userDetails['first_name'].' '.$user->userDetails['last_name'];
            })
            ->addColumn('phone', function(user $user) {
                return $user->userDetails['phone'];
            })
            ->addColumn('address', function(user $user) {
                return $user->userDetails['address'];
            })
            ->rawColumns(['status','action'])
			->toJson();

	    	break;

	    	case "user_subscribe_details":

	    	return DataTables::eloquent(subscribe::query()->with('invoices')->where('user_id','=',$user_id))
			->orderColumn('subscribe_id', '-subscribe_id $1')
			->addColumn('name', function(subscribe $subscribe) {
				if(!empty($subscribe->plugins_id)){
					return $subscribe->plugin->plugins_name;
				}elseif(empty($subscribe->plugins_id)){
					return $subscribe->software->software_title;
				}
            })
			->addColumn('status', function(subscribe $subscribe) {
				if($subscribe->subscribe_status=='active'){
					return '<button type="button" class="btn btn-primary btn-xs">Active</button>';
				}elseif($subscribe->subscribe_status=='inactive'){
					return '<button type="button" class="btn btn-warning btn-xs">Inactive</button>';
				}elseif($subscribe->subscribe_status=='cancel'){
					return '<button type="button" class="btn btn-info btn-xs">Cancel</button>';
				}elseif($subscribe->subscribe_status=='expire'){
					return '<button type="button" class="btn btn-danger btn-xs">Expire</button>';
				}else{
					return $subscribe->subscribe_status;
				}
            })
			->addColumn('action', function(subscribe $subscribe) {
				if($subscribe->subscribe_status=='active'){
					$action_button = '<li><a href="javascript:void(0)" class="change_status" subscribe_id="'.$subscribe->subscribe_id.'" todo="inactive" ><i class="fa fa-ioxhost"></i> Inctive</a></li>';
				}elseif($subscribe->subscribe_status=='inactive'){
					$action_button = '<li><a href="javascript:void(0)" class="change_status" subscribe_id="'.$subscribe->subscribe_id.'" todo="active" ><i class="fa fa-ioxhost"></i> Active</a></li>';
				}else{
					$action_button = false;
				}
				return '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">'.$action_button.'</ul>
				</div>';
            })
			->addColumn('start', function(subscribe $subscribe) {
				return Carbon::parse($subscribe->subscribe_activation_date)->format('H:i:s d-m-Y');
            })
			->addColumn('renew', function(subscribe $subscribe) {
				return Carbon::parse($subscribe->invoices->subscribe_end_date)->format('H:i:s d-m-Y');
            })
            ->rawColumns(['status','action'])->toJson();

	    	break;

	    	case "fund_log_datatable":

	    	return DataTables::eloquent(payment::query()->with('user')->where('user_id','=',$user_id))
			->orderColumn('payment_id', '-payment_id $1')
			->addColumn('status', function(payment $payment) {
				if($payment->payment_status=='paid'){
					return '<button type="button" class="btn btn-primary btn-xs">Paid</button>';
				}elseif($payment->payment_status=='due'){
					return '<button type="button" class="btn btn-warning btn-xs">Due</button>';
				}elseif($payment->payment_status=='cancel'){
					return '<button type="button" class="btn btn-info btn-xs">Cancel</button>';
				}elseif($payment->payment_status=='hole'){
					return '<button type="button" class="btn btn-danger btn-xs">Hole</button>';
				}
            })
			->addColumn('action', function(payment $payment) {
				return '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="javascript:void(0)" class="payment_details" payment_id="'.$payment->payment_id.'" ><i class="fa fa-eye"></i> View</a>
						</li>
					</ul>
				</div>';
            })
            ->rawColumns(['status','action'])
			->toJson();

	    	break;

	    	case "agent_datatable":

			return DataTables::eloquent(user::query()->with('userDetails','agent_commission')->where('user_role', '=', '2'))
			->orderColumn('user_id', '-user_id $1')
			->setTransformer(function($item){
				if($item->banned == "N"){
					$status = "<label class='label label-primary'>Active</label>";
				}elseif($item->banned == "Y"){
					$status  = "<label class='label label-danger'>Deactive</label>";
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="'.url('/agent-details/').'/'.$item->user_id.'" ><i class="fa fa-eye"></i> View</a>
						</li>
						<li>
							<a href="javascript:void(0)" class="send_message" user-id="'.$item->user_id.'" ><i class="fa fa-envelope-open"></i> Send Message</a>
						</li>
						<li>
							<a href="javascript:void(0)" class="agent_status_change" user_id="'.$item->user_id.'" ><i class="fa fa-ioxhost"></i> Change Status</a>
						</li>
						<li>
							<a href="javascript:void(0)" class="cngcom" user_id="'.$item->user_id.'" commission="'.$item->agent_commission[$item->agent_commission->count()-1]['new_rate'].'" ><i class="fa fa-paypal"></i> Change Commission</a>
						</li>
					</ul>
				</div>
				';

				return [
					'user_id' => (int) $item->user_id,
					'name' => (string) $item->userDetails->first_name.' '.$item->userDetails->last_name,
					'phone' => $item->userDetails->phone,
					'register_date' => (string) Carbon::parse($item->register_date)->format('H:i:s d-m-Y'),
					'commission' => $item->agent_commission[$item->agent_commission->count()-1]['new_rate'],
					'address' => $item->userDetails->address,
					'banned' => $status,
					'action' => $action,
                    ];
                })
			->toJson();

	    	break;

    		case "transaction_log":

			return DataTables::eloquent(agent_payment::query()->where('agent_id','=',$user_id))
			->orderColumn('agent_payment_id', '-agent_payment_id $1')
			->setTransformer(function($item){

				if ($item->payment_type=='receive') {
					$type  = '<button type="button" class="btn btn-primary btn-xs">Receive</button>';
				} elseif($item->payment_type=='withdraw') {
					$type  = '<button type="button" class="btn btn-warning btn-xs">Withdraw</button>';
				}
				

				if($item->payment_status=='paid'){
					$status  = '<button type="button" class="btn btn-primary btn-xs">Paid</button>';
					$action_button = false;
				}elseif($item->payment_status=='due'){
					$status  = '<button type="button" class="btn btn-warning btn-xs">Due</button>';
					$action_button = '<li><a href="javascript:void(0)" class="agent_pay" amount="'.$item->payment_amount.'" payment_id="'.$item->agent_payment_id.'"><i class="fa fa-paypal"></i> Pay</a></li>';
				}elseif($item->payment_status=='cancel'){
					$status  = '<button type="button" class="btn btn-danger btn-xs">Cancel</button>';
					$action_button = false;
				}

				if(!empty($item->pay_document) && Storage::exists('/uploads/document/'.$item->pay_document)){
					$document = "<a href='".url('/download-file').'/'.$item->pay_document.'/log_document'."' class='label label-info'><i class='fa fa-file'></i></a>";
				}else{
					$document = '<i class="fa fa-times" aria-hidden="true"></i>';
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">'.$action_button.'</ul>
				</div>
				';

				return [
					'payment_type' => $type,
					'subscribe_id' => $item->subscribe_id,
					'subscribe_payment_id' => $item->subscribe_payment_id,
					'payment_date' => (string) Carbon::parse($item->payment_date)->format('H:i:s d-m-Y'),
					'payment_amount' => $item->payment_amount,
					'payment_details' => $item->payment_details,
					'payment_status' => $status,
					'document' => $item->pay_document,
					'pay_note' => $item->pay_note,
					'pay_date' => (string) Carbon::parse($item->pay_date)->format('d-m-Y'),
					'subscribe_payment_id' => $item->subscribe_payment_id,
					'document' => $document,
					'action' => $action
                    ];
                })
			->toJson();

	    	break;

    		case "commision_log_datatable":

			return DataTables::eloquent(agent_commission::query()->where('agent_id','=',$user_id))
			->orderColumn('commission_id', '-commission_id $1')
			->setTransformer(function($item){

				if(!empty($item->document) && Storage::exists('/uploads/document/'.$item->document)){
					$document = "<a href='".url('/download-file').'/'.$item->document.'/log_document'."' class='label label-info'><i class='fa fa-file'></i></a>";
				}else{
					$document = '<i class="fa fa-times" aria-hidden="true"></i>';
				}

				return [
					'commission_id' => (int) $item->commission_id,
					'previous_rate' => $item->previous_rate,
					'new_rate' => $item->new_rate,
					'commission_note' => $item->commission_note,
					'document' => $document,
					'created_at' => (string) Carbon::parse($item->created_at)->format('H:i:s d-m-Y')
                    ];
                })
			->toJson();

	    	break;

    		case "withdraw_datatable":

			return DataTables::eloquent(withdraw::query()->with('userDetails','approve_by'))
			->orderColumn('withdrawal_id', '-withdrawal_id $1')
			->setTransformer(function($item){

				if($item->withdrawal_status=='requested'){
					$action_button = '<li><a href="javascript:void(0)" class="change_status" status="due" withdrawal_id="'.$item->withdrawal_id.'" > Due</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="hold" withdrawal_id="'.$item->withdrawal_id.'" > Hold</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="cancel" withdrawal_id="'.$item->withdrawal_id.'" > Cancel</a></li>
					<li><a href="javascript:void(0)" class="withdrawal_pay" amount="'.$item->withdrawal_amount.'" withdrawal_id="'.$item->withdrawal_id.'" > Pay</a></li>';
				}elseif($item->withdrawal_status=='cancel'){
					$action_button = '<li><a href="javascript:void(0)" class="change_status" status="due" withdrawal_id="'.$item->withdrawal_id.'" > Due</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="hold" withdrawal_id="'.$item->withdrawal_id.'" > Hold</a></li>';
				}elseif($item->withdrawal_status=='hold'){
					$action_button = '<li><a href="javascript:void(0)" class="change_status" status="due" withdrawal_id="'.$item->withdrawal_id.'" > Due</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="cancel" withdrawal_id="'.$item->withdrawal_id.'" > Cancel</a></li>';
				}elseif($item->withdrawal_status=='due'){
					$action_button = '<li><a href="javascript:void(0)" class="change_status" status="hold" withdrawal_id="'.$item->withdrawal_id.'" > Hold</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="cancel" withdrawal_id="'.$item->withdrawal_id.'" > Cancel</a></li>
					<li><a href="javascript:void(0)" class="withdrawal_pay" amount="'.$item->withdrawal_amount.'" withdrawal_id="'.$item->withdrawal_id.'" > Pay</a></li>';
				}elseif($item->withdrawal_status=='paid'){
					$action_button = false;
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="javascript:void(0)" class="withdrawal_details" withdrawal_id="'.$item->withdrawal_id.'" > View</a>
						</li>'.$action_button.'
					</ul>
				</div>
				';

				return [
					'withdrawal_id' => (int) $item->withdrawal_id,
					'withdrawal_type' => $item->withdrawal_type,
					'user' => $item->userDetails['first_name'].' '.$item->userDetails['last_name'],
					'withdrawal_amount' => $item->withdrawal_amount,
					'withdrawal_status' => $item->withdrawal_status,
					'created' => (string) Carbon::parse($item->created_at)->format('H:i:s d-m-Y'),
					'action' => $action
                    ];
                })
			->toJson();

	    	break;

    		case "sub_domain_datatable":

			return DataTables::eloquent(sub_domain::query())
			->orderColumn('domain_id', '-domain_id $1')
			->setTransformer(function($item){

				return [
					'domain_id' => (int) $item->domain_id,
					'user_id' => $item->user->userDetails['first_name'].' '.$item->user->userDetails['last_name'],
					'sub_domain' => (string) $item->sub_domain
                    ];
                })
			->toJson();

	    	break;
    		
    		case "super_admin_datatable":

			return datatables()->eloquent(user::query()->with('userDetails')->where('user_role','=','4'))
			->orderColumn('user_id', '-user_id $1')
			->setTransformer(function($item){

				if($item->banned == "N"){
					$status = "<label class='label label-primary'>Active</label>";
				}elseif($item->banned == "Y"){
					$status  = "<label class='label label-danger'>Deactive</label>";
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="javascript:void(0)" class="admin_status_change" user_id="'.$item->user_id.'" ><i class="fa fa-ioxhost"></i> Change Status</a>
						</li>
					</ul>
				</div>
				';

				return [
					'user_id' => (int) $item->user_id,
					'name' => (string) $item->userDetails['first_name'].' '.$item->userDetails['last_name'],
					'email' => (string) $item->email,
					'username' => (string) $item->username,
					'permission' => $item->permission,
					'status' => $status,
					'action' => $action
                    ];
                })
			->toJson();

	    	break;

	    	case "subscription_log":

			return DataTables::eloquent(subscribe::query()->with('user','softwareVariationDetails','softwareDetails'))
			->orderColumn('subscribe_id', '-subscribe_id $1')
			->setTransformer(function($item){

				if($item->subscribe_status=='active'){
					$status  = '<button type="button" class="btn btn-primary btn-xs">Active</button>';
					$action_button = 
					'<li><a href="javascript:void(0)" class="change_status" status="inactive" subscribe_id="'.$item->subscribe_id.'" > Inactive</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="cancel" subscribe_id="'.$item->subscribe_id.'" > Cancel</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="return" subscribe_id="'.$item->subscribe_id.'" > Return</a></li>';
				}elseif($item->subscribe_status=='inactive'){
					$status  = '<button type="button" class="btn btn-warning btn-xs">Inactive</button>';
					$action_button = 
					'<li><a href="javascript:void(0)" class="change_status" status="active" subscribe_id="'.$item->subscribe_id.'" > Active</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="cancel" subscribe_id="'.$item->subscribe_id.'" > Cancel</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="return" subscribe_id="'.$item->subscribe_id.'" > Return</a></li>';
				}elseif($item->subscribe_status=='cancel'){
					$status  = '<button type="button" class="btn btn-info btn-xs">Cancel</button>';
					$action_button = 
					'<li><a href="javascript:void(0)" class="change_status" status="active" subscribe_id="'.$item->subscribe_id.'" > Active</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="return" subscribe_id="'.$item->subscribe_id.'" > Return</a></li>';
				}elseif($item->subscribe_status=='expire'){
					$status  = '<button type="button" class="btn btn-danger btn-xs">Expire</button>';
					$action_button = 
					'<li><a href="javascript:void(0)" class="change_status" status="active" subscribe_id="'.$item->subscribe_id.'" > Active</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="cancel" subscribe_id="'.$item->subscribe_id.'" > Cancel</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="return" subscribe_id="'.$item->subscribe_id.'" > Return</a></li>';
				}else{
					$status  = $item->subscribe_status;
					$action_button = 
					'<li><a href="javascript:void(0)" class="change_status" status="active" subscribe_id="'.$item->subscribe_id.'" > Active</a></li>
					<li><a href="javascript:void(0)" class="change_status" status="cancel" subscribe_id="'.$item->subscribe_id.'" > Cancel</a></li>';
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="javascript:void(0)" class="subscribe_edit" subscribe_id="'.$item->subscribe_id.'" subscribe_amount="'.$item->subscribe_amount.'" subscribe_activation_date="'.$item->subscribe_activation_date.'" > Edit</a>
						</li>'.$action_button.'
					</ul>
				</div>
				';
				if(!empty($item->agent_id)){
					$agent = $item->agentDetails->userDetails['first_name'].' '.$item->agentDetails->userDetails['last_name'];
				}else{
					$agent = "N/A";
				}

				return [
					'subscribe_id' => (int) $item->subscribe_id,
					'user' => (string) $item->user->userDetails['first_name'].' '.$item->user->userDetails['last_name'],
					'agent' => (string) $agent,
					'software' => $item->softwareDetails['software_title'] ?? "N/A",
					'variation_name' => $item->softwareVariationDetails['software_variation_name'] ?? "N/A",
					'subscribe_date' => Carbon::parse($item->subscribe_date)->format('H:i:s d-m-Y'),
					'subscribe_activation_date' => Carbon::parse($item->subscribe_activation_date)->format('H:i:s d-m-Y'),
					'subscribe_amount' => $item->subscribe_amount,
					'subscribe_status' => $status,
					'action' => $action
                    ];
                })
			->toJson();

	    	break;

	    	case "subscribe_details_datatable":

			return DataTables::eloquent(subscribePayment::query()->where('subscribe_id','=',$user_id))
			->orderColumn('subscribe_payment_id', '-subscribe_payment_id $1')
			->setTransformer(function($item){

				if($item->subscribe_payment_status=='paid'){
					$status  = '<button type="button" class="btn btn-primary btn-xs">Paid</button>';
					$action_button = false;
				}elseif($item->subscribe_payment_status=='due'){
					$status  = '<button type="button" class="btn btn-warning btn-xs">Due</button>';
					$action_button = '<li><a href="javascript:void(0)" class="manuali_active" subscribe_id="'.$item->subscribe_id.'" ><i class="fa fa-ioxhost"></i> Inctive</a></li>';
				}elseif($item->subscribe_payment_status=='cancel'){
					$status  = '<button type="button" class="btn btn-info btn-xs">Cancel</button>';
					$action_button = false;
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">'.$action_button.'</ul>
				</div>
				';

				return [
					'subscribe_id' => (int) $item->subscribe_id,
					'software' => $item->softwareDetails['software_title'] ?? "N/A",
					'software_variation' => $item->softwareVariationDetails['software_variation_name'] ?? "N/A",
					'start_date' => $item->subscribe_start_date,
					'end_date' => $item->subscribe_end_date,
					'payment_amount' => $item->subscribe_payment_amount,
					'transaction_iD' =>$item->subscribe_payment_transaction_id,
					'month' => $item->subscribe_month,
					'payment_time' => (string) Carbon\Carbon::parse($item->payment_time)->format('H:i:s d-m-Y'),
					'status' => $status,
					'action' => $action
                    ];
                })
			->toJson();

	    	break;

    		case "voucher_datatable":

			return DataTables::eloquent(voucher::query()->with('user','generate'))
			->orderColumn('voucher_id', '-voucher_id $1')
			->setTransformer(function($item){
				if($item->voucher_status == "active"){
					$status = "<label class='label label-primary'>".$item->voucher_status."</label>";
				}elseif($item->voucher_status == "inactive"){
					$status  = "<label class='label label-warning'>".$item->voucher_status."</label>";
				}elseif($item->voucher_status == "cancel"){
					$status  = "<label class='label label-danger'>".$item->voucher_status."</label>";
				}

				if(!empty($item->voucher_note)){
					$note = "<a href='javascript:void(0)' class='label label-warning note' note='$item->voucher_note' ><i class='fa fa-sticky-note'></i></a>";
				}else{
					$note = "<i class='fa fa-times'></i>";
				}

				if(!empty($item->voucher_document) && Storage::exists('/uploads/document/'.$item->voucher_document)){
					$document = "<a href='".url('/download-file').'/'.$item->voucher_document.'/voucher_document'."' class='label label-success'><i class='fa fa-file'></i></a>";
				}else{
					$document = "<i class='fa fa-times'></i>";
				}

				if(!empty($item->user_id)){
					$used_by = $item->user->userDetails['first_name'].' '.$item->user->userDetails['last_name'];
				}else{
					$used_by = "N/A";
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="javascript:void(0)" class="voucher_status_change" voucher_id="'.$item->voucher_id.'"  ><i class="fa fa-ioxhost"></i> Change Status</a>
						</li>
					</ul>
				</div>
				';

				return [
					'voucher_id' => (int) $item->voucher_id,
					'voucher_title' => (string) $item->voucher_title,
					'voucher_code' => $item->voucher_code,
					'voucher_amount' => $item->voucher_amount,
					'voucher_price' => (string) $item->voucher_price,
					'generated_by' => $item->generate['first_name'].' '.$item->generate['last_name'],
					'voucher_available' => $item->voucher_available,
					'user_id' => $used_by,
					'voucher_note' => $note,
					'voucher_document' => $document,
					'voucher_status' => $status,
					'created_at' => Carbon::parse($item->created_at)->format('H:i:s d-m-Y'),
					'action' => $action,
                    ];
                })
			->toJson();

	    	break;

    		case "pos_requirements_datatable":

			return Datatables::eloquent(pos_requirements::query())
			->orderColumn('pos_requirement_id', '-pos_requirement_id $1')
			->setTransformer(function($item){
				if($item->status == "active"){
					$status = "<label class='label label-primary'>".$item->status."</label>";
				}elseif($item->status == "deactive"){
					$status  = "<label class='label label-danger'>".$item->status."</label>";
				}

				return [
					'pos_requirement_id' => $item->pos_requirement_id,
					'title' => $item->user->userDetails['first_name'].' '.$item->user->userDetails['last_name'],
					'company_name' => (string) $item->company_name,
					'company_website' => (string) $item->company_website,
					'company_email' => (string) $item->company_email,
					'company_phone' => (string) $item->company_phone,
					'company_address' => (string) $item->company_address,
					'company_city' => (string) $item->company_city,
					'company_country' => (string) $item->company_country,
					'company_postcode' => (string) $item->company_postcode,
					'vat_no' => (string) $item->vat_no,
					'vat_unit' => (string) $item->vat_unit,
					'status' => $status
                    ];
                })
			->toJson();

	    	break;

	    	case "sms_log_datatable":

			return DataTables::eloquent(smslog::query()->with('sended_to','send_by'))
			->orderColumn('sms_log_id', '-sms_log_id $1')
			->setTransformer(function($item){

				if(!empty($item->body)){
					$body = "<a href='javascript:void(0)' class='label label-warning body' body='$item->body' ><i class='fa fa-sticky-note'></i></a>";
				}else{
					$body = "<i class='fa fa-times'></i>";
				}

				if(!empty($item->send_to)){
					$sended_to = $item->sended_to['first_name'].' '.$item->sended_to['last_name'];
				}else{
					$sended_to = 'Unknown';
				}

				return [
					'sms_log_id' => $item->sms_log_id,
					'messageid' => $item->messageid,
					'send_to' => $sended_to,
					'number' => (string) $item->number,
					'body' => $body,
					'sender' => $item->send_by['first_name'].' '.$item->send_by['last_name'],
					'created_at' => (string) Carbon::parse($item->created_at)->format('H:i:s d-m-Y')
                    ];
                })
			->toJson();

	    	break;

	    	case "activity_log_datatable":

	    	return DataTables::eloquent(activitylog::query())
			->orderColumn('log_id', '-log_id $1')
			->setTransformer(function($item){

				if(!empty($item->note)){
					$note = "<a href='javascript:void(0)' class='label label-warning note' note='$item->note' ><i class='fa fa-sticky-note'></i></a>";
				}else{
					$note = "<i class='fa fa-times'></i>";
				}

				if(!empty($item->document) && Storage::exists('/uploads/document/'.$item->document)){
					$document = "<a href='".url('/download-file').'/'.$item->document.'/log_document'."' class='label label-info'><i class='fa fa-file'></i></a>";
				}else{
					$document = "<i class='fa fa-times'></i>";
				}

				return [
					'log_id' => (int) $item->log_id,
					'subject' => (string) $item->subject,
					'note' => (string) $note,
					'document' => $document,
					'ip' => (string) $item->ip,
					'agent' => (string) $item->agent,
					'user_id' => (string) $item->userDetails['first_name'].' '.$item->userDetails['last_name'],
					'created_at' => (string) Carbon::parse($item->created_at)->format('H:i:s d-m-Y'),
                    ];
                })
			->toJson();

	    	break;

	    	case "tc_datatable":
			return DataTables::eloquent(termsandcondition::query()->with('userDetails')->where('type','t&c'))
	    	->orderColumn('t_c_id', '-t_c_id $1')
			->setTransformer(function($item){
				if($item->status == "active"){
					$status = "<label class='label label-primary'>".ucwords($item->status)."</label>";
				}elseif($item->status == "deactive"){
					$status  = "<label class='label label-danger'>".ucwords($item->status)."</label>";
				}

				if(!empty($item->document)){
					$document = "<a href='".url('/download-file').'/'.$item->document.'/terms&condition'."' class='btn btn-xs btn-success'><i class='fa fa-file'></i></a>";
				}else{
					$document = "<i class='fa fa-times'></i>";
				}

				if(!empty($item->body_text)){
					$body="<button class='btn btn-xs btn-danger' onclick='show_modal(this)' text='".$item->body_text."'><i class='fa fa-eye'></button>";
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="'.url('/terms-condition/edit/').'/'.$item->t_c_id.'" ><i class="fa fa-edit"></i> Edit</a>
						</li>
						<li>
							<a href="'.url('/terms-privacy/status_change/').'/'.$item->t_c_id.'" ><i class="fa fa-ioxhost"></i> Change Status</a>
						</li>
						
						
					</ul>
				</div>
				';

				return [
					't_c_id'   => $item->t_c_id,
					'body'     => $body,
					'added_by' => (string) $item->userDetails['first_name'].' '.$item->userDetails['last_name'],
					'status'   => $status,
					'document' => $document,
					'created_at' =>Carbon::parse( $item->created_at)->format('d-m-Y H:i:s'),
					'updated_at' =>Carbon::parse(  $item->updated_at)->format('d-m-Y H:i:s'),
					'action'   => $action,
                    ];
                })->toJson();

	    	break;
			
			case "privacy_datatable":
			return DataTables::eloquent(termsandcondition::query()->with('userDetails')->where('type','privacy'))
	    	->setTransformer(function($item){
				if($item->status == "active"){
					$status = "<label class='label label-primary'>".ucwords($item->status)."</label>";
				}elseif($item->status == "deactive"){
					$status  = "<label class='label label-danger'>".ucwords($item->status)."</label>";
				}

				if(!empty($item->document)){
					$document = "<a href='".url('/download-file').'/'.$item->document.'/terms&condition'."' class='btn btn-xs btn-success'><i class='fa fa-file'></i></a>";
				}else{
					$document = "<i class='fa fa-times'></i>";
				}

				if(!empty($item->body_text)){
					$body="<button class='btn btn-xs btn-danger' onclick='show_modal(this)' text='".$item->body_text."'><i class='fa fa-eye'></button>";
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="'.url('/privacy/edit/').'/'.$item->t_c_id.'" ><i class="fa fa-edit"></i> Edit</a>
						</li>
						<li>
							<a href="'.url('/terms-privacy/status_change/').'/'.$item->t_c_id.'" ><i class="fa fa-ioxhost"></i> Change Status</a>
						</li>
						
						
					</ul>
				</div>
				';

				return [
					't_c_id'   => $item->t_c_id,
					'body'     => $body,
					'added_by' => (string) $item->userDetails['first_name'].' '.$item->userDetails['last_name'],
					'status'   => $status,
					'document' => $document,
					'created_at' =>Carbon::parse( $item->created_at)->format('d-m-Y H:i:s'),
					'updated_at' =>Carbon::parse(  $item->updated_at)->format('d-m-Y H:i:s'),
					'action'   => $action,
                    ];
                })->toJson();

	    	break;
			
			case "notification_datatable":

			return DataTables::eloquent(notification::query())
			->orderColumn('notification_id', '-notification_id $1')
	    	->setTransformer(function($item){
				if($item->status == "active"){
					$status = "<label class='label label-primary'>".ucwords($item->status)."</label>";
				}elseif($item->status == "deactive"){
					$status  = "<label class='label label-danger'>".ucwords($item->status)."</label>";
				}else{
					$status  = false;
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="'.$item->link.'" target="_blank"><i class="fa fa-eye"></i> View</a>
						</li>
						
						<li>
							<a href="javascript:void(0)" class="notification_edit" notification_id="'.$item->notification_id.'" message="'.$item->message.'" title="'.$item->title.'" link="'.$item->link.'"><i class="fa fa-edit"></i> Edit</a>
						</li>
						
						<li>
							<a href="javascript:void(0)" class="notification_status_change" notification_id="'.$item->notification_id.'" ><i class="fa fa-ioxhost"></i> Change Status</a>
						</li>
						
						<li>
							<a href="javascript:void(0)" notification_id="'.$item->notification_id.'" class="notification_delete" ><i class="fa fa-trash"></i> Delete</a>
						</li>
						
					</ul>
				</div>
				';

				return [
					'notification_id'=> $item->notification_id,
					'title'=> $item->title,
					'message'=> $item->message ,
					'created_at'=> Carbon::parse($item->created_at)->format('H:i:s d-m-Y'),
					'status'=> $status,
					'action'=> $action,
                    ];
                })->toJson();

	    	break;

	    	case "software_datatable":

			return Datatables::eloquent(software::query())
			->orderColumn('software_id', '-software_id $1')
			->setTransformer(function($item){

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="'.url('/software-variations').'/'.$item->software_id.'" > Variations</a>
						</li>
					</ul>
				</div>
				';

				return [
					'software_id' => $item->software_id,
					'software_title' => $item->software_title,
					'software_price' => $item->software_price,
					'software_status' => $item->software_status,
					'created_at' => Carbon::parse($item->created_at)->format('H:i:s d-m-Y'),
					'action' => $action
                    ];
                })
			->toJson();

			break;

	    	case "variation_datatable":

			return Datatables::eloquent(software_variation::query()->where('software_id','=',$user_id))
			->orderColumn('software_variation_id', '-software_variation_id $1')
			->setTransformer(function($item){

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="javascript:void(0)" class="variation_edit" variation_id="'.$item->software_variation_id.'" title="'.$item->software_variation_name.'" price="'.$item->software_variation_price.'" sub_price="'.$item->software_subscribe_fee.'" in_advance="'.$item->software_subscribe_in_advance.'" > Edit</a>
						</li>
					</ul>
				</div>
				';

				return [
					'software_variation_id' => $item->software_variation_id,
					'software_variation_name' => $item->software_variation_name,
					'software_variation_price' => $item->software_variation_price,
					'software_subscribe_fee' => $item->software_subscribe_fee,
					'software_subscribe_in_advance' => $item->software_subscribe_in_advance,
					'software_variation_status' => $item->software_variation_status,
					'created_at' => Carbon::parse($item->created_at)->format('H:i:s d-m-Y'),
					'updated_at' => Carbon::parse($item->updated_at)->format('H:i:s d-m-Y'),
					'action' => $action
                    ];
                })
			->toJson();
			
			break;

	    	case "user_support_datatable":

	    	return Datatables::eloquent(support::query()->where('user_id','=',$user_id)->where('ticket_type','=','ticket'))
			->orderColumn('ticket_no', '-ticket_no $1')
			->setTransformer(function($item){

				if($item->priority == "normal"){
					$priority = "<label class='label label-info'>".ucwords($item->priority)."</label>";
				}elseif($item->priority == "medium"){
					$priority  = "<label class='label label-warning'>".ucwords($item->priority)."</label>";
				}elseif($item->priority == "high"){
					$priority  = "<label class='label label-danger'>".ucwords($item->priority)."</label>";
				}else{
					$priority  = false;
				}

				$action = '
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="'.url('/ticket-details').'/'.$item->ticket_no.'" > Details</a>
						</li>
					</ul>
				</div>
				';

				return [
					'ticket_no' => $item->ticket_no,
					'priority' => $priority,
					'ticket_title' => $item->ticket_title,
					'created_at' => Carbon::parse($item->created_at)->format('H:i:s d-m-Y'),
					'updated_at' => Carbon::parse($item->updated_at)->format('H:i:s d-m-Y'),
					'status' => $item->status,
					'action' => $action
                    ];
                })
			->toJson();
			
			break;

	    	default:

	    	break;
	    }
    }
}
