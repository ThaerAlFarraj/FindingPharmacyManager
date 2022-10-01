<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\Medicine;
use App\Models\MedList;

class MedListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = MedList::all();
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
        $med_id = $request->input('med_id');
        $user_id = $request->input('user_id');
        $dosing_time = $request->input('dosing_time');
        if(!$med_id  || !$user_id|| !$dosing_time ){
            return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
        }
        $medicine = Medicine::find($med_id);
        $user = users::find($user_id);
        if(!$medicine){
            return response()->json(["result"=>"The medicine is not found!!"]);
        }
        if(!$user ){
            return response()->json(["result"=>"The user is not found!!"]);
        }
        $userIds = [$user->id];
        $medicine->user()->attach($userIds,['dosing_Times' => $dosing_time
        ,'created_at'=>date('Y-m-d H:i:s')
        ,'updated_at'=>date('Y-m-d H:i:s')]);

        return response()->json(["result"=>"Medicine has been added to list of user"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id1,$id2)
    {
        $result = DB::table('med_lists')->where('user_id','=',$id1)
        ->where('medicine_id','=',$id2)
        ->first();
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
    public function update(Request $request, $id1,$id2)
    {
        $medList = DB::table('med_lists')->where('user_id','=',$id1)
        ->where('medicine_id','=',$id2)
        ->first();
        if(!$medList){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $dosing_Times = $request->input('dosing_Times');
        if($dosing_Times){
            $result = DB::update('update med_lists set dosing_Times = ? where user_id = ? and medicine_id = ?',[$dosing_Times,$id1,$id2]);
        }
        if($result)
            return response()->json(["result"=>"Medicine In User list Updated"]);
        else
            return response()->json(["result"=>"Medicine In User list Not Updated"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $medList = DB::table('med_lists')->where('user_id','=',$id1)
        ->where('medicine_id','=',$id2)
        ->first();
        if(!$medList){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $result = DB::delete('delete from med_lists where user_id = ? and medicine_id = ?',[$id1,$id2]);
        if($result){
            return response()->json(["result"=>"Medicine In User list is deleted"]);
        }else{
            return response()->json(["result"=>"Medicine In User list not deleted"]);
        }
    }
}
