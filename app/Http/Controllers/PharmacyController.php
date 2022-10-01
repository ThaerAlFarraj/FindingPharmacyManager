<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pharmacy;
use Illuminate\Support\Str;
use App\Models\Medicine;
use App\Models\Med_In_Phare;
use Illuminate\Support\Facades\Auth;


class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = pharmacy::all();
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
        $pharmacy = new pharmacy();
        $pharmacy->name = $request->input('name');
        $pharmacy->workhours = $request->input('workhours');
        $pharmacy->phone = $request->input('phone');
        $pharmacy->address_longitude = $request->input('address_longitude');
        $pharmacy->address_latitude = $request->input('address_latitude');
        $shift_days =Str::lower($request->input('shift_days'));
        $pharmacy->shift_days = $shift_days;
            if(!$pharmacy->name || !$pharmacy->workhours || !$pharmacy->phone || !$pharmacy->address_longitude || !$pharmacy->address_latitude){
                return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
            }

        $result = $pharmacy->save();
        if($result){
            return response()->json(["result"=>"Pharmacy Added"]);
        } else{
            return response()->json(["result"=>"Pharmacy Not Added"]);
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
        $result = pharmacy::find($id);
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
        $pharmacy = pharmacy::find($id);
        if(!$pharmacy){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $name = $request->input('name');
        $workhours = $request->input('workhours');
        $phone = $request->input('phone');
        $location = $request->input('location');
        $shift_days =Str::lower($request->input('shift_days'));
        if($name){
            $pharmacy->name = $name;
        }
        if($workhours){
            $pharmacy->workhours = $workhours;
        }
        if($phone){
            $pharmacy->phone = $phone;
        }
        if($location){
            $pharmacy->location = $location;
        }
        if($shift_days){
            $pharmacy->shift_days = $shift_days;
        }

        $result = $pharmacy->save();
        if($result){
            return response()->json(["result"=>"Pharmacy Updated"]);
        } else{
            return response()->json(["result"=>"Pharmacy Not Updated"]);
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
        $pharmacy = pharmacy::find($id);
        if(!$pharmacy){
            return response()->json(["result"=>"Pharmacy is Not founded!!"]);
        }
        $result = $pharmacy-> delete();
        if($result){
            return response()->json(["result"=>"Pharmacy is deleted"]);
        }else{
            return response()->json(["result"=>"Pharmacy not deleted"]);
        }
    }

    public function alertExp($id){
        $curmonth = date('m');
        $nextmonth = date('m',strtotime('+1 month'));
        $premonth = date('m',strtotime('-1 month'));
        $datas = pharmacy::select(
            "medicines.id",
            "medicines.name",
            "medicines.exp_dare",
        )->join("med__in__phares","pharmacies.id","=","med__in__phares.pharmacies_id")
        ->where("pharmacies.id","=",$id)
        ->join("medicines", "medicines.id", "=", "med__in__phares.medicines_id")
        ->where("medicines.exp_dare","LIKE","%/{$curmonth}/%")
        ->orwhere("medicines.exp_dare","LIKE","%/{$nextmonth}/%")
        ->orwhere("medicines.exp_dare","LIKE","%/{$premonth}/%")
        ->get();
        if(Str::length($datas)>2)
        return response()->json($datas);
    else
        return response()->json(["There are no medicines that will expire soon!!"]);
    }
}
