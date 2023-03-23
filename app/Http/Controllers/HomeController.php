<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $products = Product::orderBy('updated_at', 'DESC')->take(8)->get();
        return view('dashboard', ['products'=>$products]);
    }
}
