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
<section class="content-header"><h1> Online Accounts </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">

<?php

$online = glenox::DB('RanUser')->query_fetch("SELECT UserNum,UserID FROM UserInfo WHERE UserLoginState = 1");
if(is_array($online)) {
	message('','Total Online: '.count($online));
	echo '<div class="box box-info">';
	echo '<div class="box-header with-border">';
					echo '<h3 class="box-title">Online</h3>';
					echo '<div class="box-body">';

	echo '<table class="table table-condensed table-hover">
	<thead>
	<tr>
	<th>Account</th>
	<th>Server</th>
	<th></th>
	</tr>
	</thead>
	<tbody>';
	foreach($online as $thisAccount) {
		echo '<tr>';
		echo '<td>'.$thisAccount['UserNum'].'</td>';
		echo '<td>'.$thisAccount['UserID'].'</td>';
		echo '<td style="text-align:right;"><a href="'.admincp_base("accountinfo&id=".$thisAccount['UserNum']).'" class="btn btn-xs btn-default">Account Information</a></td>';
		echo '</tr>';
	}
	echo '
	</tbody>
	</table>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
} else {
	message('error','No online accounts.');
}

?>

</div>
	</div>
</section>