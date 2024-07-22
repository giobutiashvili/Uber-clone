<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [TwilioChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toTwilio(object $notifiable)
    {
        //კოდის გენრირება 
        $loginCode = rand(11111, 99999);

        //კოდის შენახვა მომხმარებელში
        $notifiable->update([
            'login_code' => $loginCode
        ]);

        // კოდის გაგზავნა 
        return (new TilioSmsMassage())
        ->content("თქვენი ავტორიზაციის ცოდი არის:{$loginCode}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
