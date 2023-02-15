<?php

namespace App\Http\Controllers;
use App\Events\AcceptedIntern;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Display innterns
        $user = User::whereNotNull('Supervisor')->get();
        
        
        return response()->json([$user],200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $depts = Department::all();
        $Supervisors = User::whereNull('Supervisor')->get();
        $roles = ['Admin','Supervisor','Intern'];
        return view('User.create',['roles'=>$roles,'depts'=>$depts,'Supervisors'=>$Supervisors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $user = new User;
        $user->Name = $request->input('Name');
        $user->Email = $request->input('Email');
        $user->PhoneNumber = $request->input('PhoneNumber');
        $user->department_id = $request->input('Department');
        $user->Role = $request->input('Role');
        $user->Supervisor = $request->input('Supervisor');
        $user->Status = true;
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            "message" => "Successfully created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
       return response()->json([
        'message'=>'Updated', 
        'user' =>$user
       ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        return response()->json([
            "message" => 'Deleted',
            'user' => $user
        ]);
    }

    public function myInterns(Request $request){

        if($request->has(['supervisor'])){
        $user = User::findorfail($request->input('supervisor'));
        $Interns = $user->Attachee();
        }

        else{
            $Supervisors = User::where('Supervisor','01gs7sfpbyvqk9t4hpaxjgjqjf')->get();

            return view('User.index',['supervisors' =>$Supervisors]);
        }

    }
}
