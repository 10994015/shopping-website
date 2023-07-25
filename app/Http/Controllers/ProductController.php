<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public $sort_field = 'updated_at';
    public $sort_direction = 'desc';
    public $search = '';
    public $min = 0;
    public $max = 10000;
    public $category_id;
    public function index(){
        $products = Product::where('hidden', 0)->orderBy('updated_at', 'desc')->paginate(16);
        $categories = Category::all();
        
        return view('store', ['products'=>$products, 'categories'=>$categories, 'category_id'=>0]);
    }
    public function category($category){
        $products = Product::where(['hidden'=> 0, 'category_id'=>$category])->orderBy('updated_at', 'desc')->paginate(16);
        $categories = Category::all();
        return view('store', ['products'=>$products, 'categories'=>$categories, 'category_id'=>$category]);
    }
    public function show(Request $req){
        $product = Product::where('slug', $req->slug)->first();
        $products = Product::where(['hidden'=> 0, 'category_id'=>$product->category_id])->where('id', '<>', $product->id)->orderBy('updated_at', 'DESC')->take(4)->get();
        $count = Comment::where('product_id', $product->id)->count();
        $comments = Comment::where('product_id', $product->id)->orderBy('created_at', 'desc')->paginate(5)->map(function($item){
            return [
                'id'=>$item->id,
                'email'=>$this->hidename(User::find($item->user_id)->email),
                'score'=>$item->score,
                'comment'=>$item->comment,
                'user_id'=>$item->user_id,
                'created_at'=>$item->created_at->format('Y-m-d H:i'),
            ];

        });
        $user = $req->user() ?? null;
        $favorite = Favorite::where(['user_id'=>Auth::id(), 'product_id'=> $product->id])->orderBy('id', 'desc')->first() ? 1 : 0;
        return view('product-detail', compact('product', 'products', 'comments', 'count', 'user', 'favorite'));
    }
    public function hidename($email){
        $str = explode("@", $email)[0];
        $length = strlen($str);
    
        if ($length <= 2) {
            return $str; 
        }
        
        $firstChar = $str[0];
        $lastChar = $str[$length - 1];
        $middleChars = str_repeat('*', $length - 2);
        
        return $firstChar . $middleChars . $lastChar;
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
        $this->category_id = request('category_id', 0);
        
        $this->sort_field = request('sort_field', 'updated_at');
        $this->sort_direction = request('sort_direction', 'desc');
        $this->search = request('search', '');
        $this->min = request('min', 0);
        $this->max = request('max', 10000);
        if($this->sort_field != 'price'){
            if($this->category_id == 0){
                $products = Product::where('hidden', 0)->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderBy($this->sort_field, $this->sort_direction)->paginate(16);
            }else{
                $products = Product::where(['hidden'=> 0, 'category_id'=> $this->category_id])->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderBy($this->sort_field, $this->sort_direction)->paginate(16);
            }
        }else{
            if($this->category_id == 0){
                $products = Product::where('hidden', 0)->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderByRaw("CASE 
                WHEN sale_price IS NOT NULL THEN sale_price
                ELSE price
                END $this->sort_direction")
                ->paginate(16);
            }else{
                $products = Product::where(['hidden'=> 0, 'category_id'=> $this->category_id])->where('price', '>=', $this->min)->where('price', '<=', $this->max)->where('title', 'like', '%'.$this->search .'%')->orderByRaw("CASE 
                WHEN sale_price IS NOT NULL THEN sale_price
                ELSE price
                END $this->sort_direction")
                ->paginate(16);
            }
           
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
    public function createComment(Request $request){
        $user = $request->user();
        if(!$user){
            return response('請先登入！', 401);
        }
        if($request->rating <= 0){
            return response('發送失敗！請填寫星等以及評論！', 500);
        }
        if($request->comment == "" || $request->comment == null){
            return response('發送失敗！請填寫星等以及評論！', 500);
        }
        $comment = Comment::create([
            'score'=> $request->rating,
            'comment'=> $request->comment,
            'product_id'=> $request->product_id,
            'user_id'=> $user->id,
        ]);
        if($comment){
            $newComments = Comment::where('product_id', $request->product_id)->orderBy('created_at', 'desc')->paginate(5)->map(function($item){
                return [
                    'id'=>$item->id,
                    'email'=>$this->hidename(User::find($item->user_id)->email),
                    'score'=>$item->score,
                    'comment'=>$item->comment,
                    'user_id'=>$item->user_id,
                    'created_at'=>$item->created_at->format('Y-m-d H:i'),
                ];
    
            });
            return response(['comments'=>$newComments, 'message'=>'已經收到您的評論！'], 201);
        }else{
            return response('伺服器錯誤請稍後再試一次', 500);
        }
    }
    public function deleteComment($id, Request $request){
        $user = $request->user();
        $comment = Comment::find($id);
        $product_id = $comment->product_id;
        if($user->id != $comment->user_id){
            return response('您無權限刪除此評論', 403);
        }
        $comment->delete();
        $comments = Comment::where('product_id', $product_id)->orderBy('created_at', 'desc')->paginate(5)->map(function($item){
            return [
                'id'=>$item->id,
                'email'=>$this->hidename(User::find($item->user_id)->email),
                'score'=>$item->score,
                'comment'=>$item->comment,
                'user_id'=>$item->user_id,
                'created_at'=>$item->created_at->format('Y-m-d H:i'),
            ];

        });
        return response(['comments'=>$comments, 'message'=>'刪除成功！'], 200);
    }
}
