<?php 
namespace Star\wechat;

use GuzzleHttp\Client;

use Star\wechat\support\AES;

class GetPreAuthCode
{
    protected $client;
    protected $params;

    public function __construct()
    {
        $client = new Client();
        $params = [
        'appid' => config('wechat.app_id'),
        'secret'  => config('wechat.secret'),
        'toke'  => config('wechat.toke'),
        'aeskey' => config('wechat.aes_key')
        ];
    }

    public function getTicket($rawPostData)
    {
        $xml_tree = new \DOMDocument();
        $xml_tree->loadXML($rawPostData);
        $array_e = $xml_tree->getElementsByTagName('Encrypt');
        $encrypt = $array_e->item(0)->nodeValue;
        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $from_xml = sprintf($format, $encrypt);

        $pc = new AES($encodingAesKey, $appId, $token);

        $decryptedMsg = $pc->decode($from_xml);
        $xml = new \DOMDocument();
        $xml->loadXML($decryptedMsg);
        $array_e = $xml->getElementsByTagName('ComponentVerifyTicket');

        $component_verify_ticket = $array_e->item(0)->nodeValue;
        // Log::info('解密后的component_verify_ticket是：'.$component_verify_ticket);
    }
}
