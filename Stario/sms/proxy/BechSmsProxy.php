<?php 
namespace Star\sms\proxy;

use Illuminate\Support\Facades\Config;
use Star\sms\BaseFireSms;

/**
 * sms.bechtech.cn 短信代理
 */
class BechSmsProxy extends BaseFireSms
{

    /**
     * 抽象方法实现，生成特定的HTTP参数
     * 
     */
    public function makeParams()
    {
        $params = [
          'accesskey' => \Config::get('sms.Settings.akey'),
          'secretkey' => \Config::get('sms.Settings.skey'),
          'mobile' => $this->to,
          'content' => $this->content
        ];

        return $this->params = $params;
    }

    /**
     * 抽象方法实现，格式化输出
     */
    protected function formatResponse($response)
    {
        $statusCode = $response['statusCode'];
        $content = $response['content'];
        $errCode = \Config::get('sms.ErrorCode');
        $result = json_decode($content)->result;

        if ($statusCode == '200') {
            $desc = $errCode[$result];
            return response()->json(['result'=>[$desc]], 200);
        } else {
            return response()->json(['result'=>['系统错误']], 500);
        }
    }
}
