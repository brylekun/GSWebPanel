<section class="content-header"><h1> Reward settings </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.reward.xml';
	$xml = simplexml_load_file($xmlPath);

	$xml->max_level = $_POST['setting_1'];
	$xml->product_num = $_POST['setting_2'];
	
	
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

loadModuleConfigs('usercp.reward');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Level Required<br/><h6>What's your desired level for claiming rewards!</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_1" value="<?=ranconfig('max_level')?>"/>
			</td>
		</tr>
		<tr>
			<th>Product Number<br/><h6>Input your product number for your item reward!</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_2" value="<?=ranconfig('product_num')?>"/>
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