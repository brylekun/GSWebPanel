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

function admincp_base($module="") {
	if(check_value($module)) return __PATH_ADMINCP_HOME__ . "index.php?module=" . $module;
	return __PATH_ADMINCP_HOME__.'index.php';
}

function enabledisableCheckboxes($name,$checked,$e_txt,$d_txt) {
	echo '<div class="radio">';
	echo '<label class="radio">';
	if($checked == 1) {
		echo '<input type="radio" name="'.$name.'" value="1" checked>';
	} else {
		echo '<input type="radio" name="'.$name.'" value="1">';
	}
	echo $e_txt;
	echo '</label>';
	echo '<label class="radio">';
	if($checked == 0) {
		echo '<input type="radio" name="'.$name.'" value="0" checked>';
	} else {
		echo '<input type="radio" name="'.$name.'" value="0">';
	}
	echo $d_txt;
	echo '</label>';
	echo '</div>';
}

function tableExists($table_name, $db4) {
	$tableExists = $db4->query_fetch_single("SELECT * FROM sysobjects WHERE xtype = 'U' AND name = ?", array($table_name));
	if(!$tableExists) return false;
	return true;
}
