<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('ability:Admin,Supervisor');
     }
    public function index()
    {
        $Departments = Department::all();
         $data = [
            "Departments" => $Departments,
            'message' => 'All departments'
         ];
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Dept.create');
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
	    	'DeptName' => ['required'],
            	
	    ]);
        $dept = new Department;
        $dept->DepartmentName = $request->input('DeptName');
        $dept->save();

         //JSON DATA TO BE RETURNED
        $data = [
            'message' => 'Department ::'." ".$dept->DepartmentName." ". 'created'
        ];

        return response()->json($data, 201);;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{

            $department = Department::findorfail($id);
            $data = [
                'Department' => $department
            ];

            return response()->json($data, 200);
        }
        catch(ModelNotFoundException){

            return response()->json(['message' => 'Department Not found'],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $department = Department::findorfail($id);
            $data = [
                 'Department' => $department,
                 'message' => 'displaying depatment'
            ];
 
            return response()->json($data,200);
         }
 
         catch(ModelNotFoundException){
             return response()->json(['message' => 'department not found'],404);
         }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $department = Department::findorfail($id);
            $department->delete();
            return response()->json(['message'=>'Department deleted'],410);
            }
            catch(ModelNotFoundException){
                return response()->json(['message' => 'Department not found'],404);
            }
    
    }
}
