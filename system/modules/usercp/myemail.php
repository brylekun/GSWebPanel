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

if(!isLoggedIn()) redirect(1,'login');


try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
	
	if(check_value($_POST['Email_submit'])) {
		try {
			account::changeEmailAddress($_SESSION['userid'], $_POST['PinCode'], $_POST['Email_newemail'], $_SERVER['REMOTE_ADDR']);
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	
	templateBuildChangeEmail();
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}