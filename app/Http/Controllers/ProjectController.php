<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectApplied;
use App\Http\Requests\ValidRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Image;
use App\User;
use App\Type;
use App\Project;
use App\Apply;

class ProjectController extends Controller
{
    /* ================================================================
        案件登録画面へ
    =================================================================*/
    public function new(){
        
        try{
            $user = Auth::user();
            $projectType = Type::all();
    
            return view('project/new', compact('user', 'projectType'));    
            
        }catch(QueryException $e){
            // エラー時
            Log::error('メソッド"new"実行エラー：'. $e->getMessage());
            redirect('/')->with('flash_message', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        案件の新規登録
    =================================================================*/
    public function create(ValidRequest $request){

        try {
            $user_id = Auth::id();
            $project = new Project;
    
            // サムネ画像のパス名を変数に
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $filename = $thumbnail->getClientOriginalName();

                // HEIC形式の画像をJPEG形式に変換
                if ($thumbnail->getClientOriginalExtension() === 'heic') {
                    $thumbnail = Image::make($thumbnail)->encode('jpg');
                    $filename = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
                }

                // 画像を圧縮して保存 
                $compressedImage = Image::make($thumbnail)->resize(300, null, function ($constraint) {
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

            } else {
                // サムネが未選択の場合
                $filename = 'thumbnail-default.png';
            }
    
            // 案件のタイプに応じて料金の内容を変更
            if ((int)$request->type === 1) {
                // 金額は1,000をかけた値に変換
                $upperPrice = $request->upperPrice * 1000;
                $lowerPrice = $request->lowerPrice * 1000;
            } else {
                $upperPrice = null;
                $lowerPrice = null;
            }
    
            $saved = $project->fill([
                'user_id'    => $user_id,
                'title'      => $request->title,
                'type'       => $request->type,
                'upperPrice' => $upperPrice,
                'lowerPrice' => $lowerPrice,
                'thumbnail'  => '/storage/uploads/'.$filename,
                'content'    => $request->content,
            ])->save();
    
            if ($saved) {
                // 成功時
                return redirect('/mypage')->with('flash_message', '案件を投稿しました！')->with('flash_message_type', 'success');
            } else {
                // 失敗時
                return redirect('/')->with('flash_message', 'データの保存に失敗しました。')->with('flash_message_type', 'error');
            }
    
        } catch (QueryException $e) {
            // エラー内容をログに吐いてリダイレクト
            Log::error('新規案件登録処理エラー：'. $e->getMessage());
            return redirect('/')->with('flash_message', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }
    
    /* ================================================================
        案件詳細画面へ
    =================================================================*/
    public function detail($id){

        try{

            $user = Auth::user();
            $project = Project::where('id', $id)->with('user')->first();
    
            return view('project/detail', compact('user', 'project'));

        }catch(QueryException $e){
            // エラー時
            Log::error('メソッド"detail"実行エラー：'. $e->getMessage());
            redirect('/')->with('flash_message', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        案件の編集・削除画面へ
    =================================================================*/
    public function edit($project_id){

        try{

            $user = Auth::user();

            $project = Project::find($project_id);
            $savedUpperPrice = $project->upperPrice / 1000;
            $savedLowerPrice = $project->lowerPrice / 1000;
            $projectType = Type::all();
    
            return view('project/edit', compact('user', 'project', 'savedUpperPrice', 'savedLowerPrice', 'projectType'));

        }catch(QueryException $e){
            // エラー時
            Log::error('メソッド"edit"実行エラー：'. $e->getMessage());
            redirect('/')->with('flash_message', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        案件の更新処理
    =================================================================*/
    public function projectUpdate(ValidRequest $request, $id){
                
        if (!ctype_digit($id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        try{
            $project = Project::find($id);
            $user_id = Auth::id();

            // ユーザーのチェック(違う場合はリダイレクト)
            if(!$user_id === $project->user_id){
                redirect('/')->with('flash_message', 'エラーが発生しました')->with('flash_message_type', 'error');
            }

            // 案件のタイプに応じて料金の内容を変更
            if ((int)$request->type === 1) {
                // 金額は1,000をかけた値に変換
                $upperPrice = $request->upperPrice * 1000;
                $lowerPrice = $request->lowerPrice * 1000;
            } else {
                $upperPrice = null;
                $lowerPrice = null;
            }

            // サムネ画像のパス名を変数に
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $filename = $thumbnail->getClientOriginalName();
                
                // HEIC形式の画像をJPEG形式に変換
                if ($thumbnail->getClientOriginalExtension() === 'heic') {
                    $thumbnail = Image::make($thumbnail)->encode('jpg');
                    $filename = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
                }

                // 画像を圧縮して保存 
                $compressedImage = Image::make($thumbnail)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $moved = $compressedImage->save(storage_path('app/public/uploads/' . $filename));

                
                if (!$moved) {
                    // 画像の保存等が失敗した場合
                    return redirect()->back()->with('flash_message', '画像のアップロードに失敗しました。')->with('flash_message_type', 'error');
                }

            } else if ($project->thumbnail !== 'thumbnail-default.png') {
                // 画像を変更しない場合
                $filename = $project->thumbnail;
                $filename = str_replace('storage/uploads/', '', $filename);

            } else {
                // サムネが未選択の場合
                $filename = 'thumbnail-default.png';
            }

            $updated = $project->update([
                'user_id'    => $user_id,
                'title'      => $request->title,
                'type'       => $request->type,
                'upperPrice' => $upperPrice,
                'lowerPrice' => $lowerPrice,
                'thumbnail'    => '/storage/uploads/'.$filename,
                'content'    => $request->content,
            ]);

            if($updated){
                // 成功時
                return redirect('/mypage')->with('flash_message', '情報を更新しました！')->with('flash_message_type', 'success');

            }else{
                // 失敗時
                return redirect('/mypage')->with('flash_message', 'データの更新に失敗しました')->with('flash_message_type', 'error');
            }
            
        }catch(QueryException $e){
            // エラーをログに吐いてリダイレクト
            Log::error('案件更新処理エラー：'. $e->getMessage());
            redirect('/')->with('flash_message', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        案件の削除処理
    =================================================================*/
    public function projectDelete($id){

        if (!ctype_digit($id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        try{

            $project = Project::find($id);

            // ユーザーのチェック
            $auth_user_id = Auth::id();

            if(!$auth_user_id === $project->user_id){
                redirect('/')->with('flash_message', 'エラーが発生しました')->with('flash_message_type', 'error');
            }

            // データを削除
            $delete = $project->delete();

            if($delete){
                // 成功時
                return redirect('/mypage')->with('flash_message', '削除しました！')->with('flash_message_type', 'success');

            }else{
                // 失敗時
                return redirect('/')->with('flash_message', 'エラーが発生しました。')->with('flash_message_type', 'error');
            }

        }catch(QueryException $e){
            // エラーをログに吐いてリダイレクト
            Log::error('案件削除処理エラー：'. $e->getMessage());
            return redirect('/')->with('flash_message_type', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        案件への応募処理
    =================================================================*/
    public function apply($project_id, $user_id){

        if (!ctype_digit($project_id) || !ctype_digit($user_id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        $user = User::find($user_id); // 応募者
        $project = Project::where('id', $project_id)->with('user')->first();
        $post_user = $project->user; // 案件の投稿者

        try{
            
            // すでに本案件に応募している場合はリダイレクト
            $applied = Apply::where('user_id', $user_id)->where('project_id', $project_id)->first();

            if($applied){
                return redirect()->back()->with('flash_message', 'この案件には既に応募しています')->with('flash_message_type', 'error');
            }

            // 応募テーブルに追加
            $apply = new Apply;
            $applySaved = $apply->fill([
                'user_id'    => $user_id,
                'project_id' => $project_id,
            ])->save();

            if(!$applySaved){
                // 応募の処理に失敗した場合
                return redirect()->back()->with('flash_message', '応募に失敗しました')->with('flash_message_type', 'error');
            }

            // 通知（DMへの招待など）を送信
            $send_post_user = Mail::to($post_user->email)->send(new ProjectApplied($user, $post_user, $project));
            $send_user =      Mail::to($user->email)->send(new ProjectApplied($user, $post_user, $project));

            if($send_post_user === null && $send_user === null){
                // 全ての処理が成功した場合
                return redirect('/mypage')->with('flash_message', '応募完了！メールをご確認ください')->with('flash_message_type', 'success');

            }else{
                // メールの送信に失敗している場合
                return redirect()->back()->with('flash_message', '処理の途中でエラーが発生しました')->with('flash_message_type', 'error');
            }

        }catch(QueryException $e){
            Log::error('応募処理エラー：'. $e->getMessage());
            return redirect()->back()->with('flash_message', '予想外のエラーが発生しました')->with('flash_message_type', 'error');
        }
    }


}
