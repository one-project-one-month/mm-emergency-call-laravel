<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServiceProviderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\EmergencyRequestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

    Route::apiResource('users',UserController::class);
    Route::apiResource('serviceProviders',ServiceProviderController::class);
    Route::get('serviceProviders/EmergencyService/{service_id}',[ServiceProviderController::class,'ServiceID']);
    Route::get('EmergencyServiceRequests', [EmergencyRequestController::class,'index']);
    Route::get('EmergencyServiceRequests/{id}', [EmergencyRequestController::class,'show']);
    Route::post('EmergencyServiceRequests', [EmergencyRequestController::class,'store']);
    Route::put('EmergencyServiceRequests/{id}', [EmergencyRequestController::class,'update']);
    // Route::delete('EmergencyServiceRequests/{id}', [EmergencyRequestController::class,'destroy']);

    Route::get('EmergencyServiceRequest/EmergencyService/{service_id}',[EmergencyRequestController::class,'getService']);
    Route::get('EmergencyServiceRequest/EmergencyServiceProvider/{provider_id}',[EmergencyRequestController::class,'getProvider']);

    Route::post('EmergencyServiceRequest/update-service-status',[EmergencyRequestController::class,'updateServiceStatus']);
});

// Route::prefix('v1',function(){
//     Route::apiResource('EmergencyServiceRequest',EmergencyRequestController::class);
// });

