<?php

define('ROOT_PATH',dirname(__DIR__).DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'core' . DIRECTORY_SEPARATOR . 'Loader.php';

\core\App::run();