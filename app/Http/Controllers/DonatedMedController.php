<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\donatedMedicine;
use App\Models\Medicine;
use App\Models\usser;
use App\Models\pharmacy;
use Illuminate\Support\Facades\DB;

class DonatedMedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = donatedMedicine::all();
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
        $medicine_id = $request->input('medicine_id');
        $status = $request->input('status');
        $quantity = $request->input('quantity');
        if(!$user_id  || !$pharmacy_id|| !$medicine_id || !$status || !$quantity ){
            return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
        }
        $cusPhar = DB::table('cus_with_phar')->where('user_id','=',$user_id)
        ->where('pharmacy_id','=',$pharmacy_id)
        ->first();
        $medPhar = DB::table('med__in__phares')->where('medicines_id','=',$medicine_id)
        ->where('pharmacies_id','=',$pharmacy_id)
        ->first();
        if(!$cusPhar){
            return response()->json(["result"=>"The User in pharmacy is not found!!"]);
        }
        if(!$medPhar){
            return response()->json(["result"=>"The Medicine in pharmacy is not found!!"]);
        }
        $donMed = new donatedMedicine();
        $donMed->user_id = $user_id;
        $donMed->pharmacy_id = $pharmacy_id;
        $donMed->medicine_id = $medicine_id;
        $donMed->status = $status;
        $donMed->quantity = $quantity;
        $donMed->created_at = date('Y-m-d H:i:s');
        $donMed->updated_at = date('Y-m-d H:i:s');
        $result = $donMed->save();

        return response()->json(["result"=>"Medicine has been added to list of user"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = donatedMedicine::find($id);
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
        $donMed = donatedMedicine::find($id);
        if(!$donMed){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $quantity = $request->input('quantity');
        $status = $request->input('status');

        if($quantity){
            $donMed->quantity = $quantity;
        }
        if($status){
            $donMed->status = $status;
        }

        $result = $donMed->save();
        if($result){
            return response()->json(["result"=>"Donated-Medicine Updated"]);
        } else{
            return response()->json(["result"=>"Donated-Medicine Not Updated"]);
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
        $donMed = donatedMedicine::find($id);
        if(!$donMed){
            return response()->json(["result"=>"Donated-Medicine is Not founded!!"]);
        }
        // if($donMed->quantity == 1)
        $result = $donMed-> delete();
        // else
        // $donMed->quantity = $donMed->quantity-1;
        if($result){
            return response()->json(["result"=>"Donated-Medicine is deleted"]);
        }else{
            return response()->json(["result"=>"Donated-Medicine not deleted but "]);
        }
    }
}
