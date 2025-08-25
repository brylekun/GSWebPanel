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
<section class="content-header"><h1> Downloads Settings </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-9">
<?php
$downloadTypes = array (
	1 => 'Client',
	2 => 'Patch',
	3 => 'Tool'
);

function downloadTypesSelect($downloadTypes,$selected=null) {
	foreach($downloadTypes as $key => $typeOPTION) {
		if(check_value($selected)) {
			if($key == $selected) {
				echo '<option value="'.$key.'" selected="selected">'.$typeOPTION.'</option>';
			} else {
				echo '<option value="'.$key.'">'.$typeOPTION.'</option>';
			}
		} else {
			echo '<option value="'.$key.'">'.$typeOPTION.'</option>';
		}
	}
}

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'downloads.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->show_client_downloads = $_POST['setting_2'];
	$xml->show_patch_downloads = $_POST['setting_3'];
	$xml->show_tool_downloads = $_POST['setting_4'];
	
	$save = $xml->asXML($xmlPath);
	if($save) {
		message('success','Settings successfully saved.');
	} else {
		message('error','There has been an error while saving changes.');
	}
}

function updateDownloadsCACHE() {
	$dB4DATA = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM DOWNLOADS ORDER BY download_type ASC, download_id ASC");
	$cacheDATA = BuildCacheData($dB4DATA);
	UpdateCache('downloads.cache',$cacheDATA);
}

function addDownload($DATA) {
	if(check_value($DATA['downloads_add_title']) && check_value($DATA['downloads_add_link']) && check_value($DATA['downloads_add_type'])) {
		$sqlDATA = array(
			$DATA['downloads_add_title'],
			$DATA['downloads_add_link'],
			$DATA['downloads_add_size'],
			$DATA['downloads_add_host'],
			$DATA['downloads_add_type']
		);
		
		$add = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO DOWNLOADS (download_title, download_link, download_size, download_host, download_type) VALUES (?, ?, ?, ?, ?)", $sqlDATA);
		if($add) {
		
			// UPDATE CACHE
			updateDownloadsCACHE();
			
			message('success','The download link has been successfully added.');
		} else {
			message('error','There has been an error while adding the download.');
		}
	} else {
		message('error','Missing data (title, link and type are required fields!).');
	}
}

function editDownload($DATA) {
	if(check_value($DATA['downloads_edit_id']) && check_value($DATA['downloads_edit_title']) && check_value($DATA['downloads_edit_link']) && check_value($DATA['downloads_edit_type'])) {
		$edit = glenox::DB(config('SQL_RANPANEL'))->query("UPDATE DOWNLOADS SET download_title = '".$DATA['downloads_edit_title']."', download_link = '".$DATA['downloads_edit_link']."', download_size = '".$DATA['downloads_edit_size']."', download_host = '".$DATA['downloads_edit_host']."', download_type = '".$DATA['downloads_edit_type']."' WHERE download_id = '".$DATA['downloads_edit_id']."'");
		if($edit) {		
			// UPDATE CACHE
			updateDownloadsCACHE();
			message('success','The download link has been successfully updated.');
		} else {
			message('error','There has been an error while editing the download.');
		}
	} else {
		message('error','Missing data (title, link and type are required fields!).');
	}
}

function deleteDownload($id) {
	if(check_value($id)) {
		$delete = glenox::DB(config('SQL_RANPANEL'))->query("DELETE FROM DOWNLOADS WHERE download_id = '$id'");
		if($delete) {
			// UPDATE CACHE
			updateDownloadsCACHE();
			message('success','The download link has been successfully deleted.');
		} else {
			message('error','Invalid download id.');
		}
	} else {
		message('error','Invalid download id.');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

if(check_value($_POST['downloads_add_submit'])) {
	addDownload($_POST);
}

if(check_value($_POST['downloads_edit_submit'])) {
	editDownload($_POST);
}

if(check_value($_REQUEST['deletelink'])) {
	deleteDownload($_REQUEST['deletelink']);
}

loadModuleConfigs('downloads');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><h6>Enable/disable the downloads module.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Client Downloads<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_2',ranconfig('show_client_downloads'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Patches Downloads<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_3',ranconfig('show_patch_downloads'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Tools Downloads<br/></th>
			<td>
				<?=enabledisableCheckboxes('setting_4',ranconfig('show_tool_downloads'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>

	</table>
</form>

<h1> Manage Downloads </h1>

<?php
$downloads = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM DOWNLOADS ORDER BY download_type ASC, download_id ASC");
if(is_array($downloads)) {
echo '
<table class="table table-bordered">
	<tbody>
	<tr>
		<th>Title</th>
		<th>Host</th>
		<th>Link</th>
		<th style="width:80px">Size (MB)</th>
		<th style="width:100px">Type</th>
		<th></th>
	</tr>';
	
	foreach($downloads as $thisDownload) {
	echo '
	<form action="index.php?module=modules_manager&config=downloads" method="post">
	<input type="hidden" name="downloads_edit_id" value="'.$thisDownload['download_id'].'"/>
	<tr>
		<td><input type="text" name="downloads_edit_title" class="form-control" value="'.$thisDownload['download_title'].'"/></td>
		<td><input type="text" name="downloads_edit_host" class="form-control" value="'.$thisDownload['download_host'].'"/></td>
		<td><input type="text" name="downloads_edit_link" class="form-control" value="'.$thisDownload['download_link'].'"/></td>
		<td><input type="text" name="downloads_edit_size" class="form-control" value="'.round($thisDownload['download_size'],2).'"/></td>
		<td>
			<select class="form-control" name="downloads_edit_type">';
				downloadTypesSelect($downloadTypes, $thisDownload['download_type']);
		echo '
			</select>
		</td>
		<td>
		<input type="submit" class="btn btn-success" name="downloads_edit_submit" value="Save"/>
		<a href="index.php?module=modules_manager&config=downloads&deletelink='.$thisDownload['download_id'].'" class="btn btn-block">Delete</a>
		</td>
	</tr>
	</form>';
	}
	echo '</tbody>';
echo '</table>';
} else {
	message('error','You have not added any download link.');
}
?>

<h1> Add Download </h1>

<form action="index.php?module=modules_manager&config=downloads" method="post">
<table class="table table-bordered">
	<tr>
		<th>Title</th>
		<th>Host</th>
		<th>Link</th>
		<th style="width: 80px">Size (MB)</th>
		<th style="width:100px">Type</th>
	</tr>
	<tr>
		<td><input type="text" name="downloads_add_title" class="form-control"/></td>
		<td><input type="text" name="downloads_add_host" class="form-control"/></td>
		<td><input type="text" name="downloads_add_link" class="form-control"/></td>
		<td><input type="text" name="downloads_add_size" class="form-control"/></td>
		<td>
			<select class="form-control" name="downloads_add_type">
				<?=downloadTypesSelect($downloadTypes); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="5"><input type="submit" name="downloads_add_submit" class="btn btn-success" value="Add Download"/></td>
	</tr>
</table>
</form>


</div>
	</div>
</section>