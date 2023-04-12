<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->tokenCan('Admin') || Auth::user()->tokenCan('Supervisor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            
            'Name' => ['required','regex:/\b\w+\s+\w+\b/'],
            'Email' => ['required','email'],
	        'PhoneNumber' =>['required','size:13'],
            'Position' => ['required'],
            'Role' => ['required'],
        ];

    }

    public function messages()
    {
       return [
         'Name.regex' => 'Please enter your two names with a space in-between'
       
       ];
       
    }
}
