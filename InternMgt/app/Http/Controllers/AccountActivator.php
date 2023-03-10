<?php

namespace App\Http\Controllers;

use App\Events\PasswordReset;
use App\Models\Applicants;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AccountActivator extends Controller
{
    public function active()
    {
        return view('AccountActivator.Activate');
    }
    public function Activate(Request $request)
    {
        try
        {

            $validate = $request->validate([
                'email' => ['required'],
                'password' => ['required']
            ]);
             
            $user = User::where('Email',$validate['email'])->first();

            if ($user) 
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
            User::destroy($id);
            return response()->json(["message" => "Application Declined"],200);
        }
        catch(ModelNotFoundException)
        {
                         return response()->json(["message" => "Applicant Not found"],404);
        }
         

    }
    public function CheckStatus(Request $request)
    {
        
        $validate = $request->validate([
            "Email" =>['required']
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
        $validate = $request->validate([
            'email' => ['required']
        ]);
        $user = User::where('Email', $validate['email'])->first();
        PasswordReset::dispatch($user);

        return response()->json(["message" => "You will recieve an email to reset your password"],200);
    }
    public function ResetPassword(Request $request)
    {
        $validate = $request->validate([
            'password' => ['required']
        ]);

        
        User::where('user_id',Auth::user()->user_id)->update(['password'=>bcrypt($validate['password'])]);
     
        return response()->json(["message" => "Password Updated"]);   

    }

    public function ForgotPassword(Request $request)
    {
        try
        {
            $validate = $request->validate([
                'email' => ['required'],
                'password' => ['required']
            ]);

            User::firstWhere('Email',$validate['email'])->update(['password'=> bcrypt($validate['password'])]);
            return response()->json(['message'=>'Password reset'],200);
        }
        catch(ModelNotFoundException)
        {
            return response()->json(['message'=> 'Does not exist'],404);
        }
    }


}
