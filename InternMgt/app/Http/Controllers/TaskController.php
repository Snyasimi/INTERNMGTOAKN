<?php

namespace App\Http\Controllers;

use App\Models\CommentAndRemark;
use App\Models\Task;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get the tasks assigned to the authenticated user
        $user =Auth::user();
        if($user->Role == "Sup"){
            //User::where('user_id','01gs593xf14wxkn035a50bffe3')->first();Auth::user();
            $tasks = Task::where('AssignedBy',$user->user_id)->get();
             $data = [
                'Tasks' => $tasks
             ];
            return response()->json($data,200);

            }
            else{
                   //User::where('user_id','01gs593xf14wxkn035a50bffe3')->first();Auth::user();
                    $tasks = Task::where('AssignedBy',$user->user_id)->get();
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
        $Interns = User::whereNotNull('Supervisor')->get();
        $data = [
            "Interns" => $Interns,
            "message" => "Dispalying interns"
        ];
        return response()->json($data,200);
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
	    //Authenticated user obviously an admin tho check the roles
        //$Supervisor = User::findorfail('01gs56v8d1jhnkgh0dpbcdcty0');
        $Supervisor = Auth::user();
	    $Task = new Task;
	    $Task->AssignedTo = $request->input('AssignedTo');
	    $Task->Task = $request->input('Task');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //RETURN ONLY THE BODY AND THE ASSIGNED TO
        $data = ['task' => Task::findorfail($id),
        'message' => 'view task'
    ];
        return response()->json($data, 200);
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
        $task = Task::findorfail($id);
        //When the request came from a supervisor it doesnt have the status field 
        //it is assumed to come from intern so the only thing being updated is the status 
            $task->Task = $request->input('Task');
            $task->Deadline = $request->input('Deadline');
            $task->save();
            $data = [
                'message' => 'Task Changed'
            ];
            return response()->json($data, 200);

        }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
