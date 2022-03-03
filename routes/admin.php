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
    Route::post('auth', 'AuthController@auth');
    Route::get('vefiry_code', 'AuthController@createVerifyCode');
});
Route::namespace('Admin')->group(function (){
    Route::get('pcheck/dataStatistics1', 'PCompanyCheckController@statisticsData');
});

//Route::namespace('Admin')->group(function (){
Route::namespace('Admin')->middleware('auth.admin')->group(function (){
    Route::post('upload', 'UploadController@upload');
    Route::get('read', 'UploadController@read');
    Route::post('file/add', 'FileController@add');
    Route::get('file/list', 'FileController@fileList');
    Route::post('file/audit_status', 'FileController@updateAuditStatus');

    Route::post('pcheck/import', 'PCompanyCheckController@import');
    Route::get('pcheck/show', 'PCompanyCheckController@show');
    Route::get('pcheck/dataStatistics', 'PCompanyCheckController@statisticsData');
    Route::post('pcheck/text', 'PCompanyCheckController@updateText');
    Route::get('pcheck/file_list', 'PCompanyCheckController@getFileList');
    Route::post('pcheck/file_list/finalize', 'PCompanyCheckController@finalize');
    Route::post('pcheck/file_list/finalize/cancel', 'PCompanyCheckController@cancelFinalize');
    Route::get('pcheck/datasource', 'PCompanyCheckController@switchDataSourceByMonth');
    Route::get('pcheck/downlist', 'PCompanyCheckController@getDownloadList');
});
