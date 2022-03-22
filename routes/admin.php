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

//Route::namespace('Admin')->group(function (){
//    Route::get('pcheck/dataStatistics1', 'PCompanyCheckController@statisticsData');
//    Route::get('pcheck/downlist', 'PCompanyCheckController@getDownloadList');
//    Route::get('pcheck/file_list', 'PCompanyCheckController@getFileList');
//    Route::get('pcheck/datasource', 'PCompanyCheckController@switchDataSourceByMonth');
//    Route::get('pcheck/show', 'PCompanyCheckController@show');
//    Route::get('pcheck/dataStatistics', 'PCompanyCheckController@statisticsData');
//    Route::get('pcheck/file_list', 'PCompanyCheckController@getFileList');
//});

Route::namespace('Admin')->group(function (){
//Route::namespace('Admin')->middleware('auth.admin')->group(function (){
    Route::post('upload', 'UploadController@upload');
    Route::get('read', 'UploadController@read');
    Route::post('file/add', 'FileController@add');
    Route::get('file/list', 'FileController@fileList');
    Route::post('file/audit_status', 'FileController@updateAuditStatus');


    //更新文档
    Route::post('pcheck/text', 'PCompanyCheckController@updateText');
    //导入
    Route::post('pcheck/import', 'PCompanyCheckController@import');
    //定稿
    Route::post('pcheck/file_list/finalize', 'PCompanyCheckController@finalize');
    //取消定稿
    Route::post('pcheck/file_list/finalize/cancel', 'PCompanyCheckController@cancelFinalize');
    //更改密码
    Route::post('pcheck/password', 'AuthController@updatePassword');


    Route::get('pcheck/dataStatistics1', 'PCompanyCheckController@statisticsData');
    Route::get('pcheck/downlist', 'PCompanyCheckController@getDownloadList');
    Route::get('pcheck/file_list', 'PCompanyCheckController@getFileList');
    Route::get('pcheck/datasource', 'PCompanyCheckController@switchDataSourceByMonth');
    Route::get('pcheck/show', 'PCompanyCheckController@show');
    Route::get('pcheck/dataStatistics', 'PCompanyCheckController@statisticsData');
    Route::get('pcheck/file_list', 'PCompanyCheckController@getFileList');
});
