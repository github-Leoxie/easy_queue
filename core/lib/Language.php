<?php


namespace core\lib;

/**
 * 语言类
 * Class Language
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class Language
{
    public const FILE_OR_CLASS_NOT_FOUND = [
        'ZN' => '文件名或者类名没有找到',
        'EN' => 'file or class not found',
    ];

    public const FILE_NOT_FOUND = [
        'ZN' => '文件不存在',
        'EN' => 'file not found',
    ];

    public const CONFIG_OVER_5_LEVEL = [
        'ZN' => '配置key的层级超过5层了',
        'EN' => 'configuration key length over 5 level',
    ];

    public const CONFIG_KEY_EMPTY = [
        'ZN' => '配置key的不能为空',
        'EN' => 'configuration key cannot be empty',
    ];

    public const TEMPLATE_NO_EXIST = [
        'ZN' => '模板文件不存在',
        'EN' => 'template no exist',
    ];

    public const ROUTE_MODULE_OR_CONTROLLER_NO_EXIST = [
        'ZN' => '当前请求模块或者控制器不存在',
        'EN' => 'The current request module or controller does not exist',
    ];

    public const INSTALL_LOCK_FILE_ALREADY_EXIST = [
        'ZN' => '安装锁文件已经存在',
        'EN' => 'The install lock file already exist',
    ];

    public const SCENE_REQUIRE_ERROR = [
        'ZN' =>'不是存在',
        'EN' =>'not isset'
    ];

    public const RULE_METHOD_NOT_EXIST = [
        'ZN' =>'规则方法不存在',
        'EN' =>'rule method not exist'
    ];

    public const RULE_FORMAT_ERROR = [
        'ZN' =>'%s不符合%s的格式规则',
        'EN' =>'%s does not comply with rule of %s'
    ];
}