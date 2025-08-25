<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 8/1/2017
 */
?>
<section class="content-header"><h1> AdminCP Access </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
		<p>To remove an admin set their access level to 0.</p>
<?php
if(check_value($_POST['settings_submit'])) {
	try {
		#  configs
		$Configurations = ran_Configs();
		
		$newAdminUser = $_POST['new_admin'];
		$newAdminLevel = $_POST['new_access'];
		
		# remove elements
		unset($_POST['settings_submit']);
		unset($_POST['new_admin']);
		unset($_POST['new_access']);
		
		# check configs
		foreach($_POST as $adminUsername => $accessLevel) {
			if(!Validator::AlphaNumeric($adminUsername)) throw new Exception('The entered username is not valid.');
			if(!Validator::UsernameLength($adminUsername)) throw new Exception('The entered username is not valid.');
			if(!array_key_exists($adminUsername, config('admins',true))) continue;
			if(!Validator::UnsignedNumber($accessLevel)) throw new Exception('Access level must be a number between 0 and 100');
			if(!Validator::Number($accessLevel, 100, 0)) throw new Exception('Access level must be a number between 0 and 100');
			if($accessLevel == 0) {
				if($adminUsername == $_SESSION['username']) throw new Exception('You cannot remove yourself.');
				continue; # admin removal
			}
			
			$adminAccounts[$adminUsername] = (int) $accessLevel;
		}
		
		if(check_value($newAdminUser)) {
			if(array_key_exists($newAdminUser, config('admins',true))) throw new Exception('An administrator with the same username is already in the list.');
			if(!Validator::UnsignedNumber($newAdminLevel)) throw new Exception('Access level must be a number between 1 and 100');
			if(!Validator::Number($newAdminLevel, 100, 0)) throw new Exception('Access level must be a number between 1 and 100');
			
			$adminAccounts[$newAdminUser] = (int) $newAdminLevel;
		}
		
		$Configurations['admins'] = $adminAccounts;
		
		$newConfig = json_encode($Configurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.'config.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem opening the configuration file.');
		
		fwrite($cfgFile, $newConfig);
		fclose($cfgFile);
		
		message('success', 'Settings successfully saved!');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$admins = config('admins',true);

if(is_array($admins)) {
	echo '<div class="col-sm-12 col-md-6 col-lg-6">';
		echo '<form action="" method="post">';
			echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Admin Account</th>';
						echo '<th>Access Level</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					foreach($admins as $admin_account => $access_level) {
						echo '<tr>';
							echo '<td>';
								echo '<strong>'.$admin_account.'</strong>';
							echo '</td>';
							echo '<td>';
								echo '<input type="number" class="form-control" min="0" max="100" name="'.$admin_account.'" value="'.$access_level.'" required>';
							echo '</td>';
						echo '</tr>';
					}
					echo '<tr>';
						echo '<td>';
							echo '<input type="text" class="form-control" min="0" max="100" name="new_admin" placeholder="username">';
						echo '</td>';
						echo '<td>';
							echo '<input type="number" class="form-control" min="0" max="100" name="new_access" placeholder="0">';
						echo '</td>';
					echo '</tr>';
				echo '</tbody>';
			echo '</table>';
			
			echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-success">Save Settings</button>';
		echo '</form>';
	echo '</div>';
} else {
	message('error', 'Admins list is empty.');
}

?>

		</div>
	</div>
</section>