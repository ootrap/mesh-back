<?php 
namespace Star\wechat\framework;

trait ComponentVerifyTicketTrait
{
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
        Cache::forever('wx_ticket', $component_verify_ticket);
    }
}
