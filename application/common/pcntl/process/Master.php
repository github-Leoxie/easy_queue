<?php
namespace app\common\pcntl\process;

use app\common\Listener;
use app\common\Lock;
use core\lib\Storage;

/**
 * Class Manager
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common\pcntl
 */
class Master extends Process
{

    private static $globalProcess = null;
    private static $filePre = 'master';

    public static function run(): void {

        //爸爸进程设置自己
        //self::setManagerPid(getmypid());
        self::createMasterProcessFile();
        cli_set_process_title(self::getScriptName());

        while(true){
            try {
                self::printLog('监控数据变化');
                //获取监听列表
                $listenerList = Lock::getRedisListener();
                foreach ($listenerList as $no => $listenData) {
                    //判断每个项目的进程数量是否需要调整
                    self::needAdd($no, new Listener($listenData));
                    //扫描子进程
                    self::scanChildProcess($no);
                }

            }catch (\Exception $ex){
                self::printLog('异常情况：'.$ex->getMessage());
            }

            try {
                //观察Manager进程是否需要kill
                if (!self::getMasterPidFileExist()) {
                    self::printLog('需要重启脚本', true);
                }
            }catch (\Exception $ex){
                self::printLog('检测文件异常：'.$ex->getMessage());
            }
        }
    }

    /**
     * 当进程数量需要改变的时候，新增
     * @param string $no
     * @param Listener $listener
     * @return bool  true需要新增并且新增成功，false的时候=不需要新增或者新增失败
     */
    private static function needAdd(string $no,Listener $listener): bool {

        if(self::isNeedAddProcess($no,$listener)){

            $ret = self::add();
            //因为执行完新增之后，[爸爸]进程和[我]会同时向下进行

            if($ret === true){//子进程，只有返回true的可能
                self::printLog('当前PID:' .getmypid());
                Child::work($no,$listener);//while true方式的干活，干到死为止
                self::printLog('进程逃逸',true);//再次防止，有逃犯外逃，只要我进入if就决定了我的命运
            }

            if($ret === false){//主进程
                return false;//创建子进程失败
            }

            self::printLog("新增了:$ret");

            //创建子进程成功了，继续执行
            //更新Manager进程的静态数据
            self::addListenerProcess($no,(int)$ret);
            return true;
        }

        return false;
    }


    /**
     * 主进程巡检子进程的状态，当发现关闭的，就需要更新文件了
     * @param string $no
     * @return bool
     */
    private static function scanChildProcess(string $no): bool {
        $listenList = self::getListenerListByNo($no);
        self::printLog($listenList);
        foreach($listenList as $pid=>$time){
            $ret = pcntl_waitpid($pid,$status,WNOHANG);
            self::printLog("扫描到{$pid}进程,状态{$ret}");
            if($ret !== 0){
                //值可以是-1，0或者 >0的值， 如果是-1, 表示子进程出错， 如果>0表示子进程已经退出且值是退出的子进程pid
                //只有在option 参数为 WNOHANG且子进程正在运行时0
                self::printLog("监听到{$pid}进程退出");
                self::removeListenerProcess($no,$pid);
            }
        }
        return true;
    }


    private static function createMasterProcessFile(): void {
        Storage::save(self::getPidFile(self::getFilePre()),[],false);
    }

    private static function getMasterPidFileExist(): bool {
        self::printLog('检测文件：'.self::getPidFile(self::getFilePre()));
        return file_exists(self::getPidFile(self::getFilePre()));
    }


    public static function getScriptName():string {
        return self::$scriptNamePrefix.strtoupper(self::getFilePre());
    }

    /**
     * 判断当前是否需要新增进程
     * @param string $no
     * @param Listener $listener
     * @return bool
     */
    private static function isNeedAddProcess(string $no,Listener $listener): bool {
        $pidCount = self::getListenerCountByNo($no);

        $needProcessNum = self::getNeedProcessNum($listener);//这个虽然和下面的if交换可以提高效率，但是却让主进程反复调用子进程，进入假死状态

        $max = (int)$listener->getProcessMax()===-1?$needProcessNum+1:$listener->getProcessMax();

        self::printLog('>>需要进程数：'.$needProcessNum.'>>'.$listener->getProcessMax().'>>'.$max.'>>'.$pidCount);

        //如果当前进程数小于日常维护进程数 => 需要创建
        if($pidCount < $listener->getProcessNormal()){
            return true;
        }

        //如果所需进程数大于当前进程数 && 当前进程数没达到最大进程数 => 需要创建
        return $needProcessNum > $pidCount && $max > $pidCount;
    }

    private static function add(){
        $pid = pcntl_fork();

        if ($pid === -1) {//爸爸不孕不育了，不能杀死他，因为他可能还有其他孩子
            return false;//放他一马[一般不会出现这种情况，因为爷爷能生他，说明我们家有生育基因]
        }

        if($pid > 0){//爸爸要返回了
            return $pid;
        }

        return true;
    }

    public static function getListenerCountByNo(string $no): int {
        return count(self::getListenerListByNo($no));
    }

    public static function getListenerListByNo(string $no): array {
        return self::$globalProcess[$no] ?? [];
    }

    public static function getFilePre(): string {
        return self::$filePre;
    }

    public static function removeListenerProcess(string $no,int $pid): void {
        if(!isset(self::$globalProcess[$no])){
            self::$globalProcess[$no] = [];
        }

        if(isset(self::$globalProcess[$no][$pid])){
            unset(self::$globalProcess[$no][$pid]);
        }
        //顺便更新下文件，给后台用
        Storage::save(self::getPidFile($no),self::$globalProcess[$no],false);
    }

    public static function addListenerProcess(string $no,int $pid): void {
        if(!isset(self::$globalProcess[$no])){
            self::$globalProcess[$no] = [];
        }

        self::$globalProcess[$no][$pid] = date('Y-m-d H:i:s');

        //顺便更新下文件，给后台用
        Storage::save(self::getPidFile($no),self::$globalProcess[$no],false);
    }

    public static function getListenerProcess(string $no): array {
        return Storage::read(self::getPidFile($no));
    }

    public static function killProcess(): bool {
        $pidDir = self::$processDir;
        if(!file_exists($pidDir)){
            return false;
        }
        $scanArr = scandir($pidDir);
        foreach($scanArr as $fileName){
            if(in_array($fileName,['.','..'])){
                continue;
            }
            if(strpos($fileName,'.log')>0){
                continue;
            }
            $filePath = $pidDir.$fileName;
            try {
                exec("rm -f $filePath");
            }catch (\Exception $ex){
            }
        }

        return true;
    }
}
