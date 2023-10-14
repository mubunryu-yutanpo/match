<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/mypage';

    protected function sendResetResponse($response)
    {
        return redirect('/mypage')->with('flash_message', 'パスワードを変更しました')->with('flash_message_type', 'success');
    }

    // リセットの期限切れを知らせるページを表示する
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return view('auth.passwords.reset_expired');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'email'], // メールアドレスのバリデーションルール
            'password' => ['required', 'min:8'], // パスワードのバリデーションルール
            'password_confirmation' => ['required', 'same:password'], // パスワード再入力のバリデーションルール
        ]);
    }

}
