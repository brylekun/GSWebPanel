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

echo '<div class="header"><h2>'.lang('profiles_txt_2',true).'</h2></div>';
	
			loadModuleConfigs('profiles');

try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
			
				if(check_value(Decode_id($_GET['req']))) {

					try {
						$weProfiles = new weProfiles();
						$weProfiles->setType("player");
						$weProfiles->setRequest(Decode_id($_GET['req']));
						$cData = $weProfiles->data();
						
						templateBuildPlayer($cData,$custom);

					} catch(Exception $e) {
						message('error', $e->getMessage());
					}
				} else {
					message('error', lang('error_25',true));
				}

} catch(Exception $ex) {
	message('error', $ex->getMessage());
	redirect(2,'usercp/myaccount',3);
}