<?php

use App\Http\Models\User;
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
// Route::group(['namespace' => 'Auth', 'middleware' => 'api', 'prefix' => 'password'], function () {    
    Route::post('password/create', 'PasswordResetController@create');
    Route::get('password/find/{token}', 'PasswordResetController@find');
    Route::post('password/reset', 'PasswordResetController@reset');
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', 'UserController@index');

Route::post('user/login', 'UserController@login');

Route::get('user/userInfo/{id}', 'UserController@userInfo');

Route::post('user/store', 'UserController@store');

Route::put('user/update/{id}', 'UserController@update');

Route::delete('user/delete/{id}', 'UserController@delete');