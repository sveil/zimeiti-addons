<?php

// +----------------------------------------------------------------------
// | Addons for sveil/zimeiti-cms
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2020 KuangJia Inc.
// +----------------------------------------------------------------------
// | Website: https://sveil.com
// +----------------------------------------------------------------------
// | License ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee：https://gitee.com/sveil/zimeiti-addons
// | github：https://github.com/sveil/zimeiti-addons
// +----------------------------------------------------------------------

header('Content-Type:text/html;charset=utf-8');

function macArr2file($f, $arr = '')
{
    if (is_array($arr)) {
        $con = var_export($arr, true);
    } else {
        $con = $arr;
    }
    $con = "<?php\nreturn $con;";
    macWriteFile($f, $con);
}

function macWriteFile($f, $c = '')
{
    $dir = dirname($f);
    if (!is_dir($dir)) {
        macMkdirss($dir);
    }
    return @file_put_contents($f, $c);
}

function macMkdirss($path, $mode = 0777)
{
    if (!is_dir(dirname($path))) {
        macMkdirss(dirname($path));
    }
    if (!file_exists($path)) {
        return mkdir($path, $mode);
    }
    return true;
}

function geturl($url, $timeout = 10)
{
    $user_agent = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
    $curl       = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($curl, CURLOPT_REFERER, $url);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');
    curl_setopt($curl, CURLOPT_ENCODING, '');
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}

class MainDb
{
    public static function word($name)
    {global $$name;
        $key = var_export($$name, true);return "$$name=$key;\r\n";}
    public static function save($file = "./cache/data.php")
    {
        $data  = preg_replace('!\/\/.*?[\r\n]|\/\*[\S\s]*?\*\/!', '', preg_replace('/(?:\<\?php|\?\>)/', '', file_get_contents($file)));
        $lines = preg_split('/[;]+/s', $data, -1, PREG_SPLIT_NO_EMPTY);
        $word  = "<?php\r\n";
        foreach ($lines as $value) {
            $value = trim($value);
            if ($value !== '' && substr($value, 0, 1) === '$') {
                $line = explode('=', $value, 2);
                $name = str_replace('$', '', trim($line[0]));
                $word .= self::word($name);
            }
        }
        return file_put_contents($file, $word);
    }
}
