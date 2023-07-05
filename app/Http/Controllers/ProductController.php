<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public $sort_field = 'updated_at';
    public $sort_direction = 'desc';
    public $search = '';
    public $min = 0;
    public $max = 10000;
    public function index(){
        $products = Product::where('hidden', 0)->orderBy('updated_at', 'desc')->paginate(16);
        $categories = Category::all();
        
        return view('store', ['products'=>$products, 'categories'=>$categories]);
    }

    public function show(Request $req){
        $product = Product::where('slug', $req->slug)->first();
        $products = Product::where(['hidden'=> 0, 'category_id'=>$product->category_id])->where('id', '<>', $product->id)->orderBy('updated_at', 'DESC')->take(4)->get();

        return view('product-detail', ['product'=>$product, 'products'=>$products]);
    }
    public function sort(){
        $this->sort_field = request('sort_field', 'updated_at');
        $this->sort_direction = request('sort_direction', 'desc');

        if($this->sort_field != 'price'){
            $products = Product::where('hidden', 0)->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderBy($this->sort_field, $this->sort_direction)->paginate(16);
        }else{
            $products = Product::where('hidden', 0)->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderByRaw("CASE 
            WHEN sale_price IS NOT NULL THEN sale_price
            ELSE price
            END $this->sort_direction")
            ->paginate(16);
        }
        return json_encode($products);
    }
    public function search(){
        $this->sort_field = request('sort_field', 'updated_at');
        $this->sort_direction = request('sort_direction', 'desc');
        $this->search = request('search', '');
        $this->min = request('min', 0);
        $this->max = request('max', 10000);
        if($this->sort_field != 'price'){
            $products = Product::where('hidden', 0)->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderBy($this->sort_field, $this->sort_direction)->paginate(16);
        }else{
            $products = Product::where('hidden', 0)->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderByRaw("CASE 
            WHEN sale_price IS NOT NULL THEN sale_price
            ELSE price
            END $this->sort_direction")
            ->paginate(16);
        }
        return json_encode($products);
    }
    public function filter(){
        $this->min = request('min', 0);
        $this->max = request('max', 10000);

        if($this->sort_field != 'price'){
            $products = Product::where('hidden', 0)->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderBy($this->sort_field, $this->sort_direction)->paginate(16);
        }else{
            $products = Product::where('hidden', 0)->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderByRaw("CASE 
            WHEN sale_price IS NOT NULL THEN sale_price
            ELSE price
            END $this->sort_direction")
            ->paginate(16);
        }
        return json_encode($products);
    }
    
}
