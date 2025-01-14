<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function index(){
        // ユーザ一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        // ユーザ一覧ビューにユーザデータを渡す
        return view('users.index', [
            'users' => $users
        ]);
    }
    
    public function show(string $id){
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザの投稿&フォローしているユーザの投稿一覧を作成日時の降順で取得
        // $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        // ユーザ詳細ビューでそれを表示
        return view('users.show', [
            'user'       => $user,
            'microposts' => $microposts
        ]);
    }
    
    // ユーザのフォロー一覧ページを表示するアクション
    public function followings(string $id){
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);
        
        return view('users.followings', [
            'user'  => $user,
            'users' => $followings,
        ]);
    }
    
    public function followers(string $id){
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数を取得
        $user->loadRelationshipCounts();
        
        // ユーザのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);
        
        // フォロワー一覧ビューでそれらを表示
        return view('users.followers', [
            'user'  => $user,
            'users' => $followers,
        ]);
    }
    
    public function favorites(string $user_id){
        
        // idでユーザを検索して取得
        $user = User::findOrFail($user_id);
        
        // 関係するモデルの件数を取得
        $user->loadRelationshipCounts();
        
        // お気に入り一覧を取得
        $microposts = $user->favorites()->paginate(10);
        
        // お気に入り一覧をビューで表示
        return view('users.favorites',[
            'user'      => $user,
            'microposts' => $microposts,
        ]);
    }
}
