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
<section class="content-header"><h1> New Registrations </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<?php

	$newRegs = glenox::NewRegister();
	
	if(is_array($newRegs)) {
		echo '<table id="new_registrations" class="table display">';
			echo '<thead>';
			echo '<tr>';
				echo '<th>Id</th>';
				echo '<th>Username</th>';
				echo '<th>Email</th>';
				echo '<th></th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach($newRegs as $thisReg) {
				echo '<tr>';
					echo '<td>'.$thisReg['UserNum'].'</td>';
					echo '<td>'.$thisReg['UserID'].'</td>';
					echo '<td>'.$thisReg['UserEmail'].'</td>';
					echo '<td style="text-align:right;"><a href="'.admincp_base("accountinfo&id=".$thisReg['UserNum']).'" class="btn btn-xs btn-default">Account Information</a></td>';
				echo '</tr>';
			}
			echo '</tbody>';
		echo '</table>';
	}
?>
</div>
	</div>
</section>