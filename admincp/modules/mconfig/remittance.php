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
<section class="content-header"><h1> Remittance Settings </h1></section>
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
	// WESTERN UNION
	$xmlPath = __PATH_MODULE_CONFIGS__.'donation.remittance.xml';
	$xml = simplexml_load_file($xmlPath);
	$xml->active = $_POST['setting_14'];
	$save4 = $xml->asXML($xmlPath);
	
	if($save4) {
		message('success','[Remittance] Settings successfully saved.');
	} else {
		message('error','[Remittance] There has been an error while saving changes.');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('donation.remittance');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><h6>Enable/disable the remittance donation gateway.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_14',ranconfig('active'),'Enabled','Disabled'); ?>
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