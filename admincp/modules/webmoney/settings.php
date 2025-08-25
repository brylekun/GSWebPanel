<section class="content-header"><h1> WebMoney Log </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">

<?php
#add item
	
	$result = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT TOP 100 * FROM plugin_webmoney WHERE status = 1 ORDER BY DateBuy DESC");

	echo '<table id="Search" class="table table-bordered table-hover">';
	echo '<thead>';
		echo '<tr>';
			echo '<th>#</th>';
			echo '<th>Seller Name</th>';
			echo '<th>Gold</th>';
			echo '<th>Price</th>';
			echo '<th>Who\'s buy</th>';
			echo '<th>Date</th>';
			echo '<th>IP Address</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

	foreach($result as $gold) {

		echo '<tr>';
				echo '<td>'.$gold['MoneyNum'].'</td>';
				// item Main / Sub
				echo '<td>'.$gold['ChaName'].'</td>';
				echo '<td>'.plugin_WMoney::ConvertMoney($gold['UserMoney']).'</td>';
				//
				echo '<td>'.$gold['EPValue'].'</td>';
				echo '<td><a href="index.php?module=accountinfo&id='.$gold['buyUserNum'].'">Click here</td>';
				echo '<td>'.date("Y-m-d H:i:s", $gold['dateBuy']).'</td>';
				echo '<td>'.$gold['UserIPaddress'].'</td>';
	echo '</tr>';
	
}

	
		echo '</tbody>';
echo '</table>';
?>


		</div>
	</div>
</section>