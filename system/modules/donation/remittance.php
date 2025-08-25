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

(!isLoggedIn()) ? redirect(1,'login') : null;

try {

	echo '<div class="header"><h2>'.lang('module_titles_txt_11',true).' &rarr; Remittance</h2></div>';
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));


	$loadCmsCache = glenox::LoadCachedCms('remitance');
			
	echo '<div id="post_wrapper">';

	echo '<br /><br />';
		echo '<div class="table">';
			echo ''.$loadCmsCache.'';
		echo '</div>';
	echo '</div>';

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}
?>