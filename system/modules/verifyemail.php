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

echo '<h3>'.lang('module_titles_txt_20',true).'</h3><hr>';
	
	if(check_value($_GET['op'])) {
		
		/* Email Verification Operations:
		|	1. Password Change Request
		|	2. Registration
		|	3. Email Change Request
		*/
		
		switch(Decode_id($_GET['op'])) {
			case 1:
				if(!check_value($_GET['uid'])) redirect();
				if(!check_value($_GET['ac'])) redirect();
				try {
					$Account = new Account();
					$Account->changePasswordVerificationProcess($_GET['uid'],$_GET['ac']);
				} catch (Exception $ex) {
					message('error', $ex->getMessage());
				}
				break;
			case 2:
				# REGISTER: EMAIL VERIFICATION
				if(!check_value($_GET['user'])) redirect();
				if(!check_value($_GET['key'])) redirect();
				try {
					$Account = new Account();
					$Account->verifyRegistrationProcess(Decode($_GET['user']),$_GET['key']);
				} catch (Exception $ex) {
					message('error', $ex->getMessage());
				}
				break;
			default:
				if(!check_value($_GET['uid'])) redirect();
				if(!check_value($_GET['email'])) redirect();
				if(!check_value($_GET['key'])) redirect();
				try {
					$Account = new Account();
					$Account->changeEmailVerificationProcess($_GET['uid'],$_GET['email'],$_GET['key']);
					message('success', lang('success_20',true));
				} catch (Exception $ex) {
					message('error', $ex->getMessage());
				}
		}
		
	} else {
		redirect();
	}
	
?>