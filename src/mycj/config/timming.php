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
    'aa' => [
        'id'     => 'aa',
        'status' => '0',
        'name'   => 'aa',
        'des'    => '采集今日数据',
        'file'   => 'collect',
        'param'  => 'ac=cjday&xt=1&ct=&rday=24&cjflag=tv6_com&cjurl=http://cj2.tv6.com/mox/inc/youku.php',
        'weeks'  => '1,2,3,4,5,6,0',
        'hours'  => '00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23',
    ],
    'bb' => [
        'status'  => '0',
        'name'    => 'bb',
        'des'     => '生成首页',
        'file'    => 'make',
        'param'   => 'ac=index',
        'weeks'   => '1,2,3,4,5,6,0',
        'hours'   => '00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23',
        'id'      => 'bb',
        'runtime' => 1535348998,
    ],
];
