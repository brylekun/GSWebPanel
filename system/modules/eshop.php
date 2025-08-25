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

		if(!ranconfig('active')) throw new Exception(lang('error_47',true));

					$itemshop = new eshop();

					if(check_value($_POST['submit'])) {
						
							
							$itemshop->proccessItem($_SESSION['userid'],Decode_id($_POST['ItemNum']));
					}
					

					templateBuildEshop($itemshop);

	} catch (Exception $ex ) {

		message('error', $ex->getMessage());
		redirect(2,'usercp/myaccount/',3);

	}
			
			

?>