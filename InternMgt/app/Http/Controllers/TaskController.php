<?php

namespace App\Http\Controllers;

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
        $user =Auth::user(); //User::where('user_id','01gs593xf14wxkn035a50bffe3')->first();Auth::user();
        $tasks = Task::where('AssignedTo',$user->user_id)->first();
        return response()->json([
            'tasks' => $tasks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Interns = User::whereNotNull('Supervisor')->get();

        return response()->json(['Interns' => $Interns],200);
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
        $Supervisor = User::findorfail('01gs56v8d1jhnkgh0dpbcdcty0');
	    $Task = new Task;
	    $Task->AssignedTo = $request->input('AssignedTo');
	    $Task->Task = $request->input('Task');
	    $Task->Deadline = $request->date('Deadline');
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
    public function show(Task $task)
    {
        return response()->json([
            'task' => $task
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //RETURN ONLY THE BODY AND THE ASSIGNED TO
        $data = ['task' => $task,
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
    public function update(Request $request, Task $task)
    {
        $task = Task::findorfail($task);
        //When the request came from a supervisor it doesnt have the status field 
        //it is assumed to come from intern so the only thing being updated is the status 
        if($request->has('Status')){
             $task->Status = $request->input('Status');
            $task->save();
            $data = [
                'message' => 'Updated successfuly'
            ];
            return response()->json($data, 200);
        }else{

            $task->AssignedTo = $request->input('AssignedTo');
            $task->Task = $request->input("Task");
            $task->save();
            $data = [
                'message' => 'Task Assigned'
            ];
            return response()->json($data, 200);

        }

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
