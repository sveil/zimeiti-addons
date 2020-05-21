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

error_reporting(0);
require 'main.class.php';
header('Content-type:text/json');
if (empty($_COOKIE['admin_name']) || $_COOKIE['admin_name'] !== $_GET['name']) {header("location:/");exit();}
$id = $_GET['id'];
if ($id == 'sea') {
    $name = $_POST['name'];
    $apis = $_POST['apis'];
    echo SearchKeyword($apis, $name);
} elseif ($id == 'tim') {
    $name     = $_POST['name'];
    $flag     = $_POST['flag'];
    $param    = $_POST['param'];
    $tim_path = '../../application/extra/timming.php';
    $tim      = require $tim_path;
    if (!is_array($tim) || !$tim) {
        $timming = './config/timming.php';
        copy($timming, $tim_path);
    }
    echo timming($name, $flag, $param);
} elseif ($id == 'play') {
    $arr = $_POST['play'];
    echo AddPlayer($arr);
} elseif ($id == 'down') {
    $down_path = '../../application/extra/voddowner.php';
    $down      = require $down_path;
    if (!is_array($down) || !$down) {
        $voddowner = './config/voddowner.php';
        copy($voddowner, $down_path);
    }
    $flag = $_POST['flag'];
    echo AddDowner($flag);
} elseif ($id == 'all_player') {
    $type = $_POST['type'];
    $ids  = $_POST['ids'];
    echo AllPlayer($ids, $type);
} elseif ($id == 'all_jiekou') {
    $value = $_POST['value'];
    $ids   = $_POST['ids'];
    $col   = $_POST['col'];
    echo AllJiekou($ids, $value, $col);
} elseif ($id == 'playerjs') {
    $player_path = '../../static/js/player.js';
    $playerjs    = './config/player.js';
    if (copy($playerjs, $player_path)) {
        exit(json_encode(['code' => 200, 'msg' => '修复成功', 'icon' => 6], true));
    } else {
        exit(json_encode(['code' => 200, 'msg' => '修复失败，写入权限不足！', 'icon' => 5], true));
    }
} elseif ($id == 'faves') {
    if (file_exists('./cache/data.php')) {
        include './cache/data.php';
        $count = count($faves);
        if ($count >= 10) {
            exit(json_encode(['code' => 201, 'msg' => "收藏失败，最多只能收藏10个！<br>请删除不常用的，再收藏！"]));
        }
    }
    $cj_data = './cache/data.php';
    $faves[] = [
        'flag'   => $_POST["flag"],
        'name'   => $_POST["name"],
        'rema'   => $_POST["rema"],
        'apis'   => $_POST["apis"],
        'tips'   => '<span class="layui-badge layui-bg-green">我的收藏</span>',
        'coll'   => $_POST["coll"],
        'zylink' => $_POST["zylink"],
    ];
    if (Main_db::save($cj_data)) {
        exit(json_encode(['code' => 200, 'msg' => "收藏成功"]));
    } else {
        exit(json_encode(['code' => 202, 'msg' => "收藏失败!请检查/addons/mycj/cache/文件权限"]));
    }
} elseif ($id == 'del_faves') {
    include './cache/data.php';
    $id = $_POST["id"];
    unset($faves[$id]);
    $num = count($faves);
    if ($num == 0) {
        $fav = [];
    } else {
        foreach ($faves as $v) {
            $fav[] = $v;
        }
        array_values($fav);
    }
    $faves = $fav;
    if (Main_db::save('./cache/data.php')) {
        exit(json_encode(['code' => 200, 'msg' => "取消收藏成功"]));
    } else {
        exit(json_encode(['code' => 202, 'msg' => "取消失败，读写权限不足！"]));
    }
} else {
    exit(json_encode(['code' => 404, 'msg' => '参数错误'], true));
}

function AllJiekou($ids, $value, $col)
{
    $vodplayer = '../../application/extra/vodplayer.php';
    $list      = require $vodplayer;
    if (strpos($ids, ',') !== false) {
        $ids = explode(",", $ids);
    } else {
        $ids = [$ids];
    }
    $code = "MacPlayer.Html='<iframe width=\"100%\" height=\"'+MacPlayer.Height+'\" src=\"" . $value . "'+MacPlayer.PlayUrl+'\" frameborder=\"0\" allowfullscreen=\"true\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe>';MacPlayer.Show();";
    foreach ($ids as $id) {
        $list[$id]['ps'] = 1;
        $list[$id][$col] = $value;
        $player          = fwrite(fopen('../../static/player/' . $id . '.js', 'wb'), $code);
    }
    if (!file_exists('../../static/player/parse.js') || $list[$id]['ps'] == 1) {
        $parse_code = "MacPlayer.Html='<iframe width=\"100%\" height=\"'+MacPlayer.Height+'\" src=\"'+MacPlayer.Parse+MacPlayer.PlayUrl+'\" frameborder=\"0\" allowfullscreen=\"true\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe>';MacPlayer.Show();";
        $parsejs    = fwrite(fopen('../../static/player/parse.js', 'wb'), $parse_code);
    }
    $res = mac_arr2file($vodplayer, $list);
    if ($res === false) {
        return json_encode(['code' => 201, 'msg' => '修改失败，文件写入权限不足'], true);
    } else {
        return json_encode(['code' => 200, 'msg' => '修改成功！'], true);
    }
}

function AllPlayer($ids, $type)
{
    if (strpos($ids, ',') !== false) {
        $ids = explode(",", $ids);
    }
    $code = "MacPlayer.Html='<iframe width=\"100%\" height=\"'+MacPlayer.Height+'\" src=\"'+maccms.path+'/static/player/" . $type . ".html\" frameborder=\"0\" allowfullscreen=\"true\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe>';MacPlayer.Show();";
    foreach ($ids as $id) {
        $player = fwrite(fopen('../../static/player/' . $id . '.js', 'wb'), $code);
    }
    if ($player === false) {
        return json_encode(['code' => 201, 'msg' => '安装失败，文件写入权限不足'], true);
    } else {
        return json_encode(['code' => 200, 'msg' => '安装成功！'], true);
    }
}

function AddPlayer($arr)
{
    $vodplayer = '../../application/extra/vodplayer.php';
    $list      = require $vodplayer;
    $num       = count($arr['from']);
    for ($i = 0; $i < $num; $i++) {
        $ps    = 1;
        $parse = $arr['apis'][$i];
        if (strpos($arr['apis'][$i], '/static/player/') !== false) {
            $ps    = 0;
            $parse = '';
            $code  = "MacPlayer.Html='<iframe width=\"100%\" height=\"'+MacPlayer.Height+'\" src=\"" . $arr['apis'][$i] . "\" frameborder=\"0\" allowfullscreen=\"true\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe>';MacPlayer.Show();";
        } else {
            if ($arr['apis'][$i] == '') {$ps = 0;
                $parse                         = '';}
            $code = "MacPlayer.Html='<iframe width=\"100%\" height=\"'+MacPlayer.Height+'\" src=\"" . $arr['apis'][$i] . "'+MacPlayer.PlayUrl+'\" frameborder=\"0\" allowfullscreen=\"true\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe>';MacPlayer.Show();";
        }
        $play[] = [
            $arr['from'][$i] => [
                'status' => '1',
                'from'   => $arr['from'][$i],
                'show'   => $arr['name'][$i],
                'des'    => $arr['des'][$i],
                'target' => '_self',
                'ps'     => $ps,
                'parse'  => $parse,
                'sort'   => $arr['sort'][$i],
                'tip'    => $arr['tip'][$i],
                'id'     => $arr['from'][$i],
            ],
        ];
        $player = fwrite(fopen('../../static/player/' . $arr['from'][$i] . '.js', 'wb'), $code);
    }
    if (!file_exists('../../static/player/parse.js') || $ps == 1) {
        $parse_code = "MacPlayer.Html='<iframe width=\"100%\" height=\"'+MacPlayer.Height+'\" src=\"'+MacPlayer.Parse+MacPlayer.PlayUrl+'\" frameborder=\"0\" allowfullscreen=\"true\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe>';MacPlayer.Show();";
        $player     = fwrite(fopen('../../static/player/parse.js', 'wb'), $parse_code);
    }
    foreach ($play as $data) {
        $list = array_merge($list, $data);
    }
    foreach ($list as $k => &$v) {
        $sorts[] = $v['sort'];
    }
    array_multisort($sorts, SORT_DESC, SORT_FLAG_CASE, $list);
    $res = mac_arr2file($vodplayer, $list);
    if ($res === false) {
        return json_encode(['code' => 201, 'msg' => '添加失败，文件写入权限不足'], true);
    } else {
        return json_encode(['code' => 200, 'msg' => '添加成功！'], true);
    }
}

function AddDowner($flag)
{
    $desc = 'des提示信息';
    $tips = 'tip提示信息';
    $list = require '../../application/extra/voddowner.php';
    if (strpos($flag, '|') !== false) {
        $flag = explode("|", $flag);
        foreach ($flag as $fl) {
            $fla     = explode(",", $fl);
            $flags[] = $fla;
        }
        foreach ($flags as $from) {
            $show     = $from[0];
            $playfrom = $from[1];
            $sort     = $from[2];
            $data[]   = [
                $playfrom => [
                    'status' => '1',
                    'from'   => $playfrom,
                    'show'   => $show,
                    'des'    => $desc,
                    'ps'     => '0',
                    'parse'  => '',
                    'sort'   => $sort,
                    'tip'    => $tips,
                    'id'     => $playfrom,
                ],
            ];
        }
        foreach ($data as $data) {
            $list = array_merge($list, $data);
        }
    } else {
        $flag   = explode(",", $flag);
        $data[] = [
            $flag[1] => [
                'status' => '1',
                'from'   => $flag[1],
                'show'   => $flag[0],
                'des'    => $desc,
                'ps'     => '0',
                'parse'  => '',
                'sort'   => $flag[2],
                'tip'    => $tips,
                'id'     => $flag[1],
            ],
        ];
        $list = array_merge($list, $data[0]);
    }
    foreach ($list as $k => &$v) {
        $sorts[] = $v['sort'];
    }
    array_multisort($sorts, SORT_DESC, SORT_FLAG_CASE, $list);
    $res = mac_arr2file('../../application/extra/voddowner.php', $list);
    if ($res === false) {
        return json_encode(['code' => 201, 'msg' => '添加失败，文件写入权限不足'], true);
    } else {
        return json_encode(['code' => 200, 'msg' => '添加成功'], true);
    }
}

function timming($name, $flag, $param)
{
    $list   = require '../../application/extra/timming.php';
    $data[] = [
        $flag => [
            'id'     => $flag,
            'status' => '1',
            'name'   => $flag,
            'des'    => $name,
            'file'   => 'collect',
            'param'  => $param,
            'weeks'  => '1,2,3,4,5,6,0',
            'hours'  => '00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23',
        ],
    ];
    $list = array_merge($list, $data[0]);
    $res  = mac_arr2file('../../application/extra/timming.php', $list);
    if ($res === false) {
        return json_encode(['code' => 201, 'msg' => '添加失败，定时任务配置写入权限不足'], true);
    } else {
        return json_encode(['code' => 200, 'msg' => '添加成功'], true);
    }
}

function SearchKeyword($apis, $name)
{
    $html = geturl($apis . "?wd=" . $name);
    if (empty($html)) {return json_encode([], true);exit();}
    $xml = simplexml_load_string($html, 'SimpleXMLElement', LIBXML_NOCDATA);
    if (empty($xml)) {return json_encode([], true);exit();}
    $xml         = json_decode(json_encode($xml), true);
    $recordcount = $xml['list']['@attributes']['recordcount'];
    if ($recordcount == 0) {return json_encode([], true);exit();}
    if ($recordcount == 1) {
        $data = [
            $xml['list']['video'],
        ];
    } else {
        $data = $xml['list']['video'];
    }
    return json_encode($data, true);
}
