<?php


namespace core\lib;


/**
 * session类
 * Class Session
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class Session
{

    private static $instance = null;

    public static function getInstance(): ?Session {

        if ( !isset(self::$instance)){
            self::$instance = new self;
            self::$instance->startSession();
        }

        return self::$instance;
    }

    public function startSession(): void {
        session_start([
            'cookie_lifetime' => 3600,
        ]);
    }

    public function set($key,$value): void {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return $_SESSION[$key]??null;
    }
}