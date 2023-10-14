<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //他のモデルの関係
    public function project(){
        return $this->hasMany('App\Project');
    }

    public function apply(){
        return $this->hasMany('App\Apply');
    }

    public function publicMessage(){
        return $this->hasMany('App\PublicMessage');
    }

    public function directMessage(){
        return $this->hasMany('App\DirectMessage');
    }


    /**
     * Send the email verification notification.
     *
     * @return void
     */

    // メール認証用の確認メールの内容を自作（日本語版）のものに
    public function sendEmailVerificationNotification()
    {
        // 日本語化したメールを送信
        $this->notify(new \App\Notifications\VerifyEmailJapanese);
    }


    /**
     * パスワード再設定メールの送信
     *
     * @param  string  $token
     * @return void
     */

    // 自作のパスワードリセットのメールテンプレートを使う
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
}
