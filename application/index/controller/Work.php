<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\pcntl\Manager;
use app\common\pcntl\process\Master;
use app\common\pcntl\process\Process;
use app\common\Tools;
use core\lib\Output;
use core\lib\Request;
use core\lib\Rule;

/**
 * Class Work
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package app\server
 */
class Work extends Base
{
    public function start(): void {
        //重启的时候，清除pid文件
        $this->stop();

        Master::printLog('>>开启进程<<');
        (new Manager())->run();
    }

    public function stop(): void {

        Master::printLog('>>退出进程<<');

        $times = 10;
        while($times--) {
            Master::killProcess();
            Master::printLog("剩余进度{$times}/10");
            sleep(1);
        }

        Master::printLog('>>成功关闭<<');
    }

    public function webRestart(): void {
        system('php index.php /index/Work/start $s > /dev/null &');
        Output::json([
            'code'=>200,
            'msg'=>'success',
            'data'=>[]
        ]);
    }

    public function webStop(): void {
        system('php index.php /index/Work/stop $s > /dev/null &');
        Output::json([
            'code'=>200,
            'msg'=>'success',
            'data'=>[]
        ]);
    }

    public function webLog(): void {
        $post = Request::post();
        $rule = [
            'logName'=>'require',
        ];
        if(Rule::check($post,$rule) === false){
            Output::json([
                'code'=>200,
                'msg'=>Rule::getError(),
                'data'=>[]
            ]);
        }

        Output::json([
            'code'=>200,
            'msg'=>'success',
            'data'=>['context'=>$this->getLogContext($post['logName'])]
        ]);

    }

    private function getLogContext($logName){

        $filePath = Process::getLogFile($logName);
        if(!file_exists($filePath)){
            Output::json([
                'code'=>200,
                'msg'=>'日志文件不存在',
                'data'=>[]
            ]);
        }

        $context = Tools::getLastLines($filePath,100);
        return str_replace("\n",'<br/>',$context);
    }


}