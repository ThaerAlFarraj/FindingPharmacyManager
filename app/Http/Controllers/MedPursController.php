<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Med_In_Phare;
use App\Models\Purchase;
use App\Models\MedPur;
use App\Models\Medicine;
use Illuminate\Support\Arr;

class MedPursController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = MedPur::all();
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
        $pur_id = $request->input('pur_id');
        $med_id = $request->input('med_id');
        $phar_id = $request->input('phar_id');
        $quantity = $request->input('quantity');

        $unit = DB::table('medicines')->select('price')
        ->where('id','=',$med_id)
       ->first();
        $price = $quantity * Arr::first($unit);

        if(!$pur_id  || !$med_id|| !$phar_id || !$quantity || !$price){
            return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
        }
        $medPhar = DB::table('med__in__phares')->where('medicines_id','=',$med_id)
       ->where('pharmacies_id','=',$phar_id)->first();

        $purchase = Purchase::find($pur_id);
        if(!$medPhar){
            return response()->json(["result"=>"The medicine in pharmacy is not found!!"]);
        }
        if(!$purchase){
            return response()->json(["result"=>"The purchase is not found!!"]);
        }

        $MedPur = new MedPur();
        $MedPur->purchase_id = $purchase->id;
        $MedPur->medicine_id = $medPhar->medicines_id;
        $MedPur->pharmacy_id = $medPhar->pharmacies_id;
        $MedPur->quantity = $quantity;
        $MedPur->price = $price;
        $MedPur->created_at = date('Y-m-d H:i:s');
        $MedPur->updated_at = date('Y-m-d H:i:s');
        $result = $MedPur->save();

        return response()->json(["result"=>"Medicines has been added to Purchase"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id1,$id2,$id3)
    {
        $result = DB::table('med_purs')->where('purchase_id','=',$id1)
        ->where('medicine_id','=',$id2)
        ->where('pharmacy_id','=',$id3)
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
    public function update(Request $request, $id1,$id2,$id3)
    {
        $medPurs = DB::table('med_purs')->where('purchase_id','=',$id1)
        ->where('medicine_id','=',$id2)
        ->where('pharmacy_id','=',$id3)
        ->first();
        if(!$medPurs){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $quantity = $request->input('quantity');
        $price = $request->input('quanInPDon');
        if($quantity){
            $result = DB::update('update med_purs set quantity = ? where purchase_id = ? and medicine_id = ? and pharmacy_id = ?',[$quantity,$id1,$id2,$id3]);
        }
        if($price){
            $result = DB::update('update med_purs set price = ? where purchase_id = ? and medicine_id = ? and pharmacy_id = ?',[$price,$id1,$id2,$id3]);
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
    public function destroy($id1,$id2,$id3)
    {
        $medPurs = DB::table('med_purs')->where('purchase_id','=',$id1)
        ->where('medicine_id','=',$id2)
        ->where('pharmacy_id','=',$id3)
        ->first();
        if(!$medPurs){
            return response()->json(["result"=>"Medicine is Not founded!!"]);
        }
        $result = DB::delete('delete from med_purs where purchase_id = ? and medicine_id = ? and pharmacy_id = ?',[$id1,$id2,$id3]);
        if($result){
            return response()->json(["result"=>"Medicine In Purchase is deleted"]);
        }else{
            return response()->json(["result"=>"Medicine In Purchase not deleted"]);
        }
    }
}
