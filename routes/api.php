<?php

use App\Http\Controllers\Api\BsuController;
use App\Http\Controllers\Api\CategorySampahController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function(){
    Route::apiResource('user',UserController::class);
    Route::apiResource('bsu',BsuController::class);
    Route::apiResource('category-sampah',CategorySampahController::class);
});
