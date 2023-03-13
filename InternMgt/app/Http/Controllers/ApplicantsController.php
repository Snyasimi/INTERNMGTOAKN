<?php

namespace App\Http\Controllers;
use App\Events\InterviewPassed;
use App\Events\InterviewStatus;
use App\Models\Position;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use App\Models\Applicants;
use Illuminate\Http\Request;


class ApplicantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //SHOW ALL APPLICANTS
        $Applicants = Applicants::lazy();

         $data =[
		 
		 "Applicants" => $Applicants,
		 "message" => 'Displaying applicants'
	 ];
	
	return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $positions = Position::lazy();

         $data =[
             'positions' => $positions
         ];
         return response()->json($data,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
	    /*
	     * Save user to database 
	     * 
	     * */
    {
	     $validate = $request->validate([
	    	'Name' => ['required'],
		    'Email' => ['required'],
		    'PhoneNumber' =>['min_digits:8'],
		    'Position' => ['required'],
		    //'Cv'=>['required','file'],
            //'AttachmentLetter' => ['required','file']

	    ]);


	    
	     $url_to_cv = $request->file('Cv')->store('public');
	     $url_to_attachment_letter = $request->file('AttachmentLetter')->store('public');
         

	   $applicant = Applicants::create([
		   'Name' => $request->input('Name'),
		   'Email' => $request->input('Email'),
		   'PhoneNumber' => $request->input('PhoneNumber'),
		   'Position' => $request->input('Position'),

		   
		   'url_to_cv_file' => $url_to_cv,	
		   'url_to_attachment_letter' => $url_to_attachment_letter,
	
		   'ApplicationStatus' => 'Processing'
        
	   
	   ]);

	    return response()->json(["message" => "Created"], 200);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Applicants  $applicants
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    /*
	     * Returns an applicants details 
	     * */
        try{

	        $applicant = Applicants::findorfail($id);
		$cv = asset(Storage::url($applicant->url_to_cv_file));
		$attachmentLetter = asset(Storage::url($applicant->url_to_attachment_letter));
            $data = [
		    'Applicant' => $applicant,

		    'Cv' => $cv,
		    'AttachmentLetter' => $attachmentLetter, 
                'message' => 'Showing applicant'
	    ];

	        return response()->json($data,200);
        }
        catch(ModelNotFoundException){
            $data = [
                'message' => 'Model not found'
            ];

            return response()->json($data, 404);

        };
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Applicants  $applicants
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    /*
	     * Returns an applicants details by id
	     * */
        
        $app =  Applicants::findorfail($id);
        return $app;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Applicants  $applicants
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
	    /*
	     *Updates and sends email to the applicant based on the application status
	     *if the AppliactionStatus is Accepted =>  they are sent an email to come for an interview
	     *If the application status is Declined they are sent an email notifying them
	     *If the ApplicationStatus is Passed they are sent an email They can accept or decline the offer
	     */
        try{

            $Applicant = Applicants::findorfail($id);
            

            if($request->has('ApplicationStatus'))
            {
               switch($request->input('ApplicationStatus'))
               {

                case 'Accepted':
                    $Email_body = $request->input('EmailBody');
                    InterviewStatus::dispatch($Applicant,$Email_body);
                    $data = [
                        'message' => 'Email Sent successfuly'
                    ];
        
                    return response()->json($data, 200);

                    break;
                case 'Declined' :
                    $Email_body = "Declined";
                    InterviewStatus::dispatch($Applicant,$Email_body);
                    $data = [
                        'message' => 'Email Sent successfuly'
                    ];
        
                    return response()->json($data, 200);
                case 'Passed' :
                        $Email_body = "Passed Interview";
                        InterviewPassed::dispatch($Applicant);
                        $data = [
                            'message' => 'Email Sent successfuly'
                        ];
            
                        return response()->json($data, 200);

                default:
                return response()->json(["message" => 'Nothing to update'],200);
                    
                    
               }
            }
            else
            {
                return response()->json(["message" => "Bad request"], 400);
            }


           
        }
        catch(ModelNotFoundException){
            $data = [
                'message' => 'Model not found'
            ];

            return response()->json($data, 404);

        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Applicants  $applicants
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    /*
	     * Deletes an application
	     *
	     * */
        try{

	        $applicant = Applicants::findorfail($id);
	        $applicant->delete();
            $data =[
                "message" => $applicant->Name . " " . "deleted"
            ];
	        return response()->json($data,410);
        }

        catch(ModelNotFoundException){
            $data = [
                'message' => 'Not Found in database'
            ];
            return response()->json($data, 404);
        }
    }
}
