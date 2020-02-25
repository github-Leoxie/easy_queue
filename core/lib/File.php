<?php
namespace core\lib;

use core\lib\exception\ApiException;
use core\lib\exception\EasyQueueException;

/**
 * 文件处理类
 * Class File
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class File
{
    public static function create($filePath): bool {

        if (!file_exists($filePath) && !mkdir($filePath, 0777,true) && !is_dir($filePath)) {
            throw new EasyQueueException(sprintf('Directory "%s" was not created', $filePath));
        }

        return true;
    }
}