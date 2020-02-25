<?php
namespace app\common;

use core\lib\Config;
use core\lib\exception\EasyQueueException;
use core\lib\Language;
use core\lib\Storage;

/**
 * 用户类
 * Class User
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common
 */
class User
{
    private $userName = null;
    private $password = null;

    public function setUserName(string $userName): void {
        $this->userName = $userName;
    }

    public function getUserName(){
        return $this->userName;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getPassword(){
        return $this->password;
    }

}