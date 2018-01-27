<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $params = $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|max:20'
        ]);

        return ($token = Auth::guard()->attempt($params))
              ?$this->success(['token' => $token])
              :$this->failed('账号或密码错误', Response::HTTP_UNAUTHORIZED);
    }

    public function register(Request $request){
        $params = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create($params);
        $token = Auth::guard()->fromUser($user);
        return $this->success(['token' => $token]);
    }
}
