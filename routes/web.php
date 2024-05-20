<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 使うコントローラの呼び出し
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserFollowController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\MicropostsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',          [MicropostsController::class, 'index']);
Route::get('/dashboard', [MicropostsController::class, 'index'])->middleware(['auth'])->name('dashboard');
// Route::get('/',          function(){ return redirect(route('users.show', Auth::user())); });
// Route::get('/dashboard', function(){ return redirect(route('users.show', Auth::user())); });



Route::middleware('auth')->group(function () {
    Route::resource('users',      UsersController::class,      ['only' => ['index', 'show']]);
    Route::resource('microposts', MicropostsController::class, ['only' => ['store', 'destroy']]);
    
    Route::prefix('user/{id}')->group(function(){
        
        // フォロー/アンフォロー機能
        Route::post  ('follow',     [UserFollowController::class, 'store'])     ->name('user.follow');
        Route::delete('unfollow',   [UserFollowController::class, 'destroy'])   ->name('user.unfollow');
        Route::get   ('followings', [UsersController::class,      'followings'])->name('users.followings');
        Route::get   ('followers',  [UsersController::class,      'followers']) ->name('users.followers');
        
        // お気に入り機能
        Route::post  ('favorite',    [FavoritesController::class, 'store'])    ->name('favorite');
        Route::delete('unfavorite',  [FavoritesController::class, 'destroy'])  ->name('unfavorite');
        Route::get   ('favorites',       [UsersController::class,    'favorites'])->name('users.favorites');
    });
    
//     Route::get(   '/profile', [ProfileController::class, 'edit'])   ->name('profile.edit');
//     Route::patch( '/profile', [ProfileController::class, 'update']) ->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


require __DIR__.'/auth.php';
