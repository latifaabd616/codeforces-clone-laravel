<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    use Queueable;
 

    protected $message;

    /**
     * Create a new notification instance.
     */

    public function __construct($message)
    {
        $this->message = $message;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    

    public function via($notifiable)
    {
        return ['database']; // يمكنك إضافة قنوات أخرى مثل slack, sms, etc.
    }

  

        public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'action_url' => url('/'),
            'created_at' => now()->toDateTimeString()
        ];
    }
}
