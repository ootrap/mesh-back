<?php
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Common
// 
// Route::group(['prefix'=>'wechat','middleware'=>['web','wechat.oauth']],function(){

//   Route::get('/','WechatController@index');
//   Route::any('/serve', 'WechatController@serve');
//   Route::get('/callback','WechatController@callback');

// });
// 
//第三方平台
// Route::get('/', 'WechatController@index');
// Route::get('/callback', 'WechatController@callback');
// Route::post('/auth', 'WechatController@auth');

// 本地测试用

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');

Route::get('xx', 'AuthController@xx');

Route::group(['prefix' => 'api'], function () {
    Route::post('register', 'AuthController@create');
    Route::post('login', 'AuthController@authenticateLogin');
    Route::post('forget', 'AuthController@findPassword');
    Route::post('sendSms', 'AuthController@sendSms');
    Route::get('getUsers', 'AuthController@test');
});
