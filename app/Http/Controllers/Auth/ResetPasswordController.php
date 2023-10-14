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

    public function showResetForm(Request $request, $token)
    {
        $hashedToken = hash('sha256', $token);  // 受け取ったトークンをハッシュ化

        // ハッシュ化されたトークンを使用してデータベースを検索
        $record1 = DB::table('password_resets')->where('token', $hashedToken)->first();
        $record2 = DB::table('password_resets')->where('token', $hashedToken)->first();

        dd($record1,$record2,Hash::check($hashedToken, $record1->token),Hash::check($token, $record1->token),Hash::check($hashedToken, $record2->token),Hash::check($token, $record2->token),);

    
        if (!$record1) {
            dd('!$record');
            return view('auth.passwords.reset_expired');
        }
    
        // トークンの有効期限をチェック
        $expires = now()->subMinutes(config('auth.passwords.users.expire'));
        if ($record1->created_at->lt($expires)) {
            dd('その下');
            return view('auth.passwords.reset_expired');
        }
    
        // トークンが有効であれば、リセットフォームを表示
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $record1->email]
        );
    }
    
    protected function tokenHasExpired($user, $token)
    {
        $expires = now()->subMinutes(config('auth.passwords.users.expire'));
        return $user->passwordReset->created_at->lt($expires);
    }

    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ], [
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '正しいメールアドレスを入力してください。',
            'password.required' => '新しいパスワードは必須です。',
            'password.confirmed' => 'パスワードが一致しません。',
            'password.min' => 'パスワードは少なくとも8文字以上である必要があります。',
        ]);

        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('flash_message', 'パスワードを変更しました！')->with('flash_message_type', 'success');
        } else {
            return $this->sendResetFailedResponse($request, $response);
        }
    }
}
