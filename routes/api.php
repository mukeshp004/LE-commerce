<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', 'Api\Admin\LoginController@authenticated');

Route::middleware('auth:sanctum')->group(function () {

    Route::namespace('Api\Admin')->group(function () {
        Route::apiResource('users', 'UserController');

        Route::apiResources([
            'categories' => 'CategoryController',
            'currencies' => 'CurrencyController',
            'stores' => 'StoreController',
        ]);
    });

});