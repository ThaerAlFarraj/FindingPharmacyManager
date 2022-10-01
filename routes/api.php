<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterpharmacyController;
use App\Http\Controllers\AdminAddedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\pharmacyactionsController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\Med_In_PhareController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\MedListController;
use App\Http\Controllers\CusWithPharController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MedPursController;
use App\Http\Controllers\DonatedMedController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();


});






   Route::group([

    'middleware' => 'api',


], function ($router) {

    Route::group([ 'prefix' => 'admin', ], function ($router) {
        Route::post('register',[CustomAuthController::class,'register']);
        Route::post('login',[App\Http\Controllers\CustomAuthController::class,'login']);
        Route::post('logout',[CustomAuthController::class,'logout']);

    });
    Route::group([ 'prefix' => 'user', ], function ($router) {
        Route::post('register',[RegisterController::class,'register']);
        Route::post('login',[RegisterController::class,'login']);
        Route::post('logout',[RegisterController::class,'logout']);
    });
    Route::group([ 'prefix' => 'pharmacy', ], function ($router) {
        Route::post('login',[RegisterpharmacyController::class,'login']);
        Route::post('logout',[RegisterpharmacyController::class,'logout']);


    });

});


    Route::group([
      'middleware' => 'App\Http\Middleware\Admin:admin-api',
        'prefix' => 'just_admin',

    ], function () {


        Route::post('addpharmacy',[AdminAddedController::class,'addpharmacy']);
        Route::delete('delete/{id}',[AddTripController::class,'delete']);
        Route::post('updatePro/{id}',[RegisterpharmacyController::class,'updatePro2']);
        Route::delete('deletepharmacy/{id}',[RegisterpharmacyController::class,'delete']);
        Route::get('getpro/{pharmacy_id}', [RegisterpharmacyController::class,'getPro']);
        Route::get('getAllpharmacy', [RegisterpharmacyController::class,'getAllpharmacy']);
        Route::get('getuser/{user_id}', [AdminAddedController::class,'getuser']);
        Route::get('getAllUser', [AdminAddedController::class,'getAllUser']);

    });


    Route::group([
        'middleware' => 'App\Http\Middleware\pharmacyAuth:pharmacy-api',
        'prefix' => 'just_pharmacy',

    ], function () {


        Route::post('updatePro',[RegisterpharmacyController::class,'updatePro']);
        Route::post('checkpin' , [RegisterpharmacyController::class , 'checkPin']);
        Route::post('activate' , [pharmacyactionsController::class , 'activate']);
        Route::post('accept' , [pharmacyactionsController::class , 'accepteOrder']);
        Route::post('refusal' , [pharmacyactionsController::class , 'refusalOrder']);
    });
    Route::get('profile',function(){
        return 'unautheantic user ';
    })->name('login');

    Route::middleware('auth:api')->group(function ()
    {
        Route::get('getpro/{pharmacy_id}', [RegisterpharmacyController::class,'getPro']);
        Route::post('ordertrip' , [AddTripController::class , 'store']);
        Route::post('updatetrip' , [AddTripController::class , 'updatetrip']);
        Route::post('getnearby' , [AddTripController::class , 'getpharmacyNearby']);
        Route::post('pharmacys/{pharmacy_id}/comments/store','CommentController@store');
        Route::get('pharmacys/{pharmacy_id}/comments','CommentController@list');
        Route::get('autocomplete/{paramiter}' , [SearchController::class , 'autocomplete']);
    });


Route::apiResource("pharmacy",PharmacyController::class);

Route::apiResource("medicine",MedicineController::class);

Route::post("med_In_Phar",[Med_In_PhareController::class, 'store']);

Route::get('/med_In_Phar/{id1}/{id2}', [Med_In_PhareController::class, 'show']);

Route::get('med_In_Phar', [Med_In_PhareController::class, 'index']);

Route::put('/med_In_Phar/{id1}/{id2}',[Med_In_PhareController::class, 'update']);

Route::delete('/med_In_Phar/{id1}/{id2}',[Med_In_PhareController::class, 'destroy']);

Route::apiResource("users",UserController::class);

Route::get("getnear",[UserController::class,'get_near_pharmacy']);
Route::get("search/{name}",[UserController::class,'search']);  

Route::apiResource("complaint",ComplaintController::class);

Route::post("med_List",[MedListController::class, 'store']);

Route::get('/med_List/{id1}/{id2}', [MedListController::class, 'show']);

Route::get('med_List', [MedListController::class, 'index']);

Route::put('/med_List/{id1}/{id2}',[MedListController::class, 'update']);

Route::delete('/med_List/{id1}/{id2}',[MedListController::class, 'destroy']);

Route::post("cus_with_Phar",[CusWithPharController::class,'store']);

Route::get('/cus_with_Phar/{id1}/{id2}', [CusWithPharController::class, 'show']);

Route::get('cus_with_Phar', [CusWithPharController::class, 'index']);

Route::put('/cus_with_Phar/{id1}/{id2}', [CusWithPharController::class, 'update']);

Route::delete('/cus_with_Phar/{id1}/{id2}', [CusWithPharController::class, 'destroy']);

Route::apiResource("purchase",PurchaseController::class);

Route::post("med_pur",[MedPursController::class,'store']);

Route::get('/med_pur/{id1}/{id2}/{id3}', [MedPursController::class, 'show']);

Route::get('med_pur', [MedPursController::class, 'index']);

Route::put('/med_pur/{id1}/{id2}/{id3}', [MedPursController::class, 'update']);

Route::delete('/med_pur/{id1}/{id2}/{id3}', [MedPursController::class, 'destroy']);

Route::apiResource("don_med",DonatedMedController::class);

Route::get('phar_shift', [SearchController::class, 'getPharShiftWork']);

Route::get('phar_from_name/{name}', [SearchController::class, 'getPharname']);

Route::get('alertExp/{id}', [PharmacyController::class, 'alertExp']);

Route::put('confirmation/{id}', [UserController::class, 'confirmation']);

Route::put('acc_delivery/{id}', [UserController::class, 'acceptableDelivery']);

Route::delete('refu_delivery/{id}', [UserController::class, 'refusedDelivery']);

