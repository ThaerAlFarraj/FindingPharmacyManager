<?php

namespace App\Http\Controllers;
use App\models\Admin;
Use App\Models\pharmacy;
Use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
   

class AdminAddedController extends Controller
{
    protected $db_mysql;
    public function __construct()
    {
        $this ->db_mysql= config('database.connections.mysql.database');

    }
    public function addCustomer( Request $request)
    {
        $validator =Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|string|email|unique:_drivers',
            'password'=>'required|min:8',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),400);
        }
        
		
		
        $user=users::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->password),
            ]
        ));
        $credentials=$request->only(['email','password']);
        

        $token=Auth()->guard('user-api')->login($user);
                return response()->json([
            'message'=>'user added successfully',

        ],201);
    }
   
        public function addPharmacy( Request $request)
    {
        $validator =Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|string|email|unique:pharmacies',
            'phone'=>'required|string|unique:pharmacies',
            'password'=>'required|min:8',
            'image'=>'nullable',
            'address_longitude'=>'required',
            'address_latitude'=>'required',
            'shift_days'=>'required',
        'workhours'=>'required'
           // 'admin_id'=>'nullable',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),400);
        }
        
        $path="null";
        if($request->image){
            $photo=$request->image;

        $photoname=time().'.png';
        Storage::disk('product')->put($photoname,base64_decode($photo));
        $path="storage/product/$photoname";
        }
		
		
        $user=Pharmacy::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->password),
            'image'=>$path
            ]
        ));
        $credentials=$request->only(['email','password']);
        $token=auth()->guard('pharmacy-api')->login($user);
        return response()->json([
            'message'=>'pharmacy added successfully',

        ],201);
    }
        public function getAllUser(){
            $user = users::all();
    
                 return response()->json([
                  'message' => 'User you needed is',
                  'users' => $user,
    
    
              ]);
            }
            public function getuser($user_id){
                $user = users::select('id','name' , 'email')->where('id', $user_id)->first();
        
                     return response()->json([
                      'message' => 'User you needed is',
                      'user' => $user,
        
        
                  ]);
                }
}
