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
        // メールアドレスがデータベース内に存在するか確認
        $this->validate($request, ['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'そのメールアドレスは登録されていません。');
        }

        // パスワードリセットトークンを生成し、メールを送信
        Password::sendResetLink($request->only('email'));

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
