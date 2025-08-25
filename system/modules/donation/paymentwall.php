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
	
	// Load Paymentwall Settings
	loadModuleConfigs('donation.paymentwall');
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
	
	# Load Paymentwall Widget
	require_once(__PATH_CLASSES__ . 'paymentwall/paymentwall.php');
	Paymentwall_Base::setApiType(Paymentwall_Base::API_VC);
	Paymentwall_Base::setAppKey(ranconfig('app_key'));
	Paymentwall_Base::setSecretKey(ranconfig('secret_key'));

	$widget = new Paymentwall_Widget(
		$_SESSION['userid'], 
		'p10',
		array()
	);
	echo $widget->getHtmlCode(array('width' => 636, 'height' => 500));
	
} catch (Exception $ex) {
	message('error', $ex->getMessage());
}