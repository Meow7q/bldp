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

Route::namespace('Api')->group(function (){
    Route::get('jyzl', 'DpDataController@getJyzlMainData');
    Route::get('jyzl/tfl', 'DpDataController@getJyzlTflData');
    Route::get('fyztqk', 'DpDataController@getFyztqkData');
    Route::get('dkzlfx', 'DpDataController@getDkzlFx');
    Route::get('dqtx', 'DpDataController@getDqtx');
    Route::get('zjbl', 'DpDataController@getZjbl');
    Route::get('zqzch', 'DpDataController@getZqzch');
});
