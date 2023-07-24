<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\DiscountCodeUsages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    public function input(Request $request){
        log::info($request);
        if(!Auth::check()) {
            return response()->json(['message'=>'請先進行登入！'], 401);
        }
        $code = $request['discount'];
        if($code == null || $code == ""){
            return response()->json(['message'=> '請輸入折扣碼！'], 400);
        }
        $discount = Discount::where([['code', $code], ['start_date', '<=', now()], ['end_date', '>=', now()]])->first();
        if(!$discount){
            return response()->json(['message'=>'無此折價券或折價券已過期'], 404);
        }
        $usage = DiscountCodeUsages::where(['user_id'=>Auth::id(), 'discount_id'=>$discount->id])->count();
        if($usage){
            return response()->json(['message'=>'已使用過此折價券'], 422);
        }
        $data = [
            'id'=> $discount->id,
            'code'=> $discount->code,
            'discount_type'=> $discount->discount_type,
            'discount_value'=> $discount->discount_value,
            'percentage'=> $discount->percentage,
            'minimum_spend'=> $discount->minimum_spend,
        ];
        return response()->json(['data'=> $data,'message'=> '使用成功！'], 200); 
    }
}
