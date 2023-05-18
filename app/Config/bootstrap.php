<?php

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));
define('__SITE_URL',"http://".$_SERVER['HTTP_HOST'].'/jamnama/');
$site_path = realpath(dirname(__FILE__));

define('__UPLOAD_IMAGE_MAX_SIZE',2024000);
define('__UPLOAD_IMAGE_MAX_HEIGHT',20240);
define('__UPLOAD_IMAGE_MAX_WIDTH',20240);
define('__UPLOAD_IMAGE_EXTENSION',"jpg,png");
define('__UPLOAD_PDF_EXTENSION',"pdf");
define('__UPLOAD_File_EXTENSION',"doc,docx");
define('__UPLOAD_FILE_MAX_SIZE',25000);
define('__UPLOAD_PDF_MAX_SIZE',5097152);
define('__UPLOAD_THUMB',"thumb");

define ('__SITE_PATH', $site_path.DS);
define("__BackUp_Path","uploads".DS."backup".DS); 
define("__USER_IMAGE_PATH","uploads/users/"); 
define ('__PLUGINS', ROOT . DS . 'plugins' . DS);
define ('__USER','user/');
define ('__IMAGE_PATH','img/'.__USER);

Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
App::uses('PluginHandler', 'Lib');
PluginHandler::Instance()->load();
