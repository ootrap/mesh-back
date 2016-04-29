<?php
// 本地测试用

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');


Route::group(['prefix' => 'api/auth', 'middleware'=>'throttle:60'], function () {
    Route::post('register', 'AuthController@create');
    Route::post('login', 'AuthController@login');
    Route::post('refresh', 'AuthController@refreshToken');
    Route::post('forget', 'AuthController@findPassword');
    Route::post('sendSms', 'SMSController@fire');
});

Route::group(['prefix'=>'api/admin', 'middleware' => ['api']], function () {
    Route::get('home', 'HomeController@index');
    Route::get('mps', 'UserController@mps');
});

// 微信第三方平台
Route::get('/callback', 'WechatController@callback');
Route::post('/auth', 'WechatController@auth');

Route::get('/test', function () {
    dd(empty(\Cache::get('partoo')));
});
