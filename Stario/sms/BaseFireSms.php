<?php 
namespace Star\sms;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * 短信发送类
 * 需要传入:
 * 1.手机号码
 * 2.短信内容
 */
abstract class BaseFireSms
{

    protected $url; //[$url 短信代理商的API URL地址
    protected $params; //通常由各类key组成，作为Guzzle的params参数数组发送HTTP请求
    protected $content; //短信内容
    protected $to; //手机号码

    /**
     * 调用该类需要传入手机号码和短信内容
     * @param [string] $to      [目标手机号码]
     * @param [string] $content [发送给API的参数]
     */
    public function __construct($to, $content)
    {
        $this->to = $to;
        $this->url = config('sms.Settings.url');
        $this->content = urlencode($content);
        $this->params = $this->makeParams();
    }

    /**
     * 接口实现方法，使用Guzzle client向短信服务器发送请求
     * 调用getResponse方法返回短信服务器的返回值
     * @return [array] [本基类输出一个数组，需要在子类中进一步进行解析]
     */
    public function fire()
    {
        $client = new Client();
        $response = $client->get($this->url, ['query' => $this->params]);
        $result = $this->getResponse($response);
        return $this->formatResponse($result);
    }

    /**
     * 格式化短信服务器的返回值
     * @param  ResponseInterface $response [Psr\Http\Message\ResponseInterface类型]
     * @return [array]     [本基类输出一个数组，需要在子类中进一步进行解析]
     */
    protected function getResponse(ResponseInterface $response)
    {
        $body = $response->getBody();
        $content = $body->getContents();
        $statusCode = $response->getStatusCode();
        $result = [
            'statusCode' => $statusCode,
            'content' => $content
          ];
        return $result;
    }

    /**
     * 每个服务商的proxy子类根据自身API生成自身特色的params集
     * @return [type] [description]
     */
    abstract protected function formatResponse($response);

    abstract protected function makeParams();
}
