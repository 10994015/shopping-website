<?php 

namespace App\Http\Helpers;

use App\Http\Resources\ProductResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Cart
{
    public static function getCartItemsCount(): int{
        $request = \request();
        $user = $request->user();
        if($user){
            return CartItem::where('user_id', $user->id)->sum('quantity');
        }else{
            $cartItems = self::getCookieCartItems();

            return array_reduce(
                $cartItems,
                fn($carry, $item) => $carry + $item['quantity'],
                0
            );
        }
    }
    public static function getCartItems(){
        
        $request = \request();
        $user = $request->user();
        if($user){
            return CartItem::where('user_id', $user->id)->get()->map(
                fn($item) => ['product_id'=>$item->product_id, 'quantity'=>$item->quantity, 'price'=>DB::table('products')->where('id', $item->product_id)->first()->sale_price ?? DB::table('products')->where('id', $item->product_id)->first()->price]
            );
        }else{
            return self::getCookieCartItems();
        }
    }
    public static function getCookieCartItems(){
        $request = \request();

        return json_decode($request->cookie('cart_items', '[]'), true);
    }
    public static function getCountFromItems($cartItems){
        return array_reduce(
            $cartItems,
            fn($carry, $item) => $carry + $item['quantity'],
            0
        );
    }
    public static function moveCartItemsIntoDb(){
        $request = \request();
        $cartItems = self::getCookieCartItems();
        $dbCartItems = CartItem::where(['user_id'=> $request->user()->id])->get()->keyBy('product_id');
        $newCartItems = [];
        foreach($cartItems as $cartItem){
            if(isset($dbCartItems[$cartItem['product_id']])){
                continue;
            }
            $newCartItems[] = [
                'user_id' =>$request->user()->id,
                'product_id'=>$cartItem['product_id'],
                'quantity'=>$cartItem['quantity'],
            ];
        }

        if(!empty($newCartItems)){
            CartItem::insert($newCartItems);
        }
    }
    public static function getProducts($cartItems){
        $ids = Arr::pluck(json_decode($cartItems), 'product_id');
        $cartItems = (collect(json_decode($cartItems))->keyBy('product_id'));
        $products = Product::whereIn('id', $ids)->get();
        $products = $products->map(fn($product)=>[
            'id'=>$product->id,
            'slug'=>$product->slug,
            'image'=>$product->image,
            'title'=>$product->title,
            'price'=>($product->sale_price) ?(int) $product->sale_price : (int)$product->price,
            'quantity'=>$cartItems[$product->id]->quantity,
            'total'=>($product->sale_price) ? (int) $product->sale_price * $cartItems[$product->id]->quantity : (int) $product->price * $cartItems[$product->id]->quantity ,
        ]);
        $products = (collect(json_decode($products))->keyBy('id'));
        return ($products);
    }
}