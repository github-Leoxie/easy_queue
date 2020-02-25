<?php
/**
 * Auth  leo.xie
 * mail 811329263@qq.com
 */
//定义系统常量的地方
define('DS',DIRECTORY_SEPARATOR);
define('EXT','.php');
define('LANG','EN');
define('COLON',':');

define('IS_CLI', (bool)(PHP_SAPI == 'cli'));
define('NEW_LINE', IS_CLI ? PHP_EOL : '<br/>');//cgi和cli下的换行
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);

define('APP_PATH',ROOT_PATH.'application'.DS);
define('CORE_PATH',ROOT_PATH.'core'.DS);
define('LIB_PATH',CORE_PATH.'lib'.DS);
define('LOG_PATH',ROOT_PATH.'runtime'.DS.'log'.DS);
define('STORAGE_PATH',ROOT_PATH.'storage'.DS);

define('CSS_PATH','/static'.DS.'css'.DS);
define('JS_PATH','/static'.DS.'js'.DS);
define('TEMPLATE_EXT','.html');



