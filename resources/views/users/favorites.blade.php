@extends('layouts.app')
@section('content')

    <div class="sm:grid sm:grid-cols-3 sm:gap-10">
        <aside class="mt-4">
            {{-- ユーザ情報 --}}
            @include('users.card')
        </aside>
        
        <div class="sm:col-span-2 mt-4">
            
            {{-- タブ --}}
            @include('users.navtabs')
            
            {{-- お気に入り投稿一覧 --}}
            @include('microposts.microposts')
        </div>
    </div>

@endsection