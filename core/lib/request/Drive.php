<?php
namespace core\lib\request;

/**
 * Class Drive
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib\request
 */
abstract class Drive
{
    private $params = [];

    public function get($key=null,$default=null){
        $this->params = $this->getParams();
        return $this->format($key,$default);
    }

    abstract protected function getParams();

    /**
     * @param $key
     * @param $default
     * @return array|int
     */
    protected function format($key=null,$default=null) {
        if(!empty($key)){
            return $this->params[$key]??$default;
        }
        return $this->params??[];
    }
}