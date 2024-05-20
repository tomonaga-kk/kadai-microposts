<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function store(string $micropost_id){
        \Auth::user()->favorite(intval($micropost_id));
        return back();
    }
    
    public function destroy(string $micropost_id){
        \Auth::user()->unfavorite(intval($micropost_id));
        return back();
    }
}
