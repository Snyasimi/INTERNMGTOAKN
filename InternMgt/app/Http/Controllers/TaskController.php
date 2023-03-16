<?php

namespace App\Http\Controllers;

use App\Models\CommentAndRemark;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Arr;
use App\Events\TaskAssigned;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function __construct(){
    //     $this->middleware('ability:Admin,Supervisor')->except(['show','update','edit']);
    //  }

    public function index()
    {
        //Get the tasks assigned to the authenticated user
        $user =Auth::user();

        if($user->Role == "SUP"){

		$tasks = Task::where('AssignedBy',$user->user_id)->lazy();
		
		$arrTask = $tasks->map(function($Task)
		{
			$Task->assignedto = $Task->Assignedto->Name;
			unset($Task['AssignedTo']);
			return $Task;
		});

             $data = [
                'Tasks' => $arrTask
             ];
            return response()->json($arrTask,200);

            }
            else{

                    $tasks = Task::where('AssignedTo',$user->user_id)->lazy();
                     $data = [
                        'Tasks' => $tasks

                     ];
                    return response()->json($data,200);

               }
     }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $Interns = User::whereNotNull('Supervisor')->get();
        // $data = [
        //     "Interns" => $Interns,
        //     "message" => "Dispalying interns"
        // ];
        // return response()->json($data,200);
            $int = User::where('Role','INT')->lazy();
        return view('Task.create',['Interns'=> $int]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //TODO REMOVE THE ID FROM THE BINDING USE AUTH USER ONLY
    public function store(Request $request)
    {

        $validate = $request->validate([
	    	'AssignedTo' => ['required'],
            'Task' => ['required'],
            'TaskDescription' => ['required'],
            'Deadline' => ['required','date']
	]);

	
	$intern = User::findorfail($validate['AssignedTo']); 
        $Supervisor = Auth::user();
	
	$Task = new Task;
	$Task->AssignedTo = $validate['AssignedTo'];
    	$Task->Task = $request->input('Task');
        $Task->Description = $request->input('TaskDescription');
	$Task->Deadline = Carbon::parse($request->date('Deadline'))->format('d/m/y');

	$Task->Status = "Assigned";
	
       $Supervisor->Assign()->save($Task);
	
        
	TaskAssigned::dispatch($intern->Email,$validate['TaskDescription']);


	$data = [
    		"message" => 'Task assigned'
    	];
    
	return response()->json($data, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
		$task= Task::findOrfail($id);
         
		$Comments =  CommentAndRemark::where('task_id',$task->id)->orderBy('created_at')->lazy();
		$CleanedComments =  $Comments->map(function($cmts)
		{
			$cmts->madeby = $cmts->MadeBy->Name;
			unset($cmts['made_by']);
			return $cmts;
			
		});	
	    
		$data = [
			
			'task' => $task,
			'Supervisor' => $task->Assignedby->Name,
			'remarks' => ['lalala'],
			'comment' => $CleanedComments
		];
		
		return response()->json($data,200);
	
	}
	
	catch(ModelNotFoundException)
	{	
		return response()->json(['message' => 'Task Not found'],404);
	
	}
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //RETURN ONLY THE BODY AND THE ASSIGNED TO
        try{
		
		$data = [
			'task' => Task::findorfail($id),	
			'message' => 'view task'
		];
		
		return response()->json($data, 200);
	
	}
	
	catch(ModelNotFoundException)
	{
	    	return response()->json(['message' => 'task not found'],404);
        }
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	   
	    $validate = $request->validate([
		    'Task' => ['required'],
		    'Description' => ['required'],
		    'Deadline' => ['required']
	    
	    ]);
	    
	    $Task = Task::findorfail($id);
	    $Task->Task = $validate['Task'];
	    $Task->Description = $validate['Description'];
	    $Task->Deadline = Carbon::parse($validate['Deadline'])->format('d/m/y');
	    $Task->save();
	    
	    return response()->json(["msg" => "ok"],200);

    }

    public function Complete(Request $request ,$id)
    {

	    try{

		    $task = Task::findorfail($id);
		    
		    $validate = $request->validate(['Status' => ['reqiured']]);
    
		   
		    if($task->Status == 'Assigned')
		    {
			    $task->Status = "Completed";
			    $task->save();
			    
			    return response()->json(["message" => "Updated"],200);
		    }
		    
		    else
		    {
			    $task->Status = "Assigned";
			    
			    $task->save();
    
			    return response()->json(["message" => "Updated"],200);
		    }
                 
	    }
    
            
	    catch(ModelNotFoundException)
	    {

		    return response()->json(['message' => 'No such task'],404);
	    
	    }

    
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    try
	    {

		    $task = Task::findorfail($id);
		    $task->delete();
		    
		    return response()->json(["message" => 'Task deleted'],410);

	    
	    }
	    
	    catch(ModelNotFoundException)
	    {
		    
		    return response()->json(["message" => 'Task does not exist'],404);
	    
	    }
    }

}
