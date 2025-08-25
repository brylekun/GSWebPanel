<?php
function pOS_SortArray($a, $b) {
    return $a["order"] - $b["order"];
}

if(check_value($_REQUEST['page'])) {
	$subPagePath = __PATH_ADMINCP_MODULES__.$_REQUEST['module'].'/'.$_REQUEST['page'].'.php';
	if(file_exists($subPagePath)) {
		$tConf = gconfig('webmoney');
		//$pOS = new plugin_EShop();
		$pagePath = __PATH_ADMINCP_HOME__.'?module='.$_REQUEST['module'].'&page='.$_REQUEST['page'].'';
		include($subPagePath);
	} else {
		message('error','Invalid request.');
	}
} else {
	message('error','Invalid request.');
}
?>