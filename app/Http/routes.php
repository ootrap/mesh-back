<?php

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Headers: Authorization, Content-Type');

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

Route::group(['middleware' => ['api'],'prefix' => 'api'], function () {
    Route::post('register', 'APIController@register');
    Route::post('login', 'APIController@login');
    Route::group(['middleware' => 'jwt-auth'], function () {
        Route::post('getUserDetails', 'APIController@getUserDetails');
    });
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
