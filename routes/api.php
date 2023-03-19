<?php

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/get-info/{queueId}', [MainController::class, 'getInfoUser']);
Route::get('/get-cities', [MainController::class, 'getCities']);
Route::post('/users', [MainController::class, 'storeUserIdentity']);
Route::post('/search-user', [MainController::class, 'searchUser']);
