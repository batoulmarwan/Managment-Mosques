<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewUser extends Notification
{
    use Queueable;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast']; 
    }

   
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'تم إنشاء مستخدم جديد',
            'body' => 'تم إنشاء حساب جديد باسم: ' . $this->user->name,
            'user_id' => $this->user->id,
        ];
    }

    
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
