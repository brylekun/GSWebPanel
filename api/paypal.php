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
namespace Listener;
include('../system/core.php');

// Load PayPal Settings
loadModuleConfigs('donation.paypal');

use PaypalIPN;
$ipn = new PaypalIPN();

$ipn->useSandbox();
$verified = $ipn->verifyIPN();
var_dump($verified);
if ($verified) {
    /*
     * Process IPN
     * A list of variables is available here:
     * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
     */

	 // assign posted variables to local variables
	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number']; // order id = md5(time())
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$txn_type = $_POST['txn_type'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$account_id = $_POST['custom'];
	$user_id = Decode($account_id); // decoded user id

	if(strtolower($receiver_email) == strtolower(ranconfig('paypal_email'))) {
	if(($txn_type == 'web_accept' OR $txn_type == 'subscr_payment') AND $payment_status == 'Completed') {
		if($tax > 0) { $payment_amount -=$tax; }
		
		/* Donation amount */
		$add_credits = floor($payment_amount*ranconfig('paypal_conversion_rate'));
		
		/* Add credits */
		try {
			# user id
			if(!Validator::UnsignedNumber($user_id)) throw new Exception("invalid userid");
			
			# account info
			$accountInfo = glenox::accountInformation($user_id);

			if(!is_array($accountInfo)) throw new Exception("invalid account");
			

			//glenox::setIdentifier($accountInfo['UserNum']);
			
			
			$_GET['page'] = 'api';
			$_GET['subpage'] = 'paypal';
			
			glenox::addCredits($add_credits,$accountInfo['UserNum']);
			

		} catch (Exception $ex) {
			throw new Exception($ex);
		}
		
		/* Create transaction */
		glenox::paypal_transaction($txn_id,$user_id,$payment_amount,$payer_email,$item_number);
		
	} elseif($payment_status == 'Reversed' OR $payment_status == 'Refunded') {
		
		glenox::blockAccount($user_id);
		glenox::paypal_transaction_reversed_updatestatus($item_number);
		
	}

	}
}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
header("HTTP/1.1 200 OK");


?>