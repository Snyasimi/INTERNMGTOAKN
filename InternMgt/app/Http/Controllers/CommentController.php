<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentAndRemark;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
	    $comments = CommentAndRemark::where('user_id',Auth::user()->user_id)
                            ->where('task_id',$id)->lazy();

	    $data = ['comments' => $comments];
	    return response()->json($data,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
		    
		    'Intern_comment' => ['required'],
		    'Userid' => ['required']
	    ]);
	    
	    $id = $request->input('Taskid');
	    $Task = Task::findorfail($id);
	    $remark = new CommentAndRemark;
	    $remark->Comments = $validate['Intern_comment'];
	    $remark->user_id = $validate['Userid'];

	    $Task->comments()->save($remark);
 		
	    return response()->json(['message' => 'Comment Added'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	    $validate = $request->validate([
	    	'Comment' => ['required']
	    
	    ]);
	    
	    $comment = CommentAndRemark::findorfail($id);
	    $comment->Comments = $validate['Comment'];
	    $comment->save();

	    return response()->json(["message" => "updated"],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
