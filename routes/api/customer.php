<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('customer')->group(function () {
    Route::post('login', 'Api\Admin\CustomerController@login');

    Route::post('register', 'Api\Admin\CustomerController@register');

    
    Route::middleware('auth:sanctum')->group(function () {

    });

});