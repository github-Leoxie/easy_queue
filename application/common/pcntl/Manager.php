<?php
namespace app\common\pcntl;

use app\common\pcntl\process\Master;
use app\common\pcntl\process\Process;

/**
 * Class Manager
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common\pcntl
 */
class Manager
{
    public function run(): void {

        //首先解决方法不存在的问题
        if(!function_exists('pcntl_fork')){
            Master::printLog('pcntl方法不存在，请确认',true);
        }

        //进程[代号爷爷]启动，并且尝试生进程[代号爸爸/Manager]
        $pid = pcntl_fork();

        if ($pid === -1) {//当爷爷没生出爸爸的时候
            Master::printLog('创建Manager进程失败',true);//此时爸爸也不存在，把爷爷杀死，这个进程家族就灭了
        }

        //===注意：爷爷把爸爸生出来，当前这行注释，爷爷和爸爸都可以看到哦！！！===

        if ($pid > 0) {//爷爷你进来这里【爷爷进程会得到爸爸的进程号，所以这里是爷爷进程执行的逻辑】

            Master::printLog('Manager进程已启动',true);//爷爷把爸爸生出来的使命已经完成可以瞑目了【不稳定：因为他受到当前终端控制，我们不想这样】

        }

        //$pid == 0 爸爸你来这里,开始生好多孩子了，因为爸爸是稳定的
        Master::run();
    }


}