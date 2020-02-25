<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\Lock;
use app\common\Tools;
use core\lib\Config;
use core\lib\Request;
use core\lib\Rule;

/**
 * Class Install
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\index\controller
 */
class Install extends Base
{
    public function index(): void {

        if(!Lock::canInstall()){
            $this->redirect('\\');
        }

        $this->display('install');
    }

    public function createManager(): void {
        $rule = [
            'superUser'=>'require|maxLen:25|minLen:6',
            'superPassword'=>'require|maxLen:25|minLen:6',
            'reSuperPassword'=>'require|maxLen:25|minLen:6',
        ];
        $post = Request::post();
        if(Rule::check($post,$rule) === false){
            $this->alert(Rule::getError());
        }

        if((string)$post['superPassword'] !== (string)$post['reSuperPassword']){
            $this->alert('密码和确认密码不一致');
        }

        $saveInfo = Lock::installLock([
            'superUser'=>$post['superUser'],
            'superPassword'=>Tools::passwordEncry($post['superPassword']),
        ]);

        if($saveInfo === false){
            $this->alert('安装失败，请检查目录权限，另外删除掉根目录下的'.STORAGE_PATH.Config::get('installLock').'如果存在请删除,才可以再次安装');
        }

        $this->redirect('\\');
    }
}