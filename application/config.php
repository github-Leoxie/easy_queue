<?php
/**
 * Auth  leo.xie
 * mail 811329263@qq.com
 */
use app\common\exception\SelfException;

return [
    'defaultModule'=>'index',//默认的模块
    'defaultController'=>'index',//默认的控制器
    'defaultAction'=>'index',//默认的方法

    'installLock'=>'install.lock',//安装锁的锁名
    'exceptionHandler'=> SelfException::class,//自定义异常处理类

    'pcntlLoopDebug'=>true,//是否开启pcntl循环日志
    'pcntlFileSize'=>2*1024*1024,//2M
];