<?php
namespace Star\wechat\support;

use Star\wechat\support\ErrorCode;

/**
* 
*/
class WEAES
{
  
    public function __construct($key, $appId, $token)
    {
        $this->key = base64_decode($key.'=');
        if ($this->key === false || strlen($this->key) != 32) {
            // \Log::error(['Illegal AES Key', ErrorCode::$IllegalAesKey]);
            return ErrorCode::$IllegalAesKey;
        }
        $this->iv = substr($this->key, 0, 16);
        $this->appId = $appId;
        $this->token = $token;
    }

    public function encode($rawXML)
    {
        $toEncodeData = openssl_random_pseudo_bytes(16) . pack('N', strlen($rawXML)) . $rawXML . $this->appId;
        if ($encoded === false) {
            // \Log::error(['Encrypt AES Error', ErrorCode::$EncryptAESError]);
            return ErrorCode::$EncryptAESError;
        }
    }
}
