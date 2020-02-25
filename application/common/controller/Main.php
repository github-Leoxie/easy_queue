<?php
namespace app\common\controller;

use app\common\Lock;
use core\lib\Session;

/**
 * Class Main
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common\controller
 */
class Main extends Base
{
    public function __construct() {
        parent::__construct();

        if(Lock::canInstall()){
            $this->redirect('/index/install/index');
        }

        if(!(Session::getInstance())->get('user')){
            $this->redirect('/index/login/login');
        }

        $this->assign('userName',(Session::getInstance())->get('user'));
    }
}