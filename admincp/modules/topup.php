<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */
?>
<section class="content-header"><h1> Top up Settings </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<?php

function gen_pin() {
	    $tokens = '123456789ABCDEFGHIJKLMNPQRSTVWXYZ';

		$serial = '';

		for ($i = 0; $i < 3; $i++) {
		    for ($j = 0; $j < 5; $j++) {
		        $serial .= $tokens[rand(0, 35)];
		    }

		    if ($i < 2) {
		        $serial .= '-';
		    }
		}

		return $serial;

	}
	
function gen_code() {
    return substr(str_shuffle("123456789ABCDEFGHIJKLMNPQRSTVWXYZ"), 0, 10);
}

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.topup.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->topup_enable_recaptcha = $_POST['setting_2'];
	$xml->topup_recaptcha_public_key = $_POST['setting_3'];
	$xml->topup_recaptcha_private_key = $_POST['setting_4'];
	$xml->topup_log = $_POST['setting_5'];
	
	$save = $xml->asXML($xmlPath);
	if($save) {
		message('success','Settings successfully saved.');
	} else {
		message('error','There has been an error while saving changes.');
	}
}

function addTopup($DATA) {
		if(check_value($DATA['top_number']) && check_value($DATA['top_value'])) {
			if(Validator::Number($DATA['top_number']) && Validator::Number($DATA['top_value'])) {
				$date = time();
		    	$number = $DATA['top_number'];
		    	$value = $DATA['top_value'];
		    	for ($i = 0; $i < $number; $i++) {
		    		$pin = encrypt(gen_pin());
		    		$code = encrypt(gen_code());
		    		$result = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO topup (top_pin,top_code,top_value,top_date) VALUES (?,?,?,?)", array($pin,$code,$value,$date));

		    	}

		    	if($result){
		    		message('success','Generated!!');
		    	} else {
		    		message('error','Invalid!!!');
		    	}

			}
	    	

    }

	
}

function deleteTopup($id) {
	if(check_value($id)) {
		$delete = glenox::DB(config('SQL_RANPANEL'))->query("DELETE FROM topup WHERE top_id = ?", array($id));
		if($delete) {
			message('success','The top up code has been successfully deleted.');
		} else {
			message('error','Invalid top up id.');
		}
	} else {
		message('error','Invalid top up id.');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

if(check_value($_POST['gen_submit'])) {
	addTopup($_POST);
}

if(check_value($_REQUEST['delete'])) {
	deleteTopup($_REQUEST['delete']);
}

loadModuleConfigs('usercp.topup');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><h6>Enable/disable the topup module.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Recaptcha<br/><h6>Enable/disable Recaptcha validation. <br/><br/> <a href="http://www.google.com/recaptcha" target="_blank">http://www.google.com/recaptcha</a></h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_2',ranconfig('topup_enable_recaptcha'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Recaptcha Site Key<br/></th>
			<td>
				<input class="form-control" type="text" name="setting_3" value="<?=ranconfig('topup_recaptcha_public_key')?>"/>
			</td>
		</tr>
		<tr>
			<th>Recaptcha Secret Key<br/></th>
			<td>
				<input class="form-control" type="text" name="setting_4" value="<?=ranconfig('topup_recaptcha_private_key')?>"/>
			</td>
		</tr>
		<tr>
			<th>Top up Log<br/><h6>Enable/disable the topup log.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_5',ranconfig('topup_log'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>

	</table>
</form>

<h1> Manage Top up </h1>

<?php
$topup = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM topup ORDER BY top_id ASC");
if(is_array($topup)) {
echo '
<table class="table table-bordered">
	<tbody>
	<tr>
		<th style="width: 80px">#</th>
		<th>Pin</th>
		<th>Code</th>
		<th>Value</th>
		<th></th>
	</tr>';
	$i = 1;
	foreach($topup as $thisTopup) {
	echo '
	<form action="index.php?module=modules_manager&config=downloads" method="post">
	<tr>
		<td>'.$i.'</td>
		<td>'.decrypt($thisTopup['top_pin']).'</td>
		<td>'.decrypt($thisTopup['top_code']).'</td>
		<td>'.$thisTopup['top_value'].'</td>';
		echo '
		<td>
		<a href="index.php?module=topup&delete='.$thisTopup['top_id'].'" class="btn btn-danger">Delete</a>
		</td>
	</tr>
	</form>';
	$i++;
	}
	echo '</tbody>';
echo '</table>';
} else {
	message('error','You have not added any top up code.');
}
?>
<h4> Generate Top up Code </h4>
<form action="index.php?module=topup" method="post">

	<div class="col-xs-3">
		
			Number of Generate<input type="number" min="1" name="top_number" class="form-control" value="1"/>
			Value of top up code<input type="number" min="1" name="top_value" class="form-control" value="1"/>
		<br />
		<input type="submit" name="gen_submit" class="btn btn-success" value="Generate"/>
	</div>
	

</form>
<br /><br /><br /><br />

</div>
	</div>
</section>