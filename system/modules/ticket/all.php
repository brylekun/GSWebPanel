<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 8/6/2017
 */

(!isLoggedIn()) ? redirect(1,'login') : null;
echo '<style>
.table {
    width: 600px;
    max-width: 100%;
    margin-bottom: 20px;
    margin: 10px 22px 27px 0px;
}
.ticket {
    width: 100%;
    max-width: 50%;
    margin-bottom: 25px;
    margin: 70px 12px 20px 40px;
}
</style>
';
		
			if(plugins::gotEnabledPlugins()) {
				$ticket = new plugin_ticket();
				$allticket = $ticket->GetAllTicket($_SESSION['userid']);
				
				$i = 0;
				echo '<div class="ticket">';
				echo '<a href="'.__BASE_URL__.'ticket/new/"><button type="submit" class="btn btn-success">New Ticket</button></a>';
				echo '<table class="table table-bordered">
						<tbody>
							<tr>
								<td style="font-weight:bold;">No.</td>
								<td style="font-weight:bold;">TITLE</td>
								<td style="font-weight:bold;">DATE</td>
								<td style="font-weight:bold;">STATUS</td>
							</tr>';
							$a = $ticket->ChangeStatus();
							foreach ($allticket as $ticket) {
								
								$i++;
								if($i>=0) {
							  echo '<tr>
										<td>'.$i.'</td>
										<td><a href="'.__BASE_URL__.'ticket/reply/?req='.Encode_id($ticket['ticketnum']).'">'.$ticket['ticket_title'].'</a></td>
										<td>'.date("m/d/Y",$ticket['ticket_time']).'</td>
										<td>'.$a[$ticket['ticket_status']][0].'</td>
									</tr>';
								}
							}
						echo '</tbody>
					</table>';
					echo '</div>';
				
			} else {
				message('error', lang('error_47',true));
				echo '<h3>'.lang('error_47',true).'</h3>';

			}
			
		

?>