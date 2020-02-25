<?php
namespace core\lib\output\drive;

use core\lib\output\Drive;

/**
 * Class Json
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib\output\drive
 */

class Json extends Drive
{
    public function format($data):void {
        $header = [
            'Content-Type'=>'application/json'
        ];
        $this->print($header,json_encode($data,JSON_UNESCAPED_UNICODE));
    }
}