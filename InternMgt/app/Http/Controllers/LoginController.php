<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
     public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        //dd($credentials);
 
        if (Auth::attempt($credentials)) {
           
            // if(Auth::user()->Role == "Adm"){
            //     $token = Auth::user()->createToken('Login-Token',['can:doanything'])->plainTextToken;
            // }
            // elseif(Auth::user()->Role == 'Sup'){
            //     $token = Auth::user()->createToken('Login-Token',['can:assignroles'])->plainTextToken;
            // }

            $request->session()->regenerate();
            $token = Auth::user()->createToken('Login-Token')->plainTextToken;
            $response = [
                'user' => Auth::user(),
                'message' => 'User created succesfully',
                'token' => $token
            ];
            return response($response,200);
        }
        else{

            return response(["message"=>'Credentials do not match our records']);
        }


        }

        public function logout(Request $request){

            Auth::user()->currentAccessToken()->delete();
            session()->invalidate();
            //Auth::logout();

            return response()->json([
                'message'=>"Logged Out"
            ]);
        }
    
    
    }
