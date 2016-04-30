<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Star\Repositories\Eloquent\UserRepo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = '{"authorizer_info":{"nick_name":"staraw","head_img":"http:\/\/wx.qlogo.cn\/mmopen\/rPECmo1WwDPMbaCIhZibTOvriaWJHIvzymFCOsK7POVwic9FrQuA71lolKvNSjicZSWXnGVS1ug0JWkLOf8Ay7knK2gPGFRvWx6U\/0","service_type_info":{"id":2},"verify_type_info":{"id":-1},"user_name":"gh_cdb03830f467","alias":"staraw","qrcode_url":"http:\/\/mmbiz.qpic.cn\/mmbiz\/I867jOK7cedvWDZuE05BiabrlavSDBJkCknwSBCGQynsOVkGTkZktCVl8Ntich3xXcJ79QUj6lxCPwO9IJLyDfXQ\/0","business_info":{"open_pay":0,"open_shake":0,"open_scan":0,"open_card":0,"open_store":0}},"authorization_info":{"authorizer_appid":"wx299132db5e192f83","func_info":[{"funcscope_category":{"id":1}},{"funcscope_category":{"id":15}},{"funcscope_category":{"id":4}},{"funcscope_category":{"id":7}},{"funcscope_category":{"id":2}},{"funcscope_category":{"id":3}},{"funcscope_category":{"id":11}},{"funcscope_category":{"id":6}},{"funcscope_category":{"id":5}},{"funcscope_category":{"id":8}},{"funcscope_category":{"id":13}},{"funcscope_category":{"id":10}},{"funcscope_category":{"id":12}}]}}';

        // $mps = $this->user->getWxmpsById(Auth::user()->id);
        // $preAuthCode = Cache::get('preAuthCode');
        // $url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.config('wechat.app_id').'&pre_auth_code='.$preAuthCode.'&redirect_uri='.config('wechat.redirect_url');
        // $isBind = empty($mps);

        // return response()->json([
        //             'url' => $url,
        //             'isBind' => $isBind,
        //             'mps' => $mps
        //     ], 200);
    }
}
