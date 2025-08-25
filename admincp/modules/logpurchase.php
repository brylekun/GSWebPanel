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
<section class="content-header"><h1> Purchase Log </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">

<?php
//Item shop log //
echo '<div class="col-md-6">';
echo '<div class="box box-info">';
				
	$EP =  glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT TOP 30 * FROM LogPurchase WHERE LogName = 'EP' ORDER BY Logid ASC");;
	echo '<div class="box-header with-border">';
	echo '<h3 class="box-title">EP Purchase</h3>';
	echo '<div class="box-body">';
					echo '<table class="table table-no-border table-hover">';
					echo '<thead>
							<tr class="odd">
							<th>User Buy</th>
							<th>Item name</th>
							<th>Price</th>
							<th>Date</th>
							</tr>
							</thead>';
					if(is_array($EP)) {
						//var_dump($ItemLog);
						foreach($EP as $Log) {
							echo '<tr>';
							echo '<td>'.$Log['LogUserUID'].'</td>';
								echo '<td>'.$Log['LogItemName'].'</td>';
								echo '<td>'.$Log['LogPurPrice'].'</td>';
								echo '<td>'.date("Y-m-d H:i:s",$Log['LogTime']).'</td>';
							echo '</tr>';
						}
					} 
					echo '</table>';
	echo '</div>';
	echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="col-md-6">';
//Item shop log //
echo '<div class="box box-info">';
				
	$VP = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT TOP 30 * FROM LogPurchase WHERE LogName = 'VP' ORDER BY Logid ASC");;
	echo '<div class="box-header with-border">';
	echo '<h3 class="box-title">VP Purchase</h3>';
	echo '<div class="box-body">';
					echo '<table class="table table-no-border table-hover">';
					echo '<thead>
							<tr class="odd">
							<th>User Buy</th>
							<th>Item name</th>
							<th>Price</th>
							<th>Date</th>
							</tr>
							</thead>';
					if(is_array($VP)) {
						//var_dump($ItemLog);
						foreach($VP as $Log) {
							echo '<tr>';
							echo '<td>'.$Log['LogUserUID'].'</td>';
								echo '<td>'.$Log['LogItemName'].'</td>';
								echo '<td>'.$Log['LogPurPrice'].'</td>';
								echo '<td>'.date("Y-m-d H:i:s",$Log['LogTime']).'</td>';
							echo '</tr>';
						}
					} 
					echo '</table>';
	echo '</div>';
	echo '</div>';
echo '</div>';
echo '</div>';
?>

</div>
	</div>
</section>