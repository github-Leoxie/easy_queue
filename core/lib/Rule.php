<?php
namespace core\lib;

use core\lib\exception\SceneValidException;
use core\lib\rule\Scene;
use ReflectionMethod;

/**
 * 规则类
 * Class Rule
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class Rule
{

    private static $error = '';

    /**
     * 规则检验方法
     * @param array $params 要检验的数据
     * @param array $rule  检验规则
     * @return bool
     */
    public static function check(array $params,array $rule): bool {

        //清除之前的错误信息
        self::clearError();

        foreach($rule as $ruleKey=>$ruleItem){
            if(!isset($params[$ruleKey]) && strpos($ruleItem,'require') !== false){
                self::$error = "$ruleKey is require";
                return false;
            }

            $paramsItem = null;
            if(isset($params[$ruleKey])) {
                $paramsItem = $params[$ruleKey];
            }

            if(false === self::validScene($paramsItem,$ruleItem,$ruleKey)){
                return false;
            }
        }

        return true;
    }

    private static function validScene($paramsItem,$ruleValue,$ruleKey): bool {

        $sceneArr = self::getSceneArr($ruleValue);
        foreach($sceneArr as $completeScene) {

            if($completeScene === 'require'){
                continue;
            }

            $sceneParam = null;
            $scene = $completeScene;

            if (strpos($completeScene, ':') !== false) {
                [$scene, $sceneParam] = explode(':', $scene);
            }

            try {
                $refectionMethod = new ReflectionMethod(Scene::class, $scene);
                if (!$refectionMethod->isStatic()) {
                    throw new SceneValidException(Language::RULE_METHOD_NOT_EXIST[LANG]);
                }
            } catch (\ReflectionException $e) {
            }

            //不带冒号的情况
            if ((string)$completeScene === (string)$scene && Scene::$scene($paramsItem) === false) {
                self::$error = sprintf(Language::RULE_FORMAT_ERROR[LANG], $ruleKey, $completeScene);
                return false;
            }

            //带冒号的场景值
            if ((string)$completeScene !== (string)$scene && Scene::$scene($paramsItem, $sceneParam) === false) {
                self::$error = sprintf(Language::RULE_FORMAT_ERROR[LANG], $ruleKey, $completeScene);
                return false;
            }

        }

        return true;
    }

    private static function getSceneArr($ruleValue): array {
        return @explode('|',$ruleValue);
    }

    /**
     * 获取错误信息
     * @return string
     */
    public static function getError(): string {
        if(self::$error){
            return self::$error;
        }
        return '';
    }

    private static function clearError(): void {
        self::$error = '';
    }
}