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
<section class="content-header"><h1> PayPal Donations </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<?php
try {
	
	$paypalDonations = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM paypal_trans ORDER BY id DESC");

	if(!is_array($paypalDonations)) throw new Exception("There are no PayPal transactions in the database.");
	
	echo '<table id="paypal_donations" class="table table-condensed table-hover">';
	echo '<thead>';
		echo '<tr>';
			echo '<th>Transaction ID</th>';
			echo '<th>Account</th>';
			echo '<th>Amount</th>';
			echo '<th>PayPal Email</th>';
			echo '<th>Date</th>';
			echo '<th>Status</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach($paypalDonations as $data) {
		$userData = $common->accountInformation($data['UserNum']);
		$donation_status = ($data['transaction_status'] == 1 ? '<span class="badge badge-success">OK</span>' : '<span class="badge badge-important">Reversed</span>');
		
		echo '<tr>';
			echo '<td>'.$data['transaction_id'].'</td>';
			echo '<td><a href="'.admincp_base("accountinfo&id=".$data['UserNum']).'">'.$userData['UserID'].'</a></td>';
			echo '<td>$'.$data['payment_amount'].'</td>';
			echo '<td>'.$data['paypal_email'].'</td>';
			echo '<td>'.date("m/d/Y h:i A",$data['transaction_date']).'</td>';
			echo '<td>'.$donation_status.'</td>';
		echo '</tr>';
	}
	echo '
	</tbody>
	</table>';
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}
?>
</div>
	</div>
</section>