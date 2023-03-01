<?php

namespace App\Http\Controllers;

use App\Models\CommentAndRemark;
use App\Models\Task;
use App\Models\User;

use App\Events\TaskAssigned;

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

            $tasks = Task::where('AssignedBy',$user->user_id)->get();
             $data = [
                'Tasks' => $tasks
             ];
            return response()->json($data,200);

            }
            else{

                    $tasks = Task::where('AssignedTo',$user->user_id)->get();
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
            $int = User::where('Role','INT')->get();
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
            'Deadline' => ['required']
	]);

	$intern = User::findorfail($validate['AssignedTo']); 

	    //Authenticated user obviously an admin tho check the roles
        //$Supervisor = User::findorfail('01gsm95rx08arjmh1p0erp49fv');
        $Supervisor = User::findorfail("01gss613z3ew9shvvy6n5emb30");//Auth::user();
	    $Task = new Task;
	    $Task->AssignedTo = $validate['AssignedTo'];
	    $Task->Task = $request->input('Task');
        $Task->Description = $request->input('TaskDescription');
	    $Task->Deadline = $request->date('Deadline');
        //ADD DESCRIPTION
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
         
        //$comments = $task->comments()->first();
        //CHANGE THE FILTER CONDITION TO BE THE AUTHENTICATED USER
        $comments = CommentAndRemark::where('user_id','01gs58fr25xhg2a7j81wtedd85')->
                                    where('task_id',$task->id)->get();
        $data = [
            'task' => $task,
	    'Supervisor' => $task->Assignedby->Name,
	    'remarks' => CommentAndRemark::where('user_id',$task->Assignedby->user_id)->where('task_id',$task->id)->first(),
	    'comment' => CommentAndRemark::where('user_id',$task->Assignedto->user_id)->where('task_id',$task->id)->first() 
        ];
        return response()->json($data,200);
    }
    catch(ModelNotFoundException){
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
            $data = ['task' => Task::findorfail($id),
                'message' => 'view task'
            ];
            return response()->json($data, 200);
        }
        catch(ModelNotFoundException){
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

        
        catch(ModelNotFoundException){

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
        try{

            $task = Task::findorfail($id);
            $task->delete();
            return response()->json(["message" => 'Task deleted'],410);

        }
        catch(ModelNotFoundException){
            return response()->json(["message" => 'Task does not exist'],404);
        }
    }
}
