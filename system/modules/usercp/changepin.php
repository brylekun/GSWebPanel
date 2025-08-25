<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 9/21/2016
 */

(!isLoggedIn()) ? redirect(1,'login') : null;



	if(plugins::gotEnabledPlugins()) {

		
	
		if(check_value($_POST['submit'])) {
			try {
				
				
				plugin_Changepin::cPinProcess($_SESSION['userid'],$_POST['oldpincode'],$_POST['newpincode'],$_POST['password']);
				
				message('success', 'Your account pincode has been successfully changed!');

				# redirect to usercp
			    redirect(2,'usercp/myaccount/',3);
		
			} catch (Exception $ex) {
				message('error', $ex->getMessage());

			}
		}
		echo '<div id="post_wrapper">';
		echo '<div class="alignLeft">';
		echo '<br /><br />';
			echo '<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label>Old Pin :</label>
					<input class="form-control" type="text" name="oldpincode" maxlength="15" placeholder="Old Pin" autofocus="">
					<br>
					<label>New Pin :</label>
					<input class="form-control" type="text" name="newpincode" maxlength="15" placeholder="New Pin">
					<br>
					<label>Password :</label>
				</div>
				<input class="form-control" type="password" name="password" maxlength="20" placeholder="Password">

				<br />
					<button type="submit" name="submit" value="submit" class="btn btn-success">Change Pin</button>
				</form>';
		echo '</div>';
		echo '</div>';

	} else {
		message('error', lang('error_47',true));
		echo '<h3>'.lang('error_47',true).'</h3>';

	}