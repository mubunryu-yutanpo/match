<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
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
            // パスワードリセットのリンクを生成
            $actionUrl = url('password/reset', $this->token);

            // カスタマイズしたメールテンプレートを使う
            return (new MailMessage)
                ->from('match.app.infomation@gmail.com', config('app.name'))
                ->subject('パスワード再設定のご案内')
                ->markdown('emails.reset-password', ['actionUrl' => $actionUrl]);        }
        
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
