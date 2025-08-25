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
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));

		templateBuildMyAccount();

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}