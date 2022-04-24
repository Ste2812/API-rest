<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class passportAuthController extends Controller
{

    public function register(Request $request){
        $validatedData = $request->validate([
            'name'=>'required|max:255',
            'email'=>'required|emailunique:users',
            'password'=>'required|confirmed'
        ]);
        $validatedData['password']= Hash::make($request->password);

        $user= User::create($validatedData);

        $accessToken= $user->createToken('authToken')->accessToken;

        return response([
            'user'=> $user,
            'access_token'=> $accessToken,
        ]);
    }

    public function login(Request $request){

        $loginData= $request->validate([
            'email' => 'email|requires',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)){
            return response(['message'=>'Invalid credentials']);
        }
        $accessToken= auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
