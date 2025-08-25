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
<section class="content-header"><h1> Registration Settings </h1></section>
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
	$xmlPath = __PATH_MODULE_CONFIGS__.'register.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->register_enable_recaptcha = $_POST['setting_2'];
	$xml->register_recaptcha_site_key = $_POST['setting_3'];
	$xml->register_recaptcha_secret_key = $_POST['setting_4'];
	$xml->send_welcome_email = $_POST['setting_6'];
	$xml->verify_email = $_POST['setting_5'];
	$xml->verification_timelimit = $_POST['setting_7'];
	
	$save = $xml->asXML($xmlPath);
	if($save) {
		message('success','Settings successfully saved.');
	} else {
		message('error','There has been an error while saving changes.');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('register');
?>
<form action="" method="post">
	<table class="table table-bordered">
	 <tbody>
		<tr>
			<th style="width: 300px">Status<br/><h6>Enable/disable the registration module.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Recaptcha<br/><h6>Enable/disable Recaptcha validation. <br/><br/> <a href="http://www.google.com/recaptcha" target="_blank">http://www.google.com/recaptcha</a></h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_2',ranconfig('register_enable_recaptcha'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Recaptcha Site Key<br/></th>
			<td>
				<input class="form-control" type="text" name="setting_3" value="<?=ranconfig('register_recaptcha_public_key')?>"/>
			</td>
		</tr>
		<tr>
			<th>Recaptcha Secret Key<br/></th>
			<td>
				<input class="form-control" type="text" name="setting_4" value="<?=ranconfig('register_recaptcha_private_key')?>"/>
			</td>
		</tr>
		<tr>
			<th>Email Verification<br/><h6>If enabled, the user will receive an email with a verification link. The accout will not be created if the email is not verified.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_5',ranconfig('verify_email'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Send Welcome Email<br/><h6>Sends a welcome email after registering a new account.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_6',ranconfig('send_welcome_email'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Verification Time Limit<br/><h6>If <strong>Email Verification</strong> is Enabled. Set the amount of time the user has to verify the account. After the verification time limit passed, the user will have to repeat the registration process.</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_7" value="<?=ranconfig('verification_timelimit')?>"/> Hour(s)
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
		</tbody>
	</table>
</form>

</div>
	</div>
</section>