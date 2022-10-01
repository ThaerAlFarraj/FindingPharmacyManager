<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Pin;
use App\Models\pharmacy;
use App\Events\NewNotification;
use App\Notifications\SendEmail;
use Illuminate\Support\Facades\Notification;
use Dotenv\Parser\Value;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
class RegisterpharmacyController extends Controller
{

    protected $db_mysql;
    public function __construct()
    {
        $this ->db_mysql= config('database.connections.mysql.database');

    }
    /**
     * Register
     */

    public function login(Request $request)
    {
     $validator =Validator::make($request->all(),[

         'email'=>'required|string|email',
         'password'=>'required|string|min:8',
     ]);
     if ($validator->fails())
     {
         return response()->json($validator->errors()->toJson(),422);
     }
     $credentials=$request->only(['email','password']);

     if(!$token=auth()->guard('pharmacy-api')->attempt($credentials))
     {
       return response()->json(['error'=>'Unauthorized'],401);
     }
     $pharmacy = Auth::guard('pharmacy-api')->user();
     $pharmacy = $pharmacy->id;

     return response()->json([
         'access_token'=>$token,
         'user'=>auth()->guard('pharmacy-api')->user(),

       ]);

    }

    public function updatePro(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $pharmacy = pharmacy::where($id)->first();
        $validator = validator($input, [
            'name'=>'string',
            'email'=>'string|email|unique:_pharmacys',
            'password'=>'min:8',
            'image'=>'nullable|image',

            'location'=>'string',
            'number'=>'numeric',



        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()]);
        }

        if($request->exists('name')){
        $pharmacy->name= $input['name'] ;
        }
        if($request->exists('email')){
        $pharmacy->email= $input['email'] ;
        }
        if($request->exists('password')){
        $pharmacy->password= bcrypt($input['password'])  ;
        }
        if($request->exists('location')){
        $pharmacy->location=  $input['location'] ;
        }
        
        if($request->exists('number')){
            $pharmacy->number= $input['number'] ;
        }
        if ($request->image && $request->image->isValid()){

            $file_extension = $request->image->extension();
            $file_name = time() . '.' . $file_extension;
            $request->image->move(public_path('images/pharmacys'), $file_name);
            $path = "public/images/pharmacys/$file_name";
            $pharmacy->image = $path;
        }

        $pharmacy->save();
        return response()->json(['pharmacy'=>$pharmacy,'msg'=>'pharmacy update succefully']);
    }
    public function logout()
    {
        Auth::gurd('pharmacy-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function delete( $id){
        $pharmacy = pharmacy::find($id);
        $result = $pharmacy->delete();
        if($result){
            return response()->json([
                'message'=>' A pharmacy Deleted Successfully'

            ],201);
         } else{
            return response()->json([
                'message'=>'pharmacy Not Deleted '

            ],400);
            }
        }
        public function updatePro2(Request $request,$id)
        {

            $input = $request->all();
            $id = $request->id;
            $pharmacy = pharmacy::find($id);
            $validator = validator($input, [
                'name'=>'string',
                'email'=>'string|email|unique:_pharmacys',
                'password'=>'min:8',
                'image'=>'nullable|image',
                'location'=>'string',
                'number'=>'numeric',



            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()]);
            }

            if($request->exists('name')){
            $pharmacy->name= $input['name'] ;
            }
            if($request->exists('email')){
            $pharmacy->email= $input['email'] ;
            }
            if($request->exists('password')){
            $pharmacy->password=  bcrypt($input['password']);
            }
            if($request->exists('location')){
            $pharmacy->location=  $input['location'] ;
            }
           
            if($request->exists('number')){
                $pharmacy->number= $input['number'] ;
            }
            if ($request->image && $request->image->isValid()){

                $file_extension = $request->image->extension();
                $file_name = time() . '.' . $file_extension;
                $request->image->move(public_path('images/pharmacys'), $file_name);
                $path = "public/images/pharmacys/$file_name";
                $pharmacy->image = $path;
            }

            $pharmacy->save();
            return response()->json(['pharmacy'=>$pharmacy,'msg'=>'pharmacy update succefully']);
        }
        public function getPro($pharmacy_id)
    {
       $pharmacy = pharmacy::select('id','name' , 'location' , 'image' , 'number')->where('id', $pharmacy_id)->first();
       if (!$pharmacy)
       {
        return response()->json(['message' => 'pharmacy not found']);
       }
       if ( is_null($pharmacy->image) )
       {
        $image = 'null';
       }
        
            $rating = Comment::where('pharmacy_id', $pharmacy->id)->avg('rate');
            return response()->json([
             'id' => $pharmacy->id,
             'name' => $pharmacy->name,
             'location' => $pharmacy->location,
             'image' => $image,
             'number' => $pharmacy->number,
             'rating' => round($rating,1)
         ]);
    }
    public function getAllpharmacy()
    {
       $pharmacys = pharmacy::all();
       if (!$pharmacys)
       {
        return response()->json(['message' => 'pharmacy not found']);
       }
       
        else{
            
            return response()->json([
        'data'=>$pharmacys
         ]);
    }
    }




    public function generatePIN()
    {
        $pin = mt_rand(0000, 9999);
        return $pin ;

    }

    public function checkPin(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'code'=>'required|numeric',
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),422);
        }
        $pharmacy = auth()->guard('pharmacy-api')->id();
        $pin = Pin::where('pharmacy_id',$pharmacy)->latest()->first();
        if($pin->code == $request->code)
        {
            return response()->json(['message'=>'you are loged in successfully']);

        }
        else {
            return response()->json(['message'=>'check your PIN']);
        }
    }
}
