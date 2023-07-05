<?php

namespace App\Http\Controllers;
use App\Http\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request){
        $user = $request->user();
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $cartItems = Cart::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = collect($cartItems)->keyBy('product_id');
        $lineItems = [];
        $totalPrice = 0;
        foreach($products as $product){
            $price = ($product->sale_price) ? (int)$product->sale_price  : (int)$product->price;
            $totalPrice = $totalPrice + ($price * $cartItems[$product->id]['quantity']);
            $lineItems[] = [
                'price_data' => [
                'currency' => 'TWD',
                'product_data' => [
                    'name' => $product->title,
                    'images'=> [$product->image]
                ],
                'unit_amount' => $price * 100,
                ],
                'quantity' => $cartItems[$product->id]['quantity'],
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

        $paymentData = [
            'order_id'=> $order->id,
            'amount'=> $totalPrice,
            'status'=> 'pending',
            'type'=> 'cc',
            'created_by'=> $user->id,
            'updated_by'=> $user->id,
            'session_id'=>$token
        ];
        $payment = Payment::create($paymentData);

        return redirect($checkout_session->url);
    }
    public function success(Request $request){
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
    public function failure(Request $request){
        dd($request->all());
    }
}
