<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Log;

class WechatController extends Controller
{
    public function serve() {
       Log::info('请求收到');

       $wechat = app('wechat');
       $wechat->server->setMessageHandler(function($message){
          return '欢迎关注';
       });

       Log::info('返回响应');

       return $wechat->server->serve();
    }
}
