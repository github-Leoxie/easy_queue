<?php
namespace app\index\controller;

use app\common\controller\Main;
use app\common\Listener;
use app\common\Lock;
use core\lib\RedisManager;
use core\lib\Request;
use core\lib\Rule;
use core\lib\Storage;

/**
 * Class Redis
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\index\controller
 */
class Redis extends Main
{
    public function save(): void {
        $rule = [
            'no'=>'require',
            'name'=>'require',
            'select'=>'require|number',
            'key'=>'require',
            'maxlength'=>'require|number',
            'processNormal'=>'require|number',
            'processMax'=>'require|number',
            'ip'=>'require',
            'port'=>'require|number',
            'auth'=>'require',
            'curl'=>'require'
        ];
        $post = Request::post();
        if(Rule::check($post,$rule) === false){
            $this->alert(Rule::getError());
        }

        $listener = new Listener($post);
        //测试连接是否通畅
        $connectRet = (new RedisManager())->connect($listener->getIp(),$listener->getPort(),$listener->getAuth());
        if($connectRet !== true){
            $this->alert('redis连接失败，请检查用户名/端口/密码是否正确('.$connectRet.')');
        }

        Lock::addRedisListener($listener,$post['no']??'');

        $this->redirect('\\');
    }


    public function delete(): void {
        $get = Request::get();
        $rule = [
            'no'=>'require',
        ];
        if(Rule::check($get,$rule) === false){
            $this->alert(Rule::getError());
        }

        Lock::delRedisListener($get['no']);

        $this->redirect('\\');
    }


    public function edit(): void {
        $get = Request::get();

        $listenList = Lock::getRedisListener();

        $no = $get['no']??'';
        $listener = new Listener($listenList[$no] ?? []);

        $this->assign('name',$listener->getName());
        $this->assign('select',$listener->getSelect());//库
        $this->assign('key',$listener->getKey());
        $this->assign('maxlength',$listener->getMaxlength());//单进程最大处理行数，多余这个数，就开启多进程
        $this->assign('processNormal',$listener->getProcessNormal());
        $this->assign('processMax',$listener->getProcessMax());

        $this->assign('ip',$listener->getIp());
        $this->assign('port',$listener->getPort());
        $this->assign('auth',$listener->getAuth());
        $this->assign('curl',$listener->getCurl());

        $this->assign('no',$no);

        $this->display('edit');
    }

}