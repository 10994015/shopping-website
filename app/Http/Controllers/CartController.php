<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    
    public function index(){
        $cartItems = Cart::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
      
        $cartItems = collect($cartItems)->keyBy('product_id');
        // $cartItems = Arr::keyBy($cartItems, 'product_id');
        $total = 0;
        foreach($products as $product){
            if($product->sale_price){
                $total += $product->sale_price * $cartItems[$product->id]['quantity'];
            }else{
                $total += $product->price * $cartItems[$product->id]['quantity'];
            }
        }

        return view('cart.index', compact('cartItems', 'products', 'total'));
    }

    public function add(Request $request){
        Log::info($request->slug);
        $product = $request->product;
        $quantity = $request->post('quantity', 1);
        if($quantity < 1){
            return response('購物車數量請勿小於1', 401);
        }
        $user = $request->user();
        if($user){
            $cartItem = CartItem::where(['user_id'=>$user->id, 'product_id'=>$product['id']])->first();
            if($cartItem){
                $cartItem->quantity += $quantity;
                $cartItem->update();
            }else{
                $data = [
                    'user_id'=>$request->user()->id,
                    'product_id'=>$product['id'],
                    'quantity'=>$quantity,
                ];
                CartItem::create($data);
            }
            // $cartItems = CartItem::find($request->user()->id);
            $cartItems = CartItem::where('user_id', $user->id)->get()->map(fn($item)=>[
                'id'=>$item['id'],
                'user_id'=>$item['user_id'],
                'product_id'=>$item['product_id'],
                'quantity'=>$item['quantity'],
                'price'=>DB::table('products')->where('id', $item['product_id'])->first()->sale_price ?? DB::table('products')->where('id', $item['product_id'])->first()->price,
            ]);
            return response([
                'count'=>Cart::getCartItemsCount(),
                'cartItems'=>$cartItems,
            ]);
        }else{
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;
            foreach($cartItems as &$item){
                if($item['product_id'] === $product['id']){
                    $item['quantity'] += $quantity;
                    $productFound = true;
                    break;
                }
            }
            if(!$productFound){
                $price = ($product['sale_price']) ? $product['sale_price'] : $product['price'];
               
                $cartItems[] = [
                    'user_id'=>null,
                    'product_id'=>$product['id'],
                    'quantity'=>$quantity,
                    'price'=>$price,
                ];
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60*24*30);

            return response(['count'=>Cart::getCountFromItems($cartItems), 'cartItems'=>$cartItems]);
        }

         
    }

    public function remove(Request $request){
        $product = Product::where('slug', $request->slug)->first();
        $user = $request->user();
        if($user){
            $cartItem = CartItem::query()->where(['user_id'=>$user->id, 'product_id'=>$product->id])->first();
            if($cartItem){
                $cartItem->delete();
            }
            $cartItems = CartItem::where('user_id', $user->id)->get();
            $ids = Arr::pluck($cartItems, 'product_id');
            $cartItems = $cartItems->map(fn($item)=>[
                'id'=>$item['id'],
                'user_id'=>$item['user_id'],
                'product_id'=>$item['product_id'],
                'quantity'=>$item['quantity'],
                'price'=>DB::table('products')->where('id', $item['product_id'])->first()->sale_price ?? DB::table('products')->where('id', $item['product_id'])->first()->price,
            ]);
            return response([
                'count'=> Cart::getCartItemsCount(),
                'cartItems'=>$cartItems,
                'ids'=>$ids,
            ]);
        }else{
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach($cartItems as $i => &$item){
                if($item['product_id'] === $product->id){
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60*24*30);

            $ids = Arr::pluck($cartItems, 'product_id');
            return response(['count'=>Cart::getCountFromItems($cartItems), 'ids'=>$ids, 'cartItems'=>$cartItems]);
        }
    } 

    public function updateQuantity(Request $request){
        $quantity = (int)$request->quantity;
        if($quantity < 1){
            return response('購物車數量請勿小於1', 401);
        }
        $product = Product::where('slug', $request->slug)->first();
        // $quantity = (int)$request->post('quantity');
        $user = $request->user();
        if($user){
            CartItem::where(['user_id'=>$request->user()->id, 'product_id'=>$product->id])->update(['quantity'=>$quantity]);
            $cartItems = CartItem::where('user_id', $user->id)->get();
            $cartItems = $cartItems->map(fn($item)=>[
                'id'=>$item['id'],
                'user_id'=>$item['user_id'],
                'product_id'=>$item['product_id'],
                'quantity'=>$item['quantity'],
                'price'=>DB::table('products')->where('id', $item['product_id'])->first()->sale_price ?? DB::table('products')->where('id', $item['product_id'])->first()->price,
            ]);
            return response([
                'count'=> Cart::getCartItemsCount(),
                'cartItems'=>$cartItems,
            ]);
        }else{
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach($cartItems as &$item){
                if($item['product_id'] === $product->id){
                    $item['quantity'] = $quantity;
                    break;
                }
            }

            Cookie::queue('cart_items', json_encode($cartItems), 60*24*30);

            return response(['count'=>Cart::getCountFromItems($cartItems), 'cartItems'=>$cartItems]);
        }
    }

    public function getProducts(Request $request){
        $ids = Arr::pluck(($request->cartItems), 'product_id');
        $cartItems = (collect(($request->cartItems))->keyBy('product_id'));
        $products = Product::whereIn('id', $ids)->get();
        $products = $products->map(fn($product)=>[
            'id'=>$product->id,
            'slug'=>$product->slug,
            'image'=>$product->image,
            'title'=>$product->title,
            'price'=>($product->sale_price) ?(int) $product->sale_price : (int)$product->price,
            'quantity'=>$cartItems[$product->id]['quantity'],
            'total'=>($product->sale_price) ? (int) $product->sale_price * $cartItems[$product->id]['quantity'] : (int) $product->price * $cartItems[$product->id]['quantity'] ,
        ]);
        $products = (collect(json_decode($products))->keyBy('id'));
        return ($products);
    }

    
    
}
