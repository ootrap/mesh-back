<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Star\wechat\WeOpen;

class WechatController extends Controller
{

    protected $weopen;

    public function __construct(WeOpen $weopen)
    {
        $this->weopen = $weopen;
    }

    // 接收来自微信发送过来的信息，解密获取ticket，并缓存['wx_ticket']
    public function auth()
    {
        $this->weopen->getComponentVerifyTicket();
    }

    //用户在微信客户端授权后页面会跳转，如果同意授权会发送一个auth_code
    public function callback()
    {
        $data = $this->weopen->getAuthorizerAccessToken($_GET['auth_code']);
        dd($data);
    }
}
