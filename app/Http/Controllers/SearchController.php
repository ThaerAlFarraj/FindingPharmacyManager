<?php

namespace App\Http\Controllers;
use App\Models\users;
use App\Models\Region;
use App\Models\pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Carbon\Carbon;
class SearchController extends Controller
{

// public function autocomplete($paramiter){

//     $datas = Region::select("area")
//     ->where("area","LIKE","%{$paramiter}%")->get();
// return response()->json([
//     'messege'=> 'Get Area  Succesfuly ',
//     'Areas' => $datas,

// ]);
// }

public function getPharShiftWork(){
    $curday = Str::lower(Carbon::today()->format('l'));
    $datas = pharmacy::select("name")
    ->where("shift_days","LIKE","%{$curday}%")->get();
    if(Str::length($datas)>2)
        return response()->json($datas);
    else
        return response()->json(["No pharmacies on duty today"]);
}

public function getPharname($name){
    $datas = pharmacy::select("*")
    ->where("name","LIKE","%{$name}%")->get();

    if(Str::length($datas)>2)
        return response()->json($datas);
    else
        return response()->json(["No pharmacies"]);
}

}
