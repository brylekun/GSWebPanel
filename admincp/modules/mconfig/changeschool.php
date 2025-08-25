<section class="content-header"><h1> Change School Settings </h1></section>
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
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.changeschool.xml';
	$xml = simplexml_load_file($xmlPath);

	$xml->active = $_POST['setting_1'];
	$xml->costvp = $_POST['setting_2'];
	$xml->costep = $_POST['setting_3'];
	$xml->cooldown = $_POST['setting_4'];
	$xml->cooldowntime = $_POST['setting_5'];
	
	
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

loadModuleConfigs('usercp.changeschool');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><h6>Enable/disable the change school system.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Cost V-Points<br/><h6>Cost for Vpoints after change school. Set 0 to disable</h6></th>
			<td>
				<input class="input-mini" type="text" name="setting_2" value="<?=ranconfig('costvp')?>"/>
			</td>
		</tr>
		<tr>
			<th>Cost E-Points<br/><h6>Cost for Epoints after change school. Set 0 to disable</h6></th>
			<td>
				<input class="input-mini" type="text" name="setting_3" value="<?=ranconfig('costep')?>"/>
			</td>
		</tr>
		<tr>
			<th>Cooldown<br/><h6>Enable/disable for cooldown .</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_4',ranconfig('cooldown'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Cooldown time<br/><h6>1 = 1 hour</h6></th>
			<td>
				<input class="input-mini" type="text" name="setting_5" value="<?=ranconfig('cooldowntime')?>"/> Hour(s)
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