<?php
namespace app\server;

use app\common\pcntl\Manager;
use app\common\pcntl\process\Master;
use app\common\pcntl\process\Process;

/**
 * Class Work
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\server
 */
class Work
{
    public function start(): void {
        //重启的时候，清除pid文件
        $this->stop();
        echo ">>开启进程<<\n";

        (new Manager())->run();
    }

    public function stop(): void {

        echo ">>退出进程<<\n";
        $times = 10;
        while($times--) {
            Master::killProcess(true);
            echo "剩余进度{$times}/10\n";
            sleep(1);
        }
    }
}