<?php
namespace app\common\pcntl\process;

use app\common\Listener;
use app\common\Lock;
use app\common\Tools;
use core\lib\RedisManager;
use core\lib\Storage;

/**
 * Class Child
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common\pcntl
 */
class Child extends Process
{

    private static $childPid = 0;
    private static $filePre = 'child';
    private static $no = null;

    public static function work(string $no,Listener $listener): void {

        try {

            self::$no = $no;
            self::$childPid = getmypid();

            $scriptName = self::getScriptName();
            self::printLog("改名:$scriptName");

            cli_set_process_title($scriptName);

            $redisManager = new RedisManager();
            $redisManager->connect($listener->getIp(), $listener->getPort(), $listener->getAuth());

            while (true) {

                $listener = self::reloadLister(self::$no);

                //时刻关注进程情况，判断我是不是，是将死之人
                $killStatus = self::isNeedDelProcess(self::$no, self::$childPid, $listener);
                if ($killStatus === true) {
                    self::printLog("进程失控{$killStatus}", true);//需要自杀
                }
                //消费redis的数据
                $content = $redisManager->blPop($listener->getKey());
                self::printLog('消费数据'.var_export($content,true));
                //开始消费
                $iscConsume = false;
                try {
                    $url = $listener->getCurl();
                    $paramStr = json_encode($content, JSON_UNESCAPED_UNICODE);
                    $jsonData = Tools::HttpPostJson($url, $paramStr, 3);

                    self::printLog([
                        'url'=>$url,
                        'paramStr'=>$paramStr,
                        'jsonData'>$jsonData
                    ]);

                    $arrData = @json_decode($jsonData, true);

                    if (isset($arrData['code']) && in_array($arrData['code'], [0, 200, '0', '200'], true)) {
                        $iscConsume = true;
                    }

                } catch (\Exception $ex) {
                    self::printLog('请求异常'.$ex->getMessage());
                }

                //重新入队
                if ($iscConsume === false) {
                    $redisManager->rPush($listener->getKey(), $content);
                }
            }

        }catch (\Exception $ex){
            self::printLog($ex->getMessage(),true);
        }
    }

    private static function reloadLister(string $no): Listener {
        $redisListener = Lock::getRedisListener();
        if (isset($redisListener[$no])) {
            $redisListener = $redisListener[$no];
        } else {
            self::printLog('数据重置，此进程销毁',true);
        }

        return new Listener($redisListener);
    }

    public static function getScriptName():string {
        return self::$scriptNamePrefix.strtoupper(self::$filePre).self::$no.'-'.self::$childPid;
    }

    public static function getFilePre(): string {
        return self::$filePre;
    }

    public static function isNeedDelProcess(string $no,int $pid,Listener $listener): bool {
        //这是子进程的方法，要考虑进程间数据共享问题
        $processPid = Storage::read(self::getPidFile($no));
        $needProcessNum = self::getNeedProcessNum($listener);//这个虽然和下面的if交换可以提高效率，但是却让主进程反复调用子进程，进入假死状态

        if(!isset($processPid[$pid])){//当我不在[爸爸]的认筹范围内，那么我就是假进程，我需要自杀
            return true;
        }

        $countProcess = count($processPid);
        return $countProcess > $needProcessNum && $countProcess > $listener->getProcessNormal();
    }
}