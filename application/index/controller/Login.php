<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\Lock;
use app\common\Tools;
use core\lib\Request;
use core\lib\Rule;
use core\lib\Session;

/**
 * Class Login
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\index\controller
 */
class Login extends Base
{

    public function login(): void {

        if((Session::getInstance())->get('user')){
            $this->redirect('\\');
        }

        $this->display('login');
    }

    public function checkLogin(): void {
        $rule = [
            'superUser'=>'require|maxLen:25|minLen:6',
            'superPassword'=>'require|maxLen:25|minLen:6',
        ];
        $post = Request::post();
        if(Rule::check($post,$rule) === false){
            $this->alert(Rule::getError());
        }

        $user = Lock::getUserInfo();

        if($post['superUser'] !== $user->getUserName()){
            $this->alert('用户名错误');
        }

        if(Tools::passwordEncry($post['superPassword']) !== $user->getPassword()){
            $this->alert('密码错误');
        }

        (Session::getInstance())->set('user',$user->getUserName());

        $this->redirect('\\');
    }

    public function loginOut(): void {
        (Session::getInstance())->set('user',null);
        $this->redirect('\\');
    }
}