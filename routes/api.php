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
//Auth apis
Route::group(['namespace' => 'API'], function () {
    //Auth
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout')->middleware('auth:sanctum');
    //Auth user
    Route::get('/user', 'AuthController@user')->middleware('auth:sanctum');
});
