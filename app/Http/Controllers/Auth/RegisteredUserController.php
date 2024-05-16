<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // バリデーション
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // User情報をDBに保存
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ユーザー登録されたらメールアドレス認証用のメールを送る
        // /vendor/laravel/framework/src/Illuminate/Auth/Events/Registerd.phpをインスタンス化し、イベント発火を認識させる
        // /app/Providers/EventServiceProvider.phpでリッスンしているイベントが発火
        // /vendor/laravel/framework/src/Illuminate/Auth/Listeners/SendEmailVerificationNotificationが実行される
        event(new Registered($user));

        // ログインする
        Auth::login($user);
        
        // リダイレクト
        return redirect(RouteServiceProvider::HOME);
    }
}
