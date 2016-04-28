<?php 
namespace Star\wechat\framework;

trait PreAuthCodeTrait
{
   /**
     * STEP 3:获取预授权码，存入缓存
     */
    private function getPreAuthCode()
    {
        $component_access_token = Cache::get('component_access_token');
        if (empty ($component_access_token)) {
            $this->getComponenAccessToken();
            return json_encode([
                    'message'=>'微信尚未发送数据,请等待10分钟'
                ]);
        }
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='
                    .$component_access_token;
        $result = $this->client->post($uri, ['json'=>["component_appid" => $this->appId]]);
        $data = json_decode($result->getBody());
        if (empty($data->{'pre_auth_code'})) {
            $this->getComponentVerifyTicket();
            $this->getComponenAccessToken();
        }
        $preAuthCode = $data->{'pre_auth_code'};
        Cache::forever('preAuthCode', $preAuthCode);
    }
}
