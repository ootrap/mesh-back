<?php 
namespace Star\sms;

use Illuminate\Support\Facades\Cache;
use Star\utils\RandomNum;

/**
 * 生成短信内容
 */
class MakeContent
{
    use randomNum;

    private $content;

   /**
    * 生成验证码后根据配置模板生成内容，并添加缓存（存储5分钟）
    * @param  [type]  $key   [缓存key，如手机号码]
    * @param  integer $digit [验证码位数]
    */
    public function makeCode($key, $digit = 6)
    {
    	$code = $this->randomNum($digit);
    	Cache::put($key, $code, 5);
    	$pattern = '/{\w+}/';
        	$content = preg_replace($pattern, $code, \Config::get('sms.Templates.authcode'));
        	return $content;
    }
    // public function makeNotify()
    // {
    	
    // }
    // public function makeError()
    // {
    	
    // }
}
