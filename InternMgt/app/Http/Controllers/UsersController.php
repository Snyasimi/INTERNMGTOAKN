<?php

namespace App\Http\Controllers;
use App\Events\{AcceptedIntern,PasswordReset,AssignedSupervisor,CreatedSupervisor};
use App\Http\Requests\AddUserRequest;
use App\Models\{User,Role,Applicants,Position,Task};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


   //  public function __construct()
     //{
       // $this->middleware('ability:Admin,Supervisor')->except(['show','update','edit','destroy']);
     //}




    public function index(Request $request)
    {
        /**
         * This function returns the all users but the result is dependant on the Role of the user
         * who made the request
         * 
         */

	    if($request->user()->tokenCan('Admin'))
        /**
         * If an admin made the request the results are displayed as follows
         */
	    {
            switch ($request->path()){

                case env('DASHBOARD') :
                    /**
                     * Results displayed are only the statistics 
                     */

                    $data = [
                        'TotalUsers' => User::count(),
                        'TotalSupervisors' => User::where('Role',"SUP")->count(),
                        'TotalInterns' => User::where('Role',"INT")->count(),
                        'SelectedApplicants' => Applicants::whereNot('ApplicationStatus','Processing')->count(),
                        'TotalApplicants' => Applicants::where('ApplicationStatus','Processing')->count(),
                    ];
                    return response()->json($data,200);

                case env('ADM_APPLICANTS') :

                    /*
                    *Results displayed are only the applicants 
                    */

                    $data = [
                        'Applicants' => Applicants::where('ApplicationStatus','Processing')->orderBy('Name')->lazy(),
                    ];

                    return response()->json($data,200);

                case env('ADM_INTERVIEWS') :
                    /*
                    *Interviews 
                    */

                    $data = [
                        'Applicants' => Applicants::whereNot('ApplicationStatus','Processing')->orderBy('created_at','desc')->lazy(),
                    ];
    
                    return response()->json($data,200);

                case env('ADM_SUPERVISORS') :
                    /*
                    *Supervisors 
                    */


                    $data = [
                        'Supervisors' => User::where('Role',"SUP")->orderBy('Name')->lazy(),
                    ];
                    return response()->json($data,200);

                    break;

		case env('ADM_INTERNS') :
			/**
			 * When a request is made this route by an admin it returns all the interns 
			 */

			$Interns = User::where('Role','INT')->orderBy('Name')->lazy();

			$CleanedInterns = $Interns->map(function($item)
			{
				if(!$item->Supervisor == null)
				{
					$item->supervisor = $item->supervisor->Name;
					unset($item['Supervisor']);
					return $item;
				}
				else
					return $item;
				
			
			});
			
			$data = [

				'Interns' => $CleanedInterns,
			
			];
			
			return response()->json($data,200);

			break;

		default :
			
			return response()->json(["Message" => "No such route"],400);


	    
	    }

	    }

	    
	    else if($request->user()->tokenCan('Supervisor')){

		    
		    switch ($request->path()){

		    
		    case env('SUP_ASSIGNED_TASKS') :
	
			    $data = [
		    
				    'TasksAssigned' => Task::where('AssignedBy',$request->user()->user_id)->orderBy('Name')->lazy(),
			
			    ];
			    
			    return response()->json($data,200);

    
		    case env('SUP_INTERNS') :

			    $data =[
		    
				    'MyInterns' => User::where('Supervisor',$request->user()->user_id)->orderBy('Name')->lazy(),       
			    ];
	    
			    return response()->json($data,200);
                 

		    default:

			    $data = [
	    
				    'user' => User::findorfail($request->user()->user_id)
		
			    ];
			
			    return response()->json($data,200);

		    
		    }

	    
	    }

	    
	    else
	    {
		    
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
        
        //  $Supervisors = User::where('Role','SUP')->get();
        //  $roles = Role::lazy();
        //  $positions = Position::lazy();

    //    $data = [
            
    //         'Supervisors' => $Supervisors,
    //         'Roles' => $roles,
    //         'Positions' => $positions
    //     ];

    //   //  return response()->json($data,200);

    //  return view('User.create',['position' => $positions,'roles'=>$roles,'Supervisors'=>$Supervisors]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddUserRequest $request)
    {
        /**
         * This function takes the request input and cleans it 
         * if the details are ok the function adds a user in to the database but,
         * only a user with admin privilages can add users directly
         */

        $validate = $request->validated();


        $user = new User;
        $user->Name = $validate['Name'];
        $user->Email = $validate['Email'];
        $user->PhoneNumber = $validate['PhoneNumber'];
	$user->Position = $validate['Position'];
	$user->Role = $validate['Role'];

        $user->Supervisor = $request->input('Supervisor',null);
        $user->Status = true;

        $user->password = bcrypt($request->input('password',$user->PhoneNumber));

        $user->Role == 'SUP' ? CreatedSupervisor::dispatch($user->Email) :$user->save();

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
        /* This function returns the details of the user by the id type-hinted on url
        *If the user is a supervisor it returns The user object,task assigned and interns
        *else it returns a user object {by id}
        *If theres no such user it returns 404
        */ 
        try{
		
		$user = User::findorfail($id);

		
		if($user->Role == "SUP"){

			$data =[
				'User' => $user,
				"Interns" => User::where('Supervisor',$user->user_id)->orderBy('created_at')->lazy(),
				"Tasks Assigned" => Task::where('AssignedBy',$user->user_id)->orderBy('created_at')->lazy()
			];
			
			return response()->json($data,200);
		}

		
		else if($user->Role == "INT")
		{
			$user->Supervisor == !null 
				? $user->Supervisor = $user->supervisor->Name 
				: $user->Supervisor = 'No Supervisor Assigned';

			$data = [

				'User' => $user,
				'Tasks' => DB::table('tasks')->select('Task','Rating','Status')
				            ->where('AssignedTo',$user->user_id)
					    ->orderBy('created_at','asc')
					    ->lazy(),

				'Overall' => DB::table('tasks')->select('Rating')
				   ->where('AssignedTo',$user->user_id)
				   ->where('Status','Completed')
				   ->avg('Rating'),
			];
			
			return response()->json($data,200);
		}
		else
		{
			return response()->json(['User' =>$user],200);
		}


	}
	catch(ModelNotFoundException)
	{
		return response()->json(['message' => 'No such user'],404);
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
	    /* This function return the details that may need to be updated,
	     * It is used by the api to GET data that may need to be updated
	     * It returns the User and Supervisors
        */
        try{
		$user = User::findorfail($id);
        
		if(!$user->Supervisor == null)
		{
			$user['Supervisor'] = $user->supervisor->Name;
		}
     
		$Supervisors = User::where('Role','SUP')->get();
        
		$data = [
			'User' => $user,
			'Supervisors' => $Supervisors
		];

		return response()->json($data,200);
	}
	
	catch(ModelNotFoundException){
		
		return response()->json(['message' => 'No such user'],404);
	}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        /* This function checks the user input in order to determine what to update on the user model
         *If the user input has a supervisor id and InternId theun it updates the intern detail(Supervisor)
         *and sends an email to the intern notifying him/her of the change
         
         
         *Else if the request does not contain the ids it checks the role of the user who made the request
         *if its an intern or supervisor who made the request it only updates the Name,Email,Phone number
        *If the role is admin it udates the whole user object
        */

	if ($request->has(['SupervisorID','InternID']))

	{
        try
        {
            $validate = $request->validate([
			
                'SupervisorID' => ['required'],
                'InternID' => ['required']	
            
            ]);
    
            
            
            User::where('user_id',$validate['InternID'])
    
                ->update(['Supervisor' => $validate['SupervisorID']]);
            
            
            AssignedSupervisor::dispatch(User::findorfail($validate['SupervisorID']),User::findorfail($validate['InternID']));
    
    
            return response()->json(['message'=>'Updated',200]);

        }
        catch(ModelNotFoundException)
        {
            return response()->json(['message'=>'No such user'],404);
        }
		
	}

 	else
	{
        try
        {
         
            $role = Auth::user()->Role;
		
            $user = User::findorfail($id);

		
            switch ($role)
            {
		
                case "ADM":
		
                    $validate = $request->validate([
                        'Name' => ['required'],
                        'Email' => ['required'],		
                        'Position' => ['required'],
                        'PhoneNumber' => ['required']		
                    ]);

                    $user->Name = $validate['Name'];
                    $user->Email = $validate['Email'];
                    $user->Position = $validate['Position'];
                    $user->PhoneNumber = $validate['PhoneNumber'];			
                    $user->save();

                    return response()->json(['Message' => 'ok'],200);
			

                case "INT" || "SUP":

                    $validate = $request->validate([

                        'Name' => ['required'],
                        'Email' => ['required'],
                        'PhoneNumber' => ['required']	
                    ]);
		
                $user->Name = $validate['Name'];
                $user->PhoneNumber = $validate['PhoneNumber'];
		$user->Email = $validate['Email'];
		$user->save();

                return response()->json(['Message' => 'updated'],200);

                default:			
                
                return response()->json(['Message' => 'Error'],404);

            }
        
        }
        catch(ModelNotFoundException)
        {
            return response()->json(['message'=>'No such user'],404);
        }

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
        /* Deletes a user based on User_ID*/
        try{
            $user = User::findorfail($id);
            $user->delete();

            return response()->json(['message' => 'User deleted'],200);
        }
        catch(ModelNotFoundException){
             return response()->json([
                'message' => 'No such user'
             ],404);
        }
    }

    public function Perfomance($id)
    {
     try{

        $data = [

            'User' => User::findorfail($id),
			'Tasks' => DB::table('tasks')->select('Task','Rating','Status')
				        ->where('AssignedTo',$id)
					->orderBy('created_at','asc')
					->lazy(),

			'Overall' => DB::table('tasks')->select('Rating')
				  ->where('AssignedTo',$id)
				  ->where('Status','Completed'
				  )->avg('Rating'),
        ];
        return response()->json($data,200);
     }
     catch(ModelNotFoundException)
     {
        return response()->json(['message' => 'Eror'],400);
     }

       
    }
}
