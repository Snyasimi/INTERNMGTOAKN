<?php

namespace App\Http\Controllers;

use App\Models\Applicants;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\User;

class AccountActivator extends Controller
{
    public function Activate(Request $request)
    {
        try
        {

            $validate = $request->validate([
                'email' => ['required,unique:applicants'],
                'password' => ['required']
            ]);

            $user = User::where('Email',$validate['email'])->get();
            if ($user)
            {
                $user->Status->true;
                $user->password = $validate['password'];
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
    {
        try
        {
            User::destroy($id);
            return response()->json(["message" => "Application Declined"],200);
        }
        catch(ModelNotFoundException)
        {
                         return response()->json(["message" => "Declined"],400);
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
                    'Applicant' => $applicant
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


}
