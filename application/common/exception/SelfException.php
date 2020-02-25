<?php
namespace app\common\exception;

use core\lib\exception\EasyQueueException;
use core\lib\Output;
use core\lib\Template;

/**
 * 自定义异常处理类
 * Class selfException
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class SelfException extends \RuntimeException
{
    public function render($ex):void {

        if($ex instanceof ApiException){
            Output::json([
                'code'=>200,
                'msg'=>$ex->getMessage(),
                'data'=>[
                    'file'=>$ex->getFile(),
                    'line'=>$ex->getLine(),
                ]
            ]);
        }

        if($ex instanceof HtmlException){
            Template::output($ex->getMessage(),true);
        }
    }

}