<?php

define('ROOT_PATH',dirname(__DIR__).DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'core' . DIRECTORY_SEPARATOR . 'Loader.php';

global $argv;

$run = $argv[1] ?? 'start';

if(!in_array($run,['start','stop'])){
    die("目前仅支持：start|stop\n");
}

(new \app\server\Work())->$run();
