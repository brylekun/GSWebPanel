<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

 (!isLoggedIn()) ? redirect(1,'login') : null;

 try {
 			if(!ranconfig('active')) throw new Exception(lang('error_47',true));

 			$convert = new Convert();

 			$rate = config('eptovp');


 			if(check_value($_POST['submit'])) {
				
				//$account->gtopProccess($_SESSION['userid'],$gt_m, $gt_p);
				$convert->eptovp($_SESSION['userid'],$rate,$_POST['ep']);
			}

		templateBuildEPtoVP($convert,$rate);

 } catch (Exception $ex){
	message('error', $ex->getMessage());
	redirect(2,'usercp/myaccount',3);

 }