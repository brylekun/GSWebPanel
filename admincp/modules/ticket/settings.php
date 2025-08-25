<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.1.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 8/6/2017
 */
?>
<section class="content-header"><h1> Ticket System </h1></section>
<section class="content">
      <div class="row">
		<div class="col-xs-12">
<style>
.client {
	color :#FF2A2A;
	font-weight: bold;
	text-decoration : none;
}
.you{
	color: #1337e8;
    font-weight: bold;
    text-decoration: none;
}
</style>
<?php
	# News delete
	if(check_value($_REQUEST['close'])) {
		
		$closeticket = glenox::DB(config('SQL_RANPANEL'))->query("UPDATE plugin_ticket SET ticket_status = ? WHERE ticketnum = ?", array(4,$_REQUEST['close']));
		if($closeticket) {
			message('success','Ticket successfully close');
		} else {
			message('error','Invalid ticket ID');
		}
	}
	if(check_value($_REQUEST['verify'])) {
		
		$closeticket = glenox::DB(config('SQL_RANPANEL'))->query("UPDATE plugin_ticket SET ticket_status = ? WHERE ticketnum = ?", array(2,$_REQUEST['verify']));
		if($closeticket) {
			message('success','Verified Ticket');
		} else {
			message('error','Invalid ticket ID');
		}
	}
	if(check_value($_REQUEST['reply'])){
		
		//back
		echo '<a class="btn btn-default btn-sm" href="'.admincp_base("ticket&page=settings").'">Back</a>';

		if(check_value($_POST['ticket_submit'])) {
			$ticket = new plugin_ticket();
			$ticket->ReplyTicket($_REQUEST['reply'],$_SESSION['userid'],$_POST['ticket_msg']);
			
		}

		try {
			$input = $_REQUEST['reply'];
			$ticketData = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM plugin_ticket WHERE ticketnum = ?", array($input));
			$reply = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM plugin_ticketreply WHERE ticketnum = ?", array($input));
			if(!$ticketData) throw new Exception('Invalid Request.');
			echo '<br /><br /><br />';
			echo 'Title :<label>'.$ticketData['ticket_title'].'</label>';
			echo '<br />';
			echo 'Message : <label>'.$ticketData['ticket_content'].'</label>';

			foreach ($reply as $allreply) {

				if($allreply['usernum']==$_SESSION['userid']){
					echo '<hr>';
					echo '<label>Me : <span class="you">'.$allreply['reply_msg'].'</span></label>';
				} else {
					echo '<hr>';
					echo '<label>Client : <span class="client">'.$allreply['reply_msg'].'</span></label>';
				}
			}

			echo '<hr>';
			echo '<form action="" method="post">';
					echo '<label>Reply :</label>';
						echo '<textarea class="form-control" name="ticket_msg" cols="40" rows="5" required style="margin: 0px -143px 0px 0px; width: 226px; height: 121px;"></textarea>';
						
						echo '<br /><br />';
						echo '<button type="submit" name="ticket_submit" value="submit" class="btn btn-primary">Submit</button>';
			echo '</form>';
		


		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}

	} else {

		$result = $result = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM plugin_ticket ORDER BY ticket_time DESC");
		$a = $change['status'] = array(
			1 => array('Pending'),
			2 => array('Verify'),
			3 => array('Replied'),
			4 => array('Done')
		);
		echo '<table id="Search" class="table table-bordered table-hover">
				<thead>
					<tr>
						<td>No.</td>
						<td>TITLE</td>
						<td>DATE</td>
						<td>STATUS</td>
						<td></td>

					</tr>
				</thead><tbody>';
					
								foreach ($result as $ticket) {
									
									$i++;
									if($i>=0) {
								echo '<tr>
											<td>'.$ticket['ticketnum'].'</td>
											<td><a href="'.admincp_base("ticket&page=settings&reply=".$ticket['ticketnum']).'">'.$ticket['ticket_title'].'</a></td>
											<td>'.date("m/d/Y",$ticket['ticket_time']).'</td>';
											if($ticket['ticket_status']!=4){
												echo '<td>'.$a[$ticket['ticket_status']][0].'</td>';
											} else {
												echo '<td>'.$a[$ticket['ticket_status']][0].'</td>';
											}
											echo '<td>';
											if($ticket['ticket_status']!=4){
												echo '<a class="btn btn-danger btn-sm" href="'.admincp_base("ticket&page=settings&close=".$ticket['ticketnum']).'">Done</a> | ';
												echo '<a class="btn btn-primary btn-sm" href="'.admincp_base("ticket&page=settings&verify=".$ticket['ticketnum']).'">Verify</a>';
											}
											echo '</td>';
											
								echo '</tr>';
									}
								}
							echo '</tbody>
						</table>';

	}
	


?>

		</div>
	</div>
</section>