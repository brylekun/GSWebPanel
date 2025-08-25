<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 9/21/2016
 */
?>
<section class="content-header"><h1> Rankings Settings </h1></section>
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
	$xmlPath = __PATH_MODULE_CONFIGS__.'rankings.xml';
	$xml = simplexml_load_file($xmlPath);

	$xml->active = $_POST['setting_1'];
	$xml->rankings_results = $_POST['setting_2'];
	$xml->rankings_show_date = $_POST['setting_3'];
	$xml->rankings_show_default = $_POST['setting_4'];
	$xml->rankings_show_place_number = $_POST['setting_5'];
	$xml->rankings_enable_all = $_POST['setting_6'];
	$xml->rankings_enable_brawler = $_POST['setting_7'];
	$xml->rankings_enable_swords = $_POST['setting_8'];
	$xml->rankings_enable_archer = $_POST['setting_9'];
	$xml->rankings_enable_shaman = $_POST['setting_10'];
	$xml->rankings_enable_ex3m = $_POST['setting_11'];
	$xml->rankings_enable_gunner = $_POST['setting_12'];
	$xml->rankings_enable_assassin = $_POST['setting_13'];
	$xml->rankings_enable_magician = $_POST['setting_14'];
	$xml->rankings_enable_shaper = $_POST['setting_15'];
	$xml->rankings_show_kills = $_POST['setting_16'];
	$xml->rankings_show_reborn = $_POST['setting_17'];
	$xml->rankings_enable_toprich = $_POST['setting_18'];
	$xml->rankings_enable_toplevel = $_POST['setting_19'];
	
	
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

loadModuleConfigs('rankings');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><h6>Enable/disable the ranking system.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Rankings Results<br/><h6>Amount of ranking results Ran Panel should cache.</h6></th>
			<td>
				<input class="input-mini" type="text" name="setting_2" value="<?=ranconfig('rankings_results')?>"/>
			</td>
		</tr>
		<tr>
			<th>Display Last Update Date<br/><h6>Show at the bottom of the rankings the date each ranking was last updated.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_3',ranconfig('rankings_show_date'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Default Rankings<br/><h6>Which rankings will be shown by default when accessing to the rankings page.<br/><br/>Options:<ul><li>all</li><li>brawler</li><li>swordsman</li><li>archer</li><li>shaman</li></ul></h6></th>
			<td>
				<input class="input-small" type="text" name="setting_4" value="<?=ranconfig('rankings_show_default')?>"/>
			</td>
		</tr>
		<tr>
			<th>Display Position Number<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_5',ranconfig('rankings_show_place_number'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display All<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_6',ranconfig('rankings_enable_all'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display Brawler<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_7',ranconfig('rankings_enable_brawler'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display Swordsman<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_8',ranconfig('rankings_enable_swords'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display Archer<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_9',ranconfig('rankings_enable_archer'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display Shamman<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_10',ranconfig('rankings_enable_shaman'),'Yes','No'); ?>
			</td>
		</tr>
				<tr>
			<th>Display Extreme<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_11',ranconfig('rankings_enable_ex3m'),'Yes','No'); ?>
			</td>
		</tr>
				<tr>
			<th>Display Gunner<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_12',ranconfig('rankings_enable_gunner'),'Yes','No'); ?>
			</td>
		</tr>
				<tr>
			<th>Display Assassin<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_13',ranconfig('rankings_enable_assassin'),'Yes','No'); ?>
			</td>
		</tr>
				<tr>
			<th>Display Magician<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_14',ranconfig('rankings_enable_magician'),'Yes','No'); ?>
			</td>
		</tr>
				<tr>
			<th>Display Shaper<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_15',ranconfig('rankings_enable_shaper'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display PK Wins<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_16',ranconfig('rankings_show_kills'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display Reborn<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_17',ranconfig('rankings_show_reborn'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display Top Rich<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_18',ranconfig('rankings_enable_toprich'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Display Top level<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_19',ranconfig('rankings_enable_toplevel'),'Yes','No'); ?>
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