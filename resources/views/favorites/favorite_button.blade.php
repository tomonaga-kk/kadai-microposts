
{{-- お気に入り済みなら解除ボタンを表示 --}}
@if(Auth::user()->is_favorite($micropost->id))

    {{-- 解除ボタンのフォーム --}}
    <form method="POST" action="{{route('unfavorite', $micropost->id)}}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-primary btn-sm normal-case"
            onclick="return confirm('id={{$micropost->id}}をお気に入りから外します。よろしいですか？')">Unfavorite</button>
    </form>

{{-- 未お気に入りならお気に入りボタンを表示 --}}
@else

    {{-- お気に入りボタンのフォーム --}}
    <form method="POST" action="{{route('favorite', $micropost->id)}}">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm normal-case">Favorite</button>
    </form>
@endif