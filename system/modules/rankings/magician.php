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
echo '<h3>'.lang('module_titles_txt_10',true).' for '.lang('rankings_txt_35',true).'</h3><hr>';


loadModuleConfigs('rankings');

// magician class Rankings
try {
		if((!ranconfig('active')) || (!ranconfig('rankings_enable_magician'))) throw new Exception(lang('error_44',true));

		templateBuildRankingsMagician();
		
	} catch(Exception $ex) {
	message('error', $ex->getMessage());
}