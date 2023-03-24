<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $req){
        $createdentials = $req->validate([
            'email'=>['required', 'email'],
            'password'=>['required'],
            'remember'=>'boolean',
        ]);
        $remember = $createdentials['remember'] ?? false;
        unset($createdentials['remember']);
        if(!Auth::attempt($createdentials, $remember)){
            return response([
                'message'=>'帳號或密碼錯誤！',
            ], 422);
        }

        $user = Auth::user();
        if(!$user->is_admin){
            Auth::logout();

            return response([
                'message'=>'您無權限進入此網站',
            ], 403);
        }

        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user'=>new UserResource($user),
            'token'=>$token,
        ]);
    }

    public function logout(){
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response('', 204);
    }

    public function getUser(Request $req){
        return new UserResource($req->user());
    }
}
