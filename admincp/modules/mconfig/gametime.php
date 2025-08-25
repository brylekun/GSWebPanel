<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 8/6/2017
 */
?>
<section class="content-header"><h1> Convert GameTime Settings </h1></section>
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
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.gametime.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->mins_to_convert = $_POST['setting_2'];
	$xml->get_vpoints = $_POST['setting_3'];
	
	
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

loadModuleConfigs('usercp.gametime');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th style="width: 300px">Status<br/><h6>Enable/disable the login module.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Minute's<br/><h6>How many minute's to Convert</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_2" value="<?=ranconfig('mins_to_convert')?>"/>
			</td>
		</tr>
		<tr>
			<th>Vpoints Reward<br/><h6>How Many vpoints to reward</h6></th>
			<td>
				<input class="form-control" type="text" name="setting_3" value="<?=ranconfig('get_vpoints')?>"/>
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