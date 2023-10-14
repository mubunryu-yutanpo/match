<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // パスワードリセットトークンを生成し、カスタムメールテンプレートを使用してメールを送信
        Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject('パスワードリセット'); // メールの件名
            $message->view('emails.reset-password'); // カスタムメールテンプレートのビュー
        });

        // パスワードリセットリンクの送信が成功した場合の処理
        return back()->with('flash_message', 'パスワード再設定のメールを送信しました')->with('flash_message_type', 'success');
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
