<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;

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

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group( function () {
    Route::resource('maps', \App\Http\Controllers\API\MapsController::class);
    Route::resource('properties', \App\Http\Controllers\API\PropertyController::class);
    Route::resource('locations', \App\Http\Controllers\API\LocationController::class);
    Route::resource('blocks', \App\Http\Controllers\API\BlockController::class);
    Route::resource('leads', \App\Http\Controllers\API\LeadController::class);
    Route::resource('users', \App\Http\Controllers\API\UserController::class);
    Route::post('imageUpload',[\App\Http\Controllers\API\PropertyController::class,'imageUpload']);
    Route::post('fileUpload',[\App\Http\Controllers\API\MapsController::class,'fileUpload']);
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::get('/validate-token', function (Request $request) {return response()->json(['authenticated' => true]);});
    Route::get('statistics',[\App\Http\Controllers\API\PropertyController::class,'getStatistics']);
});

