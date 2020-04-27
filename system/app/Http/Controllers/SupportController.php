<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\user;
use App\Model\support;
use Carbon;
use ActivityLog;
use Upload;

class SupportController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.support.support');
    }

    public function user_support_list($user)
    {
        $user_info = user::find($user);
        return view('SuperAdmin.support.user_support_list',compact('user_info'));
    }

    public function ticket_submit(Request $ticket)
    {
        if($ticket->hasFile('document')){
            $document = $ticket->file('document');
            $filename = time() . '.' . $document->getClientOriginalExtension();
            $extension = $document->getMimeType();

            Upload::document($document,$filename,$extension);
        }else{
            $filename = false;
        }

        $support = new support;
        $support->user_id = $ticket->user_id;
        $support->ticket_type = 'ticket';
        $support->added_by = session('admin_data')[0]['admin_id'];
        $support->ticket_title = $ticket->ticket_title;
        $support->ticket_details = $ticket->ticket_details;
        $support->priority = $ticket->priority;
        $support->ticket_for = $support->ticket_no;
        $support->status = 'pending';
        $support->reply_by = 'admin';
        $support->ticket_document = $filename;
        $support->save();

        $support_next = support::find($support->ticket_no);
        $support_next->ticket_for = $support->ticket_no;
        $support_next->save();

        ActivityLog::addToLog('Ticket '.$support->ticket_no.' added to for user '.$ticket->user_id,false,$filename);

        $arr = array('msg' => 'Successfully ticket added.');
        return Response()->json($arr);
    }

    public function ticket_details($ticket_no)
    {
        $ticket = support::where('ticket_for',$ticket_no)->get();
        return view('SuperAdmin.support.user_support_details',compact('ticket'));
    }

    public function ticket_replay(Request $replay)
    {
        $support = new support;
        $support->user_id = $replay->user_id;
        $support->ticket_type = 'chat';
        $support->ticket_message = $replay->ticket_message;
        $support->ticket_for = $replay->ticket_no;
        $support->reply_by = 'admin';
        $support->save();

        $support_next = support::find($replay->ticket_no);
        $support_next->status = 'processing';
        $support_next->save();

        return Response()->json();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
