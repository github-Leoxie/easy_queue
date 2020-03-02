<?php


class ServerTest extends \Codeception\Test\Unit
{
    public function testAa(){
        while(true){
            $redis = new Redis();
            $redis->connect('39.108.245.131', 6379);//serverip port
            $redis ->rpush( "EASYQUEUE:list1" , json_encode(['aaa'=>'bbb']));
            echo 'success';
        }
    }
}
