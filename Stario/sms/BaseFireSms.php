<?php 
namespace Star\sms;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * 短信发送抽象类，同时实现接口
 */
abstract class BaseFireSms implements InterfaceFireSms
{
    /**
     * [$url 短信代理商的API URL地址]
     * @var [string]
     */
    protected $url;

    /**
     * [$params 参数集合，通常由各类key组成，作为Guzzle的params参数数组发送HTTP请求]
     * @var [array]
     */
    protected $params;

    /**
     * 实例化该类需要传入$url和参数
     */
    public function __construct($url, array $params)
    {
        $this->params = $params;
        $this->url = $url;
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
        return $this->getResponse($response);
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
}
