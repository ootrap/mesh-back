<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Star\sms\MakeContent;
use Star\sms\proxy\BechSmsProxy;

class SMSController extends Controller
{
    /**
 * 发送短信
 * @param  Request $request [前端传过来的请求]
 */
    public function fire(Request $request)
    {
        $mobile = $request->only('mobile')['mobile'];
        $content = new MakeContent;
        $fire = new BechSmsProxy($mobile, $content->makeCode($mobile));
        return $fire->fire();
    }
}
