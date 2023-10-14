<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    /**
    * The password reset token.
    * 
    /** @var string */

    public $token;
    
    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */

    
    public function __construct($token)
    {
        $this->token = $token;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->from('admin@example.com', config('app.name'))
        ->subject('パスワード再設定')
        ->line('下のボタンをクリックしてパスワードを再設定してください。')
        // ->action('パスワード再設定', url(config('app.url').route('password.reset', $this->token, false)))
        ->line(url('/welcome'))
        ->line('もし心当たりがない場合このメールを破棄してください。');    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}