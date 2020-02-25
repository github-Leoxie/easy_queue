<?php
namespace app\common;

use app\common\exception\ApiException;
use app\common\pcntl\Process;
use core\lib\Config;

/**
 * Class Tools
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common
 */
class Tools
{
    /**
     * 密码加密
     * @param $password
     * @return string
     */
    public static function passwordEncry($password): string {
        return sha1(md5($password));
    }

    public static function fixHttpType($url): string {
        $url = str_replace(' ','',$url);
        if (!(strpos($url, 'http://') === 0) && !(strpos($url, 'https://') === 0)) {
            $url = 'http://' . $url;
        }
        return $url;
    }

    /**
     * 先简单拷贝一个方法过来，后面在封装类，只有3天时间
     * @param $url
     * @param $paramStr
     * @param int $timeOut
     * @param array $headers
     * @param string $method
     * @return bool|string
     */
    public static function HttpPostJson($url, $paramStr, $timeOut = 2, $headers = [],$method='POST') {

        $url = self::fixHttpType($url);
        if(empty($headers)){
            $headers = ['Content-Type: application/json'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if(!empty($paramStr)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramStr);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new ApiException('curl server error' . curl_error($ch));
        }
        curl_close($ch);
        return $ret;
    }
}