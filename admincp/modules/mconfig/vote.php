<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 1.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 9/21/2016
 */
?>
<section class="content-header"><h1> Vote and Reward Settings </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-9">
<?php

// Load Vote Class
$vote = new Vote($common, $dB, $dB2);

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.vote.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->vote_save_logs = $_POST['setting_2'];
	$xml->char_limit = $_POST['setting_3'];
	
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

if(check_value($_POST['votesite_add_submit'])) {
	$add = $vote->addVotesite($_POST['votesite_add_title'],$_POST['votesite_add_link'],$_POST['votesite_add_reward'],$_POST['votesite_add_time']);
	if($add) {
		message('success','Votesite successfully added.');
	} else {
		message('error','There has been an error while adding the topsite.');
	}
}

if(check_value($_REQUEST['deletesite'])) {
	$delete = $vote->deleteVotesite($_REQUEST['deletesite']);
	if($delete) {
		message('success','Votesite successfully deleted.');
	} else {
		message('error','There has been an error while deleting the topsite.');
	}
}

loadModuleConfigs('usercp.vote');

?>
<form action="index.php?module=modules_manager&config=vote" method="post">
	<table class="table table-bordered">
		<tr>
			<th>Status<br/><h6>Enable/disable the vote module.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_1',ranconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Save Vote Logs<br/><h6>If enabled, every vote will be permanently logged in a database table.</h6></th>
			<td>
				<?=enabledisableCheckboxes('setting_2',ranconfig('vote_save_logs'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Character Level<br/><h6>Every vote are limit character level, 0 is disable</h6></th>
			<td>
				<input class="form-control" type="number" name="setting_3" value="<?=ranconfig('char_limit')?>"/>
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>

<hr>
<h3>Manage Vote Sites</h3>
<?php
$votesiteList = $vote->retrieveVotesites();
if(is_array($votesiteList)) {
	echo '<table class="table table-bordered">';
	echo '<tr>';
	echo '<th style="width:80px">Title</th>';
	echo '<th style="width:380px">Link (full url including http)</th>';
	echo '<th>Reward</th>';
	echo '<th>Vote Every</th>';
	echo '<th></th>';
	echo '</tr>';
	
	foreach($votesiteList as $thisVoteSite) {
		echo '<tr>';
		echo '<td><h6>'.$thisVoteSite['votesite_title'].'</h6></td>';
		echo '<td><h6>'.$thisVoteSite['votesite_link'].'</h6></td>';
		echo '<td>'.$thisVoteSite['votesite_reward'].' VP(s)</td>';
		echo '<td>'.$thisVoteSite['votesite_time'].' hour(s)</td>';
		echo '<td><a href="index.php?module=modules_manager&config=vote&deletesite='.$thisVoteSite['votesite_id'].'" class="btn btn-danger" title="Delete">X</a></td>';
		echo '</tr>';
	}
	
	echo '<form action="index.php?module=modules_manager&config=vote" method="post">';
	echo '<tr>';
	echo '<td><input name="votesite_add_title" class="form-control" type="text"/></td>';
	echo '<td><input name="votesite_add_link" class="form-control" type="text"/></td>';
	echo '<td><input name="votesite_add_reward" class="form-control" type="text"/> credit(s)</td>';
	echo '<td><input name="votesite_add_time" class="form-control" type="text"/> hour(s)</td>';
	echo '<td><input type="submit" name="votesite_add_submit" class="btn btn-success" value="Add!"/></td>';
	echo '</tr>';
	echo '</form>';
	
	echo '</table>';
} else {
	echo '<h4>Add Voting Site</h4>';
	echo '<table class="table table-bordered">';
	echo '<tr>';
	echo '<th style="width:130px">Title</th>';
	echo '<th>Link (full url including http)</th>';
	echo '<th>Reward</th>';
	echo '<th>Vote Every</th>';
	echo '<th></th>';
	echo '</tr>';
	echo '<form action="index.php?module=modules_manager&config=vote" method="post">';
	echo '<tr>';
	echo '<td><input name="votesite_add_title" class="form-control" type="text"/></td>';
	echo '<td><input name="votesite_add_link" class="form-control" type="text"/></td>';
	echo '<td><input name="votesite_add_reward" class="form-control" type="text"/> credit(s)</td>';
	echo '<td><input name="votesite_add_time" class="form-control" type="text"/> hour(s)</td>';
	echo '<td><input type="submit" name="votesite_add_submit" class="btn btn-success" value="Add!"/></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
}

?>

</div>
	</div>
</section>