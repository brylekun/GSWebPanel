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

<section class="content-header"><h1> Account Information </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<?php

$accountInfoConfig['showGeneralInfo'] = true;
$accountInfoConfig['showStatusInfo'] = true;
$accountInfoConfig['showIpInfo'] = true;
$accountInfoConfig['showCharacters'] = true;
$accountInfoConfig['showItemLog'] = true;
$accountInfoConfig['showTopLog'] = true;



if(check_value($_GET['id'])) {
	try {
		if(check_value($_POST['editaccount_submit'])) {
			try {
				if(!check_value($_POST['action'])) throw new Exception("Invalid request.");
				$sendEmail = (check_value($_POST['editaccount_sendmail']) && $_POST['editaccount_sendmail'] == 1 ? true : false);
				$accountInfo = glenox::accountInformation($_GET['id']);
				if(!$accountInfo) throw new Exception("Could not retrieve account information (invalid account).");
				switch($_POST['action']) {
					case "changepassword":
						if(!check_value($_POST['changepassword_newpw'])) throw new Exception("Please enter the new password.");
						if(!Validator::PasswordLength($_POST['changepassword_newpw'])) throw new Exception("Invalid password.");
						if(!glenox::changePassword($accountInfo['UserNum'], $accountInfo['UserID'], $_POST['changepassword_newpw'])) throw new Exception("Could not change password.");
						message('success', 'Password updated!');
						
						# send new password
						if(check_value($_POST['editaccount_sendmail'])) {
							$email = new Email();
							$email->setTemplate('ADMIN_CHANGE_PASSWORD');
							$email->addVariable('{USERNAME}', $accountInfo['UserID']);
							$email->addVariable('{NEW_PASSWORD}', $_POST['changepassword_newpw']);
							$email->addAddress($accountInfo['UserEmail']);
							$email->send();
						}
						break;
					case "changeemail":
						if(!check_value($_POST['changeemail_newemail'])) throw new Exception("Please enter the new email.");
						if(!Validator::Email($_POST['changeemail_newemail'])) throw new Exception("Invalid email address.");
						if(glenox::emailExists($_POST['changeemail_newemail'])) throw new Exception("Another account with the same email already exists.");
						if(!glenox::updateEmail($accountInfo['UserNum'], $_POST['changeemail_newemail'])) throw new Exception("Could not update email.");
						message('success', 'Email address updated!');
						
						# send new email to current email
						if(check_value($_POST['editaccount_sendmail'])) {
							$email = new Email();
							$email->setTemplate('ADMIN_CHANGE_EMAIL');
							$email->addVariable('{USERNAME}', $accountInfo['UserID']);
							$email->addVariable('{NEW_EMAIL}', $_POST['changeemail_newemail']);
							$email->addAddress($accountInfo['UserEmail']);
							$email->send();
						}
						break;
					case "changeep":
						if(!check_value($_POST['EP_new'])) throw new Exception("Please enter the new value of EP");
						//if(!Validator::Number($_POST['EP_new'])) throw new Exception("Invalid EP number");
						if(!glenox::LogPoints($_SESSION['userid'],$accountInfo['UserID'],$accountInfo['UserNum'],'EPoints',$_POST['EP_new'])) throw new Exception("System Error!");
						if(!glenox::UpdateEP($accountInfo['UserNum'], $_POST['EP_new'])) throw new Exception("Could not update E-Point");
						message('success', 'EPoints has updated');
						break;
					case "changevp":
						if(!check_value($_POST['VP_new'])) throw new Exception("Please enter the new value of VP");
						//if(!Validator::Number($_POST['VP_new'])) throw new Exception("Invalid VP number");
						if(!glenox::LogPoints($_SESSION['userid'],$accountInfo['UserID'],$accountInfo['UserNum'],'VPoints',$_POST['VP_new'])) throw new Exception("System Error!");
						if(!glenox::UpdateVP($accountInfo['UserNum'], $_POST['VP_new'])) throw new Exception("Could not update V-Point");
						message('success', 'VPoints has updated');
						break;
					default:
						throw new Exception("Invalid request.");
				}
			} catch(Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
	
		$accountInfo = glenox::accountInformation($_GET['id']);
		if(!$accountInfo) throw new Exception("Could not retrieve account information (invalid account).");
		
		echo '<div class="col-md-6">';
			echo '<div class="box box-info">';
				
				if($accountInfoConfig['showGeneralInfo']) {
					// GENERAL ACCOUNT INFORMATION
					echo '<div class="box-header with-border">';
					   echo '<h3 class="box-title">User Inforamtion</h3>';
	            	echo '<div class="box-body">';
					
						$isBanned = ($accountInfo['UserBlock'] == 0 ? '<span class="label label-success">No</span>' : '<span class="label label-danger">Yes</span>');

						$isGMAccount = ($accountInfo['UserType'] == 1 ? '<span class="label label-success">No</span>' : '<span class="label label-danger">Yes</span>');
						echo '<table class="table no-margin">';
							echo '<tr>';
								echo '<th>ID:</th>';
								echo '<td>'.$accountInfo['UserNum'].'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>Username:</th>';
								echo '<td>'.$accountInfo['UserID'].'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>Email:</th>';
								echo '<td>'.$accountInfo['UserEmail'].'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>EP:</th>';
								echo '<form role="form" method="post">';
								echo '<input type="hidden" name="action" value="changeep"/>';
									echo '<td><div class="col-xs-2"><label>'.$accountInfo['UserPoint'].' </label></div><div class="col-xs-3"><input type="text" class="form-control" name="EP_new" value="0"></div><button type="submit" name="editaccount_submit" class="btn btn-success" value="ok">Update</button></td>';
								echo '</form>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>VP:</th>';
								echo '<form role="form" method="post">';
								echo '<input type="hidden" name="action" value="changevp"/>';
									echo '<td><div class="col-xs-2"><label>'.$accountInfo['UserPoint2'].' </label></div><div class="col-xs-3"><input type="text" class="form-control" name="VP_new" value="0"></div><button type="submit" name="editaccount_submit" class="btn btn-success" value="ok">Update</button></td>';
								echo '</form>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>GM Account:</th>';
								echo '<td>'.$isGMAccount.'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>Banned:</th>';
								echo '<td>'.$isBanned.'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>Pin:</th>';
								echo '<td>'.$accountInfo['Upass'].'</td>';
							echo '</tr>';
						echo '</table>';
					echo '</div>';
					echo '</div>';
				}
				echo '</div>';


			//Item shop log //
			echo '<div class="box box-info">';
				
				if($accountInfoConfig['showItemLog']) {
					
					$ItemLog = glenox::ShopLog($accountInfo['UserID']);
					$status = ($ItemLog["PurFlag"]==0)? "IN" : "OUT";
					echo '<div class="box-header with-border">';
					echo '<h3 class="box-title">Item Purchase</h3>';
					echo '<div class="box-body">';
								if(is_array($ItemLog)) {
									echo '<table id="Search" class="table table-no-border table-hover">';
									echo '<thead>
											<tr class="odd">
											<th>Item name</th>
											<th>Status</th>
											</tr>
											</thead>';
									if(is_array($ItemLog)) {
										//var_dump($ItemLog);
										foreach($ItemLog as $Log) {
											echo '<tr>';
												echo '<td>'.$Log['ItemName'].'</td>';
												echo '<td>'.$status.'</td>';
											echo '</tr>';
										}
									} 
									echo '</table>';
								} else {
									message('warning', 'No Item purchase.', ' ');
								}

					echo '</div>';
					echo '</div>';
				}
				
			echo '</div>';

		// Item shop log //

			echo '<div class="box box-info">';
				
				if($accountInfoConfig['showIpInfo']) {
					// ACCOUNTS IP ADDRESS
					$UserNum = $accountInfo['UserNum'];
					$UserID = $accountInfo['UserID'];

					$checkLog = glenox::DB('RanUser')->query_fetch_single("SELECT * FROM LogLogin WHERE UserNum = ?", array($UserNum));
					//var_dump($UserID);
					echo '<div class="box-header with-border">';
					echo '<h3 class="box-title">Account\'s IP Address</h3>';
					echo '<div class="box-body">';
						if($checkLog) {
							$accountIpAddress = glenox::retrieveAccountIPs($UserNum);
							if(is_array($accountIpAddress)) {
								echo '<table class="table table-no-border table-hover">';
									foreach($accountIpAddress as $accountIp) {
										echo '<tr>';
											echo '<td><a href="http://whatismyipaddress.com/ip/'.$accountIp['LogIpAddress'].'" target="_blank">'.$accountIp['LogIpAddress'].'</a></td>';
										echo '</tr>';
									}
								echo '</table>';
							} else {
								message('warning', 'No IP address found.', ' ');
							}
						} else {
							message('warning', 'Could not find table <strong>LogLogin</strong> in the database.', ' ');
						}
					echo '</div>';
					echo '</div>';
				}
			echo '</div>';
				
			echo '</div>';
			echo '<div class="col-md-6">';
				echo '<div class="box box-info">';

				if($accountInfoConfig['showCharacters']) {
					// ACCOUNT CHARACTERS
					$accountCharacters = glenox::ChaInformation($accountInfo['UserNum']);
					$status = ($char["ChaDeleted"]==1)? "Deleted" : "OK";
					echo '<div class="box-header with-border">';
					   echo '<h3 class="box-title">Characters</h3>';
	            	echo '<div class="box-body">';
						if(is_array($accountCharacters)) {
							echo '<table class="table table-no-border table-hover">';
							echo '<thead>
									<tr>
									<th>Character name\'s</th>
									<th>Status</th>
									</tr>
									</thead>';
							  if(is_array($accountCharacters)) {
								foreach($accountCharacters as $char) {
									echo '<tr>';
										echo '<td><a href="'.admincp_base("editcharacter&id=".$char["ChaNum"]).'">'.$char["ChaName"].'</a></td>';
										echo '<td>'.$status.'</td>';
									echo '</tr>';
								}
							  } 
							echo '</table>';
						} else {
							message('warning', 'No characters found.', ' ');
						}
					echo '</div>';
					echo '</div>';
				}
				
			echo '</div>';
		echo '</div>';

		echo '<div class="col-md-6">';
				echo '<div class="box box-info">';
					
				if($accountInfoConfig['showTopLog']) {

					$TopLog = glenox::TopLog($accountInfo['UserNum']);

					//$status = ($ItemLog["PurFlag"]==0)? "IN" : "OUT";

					echo '<div class="box-header with-border">';
					   echo '<h3 class="box-title">Top-up Log</h3>';
	            	echo '<div class="box-body">';
								if(is_array($TopLog)) {
									echo '<table id="ItemLog" class="table table-no-border table-hover">';
									echo '<thead>
											<tr class="odd">
											<th>Pin</th>
											<th>Code</th>
											<th>Value</th>
											</tr>
											</thead>';
									  if(is_array($TopLog)) {
										foreach($TopLog as $Log) {
											echo '<tr>';
												echo '<td>'.$Log['toplog_pin'].'</td>';
												echo '<td>'.$Log['toplog_code'].'</td>';
												echo '<td>'.$Log['toplog_value'].'</td>';
											echo '</tr>';
										}
									  } 
									echo '</table>';
								} else {
									message('warning', 'No Top log.', ' ');
								}

					echo '</div>';
					echo '</div>';
				}
				
			echo '</div>';
		echo '</div>';
		
			// CHANGE PASSWORD
			echo '<div class="col-md-6">';
				echo '<div class="box box-info">';

				echo '<div class="box-header with-border">';
				echo '<h3 class="box-title">Change Account\'s Password</h3>';
				echo '<div class="box-body">';
					echo '<form role="form" method="post">';
					echo '<input type="hidden" name="action" value="changepassword"/>';
						echo '<div class="form-group">';
							echo '<label for="input_1">New Password:</label>';
							echo '<input type="text" class="form-control" id="input_1" name="changepassword_newpw" placeholder="New password">';
						echo '</div>';
						echo '<div class="checkbox">';
							echo '<label><input type="checkbox" name="editaccount_sendmail" value="1" checked> Send email to user about this change.</label>';
						echo '</div>';
						echo '<button type="submit" name="editaccount_submit" class="btn btn-success" value="ok">Change Password</button>';
					echo '</form>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			echo '</div>';
			
			// CHANGE EMAIL ADDRESS
			echo '<div class="col-md-6">';
			 echo '<div class="box box-info">';
				echo '<div class="box-header with-border">';
				echo '<h3 class="box-title">Change Account\'s Email</h3>';
				echo '<div class="box-body">';
					echo '<form role="form" method="post">';
					echo '<input type="hidden" name="action" value="changeemail"/>';
						echo '<div class="form-group">';
							echo '<label for="input_2">New Email:</label>';
							echo '<input type="email" class="form-control" id="input_2" name="changeemail_newemail" placeholder="New email address">';
						echo '</div>';
						echo '<div class="checkbox">';
							echo '<label><input type="checkbox" name="editaccount_sendmail" value="1" checked> Send email to user about this change.</label>';
						echo '</div>';
						echo '<button type="submit" name="editaccount_submit" class="btn btn-success" value="ok">Change Email</button>';
					echo '</form>';
				echo '</div>';
				echo '</div>';
			  echo '</div>';
			echo '</div>';
		
	} catch(Exception $ex) {
		echo '<h1 class="page-header">Account Information</h1>';
		message('error', $ex->getMessage());
	}
	
	} else {
		echo '<h1 class="page-header">Account Information</h1>';
		message('error', 'Please provide a valid user id.');
	}
?>

		</div>
	</div>
</section>