<?php
namespace core\lib\request\drive;

use core\lib\request\Drive;

/**
 * Class Json
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib\request\drive
 */
class Json extends Drive
{
    protected function getParams(){
        return @json_decode(file_get_contents('php://input'),true);
    }
}