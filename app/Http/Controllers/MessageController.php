<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    /* ================================================================
        DM画面へ
    =================================================================*/
    public function directMessages($id){

        $user = User::find($id);
        return view('mypage/message', compact('user'));
    }

}
