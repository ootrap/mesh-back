<?php 
namespace Star\sms\proxy;

use Illuminate\Support\Facades\Config;
use Star\sms\BaseFireSms;

/**
 * sms.bechtech.cn 短信代理
 */
class BechSmsProxy extends BaseFireSms
{

    protected $url;//API url地址
    protected $params; //参数集
    protected $content; //发送的内容
    protected $to; //要发送的手机号码

    public function __construct($to, $content)
    {
        $this->to = $to;
        $this->content = $content;
        $this->url = \Config::get('sms.Settings.url');
        $this->makeParams();
        parent::__construct($this->url, $this->params);
    }

    /**
     * 根据实际情况，覆盖基类的fire方法，输出更加人性化的返回值
     * @return [json] [description]
     */
    public function fire()
    {
        $response = parent::fire();
        return $this->formatResponse($response);
    }

    /**
     * 拼接Bech代理的指定格式参数
     * 
     */
    protected function makeParams()
    {
        $params = [
          'accesskey' => \Config::get('sms.Settings.akey'),
          'secretkey' => \Config::get('sms.Settings.skey'),
          'mobile' => $this->to,
          'content' => $this->content
        ];
        $this->params = $params;
    }

    /**
     * 格式化输出
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
