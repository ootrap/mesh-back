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
        $code = \Cache::get('code');
        return view('home', compact('code'));
    }

    // 用于对应微信开发信息中接受auth post
    public function auth()
    {
        $this->weopen->auth();
    }

    public function callback()
    {
        //用户在微信客户端授权后页面会跳转，如果同意授权会发送一个auth_code
        $this->weopen->callback($_GET['auth_code']);
    }
}
