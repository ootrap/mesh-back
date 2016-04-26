<?php

return [

       /*
         * -----------------------------------
         * bechSMS
         * -----------------------------------
         * website:http://http://sms.bechtech.cn/
         */
        'Settings' => [
            'url' => env('BECH_URL', 'your-url'),
            'akey' => env('BECH_AKEY', 'your-access-key'),
            'skey' => env('BECH_SKEY', 'your-secret-key')
        ],
        'ErrorCode' => [
            '01' => '短信已成功发送,请留意查收',
            '04' => '短信余额不足',
            '05' => '短信内容含有限制词',
            '06' => '短信内容不合法',
            '07' => '发送频繁，请过段时间再试',
            '13' => '用户账户已被冻结',
            '98' => '发送内容与免审模板不一致',
            '99' => '短信服务器升级中，请稍后再试',
            '100' => '短信服务器例行维护，请稍后再试'
        ],
        'Templates' => [
            'authcode' => '【微脉事】您的验证码是：{code}，请在5分钟内完成验证',
        ]
];
