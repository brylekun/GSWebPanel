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
<section class="content-header"><h1> ClubWar Settings </h1></section>
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
	$xmlPath = __PATH_MODULE_CONFIGS__.'clubwar.xml';
	$xml = simplexml_load_file($xmlPath);

	$xml->active = $_POST['setting_5'];
	$xml->enable_banner = $_POST['setting_1'];
	$xml->cw_battle_day = $_POST['setting_2'];
	$xml->cw_battle_time = $_POST['setting_3'];
	$xml->cw_battle_duration = $_POST['setting_4'];
	
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

loadModuleConfigs('clubwar');

message('','Only works for weekly (every 7 days) Club War schedule!','BETA:');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><h6>Enable/disable the Club War module.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_5',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Banner Status<br/><h6>Enable/disable the Club War countdown banner.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('enable_banner'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>CW Battle Day<br/><h6>Options:<ol><li>Monday</li><li>Tuesday</li><li>Wednesday</li><li>Thursday</li><li>Friday</li><li>Saturday</li><li>Sunday</li></ol></h6></th>
			<td>
				<input class="input-mini" type="text" name="setting_2" value="<?=ranconfig('cw_battle_day')?>"/>
			</td>
		</tr>
		<tr>
			<th>CW Battle Time<br/><h6>Time when the battle starts (24 hour format!)</h6></th>
			<td>
				<input class="input-mini" type="text" name="setting_3" value="<?=ranconfig('cw_battle_time')?>"/>
			</td>
		</tr>
		<tr>
			<th>CW Battle Duration<br/><h6>Battle duration in MINUTES.</h6></th>
			<td>
				<input class="input-mini" type="text" name="setting_4" value="<?=ranconfig('cw_battle_duration')?>"/> minutes
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