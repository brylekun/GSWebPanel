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
<section class="content-header"><h1> Points Log </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">

<?php

$points = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT TOP 30 * FROM LogPoints ORDER BY LogTime DESC");
if(is_array($points)) {
echo '
<table class="table table-bordered">
	<tbody>
	<tr>
		<th style="width: 80px">#</th>
		<th>UserNum</th>
		<th style="width: 300px">UserName</th>
		<th>Points Name</th>
		<th>Value</th>
		<th>Date</th>
		<th></th>
	</tr>';
	$i = 1;
	foreach($points as $data) {
	$midnight = strtotime("tomorrow 00:00:00");
	$now = $midnight - $data['LogTime'];
			
			  
	if($now < 86400){
		echo '
		<tr>
			<td>'.$i.'</td>
			<td><a href="index.php?module=accountinfo&id='.$data['LogUserNum'].'">'.$data['LogUserNum'].'</a></td>
			<td>'.$data['LogUserName'].'</td>
			<td>'.$data['LogName'].'</td>
			<td>'.$data['LogValue'].'</td>
			<td>'.date("Y-m-d H:i:s",$data['LogTime']).'</td>';
			echo '
		</tr>';
		$i++;
		}
	}
	echo '</tbody>';
echo '</table>';
} else {
	message('error','You have not added any top up code.');
}
?>

</div>
	</div>
</section>