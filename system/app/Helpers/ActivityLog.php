<?php

namespace App\Helpers;
use Request;
use Session;
use App\Model\activitylog as LogActivityModel;
use App\Model\smslog;

class ActivityLog
{
    public static function addToLog($subject,$note=false,$document=false)
    {
    	$log = [];
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['user_id'] = session('admin_data')[0]['admin_id'];
        if (!empty($note)) {
            $log['note'] = $note;
        }
        if (!empty($document)) {
            $log['document'] = $document;
        }
        LogActivityModel::create($log);
    }

    public static function AddToSmsLog($number,$body,$messageid=false,$reciver=false)
    {
        $smslog = [];
        $smslog['messageid'] = $messageid;
        $smslog['number'] = $number;
        $smslog['body'] = $body;
        $smslog['send_to'] = $reciver;
        $smslog['sender'] = session('admin_data')[0]['admin_id'];
        smslog::create($smslog);
    }
}