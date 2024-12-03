<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServiceProviderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\EmergencyRequestController;
use App\Http\Controllers\Api\EmergencyServiceController;

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



    Route::get('EmergencyRequests', [EmergencyRequestController::class,'index']);
    Route::get('EmergencyRequests/{id}', [EmergencyRequestController::class,'show']);
    Route::post('EmergencyRequests', [EmergencyRequestController::class,'store']);
    Route::put('EmergencyRequests/{id}', [EmergencyRequestController::class,'update']);
    Route::get('EmergencyRequest/EmergencyService/{service_id}',[EmergencyRequestController::class,'getService']);
    Route::get('EmergencRequest/EmergencyServiceProvider/{provider_id}',[EmergencyRequestController::class,'getProvider']);
    Route::post('EmergencyRequest/update-service-status',[EmergencyRequestController::class,'updateServiceStatus']);


    
    Route::get('emergencyServices', [EmergencyServiceController:: class, 'index']);
    Route::post('emergencyServices', [EmergencyServiceController:: class, 'store']);
    Route::get('emergencyServices/{id}', [EmergencyServiceController::class, 'getServiceById'])->where('id', '[0-9]+');
    Route::get('emergencyServices/{service_type}', [EmergencyServiceController::class, 'getServiceByType']);
});

