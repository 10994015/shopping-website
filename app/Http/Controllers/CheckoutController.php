<?php

namespace App\Http\Controllers;
use App\Http\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Discount;
use App\Models\DiscountCodeUsages;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;

class CheckoutController extends Controller
{
    public function checkout(Request $request){
        require base_path('vendor/autoload.php');
        $user = $request->user();
        $discount = (float)$request['percentage'];
        $discount_id = $request['discount_id'] ?? null;
        if($discount_id){
            $discount = Discount::find($discount_id)->percentage ?? 1;
        }else{
            $discount = 1;
        }
        $cartItems = Cart::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = collect($cartItems)->keyBy('product_id');
        $lineItems = [];
        $orderItems = [];
        $totalPrice = 0;
        $token = Str::random(100);

        $itemDescriptions = '';
        foreach($products as $product){
            $price = ($product->sale_price) ? (int)$product->sale_price : (int)$product->price;
            $price = (int)$price * $discount;
            $totalPrice = $totalPrice + ($price * $cartItems[$product->id]['quantity']);
            $lineItems[] = [
                'name'=>$product->title,
                'price'=>$price,
                'quantity' => $cartItems[$product->id]['quantity'],
            ];
            $itemDescriptions .= $product->title . ' ' . $price . ' TWD x ' . $cartItems[$product->id]['quantity'] . ', ';
            $orderItems[] = [
                'product_id'=>$product->id,
                'quantity'=>$cartItems[$product->id]['quantity'],
                'unit_price'=>$price,
            ];
        }

        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
        ]);
        $autoSubmitFormService = $factory->create('AutoSubmitFormWithCmvService');
       
        // 移除最後一個逗號和空白
        $itemDescriptions = rtrim($itemDescriptions, ', ');
        $input = [
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'Test' . time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => $totalPrice,
            'TradeDesc' => UrlService::ecpayUrlEncode('交易描述範例'),
            'ItemName' => $itemDescriptions,
            'ChoosePayment' => 'Credit',
            'EncryptType' => 1,
        
            // 請參考 example/Payment/GetCheckoutResponse.php 範例開發
            'ReturnURL' => 'http://3.1.217.108/checkout/callback',
            'ClientBackURL' => 'http://3.1.217.108/checkout/success?token='. $token,
        ];
        $action = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';
        

        $orderData = [
            'total_price'=> $totalPrice,
            'status'=> 'unpaid',
            'created_by'=> $user->id,
            'updated_by'=> $user->id,
        ];
        $order = Order::create($orderData);

        //create order items 
        foreach($orderItems as $orderItem){
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }
        $paymentData = [
            'order_id'=> $order->id,
            'amount'=> $totalPrice,
            'status'=> 'pending',
            'type'=> 'cc',
            'created_by'=> $user->id,
            'updated_by'=> $user->id,
            'session_id'=>$token,
            'discount_id'=> $discount_id,
        ];
        $payment = Payment::create($paymentData);
        $discountCoseUse = [
            'user_id'=> $user->id,
            'discount_id'=>$discount_id,
            'order_id'=>$order->id,
            'discount_amount'=>$discount,
            'used_at'=>now(),
        ];
        if($discount_id){
            DiscountCodeUsages::create($discountCoseUse);
        }

        echo $autoSubmitFormService->generate($input, $action);

        
    }
    public function checkout2(Request $request){
        $user = $request->user();
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        $discount = (float)$request['percentage'];
        $discount_id = $request['discount_id'] ?? null;
        if($discount_id){
            $discount = Discount::find($discount_id)->percentage ?? 1;
        }else{
            $discount = 1;
        }
        $cartItems = Cart::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = collect($cartItems)->keyBy('product_id');
        $lineItems = [];
        $orderItems = [];
        $totalPrice = 0;
        foreach($products as $product){
            $price = ($product->sale_price) ? (int)$product->sale_price : (int)$product->price;
            $price = (int)$price * $discount;
            $totalPrice = $totalPrice + ($price * $cartItems[$product->id]['quantity']);
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'TWD',
                    'product_data' => [
                        'name' => $product->title,
                        'images'=> [$product->image]
                    ],
                    'unit_amount' => $price * 100 ,
                ],
                'quantity' => $cartItems[$product->id]['quantity'],
            ];
            $orderItems[] = [
                'product_id'=>$product->id,
                'quantity'=>$cartItems[$product->id]['quantity'],
                'unit_price'=>$price,
            ];
        }
        // dd(route('checkout.success', [], true), route('checkout.failure', [], true));
        $token = Str::random(100);
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'customer_creation'=>'always',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}'.'&token='.$token,
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        $orderData = [
            'total_price'=> $totalPrice,
            'status'=> 'unpaid',
            'created_by'=> $user->id,
            'updated_by'=> $user->id,
        ];
        $order = Order::create($orderData);

        //create order items 
        foreach($orderItems as $orderItem){
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }
        
        $paymentData = [
            'order_id'=> $order->id,
            'amount'=> $totalPrice,
            'status'=> 'pending',
            'type'=> 'cc',
            'created_by'=> $user->id,
            'updated_by'=> $user->id,
            'session_id'=>$token,
            'discount_id'=> $discount_id,
        ];
        $payment = Payment::create($paymentData);
        $discountCoseUse = [
            'user_id'=> $user->id,
            'discount_id'=>$discount_id,
            'order_id'=>$order->id,
            'discount_amount'=>$discount,
            'used_at'=>now(),
        ];
        if($discount_id){
            DiscountCodeUsages::create($discountCoseUse);
        }
        return redirect($checkout_session->url);
    }
    public function callback(Request $request){
        log::info('success!!!');
    }
    public function success2(Request $request){
        require_once base_path('vendor/autoload.php');

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        $user = $request->user();
        try {
            $token = $_GET['token'];
            $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);
            if(!$session){
                echo "0";
                return view('cart.checkout.failure');
            }

            $payment = Payment::where(['session_id'=> $token, 'status'=>'pending'])->first();
            if(!$payment){
                echo "1";
                return view('cart.checkout.failure');
            }
            $payment->status = 'paid';
            $payment->update();

            $order = $payment->order;
            $order->status = 'paid';
            $order->update();

            CartItem::where('user_id', $user->id)->delete();

            $customer = $stripe->customers->retrieve($session->customer);
            echo "<h1>Thanks for your order, $customer->name!</h1>";
            http_response_code(200);
            return view('cart.checkout.success', compact('customer'));
          } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            return view('cart.checkout.failure');
          }
    }
    public function success(Request $request){
        require_once base_path('vendor/autoload.php');

        $user = $request->user();
        try {
            $token = $_GET['token'];
            if(!$token){
                return view('cart.checkout.failure');
            }

            $payment = Payment::where(['session_id'=> $token, 'status'=>'pending'])->first();
            if(!$payment){
                return view('cart.checkout.failure');
            }
            $payment->status = 'paid';
            $payment->update();

            $order = $payment->order;
            $order->status = 'paid';
            $order->update();

            CartItem::where('user_id', $user->id)->delete();

            echo "<h1>Thanks for your order!</h1>";
            http_response_code(200);
            return view('cart.checkout.success', compact('user'));
          } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            return view('cart.checkout.failure');
          }
    }
    public function failure(Request $request){
        dd($request->all());
    }
    public function checkoutOrder($order, Request $request){
        $order = Order::find($order);
        $user = $request->user();
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $lineItems = [];
        
        foreach($order->items as $item){
            $lineItems[] = [
                'price_data' => [
                'currency' => 'TWD',
                'product_data' => [
                    'name' => $item->product->title,
                    'images'=> [$item->product->image]
                ],
                'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'customer_creation'=>'always',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}'.'&token='.$order->payment->session_id,
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        return redirect($checkout_session->url);

        // $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        // $session = $stripe->checkout->sessions->retrieve($sessionId);
    }
}
