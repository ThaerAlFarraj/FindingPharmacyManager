<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Complaint::all();
        return $result;
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
        $complaint = new Complaint();
        $complaint->user_id = $request->input('user_id');
        $complaint->phar_id = $request->input('phar_id');
        $complaint->Content = $request->input('content');
        if(!$complaint->user_id  || !$complaint->phar_id || !$complaint->Content){
            return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
        }
        $result = $complaint->save();
        if($result){
            return response()->json(["result"=>"Complaint Added"]);
        } else{
            return response()->json(["result"=>"Complaint Not Added"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Complaint::find($id);
        if(!$result){
            return response()->json(["result"=>"Not founded!!"]);
        }
        return $result;
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
        $complaint = Complaint::find($id);
        if(!$complaint){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $content = $request->input('content');
        if($content){
            $complaint->Content = $content;
        }
        $result = $complaint->save();
        if($result){
            return response()->json(["result"=>"Complaint Updated"]);
        } else{
            return response()->json(["result"=>"Complaint Not Updated"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $complaint = Complaint::find($id);
        if(!$complaint){
            return response()->json(["result"=>"Complaint is Not founded!!"]);
        }
        $result = $complaint-> delete();
        if($result){
            return response()->json(["result"=>"Complaint is deleted"]);
        }else{
            return response()->json(["result"=>"Medicine not deleted"]);
        }
    }
}
