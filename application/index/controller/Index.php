<?php
namespace app\index\controller;

use app\common\controller\Main;

/**
 * Class Index
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\index\controller
 */
class Index extends Main
{
    public function index(): void {
        $this->display('Index/index');
    }
}