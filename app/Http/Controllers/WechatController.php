<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\session;
use Log;
use Star\wechat\WeOpen;
use Star\wechat\support\AES;
use Star\wechat\support\XML;

class WechatController extends Controller
{

    protected $weopen;

    public function __construct(WeOpen $weopen)
    {
        $this->weopen = $weopen;
    }

    public function test()
    {
        return json_encode(
            [
                    "name" => "partoo",
                    "age" => 39
            ]
        );
    }

    public function index()
    {
        // TODO:本地数据库用户校验：
        // 1.是否为新用户->注册
        // 2.已注册用户->已绑定公众号->管理后台；未绑定公众号->引导绑定
        // 以下代码假设已注册用户未绑定公众号
        $code = \Cache::get('auth_code');
        return view('home', compact('auth_code'));
    }

    // 接收来自微信发送过来的信息，解密获取ticket，并缓存['wx_ticket']
    // 然后获取预授权码
    public function auth()
    {
        $this->weopen->getComponentVerifyTicket();
         $this->weopen->getPreAuthCode();
    }

    //用户在微信客户端授权后页面会跳转，如果同意授权会发送一个auth_code
    public function callback()
    {
        $this->weopen->callback($_GET['auth_code']);
    }
}
