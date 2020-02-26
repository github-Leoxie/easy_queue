<?php
namespace app\common\pcntl\process;

use app\common\Listener;
use core\lib\Config;
use core\lib\File;
use core\lib\RedisManager;

/**
 * Class Process
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common\pcntl
 */
abstract class Process
{
    public static $processDir = STORAGE_PATH.'process'.DS;
    public static $scriptNamePrefix = 'EASY-QUEUE-';

    abstract public static function getScriptName();
    abstract public static function getFilePre();

    protected static function getLogFile(string $name): string {
        File::create(self::$processDir);
        return self::$processDir.$name.'.log';
    }

    public static function getPidFile(string $no): string {
        File::create(self::$processDir);
        return self::$processDir.$no.'.pid';
    }

    public static function printLog($data,$isDie=false): void {

        $currentPid = getmypid();
        if(is_array($data)){
            $data = var_export($data,true);
        }
        $str = NEW_LINE.$data."({$currentPid})".NEW_LINE;

        $filePre = (static::class)::getFilePre();
        self::loopDebug(self::getLogFile($filePre),$str);

        $isDie === true?die():'';
    }

    public static function loopDebug($file,$data): bool {

        if(Config::get('pcntlLoopDebug') !== true){
            return false;
        }

        clearstatcache();//filesystem的静态缓存
        $fileSize = file_exists($file)?@filesize($file):0;
        if((int)$fileSize > Config::get('pcntlFileSize')){
            //清空文件
            file_put_contents($file,'');
        }

        $string = "\n--------------{$file}:{$fileSize}----------------\n".$data."\n";
        file_put_contents($file,$string,FILE_APPEND);
        return true;
    }

    protected static function getNeedProcessNum(Listener $listener):int {
        (new RedisManager())->connect($listener->getIp(),$listener->getPort(),$listener->getAuth());
        $lLen = (new RedisManager())->lLen($listener->getKey());
        return ceil($lLen/$listener->getMaxlength());
    }
}