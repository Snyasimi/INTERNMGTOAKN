<?php

namespace App\Http\Controllers;
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
        $Applicants = Applicants::all();

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
        $positions = Position::all();
        //return view('Apply.create',['position' => $positions]);
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
    {
        //dd($request->all());
	     $validate = $request->validate([
	    	'Name' => ['required'],
		    'Email' => ['required','unique:applicants,users'],
		    'PhoneNumber' =>['min_digits:8'],
		    'Position' => ['required'],
		    'Cv'=>['file']
	
	    ]);

        
	   
        //dd($request->all());
	     $url_to_file = $request->file('Cv')->store('cv');
        //$path = $url_to_file;
	   
            
	   $applicant = Applicants::create([
	    'Name' => $request->input('Name'),
		'Email' => $request->input('Email'),
		'PhoneNumber' => $request->input('PhoneNumber'),
		'Position' => $request->input('Position'),
		'url_to_file' => $url_to_file,
        'Rating'=> 0
	   ]);
        //dd($applicant);
	    return response()->json(["message" => "Created"], 201);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Applicants  $applicants
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
       
	        $applicant = Applicants::findorfail($id);
	        $cv = Storage::url($applicant->url_to_file);
            $data = [
                'Applicant' => $applicant,
          
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
    public function edit(Applicants $applicants)
    {
        //
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
        try{
            $Applicant = Applicants::findorfail($id); 
            $data = [
                'message' => 'Updated successfuly'
            ];
        
            return response()->json($data, 200);
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
