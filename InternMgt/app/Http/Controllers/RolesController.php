<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('ability:doanything');
     }
    public function index()
    {
        $roles = Role::all();

        $data =[
            'Roles' => $roles
        ]; 
        return response()->json($data,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('Role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $validate = request()->validate([
            'Role' => ['required','unique:roles']
       ]);

       Role::create($validate);
                      
       return response()->json(['message'=>'Created'],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
        $data = [
            'role' => Role::findorfail($id),
            'message' => 'Displaying role'
        ];

        return response()->json($data,200);
    }
    catch(ModelNotFoundException){
        
            $data = [
                'message' => 'Displaying role'
            ];
    
            return response()->json(['message' => 'Role Not found'],404);

    }

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
        $Role = Role::findorfail($id);
        $Role->Role = $request->input('Role');
        $Role->save();

        return response()->json(['message' => 'Updated'],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findorfail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted'],410);
    }
}
