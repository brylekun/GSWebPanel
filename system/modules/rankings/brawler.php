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
loadModuleConfigs('rankings');

try {
		if((!ranconfig('active')) || (!ranconfig('rankings_enable_brawler'))) throw new Exception(lang('error_44',true));

		templateBuildRankingsBrawler();

	} catch(Exception $ex) {
	message('error', $ex->getMessage());
}