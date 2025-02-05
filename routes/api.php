<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\ItemController;
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

Route::group(['prefix' => 'stock'], function(){
    Route::get('productSearch', [ItemController::class,'Stocksearch']);
    Route::post('productUpdate', [ItemController::class,'StockUpdate']);
    Route::get('stockDetails', [ItemController::class,'StockDetails']);
});
