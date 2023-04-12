<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
                'Email' => ['required'],
                'PhoneNumber' =>['required','size:13'],
                'Position' => ['required'],
                'Cv'=>['required','mimes:pdf'],
                'AttachmentLetter' => ['required','mimes:pdf']
            
        ];
    }
    public function messages()
    {
        return[
            'Name.regex' => "Please enter your two names with a space in-between",
            'Cv.mimes' => "The CV must be a file of type : PDF ",
            'AttachmentLetter.mimes' => "The Attchement Letter must be a file of type : PDF ",
            'PhoneNumber.regex' => 'Digits must be 13 characters long '
            

        ];
    }
}
