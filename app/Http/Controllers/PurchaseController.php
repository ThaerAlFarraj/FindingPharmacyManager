<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\users;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Purchase::all();
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
        $purchase = new Purchase();
        $purchase->user_id = $request->input('user_id');
        $purchase->deliver_id = $request->input('deliver_id');
        $deliver = users::find($purchase->deliver_id);
        if($deliver->Type_User != 2){
            return response()->json(["The operation cannot be performed"]);
        }

        if(!$purchase->user_id  || !$purchase->deliver_id ){
            return response()->json(['message' => 'Invalid payload, all fields are required.','data'=> null],400);
        }
        $result = $purchase->save();
        if($result){
            return response()->json(["result"=>"Purchase Added"]);
        } else{
            return response()->json(["result"=>"Purchase Not Added"]);
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
        $result = Purchase::find($id);
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
        $purcahr = Purchase::find($id);
        $purcahr-> total_price = DB::table('purchases')
        ->join('med_purs', 'purchases.id', '=', 'med_purs.purchase_id')
        ->where('purchases.id', '=', $id)
        ->sum('med_purs.price');
        if(!$purcahr){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $status = $request->input('status');
        if($status){
            $purcahr->status = $status;
        }
        $total_price = $request->input('total_price');
        if($total_price){
            $purcahr->total_price = $total_price;
        }
        $result = $purcahr->save();
        if($result){
            return response()->json(["result"=>"Purchase Updated"]);
        } else{
            return response()->json(["result"=>"Purchase Not Updated"]);
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
        $purchase = Purchase::find($id);
        if(!$purchase){
            return response()->json(["result"=>"Purchase is Not founded!!"]);
        }
        $result = $purchase-> delete();
        if($result){
            return response()->json(["result"=>"Purchase is deleted"]);
        }else{
            return response()->json(["result"=>"Medicine not deleted"]);
        }
    }
}
