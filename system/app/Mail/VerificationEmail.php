<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Model\user;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    public function __construct(user $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->from('noreplay@appsowl.com')
                    ->subject('Varification email')
                    ->view('email.variationemail')
                    ->with('user',$this->user);
    }
}
