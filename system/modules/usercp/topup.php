<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

if(!isLoggedIn()) redirect(1,'login');

try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
	
	
	if(check_value($_POST['submit'])) {
		try {

			if(ranconfig('register_enable_recaptcha')) {

				$verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.ranconfig('register_recaptcha_private_key').'&response='.$_POST['g-recaptcha-response'],false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false))));
    			
				$resp = json_decode($verify,true);
				
				
				if(!$resp['success']==true) {

					throw new Exception(lang('error_18',true));
				}
			}

			account::topupProccess($_SESSION['userid'],$_POST['pin'],$_POST['code']);
			
			
		} catch (Exception $ex) {
			message('error', $ex->getMessage());

		}
	}
	
	templateBuildtopup();
	
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
	redirect(2,'usercp/myaccount',3);
}