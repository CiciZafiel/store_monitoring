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
    Route::post('/store/ping-store', 'pingStore');
    Route::post('/store/check-serial', 'checkSerialStatusPerItem');
    Route::post('/store/serial-transaction-history', 'serialTransactionHistory');
});


Route::controller(App\Http\Controllers\Sales\SalesController::class)->group(function() {
    Route::post('/sales-details', 'getSalesData');
    Route::post('/sales-posted-and-unposted-summary', 'getSalesPostedAndUnpostedSummary');
});


Route::controller(App\Http\Controllers\Inventory\InventoryController::class)->group(function(){
    Route::get('/invetory/store-lists', 'getStoreList');
    Route::post('/inventory/items', 'getItems');
    Route::post('/inventory/store-item-qty', 'getStoresItemQty');
    Route::post('/inventory/ping-store', 'pingStore');
   
});
