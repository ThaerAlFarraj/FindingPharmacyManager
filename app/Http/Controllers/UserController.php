<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\pharmacy;
use App\Models\Medicine;
use App\Models\Med_In_Phare;
use App\Models\Purchase;
use Exception;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = users::all();
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
        $user = new users();
        $user->Fname = $request->input('Fname');
        $user->Lname = $request->input('Lname');
        $user->Phone = $request->input('Phone');
        $user->Country = $request->input('Country');
        $user->City = $request->input('City');
        $user->Email = $request->input('Email');
        $user->Password = $request->input('Password');
        $user->Type_User = $request->input('Type_User');
        $result = $user->save();
        if($result){
            return response()->json(["result"=>"user Added"]);
        } else{
            return response()->json(["result"=>"user Not Added"]);
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
        $result = users::find($id);
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
        $user = users::find($id);
        if(!$user){
            return response()->json(["result"=>"Not founded!!"]);
        }
        $phone = $request->input('Phone');
        $country = $request->input('Country');
        $city = $request->input('City');
        $email = $request->input('Email');
        $password = $request->input('Password');
        if($phone){
            $user->Phone = $phone;
        }
        if($country){
            $user->Country = $country;
        }
        if($city){
            $user->City = $city;
        }
        if($email){
            $user->Email = $email;
        }
        if($phone){
            $user->Password = $password;
        }
        $result = $user->save();
        if($result){
            return response()->json(["result"=>"User Updated"]);
        } else{
            return response()->json(["result"=>"User Not Updated"]);
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
        $user = users::find($id);
        if(!$user){
            return response()->json(["result"=>"User is Not founded!!"]);
        }
        $result = $user-> delete();
        if($result){
            return response()->json(["result"=>"User is deleted"]);
        }else{
            return response()->json(["result"=>"User not deleted"]);
        }
    }

    public function confirmation($id)
    {
        $result = Purchase::where('status',false)
        ->where("id","=",$id)
        ->update(['status' => true]);
        if($result)
            return response()->json(["Done"]);
        else
             return response()->json(["Not done"]);
    }

    public function acceptableDelivery($id){
        $result = Purchase::where('acceptable',false)
        ->where("id","=",$id)
        ->update(['acceptable' => true]);
        if($result)
            return response()->json(["Done"]);
        else
             return response()->json(["Not done"]);
    }

    public function refusedDelivery($id){
        $pur = Purchase::find($id);
        if(!$pur){
            return response()->json(["result"=>"purchase is Not founded!!"]);
        }
        $result = $pur-> delete();
        if($result){
            return response()->json(["result"=>"Delivery is refused"]);
        }else{
            return response()->json(["result"=>"Delivery not refused"]);
        }
    }
    public function search(Request $request){
        try{


            $medicines=Medicine::where('name',$request->name)->get();

            if(count($medicines)==0){
                return response()->json('Not found medicine',404);
            }

        //    return $medicines;
            foreach($medicines as $medicine){
                $medicineInPharmacy=Med_In_Phare::where('medicines_id',$medicine->id)->where('quan_In_Phar','>',0)->get();

            }
            // return $medicineInPharmacy;
            $pharmacies_arr=[];
            foreach($medicineInPharmacy as $item){
                $pharmacies=pharmacy::where('id',$item->pharmacies_id)->get();
               if(count($pharmacies)!=0){
                foreach($pharmacies as $val)
                array_push($pharmacies_arr,$val);
               }
            }
            $pharmaciesDistance=[];
            $latitudeFrom=$request->input('latitude');
            $longitudeFrom= $request->input('longitude');
            foreach ($pharmacies_arr as $pharmacy){
                $theta = $longitudeFrom - $pharmacy->address_longitude;
                $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($pharmacy->address_latitude)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($pharmacy->address_latitude)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;

                $distance  = ($miles * 1.609344).' m';



                $pharmacyInfo = (object)[
                    "id" => $pharmacy->id,
                      'name'=> $pharmacy->name,
                      'workhours'=> $pharmacy->workhours,
                      'phone'=> $pharmacy->phone,
                      'distance'=>$distance,
                ];

                array_push($pharmaciesDistance, $pharmacyInfo);
            }
            $_pharmacy = collect($pharmaciesDistance);
            return response()->json($_pharmacy->sortBy('distance'),200);


        }catch(Exception $e){
            return $e;
        }
    }



    public function get_near_pharmacy(Request $request){
        try{
            $pharmacies=pharmacy::all();
            $pharmaciesDistance=[];
            $latitudeFrom=$request->input('latitude');
            $longitudeFrom= $request->input('longitude');
            foreach ($pharmacies as $pharmacy){
                $theta = $longitudeFrom - $pharmacy->address_longitude;
                $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($pharmacy->address_latitude)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($pharmacy->address_latitude)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;

                $distance  = ($miles * 1.609344).' m';



                $pharmacyInfo = (object)[
                    "id" => $pharmacy->id,
                      'name'=> $pharmacy->name,
                      'workhours'=> $pharmacy->workhours,
                      'phone'=> $pharmacy->phone,
                      'distance'=>$distance,
                ];
                $folder = substr($distance, 0, strpos($distance, '.'));
                if($folder<=1){
                    array_push($pharmaciesDistance, $pharmacyInfo);
                }

            }
            $_pharmacy = collect($pharmaciesDistance);
            return response()->json($_pharmacy->sortBy('distance'),200);


        }catch(Exception $e){
            return response()->json($e);
        }
    }

}
