<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery\Matcher\HasKey;

class AuthapiController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "name" => "required|min:3",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed",
        ]);
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);
        if (Auth::attempt($request->only(["email","password"]))){
                $token = Auth::user()->createToken('phone')->plainTextToken;
                return response()->json($token);
        }
        return response()->json([
            "message" => "Registration Faild",
            401
        ]);
    }
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8",
        ]);
        if (Auth::attempt($request->only(["email","password"]))){
            $token = Auth::user()->createToken('phone')->plainTextToken;
            return response()->json($token);
        }
        return response()->json([
            "message" => "Login Faild",
            401
        ]);
    }
    public function logout(){
        Auth::user()->currentAccessToken()->delete();
    }
    public function logoutAll(){
        Auth::user()->tokens()->delete();
    }
    public function tokens(){
        Auth::user()->currentAccessToken();
    }
}
