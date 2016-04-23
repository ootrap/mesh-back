<?php 
namespace Star\sms;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
* 使用Guzzle发送短息
*/
class FireSMS
{
    const URL = 'http://sms.bechtech.cn/Api/send/data/json';
    protected $client;
    protected $akey;
    protected $skey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => self::URL]);
        $this->akey = \Config::get('sms.Bech.akey');
        $this->skey = \Config::get('sms.Bech.skey');
    }
    /**
     * 发送验证码
     * @param  [type] $to       目标手机号码
     * @param  [type] $authcode [系统生产的验证码]
     * @return [type]          返回格式化的json
     */
    public function fireAuthCode($to, $authcode)
    {
        /**
         * content的内容需要与免审模板完全一致
         * @var string
         */
        $content = '【微脉事】您的验证码是：'.$authcode.'，请在5分钟内完成验证';
        $params = [
          'accesskey' => $this->akey,
          'secretkey' => $this->skey,
          'mobile' => $to,
          'content' => $content
        ];
        $response = $this->client->get(self::URL, ['query' => $params]);
        return $this->responseTransform($response);
    }

    /**
     * 格式化短信服务器响应返回值
     * @param  ResponseInterface $response Guzzle client
     * @return [type]                      json格式数据
     */
    private function responseTransform(ResponseInterface $response)
    {
        $body = $response->getBody();
        $content = $body->getContents();
        $result = json_decode($content, true);
        $statusCode = $response->getStatusCode();

        $errCode = \Config::get('sms.ErrorCode');
        if ($statusCode == '200') {
            $desc = $errCode[$result['result']];
            return response()->json(['result'=>[$desc]], 200);
        } else {
            return response()->json(['result'=>['系统错误']], 500);
        }
       
    }
}
