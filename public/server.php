<?php

define('ROOT_PATH',dirname(__DIR__).DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'core' . DIRECTORY_SEPARATOR . 'Loader.php';

global $argv;

$run = $argv[1] ?? 'start';

(new \app\server\Work())->$run();
