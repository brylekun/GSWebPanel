<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 8/1/2017
 */

// Load Core
include(str_replace('\\','/',dirname(dirname(__FILE__))).'/' . 'core.php');

// Load Cache Data
$cacheDATA = LoadCacheData('cron.cache');

foreach($cacheDATA as $key => $thisCRON) {
	if($key != 0) {
		$cron = array(
			'id' => $thisCRON[0],
			'file' => $thisCRON[3],
			'run_time' => $thisCRON[5],
			'last_run' => $thisCRON[4],
			'status' => $thisCRON[6]
		);
		
		if($cron['status'] == 1) {
			if(!check_value($cron['last_run'])) {
				$lrtime = $cron['run_time'];
			} else {
				$lrtime = $cron['last_run']+$cron['run_time'];
				
			}
			if(time() >= $lrtime) {
				$filePath = __PATH_CRON__.$cron['file'];
				if(file_exists($filePath)) {
					debug('[Run] ' . $thisCRON[1]);
					include($filePath);
					debug('<-- Done');
				}
			}
		}
	}
}
