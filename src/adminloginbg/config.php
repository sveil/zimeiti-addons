<?php
// +----------------------------------------------------------------------
// | Addons for sveil/zimeiti-cms
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2020 http://sveil.com All rights reserved.
// +----------------------------------------------------------------------
// | License ( http://www.gnu.org/licenses )
// +----------------------------------------------------------------------
// | gitee：https://gitee.com/sveil/zimeiti-addons
// | github：https://github.com/sveil/zimeiti-addons
// +----------------------------------------------------------------------

return [
    0 => [
        'name'    => 'mode',
        'title'   => '模式',
        'type'    => 'radio',
        'content' => [
            'fixed'  => '固定',
            'random' => '每次随机',
            'daily'  => '每日切换',
        ],
        'value'   => 'random',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '根据自身爱好选择',
        'ok'      => '',
        'extend'  => '',
    ],
    1 => [
        'name'    => 'image',
        'title'   => '固定背景图',
        'type'    => 'image',
        'content' => [
        ],
        'value'   => 'upload/addon/20180419/60766c894977757d436b641f97356b40.jpg',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '请选择文件...',
        'ok'      => '',
        'extend'  => '',
    ],
];
