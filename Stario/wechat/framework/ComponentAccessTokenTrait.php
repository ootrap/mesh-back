<?php 
namespace Star\wechat\framework;

trait ComponentAccessTokenTrait
{
    /**
    * STEP 2:获取component_access_token
    */
    private function getComponenAccessToken()
    {
        $ticket = Cache::get('wx_ticket');
        if (empty ($ticket)) {
            return json_encode([
                    'result'=>'微信尚未发送数据,请等待10分钟'
                ]);
        }
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
        $result = $this->client->post($uri, ['json'=>["component_appid" => $this->appId,
                "component_appsecret" => $this->secureKey,
                "component_verify_ticket" => $ticket
            ]]);
        $data = json_decode($result->getBody());
        Cache::forever('component_access_token', $data->{'component_access_token'});
    }
}
