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
	
	if(!ranconfig('active')) throw new Exception(lang('error_17',true));

			// Register Process
	if(check_value($_POST['Register_submit'])) {
		try {

			
			$Account = new Account();

			if(ranconfig('register_enable_recaptcha')) {

				$verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.ranconfig('register_recaptcha_private_key').'&response='.$_POST['g-recaptcha-response'],false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false))));
    			
				$resp = json_decode($verify,true);
				
				
				if(!$resp['success']==true) {

					throw new Exception(lang('error_18',true));
				}
			}
			
			$Account->registerAccount($_POST['Register_user'], $_POST['Register_pwd'], $_POST['Register_pwdc'], $_POST['Register_email']);
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}

		templateBuildRegister();

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}