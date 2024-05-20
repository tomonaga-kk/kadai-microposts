<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    
    // userモデル ⇔　micropostモデルのリレーションを定義
    public function microposts(){
        return $this->hasMany(Micropost::class);
    }
    
    
    // // micropostの数をカウントするメソッド
    // public function loadRelationshipCounts(){
    //     $this->loadCount('microposts');
    // }
 
    
    // このユーザに関係するモデルの件数をロードする
    public function loadRelationshipCounts(){
        $this->loadCount(['microposts', 'followings', 'followers']);
    }
    
    
    // このユーザがフォロー中のユーザ(1つのuser_id ⇒ 複数follow_id取得)     $user->followings
    public function followings(){
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
                //$this->belongsToMany(相手model名::class, 中間テーブル名, '1レコード' ,'複数レコード')
    }
    
    
    // このユーザをフォロー中のユーザ(1つのfollow_id ⇒ 複数user_id取得)     $user->followers
    public function followers(){
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
    // フォローする
    public function follow(int $userId){
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if($exist || $its_me){
            return false;
        }else{
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    
    // アンフォローする
    public function unfollow(int $userId){
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        // 「フォロー済み && 対象が自分自身でない」なら処理実行
        if($exist && !$its_me){
            $this->followings()->detach($userId);
            return true;
        }else{
            return false;
        }
    }
    
    
    // フォロー状況確認
    public function is_following(int $userId){
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    
    // ログインユーザとフォロー中のユーザの投稿に絞り込む
    public function feed_microposts(){
        
        // ログインユーザがフォローの中ユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        
        
        // ログインユーザのidもその配列に追加
        $userIds[] = $this->id;
        
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
}
