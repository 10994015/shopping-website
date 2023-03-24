<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $products = Product::where('hidden', 0)->orderBy('updated_at', 'DESC')->take(8)->get();

        $featured_products = Product::where([['hidden', 0], ['featured', 1]])->orderBy('updated_at', 'DESC')->take(3)->get();

        return view('dashboard', ['products'=>$products, 'featured_products'=>$featured_products]);
    }
}
