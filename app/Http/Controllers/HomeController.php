<?php

namespace App\Http\Controllers;

use App\Http\Requests;
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
        $mps = $this->user->getWxmpsById(Auth::user()->id);
        $preAuthCode = Cache::get('preAuthCode');
        $url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$your_appid.'&pre_auth_code='.$your_pre_auth_code.'&redirect_uri='.$your_own_redirect_uri;
        $isBind = empty($mps);

        return response()->json([
                    'url' => $url,
                    'isBind' => $isBind,
                    'mps' => $mps
            ], 200);
    }
}
