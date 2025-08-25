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

?>
<section class="content-header"><h1> PayPal Settings </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-9">
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	
	// PAYPAL
	$xmlPath = __PATH_MODULE_CONFIGS__.'donation.paypal.xml';
	$xml = simplexml_load_file($xmlPath);
	$xml->active = $_POST['setting_2'];
	$xml->paypal_enable_sandbox = $_POST['setting_3'];
	$xml->paypal_email = $_POST['setting_4'];
	$xml->paypal_title = $_POST['setting_5'];
	$xml->paypal_currency = $_POST['setting_6'];
	$xml->paypal_return_url = $_POST['setting_7'];
	$xml->paypal_notify_url = $_POST['setting_8'];
	$xml->paypal_conversion_rate = $_POST['setting_9'];
	$xml->credit_config = $_POST['setting_10'];
	$save2 = $xml->asXML($xmlPath);
	

	if($save2) {
		message('success','[PayPal] Settings successfully saved.');
	} else {
		message('error','[PayPal] There has been an error while saving changes.');
	}

}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('donation.paypal');

//$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><h6>Enable/disable the paypal donation gateway.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_2',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>PayPal Sandbox Mode<br/><h6>Enable/disable PayPal's IPN testing mode.<br/><br/>More info:<br/><a href="https://developer.paypal.com/" target="_blank">https://developer.paypal.com/</a></h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_3',ranconfig('paypal_enable_sandbox'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>PayPal Email<br/><h6>PayPal email where you will receive the donations.</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_4" value="<?=ranconfig('paypal_email')?>"/>
			</td>
		</tr>
		<tr>
			<th>PayPal Donations Title<br/><h6>Title of the PayPal donation. Example: "Donation for Ran Online EP".</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_5" value="<?=ranconfig('paypal_title')?>"/>
			</td>
		</tr>
		<tr>
			<th>Currency Code<br/><h6>List of available PayPal currencies: <a href="https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes" target="_blank">click here</a>.</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_6" value="<?=ranconfig('paypal_currency')?>"/>
			</td>
		</tr>
		<tr>
			<th>Return/Cancel URL<br/><h6>URL where the client will be redirected to if the donation is cancelled or completed.</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_7" value="<?=ranconfig('paypal_return_url')?>"/>
			</td>
		</tr>
		<tr>
			<th>IPN Notify URL<br/><h6>URL of RanPanel PayPal API.<br/><br/> By default it has to be in: <b>http://YOURWEBSITE.COM/api/paypal.php</b></h6></th>
			<td>
				<input class="form-control" type="text" name="setting_8" value="<?=ranconfig('paypal_notify_url')?>"/>
			</td>
		</tr>
		<tr>
			<th>Credits Conversion Rate<br/><h6>How many game credits is equivalent to 1 of real money currency.<br/><br/>Example:<br/>1 USD = 100 Credits, in this example you would type in the box 100.</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_9" value="<?=ranconfig('paypal_conversion_rate')?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>

</div>
	</div>
</section>