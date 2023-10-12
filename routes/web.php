<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* ================================================================

    全ユーザーが閲覧可能ページ

=================================================================*/

Route::get('/', 'HomeController@home')->name('home');
Route::get('/list', 'HomeController@projectList')->name('list');


/* ================================================================

    認証あり

=================================================================*/

// Auth::routes();
Auth::routes(['verify' => true]);

Route::middleware('verified')->group(function () {
    Route::group(['middleware' => 'auth'], function () {

    /* ================================================================
        View関連(認証あり)
    =================================================================*/

    // マイページ・ユーザー関連
    Route::get('/mypage', 'MypageController@mypage')->name('mypage');
    Route::get('/home', 'MypageController@mypage')->name('mypage');
    Route::get('/prof/{user_id}', 'MypageController@prof')->name('prof');
    Route::get('/withdraw/{user_id}', 'MypageController@withdraw')->name('withdraw');
    Route::get('/user/info/{user_id}', 'HomeController@userInfo')->name('user.info');

    // 案件関連
    Route::get('/new', 'ProjectController@new')->name('new');
    Route::get('/project/{project_id}/detail', 'ProjectController@detail')->name('detail');
    Route::get('/edit/project/{project_id}', 'ProjectController@edit')->name('project.edit');

    // メッセージ関連
    Route::get('/messages/{auth_user_id}/{user_id}', 'HomeController@directMessages')->name('d.message');

    // 一覧ページ関連
    Route::get('/postList/{user_id}', 'MypageController@postList')->name('postList');
    Route::get('/applyList/{user_id}', 'MypageController@applyList')->name('applyList');
    Route::get('/publicMessageList/{user_id}', 'MypageController@publicMessageList')->name('pmList');
    Route::get('/directMessageList/{user_id}', 'MypageController@directMessageList')->name('dmList');

    /* ================================================================
        処理関連
    =================================================================*/

    // マイページ関連

    Route::post('/prof/{user_id}/edit', 'MypageController@profUpdate')->name('prof.update');
    Route::post('/withdraw/{user_id}/destroy', 'MypageController@destroy')->name('destroy');

    // 案件関連

    Route::post('/new', 'ProjectController@create')->name('create');
    Route::post('/edit/project/{project_id}/update', 'ProjectController@projectUpdate')->name('project.update');
    Route::post('/edit/project/{project_id}/delete', 'ProjectController@projectDelete')->name('project.delete');
    Route::post('/project/{project_id}/{user_id}/apply', 'ProjectController@apply')->name('apply');

    // メッセージ関連

    /* ================================================================
        ログアウト
    =================================================================*/
    
    Route::get('/logout', 'HomeController@logout')->name('logout');

    });
});

/* ================================================================

    API関連

=================================================================*/

Route::middleware('api')->group(function(){

    // トップページ用：案件取得
    Route::get('/api/top/projects', 'ApiController@getTopProjects');
    // プロフィール取得
    Route::get('/api/profile/{user_id}', 'ApiController@getProfile');
    // アバター取得
    Route::get('/api/{user_id}/avatar', 'ApiController@getAvatar');
    // サムネ取得
    Route::get('/api/{project_id}/thumbnail', 'ApiController@getThumbnail');
    // マイページ情報取得
    Route::get('/api/{user_id}/mypage', 'ApiController@getMypage');
    // 案件一覧
    Route::get('/api/projects', 'ApiController@getProjects');
    // 案件詳細
    Route::get('/api/{project_id}/detail', 'ApiController@getProjectDetail');
    // 案件のパブリックメッセージ取得
    Route::get('/api/{project_id}/publicMessages', 'ApiController@getPublicMessages');
    // パブリックメッセージ追加
    Route::post('/api/project/{project_id}/{user_id}/publicMessage', 'ApiController@postPublicMessage');
    // ダイレクトメッセージの取得
    Route::get('/api/messages/{chat_id}', 'ApiController@getDirectMessage');
    // ダイレクトメッセージ追加
    Route::post('/api/message/{user_id}/{chat_id}', 'ApiController@sendMessage');
    // DMの既読化
    Route::post('api/markAsRead/{chat_id}/{sender_id}/{receiver_id}', 'ApiController@markAsRead');

    // === 一覧用 ===

    // 投稿した案件
    Route::get('/api/{user_id}/postList', 'ApiController@getPostList');
    // 応募した案件
    Route::get('/api/{user_id}/applyList', 'ApiController@getApplyList');
    // パブリックメッセに絡んだ案件
    Route::get('/api/{user_id}/publicMessageList', 'ApiController@getPublicMessageList');
    // DM取得
    Route::get('/api/{user_id}/directMessageList', 'ApiController@getDirectMessageList');


});

