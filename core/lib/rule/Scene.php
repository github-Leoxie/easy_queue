<?php
namespace core\lib\rule;

/**
 * Class Scene
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib\rule
 */
class Scene
{
    public static function number($value): bool {
        if(is_numeric($value)){
            return true;
        }
        return false;
    }

    public static function maxLen($value,$len): bool {
        return !(strlen($value) > $len);
    }

    public static function minLen($value,$len): bool {
        return !(strlen($value) < $len);
    }

    public static function in($value,$inString): bool {
        $inArr = @explode(',',$inString);
        if(in_array($value,$inArr,false)){
            return true;
        }
        return false;
    }

    public static function between($value,$betweenString): bool {
        [$min,$max] = @explode(',',$betweenString);
        return !($value < $min || $value > $max);
    }

    public static function mobile($value): bool {
        $rule = '/^1\d{10}$/';
        return self::regex($rule, $value) === true;
    }

    private static function regex($rule,$value): bool {
        return preg_match($rule, $value) === 1;
    }


}