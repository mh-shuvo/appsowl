<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Upload;
use ActivityLog;

class Sendsms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number;
    protected $text;
    protected $user_id;

    public function __construct($number, $text, $user_id=false)
    {
        $this->number = $number;
        $this->text = $text;
        $this->user_id = $user_id;
    }

    public function handle()
    {
        $send = Upload::send_sms($this->number,$this->text);
        
        if ($send['status']=='success') {
            ActivityLog::AddToSmsLog($send['number'],$this->text,$send['messageid'],$this->user_id);
        }
    }
}
