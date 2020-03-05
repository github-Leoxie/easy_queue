<?php
namespace core\lib;

use core\lib\exception\EasyQueueException;

/**
 * Class Template
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 * 模板类
 */

class Template
{
    private static $assignVariable = [];

    public static function setAssignVariable($key,$value): void {
        self::$assignVariable[$key] = $value;
    }

    /**
     * 加载模板
     * @param $file
     * @return false|string
     */
    public static function loadTemplate(string $file){

        $file = self::createTemplatePath($file);

        $content = file_get_contents($file);
        if(empty($content)){
            throw new EasyQueueException('FILE'.COLON.$file
                .NEW_LINE //换行符
                .'EXCEPTION'.COLON.Language::TEMPLATE_NO_EXIST[LANG]);
        }

        return self::parse($content);
    }

    /**
     * 完善文件路径
     * @param string $file
     * @return string
     */
    private static function createTemplatePath(string $file): string {
        $filePath = APP_PATH.Route::$MODULE.DS.'view'.DS.$file.TEMPLATE_EXT;
        if(file_exists($filePath)){
            return $filePath;
        }
        throw new EasyQueueException(Language::TEMPLATE_NO_EXIST[LANG].'. File is :'.$filePath);
    }

    private static function parse(string $content){
        preg_match_all('/({{)(.*?)(}})/',$content,$result);
        if(empty($result) || empty(current($result))){
            return $content;
        }

        $variableOrConstant = current($result);
        //做常量&变量替换
        $content = self::replaceData($variableOrConstant,$content);
        return $content;
    }

    private static function replaceData(array $variableOrConstant,string $content): string {
        foreach($variableOrConstant as $item){
            $content = self::replaceVariable($item,$content);
        }

        return $content;
    }

    private static function replaceVariable(string $variable,string $content): string {

        $variableName = self::extractVariableName($variable);
        $trimVariableName = trim($variableName,'$');

        if(strpos($variableName,'include') === 0
            && strpos($variableName,'=')){
            [$include, $filePath] = explode('=',$variableName);
            if($include === 'include'){
                $html = self::loadTemplate(trim($filePath,"'"));//加载公用模块
                $content = str_replace($variable,$html,$content);
            }
        }

        if(defined($variableName) && strpos($variableName,'$') === false){
            $constantValue = constant($variableName);
            $content = str_replace($variable,$constantValue,$content);
        }

        if(isset(self::$assignVariable[$trimVariableName]) && strpos($variableName,'$') !== false){
            $content = str_replace($variable,self::$assignVariable[$trimVariableName],$content);
        }

        return $content;
    }

    /**
     *提取变量名
     * @param $variable
     * @return string
     */
    private static function extractVariableName(string $variable): string {
        return trim($variable,'{}');
    }

    public static function output(string $content,bool $isDie): void {
        echo $content;
        if(true === $isDie){
            exit();
        }
    }

    public static function alert(string $content): void {
        echo ('<script>alert(\''.$content.'\');window.history.go(-1);</script>');
        exit;
    }
}