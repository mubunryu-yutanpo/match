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
        try{
            // メールアドレスがデータベース内に存在するか確認
            $user = User::where('email', $request->email)->first();

            if ($user === null) {
                return back()->with('flash_message', 'メールの送信に失敗しました。メールアドレスをご確認ください')->with('flash_message_type', 'error');
            }

            // パスワードリセットトークンを生成し、メールを送信
            Password::sendResetLink(['email' => $request->email]);

            // パスワードリセットリンクの送信が成功した場合の処理
            return back()->with('flash_message', 'パスワード再設定のメールを送信しました')->with('flash_message_type', 'success');

        }catch(QueryException $e){
            Log::error('パスワードリセットリンク送信エラー：'.$e->getMessage());
            return back()->with('flash_message', '予想外のエラーが発生しました')->with('flash_message_type', 'error');
        }
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
