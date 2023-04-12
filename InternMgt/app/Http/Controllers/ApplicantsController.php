<?php

namespace App\Http\Controllers;
use App\Events\{InterviewDeclined,InterviewPassed,InterviewStatus};
use App\Http\Requests\ApplicationRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Models\{Applicants,Position};
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

    public function store(ApplicationRequest $request)
	    /*
	     * Save user to database 
	     * 
	     * */
    {
        try
        {
        
             $validate = $request->validated();
    
             $url_to_cv = $request->file('Cv')->store('public');
             $url_to_attachment_letter = $request->file('AttachmentLetter')->store('public');
             
    
           Applicants::create([
               'Name' => $validate['Name'],
               'Email' => $validate['Email'],
               'PhoneNumber' => $validate['PhoneNumber'],
               'Position' => $validate['Position'],
               'url_to_cv_file' => $url_to_cv,	
               'url_to_attachment_letter' => $url_to_attachment_letter,
        
               'ApplicationStatus' => 'Processing'
            
           
           ]);
    
            return response()->json(["message" => "Created"], 201);
        }
        catch(QueryException)
        {
            return response("Make sure the data you have entered is correct");

        }
	     


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

                    $validate = $request->validate([
                        'Date' => ['required']
                    ]);
                    $Date = $validate['Date'];

                    InterviewStatus::dispatch($Applicant,$Date);

                    $data = [
                        'message' => 'Email Sent successfuly'
                    ];
        
                    return response()->json($data, 200);


                case 'Declined' :
                    
                    InterviewDeclined::dispatch($Applicant);
                    $data = [
                        'message' => 'Email Sent successfuly'
                    ];
        
                    return response()->json($data, 200);

                case 'Passed' :

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
                'message' => 'Not Found'
            ];
            return response()->json($data, 404);
        }
    }
}
