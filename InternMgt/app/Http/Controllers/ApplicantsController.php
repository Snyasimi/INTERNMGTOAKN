<?php

namespace App\Http\Controllers;
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
        return view('Apply.create');
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
		'Email' => ['required','unique:users','email'],
		'PhoneNumber' =>['min_digits:8'],
		'Position' => ['required',],
		'Cv'=>['file']
	
	    ]);

        
	   

	     $url_to_file = $request->file('CV-letter')->store('app/storage');
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
	    return redirect(route('Apply.index'));


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Applicants  $applicants
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $applicant = Applicants::findorfail($id);
	    $cv = Storage::url($applicant->url_to_file);
	    return response()->view('Apply.show',['applicant'=>$applicant,'cv'=>$cv]);
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
        $Applicant = Applicants::findorfail($id); 
        
        //dd($Applicant->Email);
        return to_route('Apply.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Applicants  $applicants
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $applicant = Applicants::findorfail($id);
	    $applicant->delete();
	    return redirect(roure("Apply.index"));//->back();

    }
}
