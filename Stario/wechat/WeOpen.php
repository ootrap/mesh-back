<?php
    namespace Star\wechat;

    use GuzzleHttp\Client;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;
    use Star\wechat\support\AES;
    use Star\wechat\support\XML;

class WeOpen
{
    public static $client;
    public static $appId;
    public static $secureKey;
    public static $token;
    public static $aeskey;

    /**
     * 对应第三方平台中填写的回调地址，并根据传入的验证码参数获取基础信息
     */
    // public function callback($authcode)
    // {
    //     $this->getAuthorizerAccessToken($authcode);
    //     $this->fetchInfo();
    // }

    /**
   * STEP 1:获取微信POST过来的授权数据后取得component_verify_ticket并缓存
   */

    public static function getComponentVerifyTicket()
    {
        $rawPostData             = file_get_contents("php://input");
        $encrypt                 = XML::parse($rawPostData)['Encrypt'];
        $rebuild                 = XML::build(['ToUserName'=>'toUser','Encrypt'=>$encrypt]);
        $pc  = new AES(self::$aeskey, self::$appId, self::$token);
        $decryptedMsg            = $pc->decode($rebuild);
        $component_verify_ticket = XML::parse($decryptedMsg)['ComponentVerifyTicket'];
        if ($component_verify_ticket) {
            echo "success";
        }
        Cache::forever('wx_ticket', $component_verify_ticket);
    }

    /**
    * STEP 2:获取component_access_token
    * 该令牌有效期2小时，并且调用不是无限制的，所以需要每隔2小时前刷新一下
    */
    public static function getComponenAccessToken()
    {
        $ticket = Cache::get('wx_ticket');
        if (empty ($ticket)) {
            return  json_encode([
                    'message'=>'微信尚未发送数据,请等待10分钟'
                ]);
        }
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
        $result = self::$client->post($uri, ['json'=>["component_appid" => self::$appId,
                "component_appsecret" => self::$secureKey,
                "component_verify_ticket" => $ticket
            ]]);
        $data = json_decode($result->getBody());
        Cache::forever('wx_component_access_token', $data->{'component_access_token'});
    }

    /**
     * STEP 3:获取预授权码，存入缓存
     */
    public static function getPreAuthCode()
    {
        if (empty ($component_access_token = Cache::get('wx_component_access_token'))) {
            self::getComponenAccessToken();
            $component_access_token = Cache::get('wx_component_access_token');
        }
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='
                    .$component_access_token;
        $result = self::$client->post($uri, ['json'=>["component_appid" => self::$appId]]);
        $data = json_decode($result->getBody());
        if (empty($data->{'pre_auth_code'})) {
            self::getComponentVerifyTicket();
            self::getComponenAccessToken();
        }
        $preAuthCode = $data->{'pre_auth_code'};
        Cache::forever('wx_preAuthCode', $preAuthCode);
        return $preAuthCode;
    }
    /**
     * STEP 4: 换取authorizer_access_token和authorizer_refresh_token
     */
    public static function getAuthorizerAccessToken($authcode)
    {
        $componentAccessToken = Cache::get('wx_component_access_token');
        if (empty($componentAccessToken)) {
             self::getComponenAccessToken();
        }
         $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='
                    .Cache::get('wx_component_access_token');

        $result = self::$client->post($uri, ['json'=>[
                "component_appid" => self::$appId,
                "authorization_code" => $authcode
                ]]);
        return  json_decode($result->getBody());
    // // TODO !!!!需要替换改为写入数据库！！！！！！！！！！！！！！！！！！！！！！
    //     Cache::forever('refreshToken', $data->authorization_info->authorizer_refresh_token);
    //     //TODO 这是授权的大众公众号的APPID,修改传递方式
    //     Cache::forever('authorizerAppId', $data->authorization_info->authorizer_appid);
    //     Cache::forever('authorizer_access_token', $data->authorization_info->authorizer_access_token);
    }

    //使用authorizer_refresh_token刷新authorizer_access_token
    public static function refreshAuthorizeToken()
    {
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='
                    .Cache::get('wx_component_access_token');

        $result = self::$client->post($uri, ['json'=>[
                "component_appid" => self::$appId,
                "authorizer_appid" => Cache::get('wx_authorizerAppId'),
                "authorizer_refresh_token" => Cache::get('wx_refreshKey')
                ]]);
        $data = json_decode($result->getBody());
        Cache::forever('wx_authorizer_access_token', $data->authorizer_access_token);
    }

    /**
     * 获取公众号的基本信息
     */
    public static function fetchInfo()
    {
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token='
                    .Cache::get('wx_component_access_token');

        $result = $this->client->post($uri, ['json'=>[
                "component_appid" => $this->appId,
                "authorizer_appid" => Cache::get('wx_authorizerAppId'),
                ]]);
        $data = json_decode($result->getBody());
        echo json_encode($data);
        //TODO qrcode_url 入库保存
    }
    
    public function fetchOptionInfo($optionName)
    {
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/ api_get_authorizer_option?component_access_token'
                    .Cache::get('wx_component_access_token');

        $result = $this->client->post($uri, ['json'=>[
                "component_appid" => $this->appId,
                "authorizer_appid" => Cache::get('wx_authorizerAppId'),
                "option_name" => $optionName
                ]]);
    }
}

    WeOpen::$client = new Client;
    WeOpen::$appId = config('wechat.app_id');
    WeOpen::$secureKey  = config('wechat.secret');
    WeOpen::$token  = config('wechat.token');
    WeOpen::$aeskey = config('wechat.aes_key');
