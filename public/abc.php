<?php
while(true){
$redis = new Redis();  
$redis->connect('39.108.245.131', 6379);//serverip port
#$redis->auth('mypassword');//my redis password 
$redis ->rpush( "EASYQUEUE:list1" , json_encode(['aaa'=>'bbb']));
echo 'success';
}
