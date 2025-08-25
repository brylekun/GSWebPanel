<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 8/8/2017
 */

(!isLoggedIn()) ? redirect(1,'login') : null;

	try {

		if(!ranconfig('active')) throw new Exception('The module are disable by administrator');

		$itemshop = new vshop();

			if(check_value($_POST['submit'])) {
				
					//$password->cPasswordProcess($_SESSION['userid'],$_POST['pincode'],$_POST['password'],$_POST['new_password'],$_POST['confirm_new_password']);
					
					$itemshop->proccessItem($_SESSION['userid'],Decode_id($_POST['ItemNum']));
			}
			
			templateBuildVshop($itemshop);

	} catch (Exception $ex ) {

		message('error', $ex->getMessage());
		redirect(2,'usercp/myaccount/',3);

	}
			
			

?>