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


echo '<div class="page_header">
<div class="col-lg-6">
	<h3>Change Pin</h3>
</div>
</div>
<div class="reg-form container">
		<div class="col-md-6 reg-form-inner">';
			if(plugins::gotEnabledPlugins()) {
				

				if(check_value($_POST['ticket_submit'])) {
					try {
			
						
						$ticket = new plugin_ticket();
						
						$ticket->PostNewTicket($_SESSION['userid'],$_POST['ticket_title'], $_POST['ticket_msg']);
					} catch (Exception $ex) {
						message('error', $ex->getMessage());
					}
				}
				echo '<form class="form" action="" method="post">';
						echo '<label> Title: </label>';
							echo '<input type="text" class="_input"" id="title" name="ticket_title" required>';
					echo '<label> Message: </label>';
							
							echo '<textarea class="_input" name="ticket_msg" cols="40" rows="10"></textarea>';
							echo '<br /><br />';
							echo '<button type="submit" name="ticket_submit" value="submit" class="btn btn-blue ml10">Submit</button>';
				echo '</form>';
				
				
			} else {
				message('error', lang('error_47',true));
				echo '<h3>'.lang('error_47',true).'</h3>';

			}
			
		echo '</div>';
		echo '</div>';

?>