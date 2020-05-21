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

header('Content-type:text/json');
include 'data.php';
if (count($faves) == 0) {
    $code = 'var faves;';
} else {
    $data_arr['faves'] = [
        'head' => '我的收藏',
        'tips' => '可将常用的资源站，收藏到这里，如果api无法采集，请取消收藏后，重新收藏！',
        'rows' => $faves,
    ];
    $datainfo = json_encode($data_arr, true);
    $code     = 'var faves = ' . $datainfo . '';
}
echo $code;
