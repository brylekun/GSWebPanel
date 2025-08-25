<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */
header('Content-Type: text/html; charset=utf-8');
#License Key :)
define('__LICENSE_KEY___','NULLED');
# Version
define('__RANPANEL_VERSION__', '2.1.0');

#Panel Copyright
define('___PANEL_COPY___', '<a href="https://facebook.com/Parad0x25" target="_blank"> Copyright © 2018 Ran Panel v'.__RANPANEL_VERSION__.' Paradox25</a>');

# Set Encoding
ini_set('mssql.charset', 'UTF-8');

# Server Time
# http://php.net/manual/en/timezones.php
date_default_timezone_set('Asia/Kuala_Lumpur');

if(!isset($_SERVER['SCRIPT_NAME'])) $_SERVER['SCRIPT_NAME'] = '';
if(!isset($_SERVER['SCRIPT_FILENAME'])) $_SERVER['SCRIPT_FILENAME'] = '';

# Global Paths
define('HTTP_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI');
define('SERVER_PROTOCOL', (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://');
define('__ROOT_DIR__', str_replace('\\','/',dirname(dirname(__FILE__))).'/'); // /home/user/public_html/
define('__RELATIVE_ROOT__', str_ireplace(rtrim(str_replace('\\','/', realpath(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']))), '/'), '', __ROOT_DIR__));// /
//define('__BASE_URL__', SERVER_PROTOCOL.HTTP_HOST.__RELATIVE_ROOT__); // http(s)://www.mysite.com/
#for linux or cPanel
define('__BASE_URL__', SERVER_PROTOCOL.HTTP_HOST.__RELATIVE_ROOT__); // http(s)://www.mysite.com/
#for windows or plesk panel
//define('__BASE_URL__', SERVER_PROTOCOL.HTTP_HOST.'/'); 

# CMS Paths
define('__PATH_SYSTEM__', __ROOT_DIR__.'system/');
define('__PATH_TEMPLATES__', __ROOT_DIR__.'templates/');
define('__PATH_LANGUAGES__', __PATH_SYSTEM__ . 'languages/');
define('__PATH_CLASSES__', __PATH_SYSTEM__.'classes/');
define('__PATH_FUNCTIONS__', __PATH_SYSTEM__.'functions/');
define('__PATH_MODULES__', __PATH_SYSTEM__.'modules/');

define('__PATH_MODULES_USERCP__', __PATH_MODULES__.'usercp/');
define('__PATH_EMAILS__', __PATH_SYSTEM__.'emails/');
define('__PATH_CACHE__', __PATH_SYSTEM__.'cache/');
define('__PATH_ADMINCP__', __ROOT_DIR__.'admincp/');
define('__PATH_ADMINCP_INC__', __ROOT_DIR__.'admincp/inc/');
define('__PATH_ADMINCP_MODULES__', __ROOT_DIR__.'admincp/modules/');

define('__PATH_NEWS_CACHE__', __PATH_CACHE__.'news/');
define('__PATH_CMS_CACHE__', __PATH_CACHE__.'donation/');
define('__PATH_PLUGINS__', __PATH_SYSTEM__.'plugins/');
define('__PATH_CONFIGS__', __PATH_SYSTEM__.'config/');
define('__PATH_MODULE_CONFIGS__', __PATH_CONFIGS__.'modules/');
define('__PATH_CRON__', __PATH_SYSTEM__.'cron/');

# Public Paths
define('__PATH_MODULES_RANKINGS__', __BASE_URL__.'rankings/');
define('__PATH_ADMINCP_HOME__', __BASE_URL__.'admincp/');
#Load PHP Mailer

# Auto Loading classes and function
$sapi_type = php_sapi_name();

function __autoload($name){$fn=array(__PATH_CLASSES__,__PATH_FUNCTIONS__);if(file_exists($fn[0].$name.".php")){include_once $fn[0].$name.".php";}else{include_once $fn[1].$name.".php";}}
# Load Functions
if(!@include_once(__PATH_SYSTEM__ . 'functions.php')) throw new Exception('Could not load functions.');

# Load Configurations
$config = ran_Configs();

define('__PATH_TEMPLATE__', __BASE_URL__ . 'templates/' . $config['website_template'] . '/');
define('__PATH_TEMPLATE_IMG__', __PATH_TEMPLATE__ . 'images/');

# CMS Status
if(!$config['system_active']) {
	throw new Exception('The website is currently under maintenance, please try again later.');
}

# Error Reporting
if($config['error_reporting']) {
	ini_set('display_errors', true);
	error_reporting(E_ALL & ~E_NOTICE);
} else {
	ini_set('display_errors', false);
	error_reporting(0);
}

# Load Custom Data
if(!@include_once(__PATH_SYSTEM__.'custom.php')) throw new Exception('Could not load custom data.');


# Anti-flood System
if($config['flood_check_enable']) {
	if(!check_value($_SESSION['track_timestamp'])) {
		$_SESSION['track_timestamp'] = time();
		$_SESSION['track_actions'] = 0;
	}
	
	if(time() > $_SESSION['track_timestamp']+60) {
		$_SESSION['track_timestamp'] = time();
		$_SESSION['track_actions'] = 0;
	}
	
	if($_SESSION['track_actions'] >= $config['flood_actions_per_minute']) throw new Exception('Flood limit reached, please try again in a moment.');
	
	$_SESSION['track_actions'] += 1;
}

if(config('plugins_system_enable')) {
	if(plugins::gotEnabledPlugins()) {
		$pluginsCACHE = LoadCacheData('plugins.cache');
		$pli = 0;
		foreach($pluginsCACHE as $thisPlugin) {
			if($pli >= 1) {
				$pPath = plugins::pluginPath($thisPlugin[0]);
				$pFiles = explode("|",$thisPlugin[1]);
				foreach($pFiles as $pFile) {
					if(!@include_once($pPath.$pFile)) throw new Exception('Could not load plugin file ('.$pPath.$pFile.').');
				}
			}
			$pli++;
		}
	}
}

# Start Website
if(!substr($sapi_type, 0, 3) == 'cli' || !empty($_SERVER['REMOTE_ADDR'])) {
glenox::Start();
}