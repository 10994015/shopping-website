<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(){
        $products = Product::where('hidden', 0)->orderBy('updated_at', 'DESC')->paginate(16);
        $categories = Category::all();
        
        return view('store', ['products'=>$products, 'categories'=>$categories]);
    }

    public function show(Request $req){
        $product = Product::where('slug', $req->slug)->first();
        $products = Product::where('hidden', 0)->orderBy('updated_at', 'DESC')->take(4)->get();

        return view('product-detail', ['product'=>$product, 'products'=>$products]);
    }
}
