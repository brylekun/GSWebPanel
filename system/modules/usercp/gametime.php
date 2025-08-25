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

(!isLoggedIn()) ? redirect(1,'login') : null;
		
try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
			

			$gt_m = ranconfig('mins_to_convert');
			$gt_p = ranconfig('get_vpoints');
			
			// Vote Process
			if(check_value($_POST['submit'])) {
				
				account::gtopProccess($_SESSION['userid'],$gt_m, $gt_p);
				
			}
			

			$gametimeinfo = glenox::accountInformation($_SESSION['userid']);

			$Minutes=($gametimeinfo['Gametime3']) / ($gt_m);
			$hours = (int)$Minutes;
			$MinutesTotal = $gametimeinfo['Gametime3']-$gt_m*$hours;
			$ups = $gt_p * $hours;

			templateBuildGameTime($Minutes,$hours,$MinutesTotal,$ups,$gt_m,$gt_p);


} catch(Exception $ex) {
	message('error', $ex->getMessage());
}