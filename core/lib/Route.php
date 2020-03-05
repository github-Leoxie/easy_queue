<?php
namespace core\lib;

use core\lib\exception\EasyQueueException;

/**
 * 路由解析类
 * Class Route
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class Route
{
    public static $SERVER_NAME = '';
    public static $SERVER_PORT = '';
    public static $MODULE = '';
    public static $CONTROLLER = '';
    public static $ACTION = '';
    public static $COMPLETE_URL = '';

    public static function parseUrlInfo(): bool {
        if(IS_CLI){
            return self::parseUrlInCli();
        }else{
            return self::parseUrlInCgi();
        }
    }

    private static function parseUrlInCgi(): bool {
        //除了http/https部分的最完整连接
        $completeUrl = $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];

        //去掉参数部分
        $parseCompleteUrl = current(explode('?', $completeUrl));
        //$bindRoute = Config::get('bindRoute');
        [$serverName, $module, $controller, $action] = explode('/', $parseCompleteUrl);

        //判断当前是http还是https，完善最完整连接
        self::$MODULE = self::getModule($module);
        self::$ACTION = self::getAction($action);
        self::$CONTROLLER = self::getController($controller);

        self::$SERVER_NAME = (self::isHttps()?'https://':'http://').$serverName;
        self::$SERVER_PORT = end(explode(':',$serverName));
        self::$COMPLETE_URL = (self::isHttps()?'https://':'http://').$completeUrl;

        return true;
    }

    private static function parseUrlInCli(): bool {

        global $argv;

        [$serverName,$module, $controller, $action] = explode('/', $argv[1]??'');

        //判断当前是http还是https，完善最完整连接
        self::$MODULE = self::getModule($module);
        self::$ACTION = self::getAction($action);
        self::$CONTROLLER = self::getController($controller);

        return true;
    }


    public static function isHttps(): bool {

        if( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
             || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')){
            return true;
        }

        return false;
    }

    public static function getNamespace(): string {
        if(empty(self::$MODULE) || empty(self::$CONTROLLER)){
            $errMsg = '--->Message：'.Language::ROUTE_MODULE_OR_CONTROLLER_NO_EXIST[LANG];
            $errMsg .= '--->controller：'.self::$CONTROLLER;
            $errMsg .= '--->module：'.self::$MODULE;

            throw new EasyQueueException($errMsg);
        }
        return 'app\\'.self::$MODULE.'\\controller\\'.self::$CONTROLLER;
    }

    public static function bindRoute(string $route): void {
        Config::set('bindRoute',$route);
    }

    /**
     * 模块要纯小写
     * @param $module
     * @return string
     */
    public static function getModule($module): string {
        if(empty($module)){
            $module = Config::get('defaultModule');
        }
        $module = self::filter($module);
        return strtolower($module);
    }

    /**
     * action规定是小写开头的驼峰
     * @param $action
     * @return string
     */
    public static function getAction($action): string {
        if(empty($action)){
            $action = Config::get('defaultAction');
        }
        return lcfirst(self::filter($action));
    }

    /**
     * 规定是大写开头的驼峰
     * @param $controller
     * @return string
     */
    public static function getController($controller): string {
        if(empty($controller)){
            $controller = Config::get('defaultController');
        }
        return ucfirst(self::filter($controller));
    }

    public static function filter(string $str): string {
        preg_match_all('/[0-9a-zA-Z]+/',$str,$result);
        if(implode('',current($result)) === $str){
            return $str;
        }
        throw new EasyQueueException(Language::FILE_OR_CLASS_NOT_FOUND[LANG]);
    }
}