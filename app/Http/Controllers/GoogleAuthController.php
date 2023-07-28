<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Http\Helpers\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }
    public function callback(){
        try{
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();
            if ($user) {
                Auth::login($user);
                Cart::moveCartItemsIntoDb();
                return redirect('/');
            } else {
                $new_user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                ]);
                $customer = new Customer();
                $names = [mb_substr($new_user->name,0, 1, 'UTF-8'), mb_substr($new_user->name,1, null, 'UTF-8')];
                $customer->user_id = $new_user->id;
                $customer->first_name = $names[0];
                $customer->last_name = $names[1] ?? '';
                $customer->save();
                Auth::login($new_user);
                Cart::moveCartItemsIntoDb();
                return redirect('/');
            }

        }catch(\Throwable $th){
            dd($th->getMessage());
        }
    }
}
