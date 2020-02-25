<?php
namespace core\lib;

use core\lib\exception\EasyQueueException;

/**
 * 配置类
 * Class Config
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class Config
{
    private static $config = null;

    private static function init(): array {

        if(self::$config === null){//未初始化过，初始化完成的都是数组，无论get/set操作
            self::$config = (array)Loader::requireFile(APP_PATH.'config'.EXT);
        }

        return self::$config;
    }

    /**
     * 获取值
     * @param $key
     * @return bool|mixed
     */
    public static function get($key){
        self::init();

        $keyArr = self::parseKey($key);

        return self::getRecursion(self::$config,0,$keyArr);
    }

    /**
     * 设置值
     * @param $key
     * @param $value
     * @return bool
     */
    public static function set($key,$value): bool {
        self::init();

        $keyArr = self::parseKey($key);

        self::setRecursion(0,$keyArr,$value);

        return true;
    }

    /**
     * 解析key
     * @param $key
     * @return array
     */
    private static function parseKey($key): array {

        if(empty($key)){
            throw new EasyQueueException(Language::CONFIG_KEY_EMPTY[LANG]);
        }

        $keyArr = explode('.',$key);
        if(count($keyArr) > 5){
            throw new EasyQueueException(Language::CONFIG_OVER_5_LEVEL[LANG]);
        }

        return $keyArr;
    }

    /**
     * 无限递归赋值
     * @param $config
     * @param $index
     * @param $keyArr
     * @param $value
     * @return mixed
     */
    private static function setRecursion(int $index,array $keyArr,$value){
        if(!isset(self::$config[$keyArr[$index]])){
            self::$config[$keyArr[$index]] = [];
        }

        if($index === count($keyArr)-1){
            self::$config[$keyArr[$index]] = $value;
            return true;
        }

        return self::setRecursion(self::$config[$keyArr[$index]],$index+1,$keyArr,$value);
    }

    /**
     * 无限递归获取值
     * @param $config
     * @param $index
     * @param $keyArr
     * @return bool|mixed
     */
    private static function getRecursion($config,int $index,array $keyArr){

        if(!isset($config[$keyArr[$index]])){
            return false;
        }

        if($index === count($keyArr)-1){
            return $config[$keyArr[$index]];
        }

        return self::getRecursion($config[$keyArr[$index]],$index+1,$keyArr);
    }
}