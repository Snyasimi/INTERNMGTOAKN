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

            $AUTH_USER = Auth::user();

            if(Auth::user()->Role == "ADM"){
                $token = Auth::user()->createToken('Login-Token',['Admin'])->plainTextToken;
                //REDIRECT TO ADMIN PAGE
            }
            elseif($AUTH_USER->Role == "SUP"){
                $token = Auth::user()->createToken('Login-Token',['Supervisor'])->plainTextToken;
                //REDIRECT TO SUPERVISOR PAGE
            }


            else{
            $token = Auth::user()->createToken('Login-Token',['Intern'])->plainTextToken;
            //REDIRECT TO INTERNPAGE
            }
            $response = [
                'user' => Auth::user(),
                'message' => 'Logged in',
                'token' => $token
            ];
            return response($response,200);
        }
        else{

            return response(["message"=>'Credentials do not match our records'],401);
        }


        }

        public function logout(Request $request){

		Auth::user()->currentAccessToken()->delete();
		Auth::user()->tokens()->delete();
	    	session()->invalidate();

            //Auth::logout();

            return response()->json([
                'message'=>"Logged Out"
            ]);
        }


    }
