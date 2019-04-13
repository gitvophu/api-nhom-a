<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Models\User;

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
Route::get('/users','UserController@index')->name('showalluser');
Route::get('/users/page','UserController@paging')->name('paging');
Route::get('/users/search','UserController@search')->name('search');
Route::get('/users/{id}','UserController@showUser')->name('showauser');
Route::post('/users/login','UserController@loginUser')->name('logoutUser');
Route::post('/users/logout','UserController@logoutUser')->name('loginUser');
Route::post('/users/create','UserController@createUser')->name('createUser');
Route::put('/users/update/{id}','UserController@updateUser')->name('updateuser');
Route::delete('/users/delete/{id}','UserController@deleteUser')->name('deleteuser');
