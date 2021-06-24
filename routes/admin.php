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

Route::namespace('Admin')->group(function (){
    Route::get('auth', 'AuthController@auth');
});

Route::namespace('Admin')->middleware('jwt.cauth')->group(function (){
    Route::post('upload', 'UploadController@upload');
    Route::get('read', 'UploadController@read');
});
