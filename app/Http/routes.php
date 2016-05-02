<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');


 Route::get('test', 'TestController@index');
 
Route::group(['prefix' => 'api/auth', 'middleware'=>'throttle:60'], function () {
    Route::post('register', 'AuthController@create');
    Route::post('login', 'AuthController@login');
    Route::post('refresh', 'AuthController@refreshToken');
    Route::post('forget', 'AuthController@findPassword');
    Route::post('sendSms', 'SMSController@fire');
});

Route::group(['prefix'=>'api/admin', 'middleware' => ['api']], function () {
    Route::get('home', 'HomeController@index');
    Route::get('callback', 'WechatController@callback');
    Route::get('mps', 'UserController@mps');
});

// 微信第三方平台
Route::any('/auth', 'WechatController@auth');
