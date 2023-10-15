<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\User;
use Illuminate\Database\QueryException;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        // 正常にメールが送られた場合
        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('flash_message', 'メールを送信しました！')->with('flash_message_type', 'success');
        }

        // バリデーションエラーが発生した場合
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'メールを送信できませんでした。メールアドレスをご確認ください']);
    
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
