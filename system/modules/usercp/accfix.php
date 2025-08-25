<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 1.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 9/21/2016
 */

(!isLoggedIn()) ? redirect(1,'login') : null;

echo '<div class="header"><h2>'.lang('module_titles_txt_16',true).'</h2></div>';


		if(ranconfig('active')) {
			
			$accfix = glenox::AccountFix($_SESSION['userid']);
			
			if($accfix){
				message('success', lang('success_11',true));
				# redirect to login (5 seconds)
				
			} else {
				message('error', lang('error_59',true));
				redirect(2,'usercp/myaccount/',3);
			}
			
			
	} else {
		message('error', lang('error_47',true));
	}
	
?>