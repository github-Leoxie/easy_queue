<?php
namespace core\lib\output;

/**
 * Class Drive
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib\output
 */
abstract class Drive
{
    private $params = [];

    abstract public function format($data);

    protected function print(array $header,string $data): void {

        foreach($header as $key=>$value){
            //例如：header('Content-Type:application/json');
            header(sprintf('%s:%s',$key,$value));
        }

        echo $data;
        exit;
    }
}