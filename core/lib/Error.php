<?php
namespace core\lib;

use core\lib\exception\ApiException;

/**
 * 错误信息托管类
 * Class Error
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
class Error
{
    public function handler(): void {
        // set_exception_handler — 设置用户自定义的异常处理函数
        set_exception_handler([$this,'ex']);
        // set_error_handler — 设置用户自定义的错误处理函数
        set_error_handler([$this,'err']);
        // register_shutdown_function — 注册一个会在php中止时执行的函数
        register_shutdown_function( [ $this,'lastError' ]);
    }

    // 异常接管
    public function ex($ex): void {

        //自定义托管
        if(!empty(Config::get('exceptionHandler'))){
            call_user_func([Config::get('exceptionHandler'),'render'],$ex);
        }

        // 获取错误异常信息
        $message = $ex->getMessage();
        // 获取错误异常代码
        $code    = $ex->getCode();
        // 获取错误异常文件
        $file    = $ex->getFile();
        // 获取错误异常文件行数
        $line    = $ex->getLine();
        // 获取trace信息
        $trace   = $ex->getTraceAsString();

        $this->printLog( $code, $message,$file ,$line,$trace);
    }

    // 错误接管
    public function err( $code, $message,$file ,$line ): void {
        // 记录日志
        if(in_array($code,[E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR,E_COMPILE_WARNING],true)) {
            $this->printLog($code, $message, $file, $line);
        }
    }

    public function lastError(): void {
        // error_get_last — 获取最后发生的错误
        $last = error_get_last();
        // set_error_handler有些错误是无法获取的，所以加个判断
        $type = (int)$last['type']??0;
        if(in_array($type,[E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR,E_COMPILE_WARNING],true)){
            $this->printLog( $type,$last['message'],$last['file'],$last['line'] );
        }
    }

    private function printLog( $code, $message,$file ,$line,$trace='' ): void {
        // 拼接错误信息
        $errStr  =  date('Y-m-d h:i:s').NEW_LINE.NEW_LINE;
//        $errStr .= '  错误级别：'.$code.NEW_LINE;
        $errStr .= '  模块：'.Route::$MODULE.' 控制器：'.Route::$CONTROLLER.' 方法：'.Route::$ACTION.NEW_LINE.NEW_LINE;
        $errStr .= '  错误信息：'.$message.NEW_LINE;
        $errStr .= '  错误文件：'.$file.NEW_LINE;
        $errStr .= '  错误行数：'.$line.NEW_LINE;
        $errStr .= '  错误详情：'.$trace.NEW_LINE;
        $errStr .= NEW_LINE;

        // error_log — 发送错误信息到某个地方
        Template::output($errStr,true);
    }
}