<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 9/21/2016
 */

(!isLoggedIn()) ? redirect(1,'login') : null;

echo '<div class="header"><h2>'.lang('module_titles_txt_29',true).'</h2></div>';

try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));

			
	templateBuildChangeSchool();

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}