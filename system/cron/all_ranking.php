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

 // File Name
$file_name = basename(__FILE__);

$Rankings = new Rankings();

 // Load Ranking Configs
 loadModuleConfigs('rankings');
 
 if(ranconfig('active')) {
	 if(ranconfig('rankings_enable_all')) {
		 $Rankings->UpdateRankingCache('all');
	 }
 }
 updateCronLastRun($file_name);