<?php
namespace app\common\controller;

use core\Controller;

/**
 * Class Base
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common\controller
 */
class Base extends Controller
{
    public function __construct() {
       $this->assign('xdebug','XDEBUG_SESSION_START=PHPSTORM');
    }
}