<?php
namespace core\lib;


use core\lib\output\drive\Json;

/**
 * Trait Output
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
trait Output
{
    /**
     * json形式输出
     * @param array $data
     */
    public static function json(array $data): void {
        (new Json())->format($data);
    }
}