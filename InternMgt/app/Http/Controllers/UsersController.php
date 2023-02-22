<?php

namespace App\Http\Controllers;
use App\Events\AcceptedIntern;
use App\Models\Applicants;
use App\Models\Position;
use App\Models\Task;
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


    //  public function __construct(){
    //     $this->middleware('ability:Admin,Supervisor')->except(['show','update','edit','destroy']);
    //  }




    public function index(Request $request)
    {
        //Display Details depending on the role

	    if($request->user()->tokenCan('Admin'))
	    {
            switch ($request->path()){

                case 'api/Admin/User/Dashboard' :

                    $data = [
                        'Total Users' => User::all()->count(),
                        'Total Supervisors' => User::where('Role',"SUP")->count(),
                        'Total Interns' => User::where('Role',"INT")->count(),
                        'Total Applicants' => Applicants::all()->count(),
                    ];
                    return response()->json($data,200);

                    break ;

                case 'api/Admin/User/Applicants' :

                    $data = [
                        'Applicants' => Applicants::all(),
                    ];

                    return response()->json($data,200);

                    break;

                case 'api/Admin/User/Supervisors' :

                    $data = [
                        'Supervisors' => User::where('Role',"SUP")->get(),
                    ];
                    return response()->json($data,200);

                    break;

                case 'api/Admin/User/Interns' :

                    $data = [
                        'Interns' => User::where('Role',"INT")->get(),
                    ];
                    return response()->json($data,200);

                    break;

                default :
                    return response()->json(["Message" => "No such route"],400);

                    break;




            }

	    }

        else if($request->user()->tokenCan('Supervisor')){

            switch ($request->path()){

                case 'api/Supervisor/User/AssignedTasks' :

                    $data = [
                        'TasksAssigned' => Task::where('AssignedBy',$request->user()->user_id)->get(),
                    ];
                    return response()->json($data,200);

                    break ;

                case 'api/Supervisor/User/MyInterns' :

                    $data =[
                        'MyInters' => User::where('Supervisor',$request->user()->user_id)->get(),
                        
                    ];

                    return response()->json($data,200);

                    break ;

                default:

                   $data = [
                        'user' => User::findorfail($request->user()->user_id)
                    ];
                    return response()->json($data,200);

            }

        }

        else{

            // $data = [
            //    'user' => $request->user()
            // ];

            return response()->json(["user" => $request->user()],200);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
     {
        
        $Supervisors = User::where('Role','SUP')->get();
        $roles = Role::all();
        $positions = Position::all();

        $data = [
            
            'Supervisors' => $Supervisors,
            'Roles' => $roles,
            'Positions' => $positions
        ];

      //  return response()->json($data,200);

    return view('User.create',['position' => $positions,'roles'=>$roles,'Supervisors'=>$Supervisors]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = $request->validate([
	    	'Name' => ['required'],
		    'Email' => ['required','unique:applicants,users'],
		    'PhoneNumber' =>['min_digits:8'],
		    'Position' => ['required'],
		    'Role' => ['required'],

	    ]);

        $user = new User;
        $user->Name = $request->input('Name');
        $user->Email = $request->input('Email');
        $user->PhoneNumber = $request->input('PhoneNumber');
	    $user->Position = $request->input('Position');
	    $user->Role = $request->input('Role');

        $user->Supervisor = $request->input('Supervisor',null);
        $user->Status = true;
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(["message" => "Successfully created"],201);
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

            if($user->Role == "SUP"){

                $data =[
                   'User' => $user,
                   "Interns" => User::where('Supervisor',$user->user_id)->get(),
                   "Tasks Assigned" => Task::where('AssignedBy',$user->user_id)->get()
                ];
                return response()->json($data,200);
            }

            else{
                $data = [
                    'User' => $user
                ];
                return response()->json($data,200);
        }


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

        if ($request->has(['SupervisorID','InternID'])){

            $validate = $request->validate([
                'SupervisorID' => ['required'],
                'InternID' => ['required']
            ]);

            User::where('user_id',$validate['InternID'])
                ->update(['Supervisor' => $validate['SupervisorID']]);


            return response()->json(['message'=>'Updated',200]);
        }

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
