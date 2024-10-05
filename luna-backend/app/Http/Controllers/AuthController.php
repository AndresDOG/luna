<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credentials = request(['email','password']);
        
        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return response()->json();            
        }

        return response()->json(['message' => 'Unauthorized'],401);
        
    }

    public function logout(Request $request)
    {
        Auth::logout();
     
        $request->session()->invalidate();     
        $request->session()->regenerateToken();     
        return response()->noContent();
    }
}