<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Position;
class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct(){
    //     $this->middleware('ability:Admin,Supervisor');
    //  }
    public function index()
    {
        $data =[
            'Positions'=> Position::lazy(),
            'message' => 'Displaying positions'
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
        return view('Position.create');
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
            "Position" => ['required','unique:App\Models\Position,positions']
        ]);

        Position::create($validate);

        return response()->json(['message' => 'created'],201);
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
            $position = Position::findorfail($id);
            $data =[
                'position' => $position,
                'message' => 'Displaying position'
            ];
            return response()->json($data,200);
        }
    
        catch(ModelNotFoundException){

            return response()->json(['message' => 'resource not found'],404);
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
        try{
           $position = Position::findorfail($id);
           $data = [
                'position' => $position,
                'message' => 'displaying position'
           ];

           return response()->json($data,200);
        }

        catch(ModelNotFoundException){
            return response()->json(['message' => 'Position not found'],404);
        }
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
            'Position' => ['required']
        ]);
        $Position = Position::findorfail($id);
        $Position->Position = $validate['Position'];
        $Position->save();

        return response()->json(['message' => 'Updated'],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
        $position = Position::findorfail($id);
        $position->delete();
        return response()->json(['message'=>'Position deleted'],200);
        }
        catch(ModelNotFoundException){
            return response()->json(['message' => 'Position not found'],404);
        }
    }
}
