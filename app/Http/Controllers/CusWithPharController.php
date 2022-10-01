<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\Pharmacy;
use App\Models\cusWithPhar;
use Illuminate\Support\Facades\DB;

class CusWithPharController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = cusWithPhar::all();
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
        $user_id = $request->input('user_id');
        $pharmacy_id = $request->input('pharmacy_id');
        $blacklist = $request->input('blacklist');
        if(!$user_id  || !$pharmacy_id){
            return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
        }
        $user = users::find($user_id);
        $pharmacy = pharmacy::find($pharmacy_id);
        if(!$user){
            return response()->json(["result"=>"The user is not found!!"]);
        }
        if(!$pharmacy ){
            return response()->json(["result"=>"The pharmacy is not found!!"]);
        }
        $pharIds = [$pharmacy->id];
        $user->pharmacy()->attach($pharIds,['blacklist' => $blacklist
        ,'created_at'=>date('Y-m-d H:i:s')
        ,'updated_at'=>date('Y-m-d H:i:s')]);

        return response()->json(["result"=>"User has been registered to pharmacy"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id1,$id2)
    {
        $result = DB::table('cus_with_phar')->where('user_id','=',$id1)
        ->where('pharmacy_id','=',$id2)
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
        $cusWithPhar = DB::table('cus_with_phar')->where('user_id','=',$id1)
        ->where('pharmacy_id','=',$id2)
        ->first();

        if(!$cusWithPhar){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $result = false;
        $blacklist = $request->input('blacklist');
        if($blacklist != $cusWithPhar->blacklist){
            $result = DB::update('update cus_with_phar set blacklist = ? where user_id = ? and pharmacy_id = ?',[$blacklist,$id1,$id2]);
        }

        if($result)
            return response()->json(["result"=>"Blacklist Updated"]);
        else
            return response()->json(["result"=>"Blacklist Not Updated"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id1,$id2)
    {
        $cusWithPhar = DB::table('cus_with_phar')->where('user_id','=',$id1)
        ->where('pharmacy_id','=',$id2)
        ->first();
        if(!$cusWithPhar){
            return response()->json(["result"=>"User in Pharmacy is Not founded!!"]);
        }
        $result = DB::delete('delete from cus_with_phar where user_id = ? and pharmacy_id = ?',[$id1,$id2]);
        if($result){
            return response()->json(["result"=>"User In Pharmacy is deleted"]);
        }else{
            return response()->json(["result"=>"User In Pharmacy not deleted"]);
        }
    }
}
