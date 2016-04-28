<?php 
namespace Star\wechat\framework;

trait AuthorizerAccessTokenTrait
{
/**
     * STEP 4: 换取authorizer_access_token和authorizer_refresh_token
     */
    private function getAuthorizerAccessToken($authcode)
    {
         $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='
                    .Cache::get('component_access_token');

        $result = $this->client->post($uri, ['json'=>[
                "component_appid" => $this->appId,
                "authorization_code" => $authcode
                ]]);
        $data = json_decode($result->getBody());
    // TODO !!!!需要替换改为写入数据库！！！！！！！！！！！！！！！！！！！！！！
        Cache::forever('refreshKey', $data->authorization_info->authorizer_refresh_token);
        //TODO 这是授权的大众公众号的APPID,修改传递方式
        Cache::forever('authorizerAppId', $data->authorization_info->authorizer_appid);
        Cache::forever('authorizer_access_token', $data->authorization_info->authorizer_access_token);
    }
}
