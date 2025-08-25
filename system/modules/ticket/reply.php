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
.you{
	color: #1337e8;
    font-weight: bold;
    text-decoration: none;
}
</style>
';

		
echo '<div class="heading">
<h3><i class="fa fa-crosshairs"></i>Help Desk</h3>
</div><div class="info-wrap">';
		try {	
			if(plugins::gotEnabledPlugins()) {
				if(check_value(Decode_id($_GET['req']))) {
					$ticket = new plugin_ticket();
					$post = $ticket->ShowTicket(Decode_id($_GET['req']),$_SESSION['userid']);
					$reply = $ticket->ShowReply(Decode_id($_GET['req']));
					$i = 0;
						try {
								
								if(check_value($_POST['ticket_submit'])) {
											$ticket->ReplyTicket(Decode_id($_GET['req']),$_SESSION['userid'],$_POST['ticket_msg']);
											
										}
						} catch (Exception $ex) {
							message('error', $ex->getMessage());
						}
						echo '<label>Title : <b>'.$post['ticket_title'].'</b></label><br /><br /><br />';
						echo '<label>Message : <a>'.$post['ticket_content'].'</a></label>';
						

						foreach ($reply as $allreply) {

								if($allreply['usernum']==$_SESSION['userid']){
									echo '<hr>';
									echo '<label>You : <span class="you">'.$allreply['reply_msg'].'</span></label>';
								} else {
									echo '<hr>';
									echo '<label>Admin : <a>'.$allreply['reply_msg'].'</a></label>';
								}
						}
						echo '<br /><br /><br />';
						if($ticket->checkStatus(Decode_id($_GET['req']),$_SESSION['userid'])!=4){
							echo '<hr>';
							echo '<label>Reply :</label>';
							echo '<form class="form" action="" method="post">';
							echo '<div class="form-group">';
									
										echo '<textarea class="_input" name="ticket_msg" cols="40" rows="10" required></textarea>';
							echo '</div>';
										echo '<br /><br />';
										echo '<button type="submit" name="ticket_submit" value="submit" class="btn btn-blue ml10">Submit</button>';
							echo '</form>';
						}
				} else {
					message('error', 'Invalid Request.');
				}
				
			} else {
				message('error', lang('error_47',true));
				echo '<h3>'.lang('error_47',true).'</h3>';

			}
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
			redirect(2,'ticket/all',3);
		}
		
		echo '</div>';

	
?>