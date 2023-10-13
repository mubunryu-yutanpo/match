<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\ProfileUpdated;
use App\Project;
use App\User;


class MypageController extends Controller
{

    /* ================================================================
        マイページへ
    =================================================================*/
    public function mypage(){
        $user = Auth::user();
        return view('mypage/mypage', compact('user'));
    }

    /* ================================================================
        プロフィール編集画面へ
    =================================================================*/
    public function prof($id){
        
        try{

            $user = User::find($id);
            return view('mypage/prof', compact('user'));

        }catch(QueryException $e){
            Log::error('メソッド"prof"実行エラー：'. $e->getMessage());
            return redirect()->back()->with('flash_message', '予想外のエラーが発生しました')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        プロフィール編集処理
    =================================================================*/
    public function profUpdate(ValidRequest $request, $id){

        if (!ctype_digit($id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        try{
            $user = User::find($id);

            // メールアドレスの変更を判定
            $isEmailUpdated = $user->email !== $request->email;
            if($isEmailUpdated){
                // 変更前のアドレスを格納
                $oldEmail = $user->getOriginal('email');
            }

            // アバター画像のパス名を変数に
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = $avatar->getClientOriginalName();
                
                // HEIC形式の画像をJPEG形式に変換
                if ($avatar->getClientOriginalExtension() === 'heic') {
                    $avatar = Image::make($avatar)->encode('jpg');
                    $filename = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
                }

                // 画像を圧縮して保存 
                $compressedImage = Image::make($avatar)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // 画像をpublic/uploadsディレクトリに移動
                // $moved = $compressedImage->save(public_path('uploads/'.$filename));
                $moved = $compressedImage->save(storage_path('app/public/uploads/' . $filename));

                
                if (!$moved) {
                    // 画像の保存等が失敗した場合
                    return redirect()->back()->with('flash_message', '画像のアップロードに失敗しました。')->with('flash_message_type', 'error');
                }

            } else if ($user->avatar !== 'default-avatar.png') {
                // 画像を変更しない場合
                $filename = $user->avatar;
                $filename = str_replace('/storage/uploads/', '', $filename);

            } else {
                // アバターが未選択の場合
                $filename = 'default-avatar.png';
            }

            $updated = $user->update([
                'name'         => $request->name,
                'email'        => $request->email,
                'avatar'       => '/storage/uploads/'.$filename,
                'introduction' => $request->introduction,
            ]);

            if($updated){
                
                // メールアドレスの変更がある場合は、変更前・変更後のメアドに通知を送信
                if ($isEmailUpdated) {
                    $send_user = Mail::to($user->email)->send(new ProfileUpdated($user, $oldEmail));
                    $send_old_user = Mail::to($oldEmail)->send(new ProfileUpdated($user, $oldEmail));

                    if($send_user !== null && $send_old_user !== null){
                        return redirect('/mypage')->with('flash_message', '変更通知メールの送信に失敗しました')->with('flash_message_type', 'error');
                    }
                }

                // 成功時
                return redirect('/mypage')->with('flash_message', 'プロフィールを更新しました！')->with('flash_message_type', 'success');

            }else{
                // 失敗時
                return redirect()->back()->with('flash_message', 'データの保存に失敗しました')->with('flash_message_type', 'error');
            }

        }catch(QueryException $e){
            // エラー内容をログに吐いて、リダイレクト
            Log::error('プロフィール変更処理エラー：'. $e->getMessage());
            return redirect('/mypage')->with('flash_message', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }


    /* ================================================================
        退会画面へ
    =================================================================*/
    public function withdraw($id){

        try{

            $user = User::find($id);
            return view('mypage/withdraw', compact('user'));

        }catch(QueryException $e){
            Log::error('メソッド"withdrow"実行エラー：'. $e->getMessage());
            return redirect()->back()->with('flash_message', '予想外のエラーが発生しました')->with('flash_message_type', 'error');
        }
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
            Log::error('退会処理エラー:' . $e->getMessage());
            return redirect('/mypage')->with('flash_message', '予定外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        投稿一覧ページへ
    =================================================================*/
    public function postList($user_id){
        if (!ctype_digit($user_id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        return view('lists/postList', compact('user_id'));

    }

    /* ================================================================
        応募した案件一覧ページへ
    =================================================================*/
    public function applyList($user_id){
        if (!ctype_digit($user_id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        return view('lists/applyList', compact('user_id'));
    }

    /* ================================================================
        自分のパブリックメッセージ一覧ページへ
    =================================================================*/
    public function publicMessageList($user_id){
        if (!ctype_digit($user_id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        return view('lists/publicMessageList', compact('user_id'));
    }

    /* ================================================================
        自分のダイレクトメッセージ一覧ページへ
    =================================================================*/
    public function directMessageList($user_id){
        if (!ctype_digit($user_id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        return view('lists/directMessageList', compact('user_id'));
    }



}
