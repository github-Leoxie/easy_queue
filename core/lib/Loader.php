<?php
namespace core\lib;

use core\lib\exception\EasyQueueException;

/**
 * Class Loader
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 * 加载类
 */
class Loader
{
    const STRING_STRPOS_START = 0;//字符串开始的地方

    private static $psr4Alias = [
        'app\\'=>'application\\'
    ];

    public static function register(): void{

    }

    public static function autoload(): void{
        // 注册系统自动加载
        spl_autoload_register('\core\lib\Loader::registerClass', true, true);
    }

    private static function registerClass(string $class): void{
        self::findFile($class);
    }

    /**
     * 查找对应的类名，符合psr4/psr0规范的
     * @param string $class 要查找的类名
     * @return bool 只有加载成功，失败就会报异常
     */
    private static function findFile(string $class):bool {

        $newClass = $class;
        foreach(self::$psr4Alias as $psrPrefix=>$psrVal){
            if(strpos($class,$psrPrefix) === self::STRING_STRPOS_START){
                $len = strlen($psrPrefix);
                $newClass = $psrVal.substr($class,$len);
                break;
            }
        }

        $path = ROOT_PATH.$newClass.EXT;

        $path = str_replace('\\',DS,$path);

        if(is_file($path)){
            self::includeFile($path);
            return true;
        }

        throw new EasyQueueException($newClass.COLON.Language::FILE_OR_CLASS_NOT_FOUND[LANG]);
    }

    /**
     * 加载类
     * @param $class
     */
    private static function includeFile(string $class): void{

        self::fileExists($class);
        include_once $class;
    }

    public static function requireFile(string $class){

        self::fileExists($class);
        return require_once $class;
    }

    private static function fileExists($class){

        if(!file_exists($class)){
            throw new EasyQueueException($class.COLON.Language::FILE_OR_CLASS_NOT_FOUND[LANG]);
        }

        return true;
    }

}