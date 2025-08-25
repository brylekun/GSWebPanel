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
			
			
			// Vote Process
			if(check_value($_POST['submit'])) {
				vote::voteProcess($_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_POST['voting_site_id']);
			}

			 templateBuildVote();

} catch(Exception $ex) {
	message('error', $ex->getMessage());
	redirect(2,'usercp/myaccount',3);
}