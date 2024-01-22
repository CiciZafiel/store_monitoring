<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::controller(App\Http\Controllers\Dashboard\DashboardController::class)->group(function() {
    Route::get('/total-unposted-to-sap','totalUnpostedToSAP');
    Route::get('/total-posted-to-sap','gettotalPostedToSAPToday');
    Route::get('/total-posted-to-server','gettotalPostedToServerToday');
    Route::get('/total-unposted-to-sap-today','gettotalUnpostedToSAPToday');
    Route::post('/store-lists', 'getStoreList');
    Route::post('/store-availability', 'pingIpAddress');
});


Route::controller(App\Http\Controllers\Store\StoreController::class)->group(function() {
    Route::post('/imei-lists', 'getIMEIList');
    Route::post('/search-imei', 'searchIMEI');
});

