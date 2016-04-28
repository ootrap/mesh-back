<?php
    namespace Star\wechat;

    use GuzzleHttp\Client;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;
    use Star\wechat\support\AES;
    use Star\wechat\support\XML;

class WeOpen
{
    protected $client;
    protected $component_access_token;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->appId = config('wechat.app_id');
        $this->secureKey  = config('wechat.secret');
        $this->token  = config('wechat.token');
        $this->aeskey = config('wechat.aes_key');
    }

    /**
    * 供微信平台填写的验证url监听并解密其Ticket
    * @return [Laravel Cache] [将ticket写入缓存]
    */
    public function auth()
    {
        $this->getComponentVerifyTicket();
        $this->getComponenAccessToken();
        $this->getPreAuthCode();
    }
     /**
     * 引导已注册用户绑定自己的公众号
     * @return [type] [description]
     */
    public function bindMP()
    {
        // 绑定公众号需要视图中的超链接加入pre_auth_code参数
        // https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid
        // =wxf50497c041778a84&pre_auth_code={{$code}}
        // &redirect_uri=http://w.stario.net/callback
        $this->getPreAuthCode();
    }

    /**
     * 对应第三方平台中填写的回调地址，并根据传入的验证码参数获取基础信息
     */
    public function callback($authcode)
    {
        $this->getAuthorizerAccessToken($authcode);
        $this->fetchInfo();
    }

    /**
   * STEP 1:获取微信POST过来的授权数据后取得component_verify_ticket并缓存
   */

    private function getComponentVerifyTicket()
    {
        $rawPostData             = file_get_contents("php://input");
        $encrypt                 = XML::parse($rawPostData)['Encrypt'];
        $rebuild                 = XML::build(['ToUserName'=>'toUser','Encrypt'=>$encrypt]);
        $pc  = new AES($this->aeskey, $this->appId, $this->token);
        $decryptedMsg            = $pc->decode($rebuild);
        $component_verify_ticket = XML::parse($decryptedMsg)['ComponentVerifyTicket'];
        if ($component_verify_ticket) {
            echo "success";
        }
        Cache::forever('ticket', $component_verify_ticket);
    }

    /**
    * STEP 2:获取component_access_token
    */
    private function getComponenAccessToken()
    {
        $ticket = Cache::get('ticket');
        if (empty ($ticket)) {
            return json_encode([
                    'message'=>'微信尚未发送数据,请等待10分钟'
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
        Cache::forever('code', $preAuthCode);
    }
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

    //使用authorizer_refresh_token刷新authorizer_access_token
    private function refreshAuthorizeToken()
    {
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='
                    .Cache::get('component_access_token');

        $result = $this->client->post($uri, ['json'=>[
                "component_appid" => $this->appId,
                "authorizer_appid" => Cache::get('authorizerAppId'),
                "authorizer_refresh_token" => Cache::get('refreshKey')
                ]]);
        $data = json_decode($result->getBody());
        Cache::forever('authorizer_access_token', $data->authorizer_access_token);
    }

    /**
     * 获取公众号的基本信息
     * authorizer_info:{
     * nick_name head_img
     * service_type_info: 0代表订阅号 1代表老账号升级的订阅号 2代表服务号
     * verify_type_info: 授权方认证类型，-1代表未认证，0代表微信认证，1代表新浪微博认证，
     * 2代表腾讯微博认证，3代表已资质认证通过但还未通过名称认证，
     * 4代表已资质认证通过、还未通过名称认证，但通过了新浪微博认证，
     * 5代表已资质认证通过、还未通过名称认证，但通过了腾讯微博认证
     * user_name: 授权方公众号的原始ID
     * alias:授权方公众号所设置的微信号，可能为空
     * business_info:用以了解以下功能的开通状况（0代表未开通，1代表已开通）：
                             open_store:是否开通微信门店功能
                             open_scan:是否开通微信扫商品功能
                             open_pay:是否开通微信支付功能
                             open_card:是否开通微信卡券功能
                             open_shake:是否开通微信摇一摇功能
     *  qrcode_url: 二维码图片的URL，开发者最好自行也进行保存（只能在微信客户端访问）
     * }
     * authorization_info:{
     * appid: 授权方appid
     * func_info: 公众号授权给开发者的权限集列表，ID为1到15时分别代表：
                        消息管理权限
                        用户管理权限
                        帐号服务权限
                        网页服务权限
                        微信小店权限
                        微信多客服权限
                        群发与通知权限
                        微信卡券权限
                        微信扫一扫权限
                        微信连WIFI权限
                        素材管理权限
                        微信摇周边权限
                        微信门店权限
                        微信支付权限
                        自定义菜单权限
     * }
     * @return [type] [description]
     */
    private function fetchInfo()
    {
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token='
                    .Cache::get('component_access_token');

        $result = $this->client->post($uri, ['json'=>[
                "component_appid" => $this->appId,
                "authorizer_appid" => Cache::get('authorizerAppId'),
                ]]);
        $data = json_decode($result->getBody());
        echo json_encode($data);
        //TODO qrcode_url 入库保存
    }

    
    private function fetchOptionInfo($optionName)
    {
        $uri = 'https://api.weixin.qq.com/cgi-bin/component/ api_get_authorizer_option?component_access_token'
                    .Cache::get('component_access_token');

        $result = $this->client->post($uri, ['json'=>[
                "component_appid" => $this->appId,
                "authorizer_appid" => Cache::get('authorizerAppId'),
                "option_name" => $optionName
                ]]);
    }
}
