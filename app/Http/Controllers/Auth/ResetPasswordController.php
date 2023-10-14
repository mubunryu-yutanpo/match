<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/mypage';

    // パスワードリセットフォームへ
    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }


    // リセットの期限切れを知らせるページを表示する
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return view('auth.passwords.reset_expired');
    }

    // リセットの成功メッセージをフラッシュメッセージとして表示する
    protected function sendResetResponse($response)
    {
        return redirect()->route('login')->with('flash_message', 'パスワードを変更しました！');
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
