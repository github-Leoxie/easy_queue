<?php
namespace core;

use core\lib\Route;

/**
 * Class App
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core
 */
class App
{
    public static function run(){
        //解析完当前url
        Route::parseUrlInfo();
        //调用方法，启动函数
        self::LoaderAction();
    }

    private static function LoaderAction(): void {
        $namespace = Route::getNamespace();
        $action = Route::$ACTION;
        (new $namespace())->$action();
    }

}