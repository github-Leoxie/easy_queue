<?php
namespace core\lib;

use app\common\pcntl\Manager;
use Redis;
/**
 * Redis操作类
 * Class RedisManager
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class RedisManager
{
    private static $handler = null;

    public function getInstance(): ?Redis {
        if(!self::$handler){
            self::$handler = new Redis();
        }
        return self::$handler;
    }

    public function connect(string $host,int $post,string $password,int $timeOut=2,int $times=3){
        try{
            ($this->getInstance())->connect($host,$post,$timeOut);
            if(!empty($password)){
                return ($this->getInstance())->auth($password);
            }
        }catch (\Exception $ex){
            //3次重试
            //Manager::printLog($ex->getMessage());
            if(--$times > 0 && self::canTry($ex->getMessage())){
                $this->connect($host,$post,$password,$timeOut,$times);
            }else{
                return $ex->getMessage();
            }
        }
        return true;
    }

    public function lLen(string $key,int $times=3){
        try {
            return ($this->getInstance())->lLen($key);
        }catch (\Exception $ex){
            //Manager::printLog($ex->getMessage());
            if(--$times > 0 && self::canTry($ex->getMessage())){
                $this->lLen($key,$times);
            }else {
                throw $ex;
            }
        }
    }

    public function blPop(string $key,int $times=3): array {
        try {
            $lPop = ($this->getInstance())->blPop($key, 1);
            $lPop = end($lPop);
            return self::formatOut($lPop);

        }catch (\Exception $ex){
            //Manager::printLog($ex->getMessage());
            if(--$times > 0 && self::canTry($ex->getMessage())){
                $this->blPop($key,$times);
            }else {
                throw $ex;
            }
        }
    }

    public function rPush(string $key,array $data,int $times=3) {
        try {
            $encode = self::formatIn($data);
            return ($this->getInstance())->rPush($key, $encode);
        }catch (\Exception $ex){
            //Manager::printLog($ex->getMessage());
            if(--$times > 0 && self::canTry($ex->getMessage())){
                $this->rPush($key,$data,$times);
            }else {
                throw $ex;
            }
        }
    }

    private static function formatIn(array $data){
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    private static function formatOut(string $data):array {
        return json_decode($data,true)??[];
    }

    private static function canTry($message): bool {
        return strpos($message, 'Redis server went away') !== false
            || strpos($message, 'Connection timed out') !== false;
    }
}