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

echo '<div class="header"><h2>'.lang('module_titles_txt_10',true).' for '.lang('rankings_txt_36',true).'</h2></div>';


loadModuleConfigs('rankings');

try {
		if((!ranconfig('active')) || (!ranconfig('rankings_enable_shaper'))) throw new Exception(lang('error_44',true));

		templateBuildRankingsShaper();
		
	} catch(Exception $ex) {
	message('error', $ex->getMessage());
}