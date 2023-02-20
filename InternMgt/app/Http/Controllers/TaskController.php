<?php

namespace App\Http\Controllers;

use App\Models\CommentAndRemark;
use App\Models\Task;
use App\Models\User;

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

	    //Authenticated user obviously an admin tho check the roles
        //$Supervisor = User::findorfail('01gsm95rx08arjmh1p0erp49fv');
        $Supervisor = Auth::user();
	    $Task = new Task;
	    $Task->AssignedTo = $validate['AssignedTo'];
	    $Task->Task = $request->input('Task');
        $Task->Description = $request->input('TaskDescription');
	    $Task->Deadline = $request->date('Deadline');
        //ADD DESCRIPTION
	    $Task->Status = false;
	    $Supervisor->Assign()->save($Task);

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
            'comments' => $comments,

            //'madeby'=> //$comments->MadeBy()->get()
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
            $user_id = $request->input('User_id');
            $user = User::findorfail($user_id);
            $task = Task::findorfail($id);
             //If the supervisor updated the task only things that will be updated is deadline and task

            if($user->Role == 2){
                $task->Task = $request->input('Task');
                $task->Deadline = $request->input('Deadline');
                $task->save();
                $data = [
                    'message' => 'Task Changed'
                ];
                return response()->json($data, 200);
            }
            else{

                $task->Status = $request->input('Status');
                $task->save();
                return response()->json(['message' => 'Task done'], 200);

            }
            //When the request came from a supervisor it doesnt have the status field
            //it is assumed to come from intern so the only thing being updated is the status

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
