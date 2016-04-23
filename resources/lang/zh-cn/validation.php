<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 必须接受',
    'active_url'           => ':attribute 不是正确的URL.',
    'after'                => ':attribute 需要是一个:date之后的日期',
    'alpha'                => ':attribute 只能包含字母',
    'alpha_dash'           => ':attribute 只能包含字母,数字和下划线',
    'alpha_num'            => ':attribute 智能包含字母和数字',
    'array'                => ':attribute 必须是一个数组',
    'before'               => ':attribute 必须是一个:date之前的日期',
    'between'              => [
        'numeric' => ':attribute 必须大于:min 且小于 :max',
        'file'    => ':attribute 必须大于 :min 且小于 :max kb',
        'string'  => ':attribute 必须多于 :min 且少于 :max 个字符',
        'array'   => ':attribute 必须有大于 :min 且少于 :max 个条目',
    ],
    'boolean'              => ':attribute 字段必须为 true 或 false.',
    'confirmed'            => ':attribute 两次输入不一致',
    'date'                 => ':attribute 不是正确的日期格式',
    'date_format'          => ':attribute 与 :format格式不符',
    'different'            => ':attribute 和 :other 必须不同',
    'digits'               => ':attribute 必须为 :digits 位',
    'digits_between'       => ':attribute 必须从 :min 到 :max位',
    'email'                => ':attribute 必须为正确的邮箱地址',
    'filled'               => ':attribute 必须填写',
    'exists'               => '您选择的 :attribute 非法',
    'image'                => ':attribute 必须为图片格式',
    'in'                   => '您选择的 :attribute 非法',
    'integer'              => ':attribute 必须为整数',
    'ip'                   => ':attribute 必须为合法的IP地址',
    'max'                  => [
        'numeric' => ':attribute 不能大于 :max.',
        'file'    => ':attribute 不能大于 :max kb',
        'string'  => ':attribute 不能多于 :max 个字符',
        'array'   => ':attribute 不能多于 :max 个条目',
    ],
    'mimes'                => ':attribute 必须为 :values 类型文件',
    'min'                  => [
        'numeric' => ':attribute 不能小于 :min.',
        'file'    => ':attribute 不能小于 :min kb',
        'string'  => ':attribute不能少于 :min 个字符',
        'array'   => ':attribute 至少需要 :min 个条目',
    ],
    'not_in'               => '选择的 :attribute 非法',
    'numeric'              => ':attribute 必须是一个数字',
    'regex'                => ':attribute 格式错误',
    'required'             => ':attribute 字段需要填写',
    'required_if'          => '当 :other 是 :value时,:attribute 需要填写',
    'required_with'        => ':attribute 字段需要填写',
    'required_with_all'    => ':attribute 字段需要填写',
    'required_without'     => ':attribute 字段需要填写',
    'required_without_all' => ':attribute 字段需要填写',
    'same'                 => ':attribute 和 :other 需要一致',
    'size'                 => [
        'numeric' => ':attribute 必须为 :size.',
        'file'    => ':attribute 必须为 :size kb',
        'string'  => ':attribute 必须为 :size 个字符',
        'array'   => ':attribute 必须包含 :size 个条目',
    ],
    'string'               => ':attribute 必须为字符',
    'timezone'             => ':attribute 必须为合法时区',
    'unique'               => ':attribute 已经被使用过了',
    'url'                  => ':attribute 格式不正确',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
