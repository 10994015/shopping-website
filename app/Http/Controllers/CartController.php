<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    
    public function index(){
        $cartItems = Cart::getCartItems();
        log::info($cartItems);
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
      
        $cartItems = collect($cartItems)->keyBy('product_id');
        log::info($cartItems);
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
        $product = $request->product;
        Log::info($product);
        $quantity = $request->post('quantity', 1);
        log::info($quantity);
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
            return response([
                'count'=>Cart::getCartItemsCount(),
            ]);
        }else{
            log::info('no login');
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

            return response(['count'=>Cart::getCountFromItems($cartItems)]);
        }

         
    }

    public function remove(Request $request, Product $product){
        $user = $request->user();
        if($user){
            $cartItem = CartItem::query()->where(['user_id'=>$user->id, 'product_id'=>$product->id])->first();
            if($cartItem){
                $cartItem->delete();
            }

            return response([
                'count'=> Cart::getCartItemsCount(),
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

            return response(['count'=>Cart::getCountFromItems($cartItems)]);
        }
    } 

    public function updateQuantity(Request $request, Product $product){
        $quantity = (int)$request->post('quantity');
        $user = $request->user();

        if($user){
            CartItem::where(['user_id'=>$request->user()->id, 'product_id'=>$product->id])->update(['quantity'=>$quantity]);

            return response([
                'count'=> Cart::getCartItemsCount(),
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

            return response(['count'=>Cart::getCountFromItems($cartItems)]);
        }
    }
}
