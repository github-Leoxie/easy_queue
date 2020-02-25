<?php


namespace core\lib;

use core\lib\exception\EasyQueueException;

/**
 * 本地存储类,暂不考虑独占锁的问题，目前是后台针对文件json做改变
 * Class Storage
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class Storage
{
    public static function read($filePath): array {
        if(!file_exists(dirname($filePath))){
            throw new EasyQueueException(Language::FILE_NOT_FOUND[LANG]);
        }
        return (array)json_decode(file_get_contents($filePath),true);
    }

    private static function write(string $filePath,string $contents): bool {
        return file_put_contents($filePath,$contents);
    }


    public static function save(string $filePath,array $data,bool $isAppend = true): bool {

        $contents = $isAppend === true?self::read($filePath):[];

        foreach($data as $saveKey=>$saveItem){
            $contents[$saveKey] = $saveItem;
        }

        return self::write($filePath,json_encode($contents,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }


}