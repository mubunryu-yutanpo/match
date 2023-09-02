<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\User;


class MypageController extends Controller
{

    /* ================================================================
        マイページへ
    =================================================================*/
    public function mypage(){
        return view('mypage/mypage');
    }

    /* ================================================================
        プロフィール編集画面へ
    =================================================================*/
    public function prof($id){

        $user = User::find($id);
        return view('mypage/prof', compact('user'));
    }

    /* ================================================================
        プロフィール編集処理
    =================================================================*/
    public function profUpdate($id){

        if (!ctype_digit($id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }


        $user = User::find($id);
        return view('mypage/message', compact('user'));
    }


    /* ================================================================
        退会画面へ
    =================================================================*/
    public function withdraw($id){

        $user = User::find($id);
        return view('mypage/withdrow', compact('user'));
    }

    /* ================================================================
        退会処理
    =================================================================*/
    public function destroy($id){

        if (!ctype_digit($id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        try{

            // ユーザーデータを（物理）削除してリダイレクト

            $user = User::find($id);

            $deleted = $user->delete();

            if($deleted){
                // 成功時
                return redirect('/')->with('flash_message', '退会しました')->with('flash_message_type', 'success');

            }else{
                // 失敗時
                return redirect()->back()->with('flash_messge', 'データの処理に失敗しました')->with('flash_message_type', 'error');
            }

        }catch(QueryException $e){
            // エラー内容をログに吐いて、リダイレクト
            Log::error('退会処理SQLエラー:' . $e->getMessage());
            return redirect('/mypage')->with('flash_message', '予定外のエラーが発生しました。')->with('flash_message_type', 'error');
        }

    }


}
