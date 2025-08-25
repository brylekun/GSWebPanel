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

if(isLoggedIn()) redirect();


try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
	
	if(check_value($_GET['ui']) && check_value($_GET['ue']) && check_value($_GET['key'])) {
		
		# recovery process
		try {
			$Account = new Account();
			$Account->passwordRecoveryVerificationProcess($_GET['ui'], $_GET['ue'], $_GET['key']);
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
		
	} else {
		
		# form submit
		if(check_value($_POST['Email_submit'])) {
			try {
				$Account = new Account();
				$Account->passwordRecoveryProcess($_POST['Email_current'],$_POST['UserID_current'], $_SERVER['REMOTE_ADDR']);
			} catch (Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
		
		templateBuildForgotPass();
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}