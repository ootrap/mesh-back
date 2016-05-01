<?php 
namespace Star\wechat\support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Star\wechat\support\ErrorCode;

class AES {

    private $key;

    private $appId;

    private $token;

    private $iv;

    public function __construct($key, $appId, $token)
    {
        $this->key = base64_decode($key.'=');
        if ($this->key === false || strlen($this->key) != 32) {
            Log::error(['Illegal AES Key', ErrorCode::$IllegalAesKey]);
        }
        $this->iv = substr($this->key, 0, 16);
        $this->appId = $appId;
        $this->token = $token;
    }

    /**
     * @param string $originalXML 原始的XML消息
     * @return string 符合微信格式的加密的XML
     * @throws \Exception
     */
    public function encode($originalXML)
    {
        $toEncodeData = openssl_random_pseudo_bytes(16) . pack('N', strlen($originalXML)) . $originalXML . $this->appId;

        $toEncodeData = $this->pkcs7pad($toEncodeData, 32);
        $encoded = openssl_encrypt($toEncodeData, 'AES-256-CBC', $this->key, OPENSSL_ZERO_PADDING, substr($this->key, 0, 16));

        if ($encoded === false) {
            Log::error(['Encrypt AES Error', ErrorCode::$EncryptAESError]);
        }

        $timestamp = time();
        $nonce = rand(100000000, 999999999);
        $signature = $this->signature($timestamp, $nonce, $encoded);

        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<xml><Encrypt><![CDATA[$encoded]]></Encrypt><MsgSignature><![CDATA[$signature]]></MsgSignature><TimeStamp>$timestamp</TimeStamp><Nonce><![CDATA[$nonce]]></Nonce></xml>\r\n";
    }

    public function decode($encryptedXML)
    {
        $stack = new \SplStack();
        $arr = array();
        $x = xml_parser_create('UTF-8');
        //考虑到PHP5.4以上版本才能用不补位的openssl函数，所以闭包是一定可以支持的。
        xml_set_element_handler($x, function ($x, $name) use ($stack) {
            $stack->push($name);
        }, function () use ($stack) {
            $stack->pop();
        });
        xml_set_character_data_handler($x, function ($x, $data) use (&$arr, $stack) {
            $name = strtolower($stack->top());
            $arr[$name] = $data;
        });
        if (!xml_parse($x, $encryptedXML, true)) {
            Log::error(['Parse XML Error', ErrorCode::$ParseXmlError]);
        }
        xml_parser_free($x);
        if (!isset($arr['encrypt'])) {
            return $encryptedXML;
        }

        $encrypt = $arr['encrypt'];
        $nonce = Cache::get('nonce');
        $timestamp = Cache::get('timestamp');
        $msgSignature = Cache::get('msgsignature');

        $signature = $this->signature($timestamp, $nonce, $encrypt);

        if ($msgSignature !== $signature) {
            Log::error(['Validate Signature Error', ErrorCode::$ValidateSignatureError]);
        }
        $decrypt = openssl_decrypt($encrypt, 'AES-256-CBC', $this->key, OPENSSL_ZERO_PADDING, substr($this->key, 0, 16));

        if ($decrypt === false) {
            Log::error(['Decrypt AES Error', ErrorCode::$DecryptAESError]);
        }
        $decrypt = $this->pkcs7unpad($decrypt, 32);

        $content = substr($decrypt, 16);
        $len = unpack("N", substr($content, 0, 4));
        $len = $len[1];
        $xml = substr($content, 4, $len);
        $fromAppId = substr($content, $len + 4);

        if ($fromAppId !== $this->appId) {
            Log::error(['Validate AppId Error', ErrorCode::$ValidateAppIdError]);
        }

        return $xml;
    }

    private function pkcs7pad($str, $blockSize = 16)
    {
        $padLen = $blockSize - strlen($str) % $blockSize;
        return $str . str_repeat(chr($padLen), $padLen);
    }

    private function pkcs7unpad($str, $blockSize = 16)
    {
        $padLen = ord(substr($str, -1));
        if ($padLen < 1 || $padLen > $blockSize) {
            return $str;
        }
        return substr($str, 0, -$padLen);
    }

    private function signature($timestamp, $nonce, $data)
    {
        $a = [$this->token, $timestamp, $nonce, $data];
        sort($a, SORT_STRING);
        return sha1(implode('', $a));
    }
}
