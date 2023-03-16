<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AccountManager extends Controller
{
	public function UpdateDetails(Request $request,$id)
	{
		try
		{
			
			
			User::where('user_id',$id)->update(

				['Email' => $validate['Email'],	
				['PhoneNumber' => $validate['PhoneNumber']]
			
			);

			return response()->json(['message' => 'Updated'],200);
		}
		catch(ModelNotFoundException)
		{
			return response->json(['message' => 'User Does Not Exist']);
		}
	       

			
		//CHANGE PASSWORD
	}

	
}
