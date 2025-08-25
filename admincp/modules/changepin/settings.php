<section class="content-header"><h1> Change pin settings </h1></section>
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
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.changepin.xml';
	$xml = simplexml_load_file($xmlPath);

	$xml->active = $_POST['setting_1'];
	
	
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

loadModuleConfigs('usercp.changepin');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the change pin system.</span></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('active'),'Enabled','Disabled'); ?>
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