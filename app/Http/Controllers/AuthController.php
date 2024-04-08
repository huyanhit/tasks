<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseSuccess(['token' => $token]);
    }

    public function login(LoginRequest $request){
        $request->authenticate();
        $token = Auth::user()->createToken('auth_token')->plainTextToken;

        return $this->responseSuccess(['token' => $token]);
    }

    public function logout() {
       return Auth::user()->tokens()->delete();
    }
}
