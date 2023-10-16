<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/mypage';

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
    
        // パスワードリセットのロジックを実行
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
    
        // トークンが期限切れているかどうかを確認
        if ($response === Password::PASSWORD_RESET) {
            // パスワードが正常にリセットされた場合のリダイレクト
            return redirect('/mypage')->with('flash_message', 'パスワードを変更しました')->with('flash_message_type', 'success');
        } else {
            // トークンが期限切れている場合のリダイレクト
            return redirect('/reset-link-expired')->with('flash_message', 'リンクの有効期限が切れています')->with('flash_message_type', 'error');
        }
    }
    
}
