<?php
namespace core;

use core\lib\Route;
use core\lib\Template;

/**
 * Class Controller
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core
 */
class Controller
{

    /**
     * 展示模板内容【绝对路径是在webroot下面】
     * @param $htmlName
     */
    protected function display($htmlName): void {
        $templateString = Template::loadTemplate($htmlName);
        Template::output($templateString,true);
    }

    protected function assign($key,$value): void {
        Template::setAssignVariable($key,$value);
    }

    protected function redirect($url): void {
        header('Cache-control','no-cache,must-revalidate');
        header(sprintf('Location:%s',Route::$SERVER_NAME.$url));
        exit;
    }

    protected function alert(string $errorInfo): void {
        Template::alert($errorInfo);
    }
}