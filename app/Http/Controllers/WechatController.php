<?php

namespace App\Http\Controllers;

use Star\wechat\WeOpen;

class WechatController extends Controller
{

    // 接收来自微信发送过来的信息，解密获取ticket，并缓存['wx_ticket']
    public function auth()
    {
        WeOpen::getComponentVerifyTicket();
    }

    //用户在微信客户端授权后页面会跳转，如果同意授权会发送一个auth_code
    public function callback()
    {
        $data = WeOpen::getAuthorizerAccessToken($_GET['auth_code']);
    }
}
