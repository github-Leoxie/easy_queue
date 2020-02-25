<?php
namespace app\common;

use core\lib\Config;
use core\lib\File;
use core\lib\Storage;

/**
 * 安装锁类
 * Class InstallLock
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common
 */
class Lock
{
    /**
     * 判断是否可以安装
     * @return bool
     */
    public static function canInstall(): bool {
        $installLockFile = self::getLockFile();
        if(!file_exists($installLockFile)){
            return true;
        }
        return false;
    }

    public static function installLock(array $userNameAndPassword): bool {
        if(self::canInstall() === false){//首先检测锁是否存在,存在的时候，就不能重复安装了
            return false;
        }

        $installLockFile = self::getLockFile();
        $filePath = dirname($installLockFile);

        File::create($filePath);

        return Storage::save($installLockFile,$userNameAndPassword,false);
    }

    private static function getLockFile(): string {
        return STORAGE_PATH.Config::get('installLock');
    }

    /**
     * 获取锁信息
     * @return array
     */
    private static function getLockInfo(): array {
        return Storage::read(self::getLockFile());
    }

    public static function getUserInfo(): User {
        $lockInfo = self::getLockInfo();

        $user = new User();
        $user->setUserName($lockInfo['superUser']??'');
        $user->setPassword($lockInfo['superPassword']??'');

        return $user;
    }

    public static function getRedisListener(): array {
        $lockInfo = self::getLockInfo();
        return $lockInfo['redisListener']??[];
    }

    public static function addRedisListener(Listener $listener,string $no=''): bool {
        $lockInfo = self::getLockInfo();
        $redisListener = $lockInfo['redisListener']??[];

        if(empty($no)){
            $no = md5(microtime(true));
        }

        $redisListener[$no] = [
            'name'=>$listener->getName(),
            'auth'=>$listener->getAuth(),
            'ip'=>$listener->getIp(),
            'key'=>$listener->getKey(),
            'maxlength'=>$listener->getMaxlength(),
            'port'=>$listener->getPort(),
            'processMax'=>$listener->getProcessMax(),
            'processNormal'=>$listener->getProcessNormal(),
            'select'=>$listener->getSelect(),
            'curl'=>$listener->getCurl(),
        ];

        return Storage::save(self::getLockFile(),[
            'redisListener' => $redisListener
        ],true);
    }

    public static function delRedisListener(string $no): bool {
        $lockInfo = self::getLockInfo();
        $redisListener = $lockInfo['redisListener']??[];

        if(isset($redisListener[$no])){
            unset($redisListener[$no]);
        }

        return Storage::save(self::getLockFile(),[
            'redisListener' => $redisListener
        ],true);
    }

}