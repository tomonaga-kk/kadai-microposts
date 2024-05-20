<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    public function store(string $id){
        
        // ログインユーザが対象idのユーザをフォローする
        \Auth::user()->follow(intval($id));
        
        // 前のURLにリダイレクト
        return back();
    }
    
    public function destroy(string $id){
        
        // ログインユーザが対象idのユーザをアンフォローする
        \Auth::user()->unfollow(intval($id));
        
        // 前のURLへリダイレクト
        return back();
    }
}
