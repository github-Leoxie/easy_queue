<?php
namespace core\lib\request\drive;

use core\lib\request\Drive;

/**
 * Class Get
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib\request\drive
 */
class Get extends Drive
{
    protected function getParams(){
        return $_GET;
    }
}