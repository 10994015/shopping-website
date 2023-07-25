<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function index(Request $request){
        $products = Favorite::where('user_id', Auth::id())->with('product')->orderBy('id', 'desc')->get();
        Log::info($products);
        return view('favorites', compact('products'));
    }
    public function add(Request $request){
        $favorite = Favorite::create([
            'user_id'=> Auth::id(),
            'product_id'=> $request['id'],
        ]);

        return response(json_encode($favorite), 200);
    }
    public function remove(Request $request){
        Favorite::where(['product_id'=>$request['id'], 'user_id'=>Auth::id()])->delete();
        
        return response("", 204);
    }
}
