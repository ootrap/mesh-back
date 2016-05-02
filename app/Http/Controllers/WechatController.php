<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Wxmp;
use Star\wechat\WeOpen;

class WechatController extends Controller
{

    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    // 接收来自微信发送过来的信息，解密获取ticket，并缓存['wx_ticket']
    public function auth()
    {
        WeOpen::getComponentVerifyTicket();
    }

    //用户在微信客户端授权后页面会跳转，如果同意授权会发送一个auth_code
    public function callback()
    {
        $data = WeOpen::getAuthorizerAccessToken($_GET['auth_code']);
        $this->user->createMp($data);
    }
}
