<?php

namespace App\Http\Controllers;
use App\Events\AcceptedIntern;
use App\Models\Applicants;
use App\Models\Position;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\User;
use App\Models\Role;
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
        $data =[
            'Users' =>  User::all(),
            'Applicants' => Applicants::all(),
            'Interns' => User::where('Role',3)->get(),
            'Supervisors' => User::where('Role',2)->get()
        ];
        
        
        
        return response()->json([$data],200);
        }

    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
     {
        $depts = Department::all();
        $Supervisors = User::where('Role',2)->get();
        $roles = Role::all();
        $positions = Position::all();
        
    //     $data = [
    //         'Departments' => $depts,
    //         'Supervisors' => $Supervisors,
    //         'Roles' => $roles,
    //         'Positions' => $positions
    //     ];

    //     return response()->json($data,200);
    return view('User.create',['depts'=> $depts,'position' => $positions,'roles'=>$roles,'Supervisors'=>$Supervisors]);      
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
	    $user->Position = $request->input('Position');
	    $user->Role = $request->input('Role');

        $user->Supervisor = $request->input('Supervisor');
        $user->Status = true;
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            "message" => "Successfully created"
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $user = User::findorfail($id);
            $data = [
                'User' => $user
            ];

            return response()->json($data,200);
        }
        catch(ModelNotFoundException){
             return response()->json([
                'message' => 'No such user'
             ],404);
        }

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $user = User::findorfail($id);
            $data = [
                'User' => $user
            ];

            return response()->json($data,200);
        }
        catch(ModelNotFoundException){
             return response()->json([
                'message' => 'No such user'
             ],404);
        }
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
    public function destroy($id)
    {
        try{
            $user = User::findorfail($id);
            $user->delete();

            return response()->json(['message' => 'User deleted'],410);
        }
        catch(ModelNotFoundException){
             return response()->json([
                'message' => 'No such user'
             ],404);
        }
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
