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
    Route::get('mplist', 'HomeController@mplist');
    Route::get('home', ['as' => 'home', 'action' => 'HomeController@index']);
    Route::get('mps', 'UserController@mps');
});

// 微信第三方平台
Route::any('/auth', 'WechatController@auth');
Route::get('/callback', 'HomeController@callback');

Route::get('/', function () {
    return view('home');
});
