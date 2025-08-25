<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 3.0.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

if(!isLoggedIn()) redirect(1,'login');

try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
	
	if(ranconfig('change_password_email_verification') && glenox::hasActivePasswordChangeRequest($_SESSION['userid'])) {
		throw new Exception(lang('error_19',true));

	}
	
	if(check_value($_POST['submit'])) {
		try {
			//$Account = new Account($dB1, $dB2, $dB3, $dB4);
			
			if(ranconfig('change_password_email_verification')) {
				# verification required
				account::changePasswordProcess_verifyEmail($_SESSION['userid'],$_POST['pincode'],$_POST['password'],$_POST['new_password'],$_POST['confirm_new_password'],$_SERVER['REMOTE_ADDR']);
			} else {
				# no verification
				account::cPasswordProcess($_SESSION['userid'],$_POST['pincode'],$_POST['password'],$_POST['new_password'],$_POST['confirm_new_password']);
			}
			//message('success', lang('success_2',true));
			//redirect(2,'usercp/myaccount',3);
		} catch (Exception $ex) {
			message('error', $ex->getMessage());

		}
	}
	
	templateBuileChangePass();
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
	redirect(2,'usercp/myaccount',3);
}