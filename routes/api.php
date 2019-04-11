<?php

use Illuminate\Http\Request;

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
Route::get('users','Api\PhuController@index');
Route::post('users','Api\PhuController@store');
Route::get('users/{user}','Api\PhuController@show');
Route::put('users/{user}','Api\PhuController@update');
Route::post('users/login','Api\PhuController@login');
Route::delete('users/{user}','Api\PhuController@delete');