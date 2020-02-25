<?php
/**
 * Auth  leo.xie
 * mail 811329263@qq.com
 */
//加载常量
require_once ROOT_PATH . 'core' . DIRECTORY_SEPARATOR . 'Constant.php';

//加载注册类
require_once LIB_PATH.'Loader.php';
//注册psr0 psr4(后续再说)
\core\lib\Loader::register();
//自动加载类
\core\lib\Loader::autoload();
//加载错误处理类
(new \core\lib\Error())->handler();


