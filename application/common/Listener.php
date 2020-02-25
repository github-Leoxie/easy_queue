<?php
namespace app\common;


/**
 * 监听类
 * Class Listener
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\common
 */
class Listener
{
    private $name = null;
    private $select = 0;
    private $key = null;
    private $maxlength = 0;
    private $processNormal = 0;
    private $processMax = 0;
    private $ip = null;
    private $port = 0;
    private $auth = null;
    private $curl = null;

    public function __construct(array $data) {
        $this->setName($data['name']??'');
        $this->setSelect($data['select']??'');
        $this->setKey($data['key']??'');
        $this->setMaxlength($data['maxlength']??'');
        $this->setProcessNormal($data['processNormal']??'');
        $this->setProcessMax($data['processMax']??'');
        $this->setIp($data['ip']??'');
        $this->setPort($data['port']??'');
        $this->setAuth($data['auth']??'');
        $this->setCurl($data['curl']??'');
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setSelect(string $select): void {
        $this->select = $select;
    }

    public function setKey(string $key): void {
        $this->key = $key;
    }

    public function setMaxlength(string $maxlength): void {
        $this->maxlength = $maxlength;
    }

    public function setProcessNormal(string $processNormal): void {
        $this->processNormal = $processNormal;
    }

    public function setProcessMax(string $processMax): void {
        $this->processMax = $processMax;
    }

    public function setIp(string $ip): void {
        $this->ip = $ip;
    }

    public function setPort(string $port): void {
        $this->port = $port;
    }

    public function setAuth(string $auth): void {
        $this->auth = $auth;
    }

    public function setCurl(string $curl): void {
        $this->curl = $curl;
    }




    public function getName():string {
        return $this->name;
    }

    public function getSelect(): string {
        return $this->select;
    }

    public function getKey(): string {
        return $this->key;
    }

    public function getMaxlength(): string {
        return $this->maxlength;
    }

    public function getProcessNormal(): string {
        return $this->processNormal;
    }

    public function getProcessMax(): string {
        return $this->processMax;
    }

    public function getIp():string {
        return $this->ip;
    }

    public function getPort(): string {
        return $this->port;
    }

    public function getAuth():string {
        return $this->auth;
    }

    public function getCurl():string {
        return $this->curl;
    }
}