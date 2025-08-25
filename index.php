<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

define('access', 'index');

try {
	

	if(!@include_once('system/core.php')) throw new Exception('Could not load the core of website.');
	
} catch (Exception $ex) {

	$errorPage = file_get_contents('system/error.html');
	echo str_replace("{ERROR_MESSAGE}", $ex->getMessage(), $errorPage);
	
}
