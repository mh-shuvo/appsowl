<?php
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use View;
	use App\Model\notification;
	use App\Model\tutorial;
	use App\Model\termsandcondition;
	use Carbon;
	use Session;
	use Upload;
	use ActivityLog;	
	
	
	class WebController extends Controller
	{
		public function notification(){
			return view('WebModiul.notification_manage');
		}
		
		public function notification_submit(Request $notification){
			
			notification::updateOrCreate(
			['notification_id' => $notification->notification_id],
			[
			'title' => $notification->title,
			'message' => $notification->message,
			'link' => $notification->link
			]
			);
			
			ActivityLog::addToLog("Notification Add By ".Session('admin_data')[0]['first_name'].' '.Session('admin_data')[0]['last_name'],$notification->title,false);
			
			$arr = array('msg' => 'Notification Successfully Added', 'status' => true);
			return Response()->json($arr);
		}
		
		public function notification_status_change(Request $notification){
			$notification_data = notification::find($notification->notification_id);
			if($notification_data->status == "deactive"){
		        $notification_data->status = "active";
				} elseif($notification_data->status == "active") {
		        $notification_data->status = "deactive";
			}
			$notification_data->save();
			
			ActivityLog::addToLog($notification->notification_id." No. Notification Status Change By ".Session('admin_data')[0]['first_name'].' '.Session('admin_data')[0]['last_name'],$notification_data->title,false);
			$arr = array('msg' => 'Notification status changed.');
			return Response()->json($arr);
		}
		
		public function career(){
			return view('WebModiul.careers');
		} 
		
		public function tutorial(){
			$tutorials = tutorial::all();
			return view('WebModiul.tutorial',compact('tutorials'));
		}  
		
		public function tutorial_submit(Request $tutorial){
			$this->validate($tutorial, [
			'title'=> 'required',
			'link'=> 'required'
			]);
			
			$tutorials = new tutorial;
	        $tutorials->title = $tutorial->title;
	        $tutorials->link = $tutorial->link;
	        $tutorials->save();
			
	        return redirect()->back();
		}
		
		public function tutorial_edit($id){
			$tutorials = tutorial::all();
			$tutorial = tutorial::where('tutorial_id', $id)->first();
			return view('WebModiul.tutorial',compact('tutorial','tutorials'));
		}
		
		public function tutorial_update(Request $tutorial){
			$this->validate($tutorial, [
			'title'=> 'required',
			'link'=> 'required'
			]);
			
			$tutorials = tutorial::find($tutorial->tutorial_id);
			$tutorials->title = $tutorial->title;
			$tutorials->link = $tutorial->link;
			$tutorials->save();
			return redirect('/tutorial-manage');
		}
		
		public function tutorial_status($tutorial_id){
			$tutorials = tutorial::findOrFail($tutorial_id);
			if($tutorials->status == "deactive"){
		        $tutorials->status = "active";
				} elseif($tutorials->status == "active") {
		        $tutorials->status = "deactive";
			}
			$tutorials->save();
			return redirect('/tutorial-manage');
		}
		
		public function tutorial_delete($id){
			tutorial::where('tutorial_id',$id)->delete();
			$tutorials = tutorial::all();
			return view('WebModiul.tutorial',compact('tutorials'));
		}
		
		public function terms_condition_manage(){
			return view('WebModiul.terms_condition_manage');
		}  
		
		public function create_terms_condition(){
			return view('WebModiul.create_terms_condition');
		}
		
		public function store_terms_condition(Request $request){
			$filename='';
			$tc='';
			$activaty_msg="Add Terms & Condition By ".Session('admin_data')[0]['first_name'].' '.Session('admin_data')[0]['last_name'];
			if($request->hasFile('tc_document')){
	    		$document = $request->file('tc_document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				Upload::document($document,$filename,$extension);
			}
			if(isset($request->id)){
				$tc = termsandcondition::find($request->id);
				if($request->hasFile('tc_document')==false){
					$filename = $tc['document'];
				}
				$activaty_msg="Update Terms & Condition By ".Session('admin_data')[0]['first_name'].' '.Session('admin_data')[0]['last_name'];
			}
			else{
				$tc = new termsandcondition;
			}
			$tc->added_by = Session('admin_data')[0]['admin_id'];
			$tc->type = "t&c";
			$tc->document = $filename;
			$tc->body_text = $request->body;
			$tc->status = 'deactive';
			$tc->created_at = Carbon::now();
			$tc->save();
			ActivityLog::addToLog($activaty_msg,false,$filename);
			$arr = array('msg' => 'Terms & Condition Successfully Added.', 'status' => true);
			return Response()->json($arr);
		}  
		public function edit_terms_condition($id){
			$tc = termsandcondition::find($id);
			return view('WebModiul.create_terms_condition',compact('tc'));
		}           
		
		
		public function privacy_manage(){
			return view('WebModiul.privacy_manage');
		}  
		
		public function create_privacy(){
			return view('WebModiul.create_privacy');
		}
		
		public function store_privacy(Request $request){
			$filename='';
			$tc='';
			$activaty_msg="Add Privacy & Policy By ".Session('admin_data')[0]['first_name'].' '.Session('admin_data')[0]['last_name'];
			if($request->hasFile('tc_document')){
				
				$document = $request->file('tc_document');
				$filename = time() . '.' . $document->getClientOriginalExtension();
				$extension = $document->getMimeType();
				
				Upload::document($document,$filename,$extension);
			}
			if(isset($request->id)){
				$tc = termsandcondition::find($request->id);
				if($request->hasFile('tc_document')==false){
					$filename = $tc['document'];
				}
				$activaty_msg="Update Privacy & Policy By ".Session('admin_data')[0]['first_name'].' '.Session('admin_data')[0]['last_name'];
			}
			else{
				$tc = new termsandcondition;
			}
			$tc->added_by = Session('admin_data')[0]['admin_id'];
			$tc->type = "privacy";
			$tc->document = $filename;
			$tc->body_text = $request->body;
			$tc->status = 'deactive';
			$tc->created_at = Carbon::now();
			$tc->save();
			
			ActivityLog::addToLog($activaty_msg,false,$filename);
			
			$arr = array('msg' => 'Privacy & Policy Successfully Added.', 'status' => true);
			return Response()->json($arr);
		}  
		public function edit_privacy($id){
			$privacy = termsandcondition::find($id);
			return view('WebModiul.create_privacy',compact('privacy'));
		}
		public function Terms_PrivacyStatusChange($id)
		{
			$tc_p = termsandcondition::find($id);
			$status='';
			if($tc_p->status =='active'){
				$status = 'deactive';
			}
			else{
				$status = 'active';
			}
			if($tc_p->type != 'privacy'){
				$activaty_msg=$tc_p->t_c_id." No. Terms & Condition Status Change to ".$status.' by '.$tc_p->userDetails['first_name'].' '.$tc_p->userDetails['first_name'];
			}
			else{
				$activaty_msg=$tc_p->t_c_id." No. Privacy & Policy Status Change to ".$status.' by '.$tc_p->userDetails['first_name'].' '.$tc_p->userDetails['first_name'];
			}
			$tc_p->status = $status;
			$tc_p->updated_at = Carbon::now();
			$tc_p->save();
			
			ActivityLog::addToLog($activaty_msg,false,false);
			
			return back();
		}               
	}
