<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Medicine::all();
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
        $medicine = new Medicine();
        $medicine->name = $request->input('name');
        $medicine->descrip = $request->input('descrip');
        $medicine->manufacture = $request->input('manufacture');
        $medicine->exp_dare = $request->input('exp_date');
        $medicine->price = $request->input('price');
        if(!$medicine->name || !$medicine->descrip || !$medicine->manufacture || !$medicine->exp_dare || !$medicine->price){
            return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
        }
        $result = $medicine->save();
        if($result){
            return response()->json(["result"=>"Medicine Added"]);
        } else{
            return response()->json(["result"=>"Medicine Not Added"]);
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
        $result = Medicine::find($id);
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
        $medicine = Medicine::find($id);
        if(!$medicine){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $name = $request->input('name');
        $descrip = $request->input('descrip');
        $manufacture = $request->input('manufacture');
        $exp_dare = $request->input('exp_date');
        $price = $request->input('price');

        if($name){
            $medicine->name = $name;
        }
        if($descrip){
            $medicine->descrip = $descrip;
        }
        if($manufacture){
            $medicine->manufacture = $manufacture;
        }
        if($exp_dare){
            $medicine->exp_dare = $exp_dare;
        }
        if($price){
            $medicine->price = $price;
        }

        $result = $medicine->save();
        if($result){
            return response()->json(["result"=>"Medicine Updated"]);
        } else{
            return response()->json(["result"=>"Medicine Not Updated"]);
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
        $medicine = Medicine::find($id);
        if(!$medicine){
            return response()->json(["result"=>"Medicine is Not founded!!"]);
        }
        $result = $medicine-> delete();
        if($result){
            return response()->json(["result"=>"Medicine is deleted"]);
        }else{
            return response()->json(["result"=>"Medicine not deleted"]);
        }
    }
}
