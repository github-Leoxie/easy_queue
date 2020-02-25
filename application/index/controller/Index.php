<?php
namespace app\index\controller;

use app\common\controller\Main;
use app\common\Lock;
use app\common\pcntl\process\Master;

/**
 * Class Index
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\index\controller
 */
class Index extends Main
{
    public function index(): void {

        $this->assign('tableString',$this->createHtmlString());

        $this->display('index');
    }

    private function createHtmlString(): string {

        $listenList = Lock::getRedisListener();

        $str = '';
        foreach($listenList as $no=>$item){

            $detailArr = Master::getListenerProcess($no);
            $detailString = '进程数量：'.count($detailArr).NEW_LINE;

            foreach($detailArr as $pid=>$time){
                $detailString .= "{$time}（{$pid}）".NEW_LINE;
            }

            $edit = 'location.href="/index/redis/edit?no='.$no.'"';
            $delete = 'location.href="/index/redis/delete?no='.$no.'"';

            $str .= "<tr>
                       <td>{$item['name']}</td>
                       <td>{$item['key']}</td>
                       <td>$detailString</td>
                       <td>{$item['maxlength']}</td>
                       <td>{$item['processNormal']}</td>
                       <td>{$item['processMax']}</td>
                       <td>
                         <button class='btn' onclick='{$edit}'>编辑</button>
                         <button class='btn' onclick='{$delete}'>删除</button>
                       </td>
                   </tr>";
        }

        return $str;
    }

}