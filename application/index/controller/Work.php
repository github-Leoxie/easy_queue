<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\pcntl\Manager;
use app\common\pcntl\process\Master;

/**
 * Class Work
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\server
 */
class Work extends Base
{
    public function start(): void {
        //重启的时候，清除pid文件
        $this->stop();

        Master::printLog('>>开启进程<<');
        (new Manager())->run();
    }

    public function stop(): void {

        Master::printLog('>>退出进程<<');

        $times = 10;
        while($times--) {
            Master::killProcess();
            Master::printLog("剩余进度{$times}/10");
            sleep(1);
        }
    }
}