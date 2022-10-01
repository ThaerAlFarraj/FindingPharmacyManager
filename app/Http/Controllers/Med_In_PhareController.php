<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Med_In_Phare;
use App\Models\pharmacy;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
class Med_In_PhareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Med_In_Phare::all();
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
        $phar_id = $request->input('phar_id');
        $quan_In_Phar = $request->input('quanInPhar');
        $quan_In_Don = $request->input('quanInPDon');
        if(!$med_id  || !$phar_id|| !$quan_In_Phar ){
            return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
        }
        $medicine = Medicine::find($med_id);
        $pharmacy = pharmacy::find($phar_id);
        if(!$medicine){
            return response()->json(["result"=>"The medicine is not found!!"]);
        }
        if(!$pharmacy ){
            return response()->json(["result"=>"The pharmacy is not found!!"]);
        }
        $pharIds = [$pharmacy->id];
        $medicine->pharmacy()->attach($pharIds,['quan_In_Phar' => $quan_In_Phar,'quan_In_Don'=> $quan_In_Don
        ,'created_at'=>date('Y-m-d H:i:s')
        ,'updated_at'=>date('Y-m-d H:i:s')]);

        return response()->json(["result"=>"Medicine has been added to pharmacy"]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id1,$id2)
    {
        $result = DB::table('med__in__phares')->where('medicines_id','=',$id1)
        ->where('pharmacies_id','=',$id2)
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
        $medInPhar = DB::table('med__in__phares')->where('medicines_id','=',$id1)
        ->where('pharmacies_id','=',$id2)
        ->first();
        if(!$medInPhar){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $quanInPhar = $request->input('quanInPhar');
        $quanInPDon = $request->input('quanInPDon');
        if($quanInPhar){
            $medInPhar->quan_In_Phar = $quanInPhar;
            $result = DB::update('update med__in__phares set quan_In_Phar = ? where medicines_id = ? and pharmacies_id = ?',[$quanInPhar,$id1,$id2]);
        }
        if($quanInPDon){
            $medInPhar->quan_In_Don = $quanInPDon;
            $result = DB::update('update med__in__phares set quan_In_Don = ? where medicines_id = ? and pharmacies_id = ?',[$quanInPhar,$id1,$id2]);
        }
        if($result)
            return response()->json(["result"=>"Medicine In Pharmacy Updated"]);
        else
            return response()->json(["result"=>"Medicine In Pharmacy Not Updated"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id1,$id2)
    {
        $medInPhar = DB::table('med__in__phares')->where('medicines_id','=',$id1)
        ->where('pharmacies_id','=',$id2)
        ->first();
        if(!$medInPhar){
            return response()->json(["result"=>"Medicine is Not founded!!"]);
        }
        $result = DB::delete('delete from med__in__phares where medicines_id = ? and pharmacies_id = ?',[$id1,$id2]);
        if($result){
            return response()->json(["result"=>"Medicine In Pharmacy is deleted"]);
        }else{
            return response()->json(["result"=>"Medicine In Pharmacy not deleted"]);
        }
    }
}
