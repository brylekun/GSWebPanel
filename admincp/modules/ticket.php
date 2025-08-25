<?php

/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 8/6/2017
 */

function pOS_SortArray($a, $b) {
    return $a["order"] - $b["order"];
}

if(check_value($_REQUEST['page'])) {
	$subPagePath = __PATH_ADMINCP_MODULES__.$_REQUEST['module'].'/'.$_REQUEST['page'].'.php';
	if(file_exists($subPagePath)) {
		$tConf = gconfig('ticket');
		$pOS = new plugin_ticket();
		$pagePath = __PATH_ADMINCP_HOME__.'?module='.$_REQUEST['module'].'&page='.$_REQUEST['page'].'';
		include($subPagePath);
	} else {
		message('error','Invalid request.');
	}
} else {
	message('error','Invalid request.');
}
?>