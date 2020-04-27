<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Model\user as User;

class VerificationEmail extends Notification implements ShouldQueue
{
    use Queueable;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->user->username)
                    ->line($this->user->email_varification_token)
                    ->action('Activate', route('email-varify',$this->user->email_varification_token))
                    ->line('Thank you for using our application!');
    }

   public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
