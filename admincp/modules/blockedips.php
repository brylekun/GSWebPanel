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
<section class="content-header"><h1> Block IP Address <small>(web) </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<form class="form-inline" role="form" method="post">
	<div class="form-group">
		<input type="text" class="form-control" id="input_1" name="ip_address" placeholder="0.0.0.0"/>
	</div>
	<button type="submit" class="btn btn-primary" name="submit_block" value="ok">Block</button>
</form>
<br />
<?php
if(check_value($_POST['submit_block'], $_POST['ip_address'])) {
	if(glenox::blockIpAddress($_POST['ip_address'],$_SESSION['username'])) {
		message('success','IP address blocked.');
	} else {
		message('error','Error blocking IP.');
	}
}

if(check_value($_GET['unblock'])) {
	if(glenox::unblockIpAddress($_REQUEST['unblock'])) {
		message('success','IP address unblocked.');
	} else {
		message('error','Error unblocking IP.');
	}
}

$blockedIPs = glenox::retrieveBlockedIPs();
if(is_array($blockedIPs)) {
	echo '<div class="row">';
	echo '<div class="col-md-6">';
	echo '<table id="blocked_ips" class="table table-striped table-condensed table-hover">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>IP Address</th>';
				echo '<th>Blocked By</th>';
				echo '<th>Date Blocked</th>';
				echo '<th></th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($blockedIPs as $thisIP) {
			echo '<tr>';
				echo '<td>'.$thisIP['block_ip'].'</td>';
				echo '<td><a href="'.admincp_base("accountinfo&id=".glenox::retrieveUserID($thisIP['block_by'])).'">'.$thisIP['block_by'].'</a></td>';
				echo '<td>'.date("m/d/Y H:i", $thisIP['block_date']).'</td>';
				echo '<td style="text-align:right;"><a href="'.admincp_base($_REQUEST['module']."&unblock=".$thisIP['id']).'" class="btn btn-xs btn-danger">Lift Block</a></td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
}

?>

</div>
	</div>
</section>
