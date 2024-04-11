<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::namespace('App\Http\Controllers')->group(function () {

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::post('login', 'Api\Admin\LoginController@authenticated');
    });

    // customer Routes
    Route::prefix('customer')->group(function () {
        Route::post('login', 'Api\Admin\CustomerController@login');
        Route::post('register', 'Api\Admin\CustomerController@register');
    });

    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });


    Route::middleware('auth:customer,admin')->get('/user', function (Request $request) {
        return $request->user();
    });




    // Route::post('login', 'Api\Admin\LoginController@authenticated');
    // Route::post('customer/login', 'Api\Admin\CustomerController@login');

    Route::apiResources([
        'file' => 'FileController'
    ]);

    Route::middleware('auth:sanctum')->group(function () {

        Route::namespace('Api\Admin')->group(function () {
            Route::apiResource('users', 'UserController');

            Route::apiResources([
                'categories' => 'CategoryController',
                'products' => 'ProductController',
                'attributes' => 'AttributeController',
                'attribute/families' => 'AttributeFamilyController',
                'currencies' => 'CurrencyController',
                'stores' => 'StoreController',
                'inventory-source' => 'InventorySourceController',
            ]);
        });
    });


    Route::namespace('Api\Admin')->group(function () {
        Route::apiResources([
            'categories' => 'CategoryController'
        ]);

        Route::post('products/slug/{slug}', 'ProductController@getProductBySlug');
    });
});
