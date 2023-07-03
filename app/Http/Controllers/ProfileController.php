<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $customer = $user->customer;
        $billing_default = [
            'address1'=>'',
            'state'=>'',
            'country_code'=>'taiwan',
            'city'=>''
        ];
        $shipping_default = [
            'address1'=>'',
            'state'=>'',
            'country_code'=>'taiwan',
            'city'=>''
        ];
        $billingAddress = CustomerAddress::where(['customer_id'=>$customer->user_id, 'type'=>'billing'])->first() ?? $billing_default;
        $shippingAddress = CustomerAddress::where(['customer_id'=>$customer->user_id, 'type'=>'shipping'])->first() ?? $shipping_default;
        return view('profile', compact('customer', 'user', 'billingAddress', 'shippingAddress'));
    }

    public function update(ProfileRequest $request){
        $customerData = $request->validated();
        
        $user = $request->user(); 
        $customer = $user->customer;
        $customer->update($customerData);
        
        if(CustomerAddress::where(['customer_id'=>$customer->user_id, 'type'=>'billing'])->count() > 0){
            $billing_customer_address = CustomerAddress::where(['customer_id'=>$customer->user_id, 'type'=>'billing'])->first();
            $billing_customer_address->address1 = $customerData['billing_address1'];
            $billing_customer_address->address2 = $customerData['billing_address1'];
            $billing_customer_address->city = $customerData['billing_city'];
            $billing_customer_address->zipcode = $customerData['billing_city'];
            $billing_customer_address->state = $customerData['billing_state'];
            $billing_customer_address->country_code = $customerData['billing_country_code'];
            $billing_customer_address->save();
        }else{
            $billing_customer_address = new CustomerAddress();
            $billing_customer_address->type ='billing';
            $billing_customer_address->address1 = $customerData['billing_address1'];
            $billing_customer_address->address2 = $customerData['billing_address1'];
            $billing_customer_address->city = $customerData['billing_city'];
            $billing_customer_address->zipcode = $customerData['billing_city'];
            $billing_customer_address->state = $customerData['billing_state'];
            $billing_customer_address->country_code = $customerData['billing_country_code'];
            $billing_customer_address->customer_id = $customer->user_id;
            $billing_customer_address->save();
        }

        if(CustomerAddress::where(['customer_id'=>$customer->user_id, 'type'=>'shipping'])->count() > 0){
            $shipping_customer_address = CustomerAddress::where(['customer_id'=>$customer->user_id, 'type'=>'shipping'])->first();
            $shipping_customer_address->address1 = $customerData['shipping_address1'];
            $shipping_customer_address->address2 = $customerData['shipping_address1'];
            $shipping_customer_address->city = $customerData['shipping_city'];
            $shipping_customer_address->zipcode = $customerData['shipping_city'];
            $shipping_customer_address->state = $customerData['shipping_state'];
            $shipping_customer_address->country_code = $customerData['shipping_country_code'];
            $shipping_customer_address->save();
        }else{
            $shipping_customer_address = new CustomerAddress();
            $shipping_customer_address->type ='shipping';
            $shipping_customer_address->address1 = $customerData['shipping_address1'];
            $shipping_customer_address->address2 = $customerData['shipping_address1'];
            $shipping_customer_address->city = $customerData['shipping_city'];
            $shipping_customer_address->zipcode = $customerData['shipping_city'];
            $shipping_customer_address->state = $customerData['shipping_state'];
            $shipping_customer_address->country_code = $customerData['shipping_country_code'];
            $shipping_customer_address->customer_id = $customer->user_id;
            $shipping_customer_address->save();
        }
        $request->session()->flash('flash_message', '資料儲存更改成功！');
        return redirect()->route('profile');
    }
    public function resetPassword(Request $request){
        return view('reset-password');
    }
    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = $request->user();

        $passwordData = $request->validated();

        $user->password = Hash::make($passwordData['new_password']);
        $user->save();

        $request->session()->flash('flash_message', '更改密碼成功！');

        return redirect()->route('profile');
    }
}
