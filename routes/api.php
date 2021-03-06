<?php

use App\Http\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

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

Route::get('/users','UserController@index')->name('showalluser');
Route::post('/users/page','UserController@paging')->name('paging');
Route::get('/users/search','UserController@search')->name('search');
Route::get('/users/{id}','UserController@showUser')->name('showauser');
Route::post('/users/login','UserController@loginUser')->name('loginUser');
Route::post('/users/logout','UserController@logoutUser')->name('logoutUser');
Route::post('/users/create','UserController@createUser')->name('createUser');
Route::put('/users/update/{id}','UserController@updateUser')->name('updateuser');
Route::put('/users/change-password/{id}','UserController@changeUserPassword')->name('change-password');
Route::delete('/users/delete/{id}','UserController@deleteUser')->name('deleteuser');


Route::group(['prefix'=>'users'],function(){
    Route::post('/update-with-image','UserController@updateWithImage');
    Route::post('/sendMail','UserController@phuSendMail');
});


//product

Route::get('/products','ProductController@index')->name('index');
Route::post('/products/page','ProductController@paging')->name('paging');
Route::get('/products/{id}','ProductController@showProduct')->name('showProduct');
Route::post('/products/create','ProductController@createProduct')->name('createProduct');
Route::put('/products/update/{id}','ProductController@updateProduct')->name('updateProduct');
Route::delete('/products/delete/{id}','ProductController@deleteProduct')->name('deleteProduct');

//images

Route::group(['prefix'=>'images'],function(){
    Route::post('/','ImageController@store');
    Route::delete('/delete/{id}','ImageController@delete');
    Route::post('/update','ImageController@update_image');
});

//user type

Route::group(['prefix'=>'user_type'],function(){
    Route::get('/','UserTypeController@index');
    Route::post('/create','UserTypeController@create');
    Route::put('/update/{id}','UserTypeController@edit');
    Route::delete('/delete/{id}','UserTypeController@destroy');
});