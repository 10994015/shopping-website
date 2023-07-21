<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $products = Product::where('hidden', 0)->orderBy('created_at', 'DESC')->take(8)->get();

        $featured_products = Product::where([['hidden', 0], ['featured', 1]])->orderBy('updated_at', 'DESC')->take(3)->get();

        $comments = [
            [
                'username'=>'ggininder',
                'comment'=>'房子ROW真是我的幸運之選！我在這裡購買了一套客廳家具，品質絕佳，舒適度超乎預期。還有一流的客戶服務！他們的團隊非常熱心和專業，解答了我所有的問題，並確保及時送貨。家具完美地融入了我的家居風格，我對這次購物經歷非常滿意。謝謝房子ROW！我會向所有朋友推薦你們的！',
            ],
            [
                'username'=>'gugigugi333',
                'comment'=>'這是我第二次在房子ROW購物了，他們的家具品質和設計總是讓我驚艷！我這次購買了一張餐桌和椅子，質量非常出色，價格也相當實惠。組裝過程也很簡單，很快就完成了。我很喜歡這次購物體驗，感謝您們提供這麼棒的家具選擇。我肯定還會再來的！'
            ],
            [
                'username'=>'kingjames',
                'comment'=>'我對房子ROW的購物體驗感到非常滿意！我找了很長時間的書架，終於在這裡找到了完美的一款。訂購過程非常簡單，交付速度也超出我的預期。家具包裝得非常好，沒有任何損壞。書架的質量很好，看起來很耐用。我非常高興這次選擇了房子ROW，如果未來需要家具，我肯定會再次光顧這個令人愉快的購物網站。謝謝你們！'
            ]
        ];

        return view('dashboard', ['products'=>$products, 'featured_products'=>$featured_products, 'comments'=>$comments]);
    }
}
