<?php

namespace App\Http\Controllers;

use App\Events\PasswordReset;
use App\Http\Requests\SignupRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\{User,Applicants};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Mime\Email;

class AccountActivator extends Controller
{
    public function active()
    {
        //INSERT FRONT END URL HERE
        return redirect()->away(env('FRONTEND_SIGNIN_URL'));
    }
    public function PasswordResetRedirect($id)
    {
        //INSERT FRONTEND URL HERE
        return redirect()->away(env('FRONTEND_PASSWDRESET_URL').$id);
    }
    public function Activate(SignupRequest $request)
    {
        try
        {

            $validate = $request->validated();
             
            $user = User::where('Email',$validate['email'])->first();

            if ($user && $user->password == null) 
            {
                $user->Status=true;
                $user->password = bcrypt($validate['password']);
                $user->save();

                return response()->json(["message"=> "Password Updated"],201);
            }
            else
            {
                return response()->json(["message" => "Please Apply to Login"],401);
        
            }


        }

        catch(ModelNotFoundException)
        {
           return response()->json(["message" => "Declined"],400);
        }


    }
    public function Deactivate($id)
    /*When the accepted intern declines the invitaion/internship opportunity
    *the account is deleted from the users table
    * but the application still remains in the applicants table 
    */
    {
        try
        {
            
            $user = User::findorfail($id);
            Applicants::where('Email',$user->Email)->delete();
            $user->delete();
            
            return response()->json(["message" => "Application Declined"],200);
        }
        catch(ModelNotFoundException)
        {
           return response()->json(["message" => "Applicant not found"],404);
        }
         

    }
    public function CheckStatus(Request $request)
    {
        
        $validate = $request->validate([
            "Email" =>['required','email']
        ]);

        try
        {
            $applicant = Applicants::where('Email',$validate['Email'])->first();
            if (!$applicant == null)
            {
                $data = [
                    'ApplicationStatus' => $applicant
                ];
                return response()->json($data,200);
            }
            else
            {
                return response()->json(["message" => "No applicant"],404);
            }
            
            
        }
        catch(ModelNotFoundException)
        {
            return response()->json(["message" => "No applicant"],404);
        }

    }
    public function RequestPasswordReset(Request $request)
    {
        try
        {
            $validate = $request->validate([
                'email' => ['required','email']
            ]);
            $user = User::where('Email', $validate['email'])->first();
            $user->tokens()->delete();

            $token = $user->createToken('Reset-password',['ResetPassword'])->plainTextToken;
            
            PasswordReset::dispatch($user,$token);
    
            return response()->json(["message" => "If the email exists,
                                     you will recieve instructions on how to reset your password "],200);
        }
        catch(ModelNotFoundException)
        {
            return response()->json(['message' => 'No Such user'],404);
        }
    }

    public function ResetPassword(Request $request)
    {
        try
        {
            $validate = $request->validate([
                'token' => ['required'],
                'password' => ['required',Password::min(6)->numbers()],
        
            ]);

            function removeFirstCharactersUpToCharacter($string, $character) {
                // Find the position of the character to remove up to
                $pos = strpos($string, $character);
                // If the character is found, remove the characters up to and including it
                if ($pos !== false) {
                    $string = substr($string, $pos + 1);
                }
            
                return $string;
            }
            $Token = removeFirstCharactersUpToCharacter($validate['token'],"|");
            

            $token = DB::table('personal_access_tokens')->where('token','=', hash('sha256',$Token))
                            ->where('abilities','=','["ResetPassword"]')->first();

    
            if($token)
            {
                $user = User::findorfail($token->tokenable_id);
                
                if($user)
                {
                    
                    $user->password = bcrypt($validate['password']);
                    $user->save();
                    $user->tokens()->delete();
                    return response()->json(["message" => "Password Updated"],200);
                }
                else
                {
                    return response()->json(['message' => 'User did not request password reset'],406);
                }
    
            }
    
            else
            {
                return response()->json(['message' => 'User Does not exist'],404);
            }
       
        }
        catch(ModelNotFoundException)
        {
            return response()->json(['message' => 'No such user'],404);
        }

    }


 
    // public function ForgotPassword(Request $request)
    // {
    //     try
    //     {
    //         $validate = $request->validate([
    //             'email' => ['required'],
    //             'password' => ['required']
    //         ]);

    //         User::firstWhere('Email',$validate['email'])->update(['password'=> bcrypt($validate['password'])]);
    //         return response()->json(['message'=>'Password reset'],200);
    //     }
    //     catch(ModelNotFoundException)
    //     {
    //         return response()->json(['message'=> 'Does not exist'],404);
    //     }
    // }


}
