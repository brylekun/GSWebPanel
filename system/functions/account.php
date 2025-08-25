<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 3.0.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

class Account {
	
	public function registerAccount($username, $password, $cpassword, $email) {
		
		if(!check_value($username)) throw new Exception(lang('error_4',true));
		if(!check_value($password)) throw new Exception(lang('error_4',true));
		if(!check_value($cpassword)) throw new Exception(lang('error_4',true));
		if(!check_value($email)) throw new Exception(lang('error_4',true));

		// Filters
		if(!Validator::UsernameLength($username)) throw new Exception(lang('error_5',true));
		if(!Validator::AlphaNumeric($username)) throw new Exception(lang('error_6',true));
		if(!Validator::PasswordLength($password)) throw new Exception(lang('error_7',true));
		if(!Validator::AlphaNumeric($password)) throw new Exception(lang('error_75',true));
		if($password != $cpassword) throw new Exception(lang('error_8',true));
		if(!Validator::Email($email)) throw new Exception(lang('error_9',true));
		
		# load registration configs
		$regCfg = loadConfigurations('register');
		
		# check if username / email exists
		if(glenox::userExists($username)) throw new Exception(lang('error_10',true));
		if(glenox::emailExists($email)) throw new Exception(lang('error_11',true));
		
		
		
		//return

		# Email Verification System (EVS)
		if($regCfg['verify_email']) {
			# check if username / email exists
			if($this->checkUsernameEVS($username)) throw new Exception(lang('error_10',true));
			if($this->checkEmailEVS($email)) throw new Exception(lang('error_11',true));
			
			# generate verification key
			$verificationKey = $this->createRegistrationVerification($username,$password,$email);
			if(!check_value($verificationKey)) throw new Exception(lang('error_23',true));
			
			# send verification email
			$this->sendRegistrationVerificationEmail($username,$email,$verificationKey);
			message('success', lang('success_18',true));
			return;
		} else {

			$pincode = glenox::generate_pin();

			if(glenox::MD5Hash()) {

				$pass = glenox::CRYPTMD5($password);
				$pass2 = glenox::CRYPTMD5($pincode);
			} else {
				$pass = $password;
				$pass2 = $pincode;
			}
		
		# insert data
		$data = array(
			'username' => $username,
			'userid'   => $username,
			'password' => $pass,
			'password2'=> $pass2,
			'email' => $email,
			'pincode' => $pincode
		);
		
		# query
		if(glenox::MD5Hash()) {
			$query = "INSERT INTO UserInfo (UserName, UserID, UserPass, UserPass2, UserEmail, UPass) VALUES (:username, :userid, :password, :password2, :email, :pincode)";
		} else {
			$query = "INSERT INTO UserInfo (UserName, UserID, UserPass, UserPass2, UserEmail, UPass) VALUES (:username, :userid, :password, :password2, :email, :pincode)";
		}
		
		# register account
		$result = glenox::DB('RanUser')->query($query, $data);
		if(!$result) throw new Exception(lang('error_22',true));
		
		# send welcome email
		if($regCfg['send_welcome_email']) {
			$this->sendWelcomeEmail($username, $email, $pincode);
		}
		
		# success message
		message('success', lang('success_1',true));
		
		# redirect to login (5 seconds)
		redirect(2,'',5);
		//redirect();

		}// End of register Account :)
	}
	
	public function changePasswordProcess($userid, $pincode, $password, $new_password, $confirm_new_password) {
		if(!check_value($userid)) throw new Exception(lang('error_4',true));
		if(!check_value($pincode)) throw new Exception(lang('error_4',true));
		if(!check_value($password)) throw new Exception(lang('error_4',true));
		if(!check_value($new_password)) throw new Exception(lang('error_4',true));
		if(!check_value($confirm_new_password)) throw new Exception(lang('error_4',true));
		if(!Validator::PasswordLength($new_password)) throw new Exception(lang('error_7',true));
		if($new_password != $confirm_new_password) throw new Exception(lang('error_8',true));
		
		$accountData = glenox::accountInformation($userid);

		# check user credentials
		if(!glenox::validateUser($accountData['UserID'], $password)) throw new Exception(lang('error_13',true));
		
		# check online status
		if(glenox::accountOnline($userid)) throw new Exception(lang('error_14',true));

		# check if user pincode is correct
		if(!glenox::accountPincode($userid,$pincode)) throw new Exception(lang('error_60',true));
		
		# change password
		if(!glenox::changePassword($userid, $username, $new_password)) throw new Exception(lang('error_23',true));
		
		# send email with new password
		
		try {
			$email = new Email();
			$email->setTemplate('CHANGE_PASSWORD');
			$email->addVariable('{USERNAME}', $username);
			$email->addVariable('{NEW_PASSWORD}', $new_password);
			$email->addAddress($accountData['UserEmail']);
			$email->send();
		} catch (Exception $ex) {}
		
		# success message
		message('success', lang('success_2',true));
	}
	
	public static function changePasswordProcess_verifyEmail($userid, $pincode, $password, $new_password, $confirm_new_password, $ip_address) {
		if(!check_value($userid)) throw new Exception(lang('error_4',true));
		if(!check_value($pincode)) throw new Exception(lang('error_4',true));
		if(!check_value($password)) throw new Exception(lang('error_4',true));
		if(!check_value($new_password)) throw new Exception(lang('error_4',true));
		if(!check_value($confirm_new_password)) throw new Exception(lang('error_4',true));
		if(!Validator::AlphaNumeric($new_password))  throw new Exception(lang('error_75',true));
		if(!Validator::PasswordLength($new_password)) throw new Exception(lang('error_7',true));
		if($new_password != $confirm_new_password) throw new Exception(lang('error_8',true));
		
		# load changepw configs
		$mypassCfg = loadConfigurations('usercp.mypassword');
		
		# load account data
		$accountData = glenox::accountInformation($userid);
		if(!is_array($accountData)) throw new Exception(lang('error_21',true));

		# check user credentials
		if(!glenox::validateUser($accountData['UserID'], $password)) throw new Exception(lang('error_13',true));
		
		# check online status
		if(glenox::accountOnline($userid)) throw new Exception(lang('error_14',true));
		
		# check if user has an active password change request
		if(glenox::hasActivePasswordChangeRequest($userid)) throw new Exception(lang('error_19',true));
		
		# check if user pincode is correct
		if(!glenox::accountPincode($userid,$pincode)) throw new Exception(lang('error_60',true));
		
		# request data
		$auth_code = mt_rand(111111,999999);
		$link = glenox::generatePasswordChangeVerificationURL($userid, $auth_code);
		
		# add request to database
		$addRequest = glenox::addPasswordChangeRequest($userid, $new_password, $auth_code);
		if(!$addRequest) throw new Exception(lang('error_21',true));
		
		# send verification email
		try {
			$email = new Email();
			$email->setTemplate('CHANGE_PASSWORD_EMAIL_VERIFICATION');
			$email->addVariable('{USERNAME}', $accountData['UserID']);
			$email->addVariable('{DATE}', date("m/d/Y @ h:i a"));
			$email->addVariable('{IP_ADDRESS}', $ip_address);
			$email->addVariable('{LINK}', $link);
			$email->addVariable('{EXPIRATION_TIME}', $mypassCfg['change_password_request_timeout']);
			$email->addAddress($accountData['UserEmail']);
			$email->send();
			
			message('success', lang('success_3',true));
			redirect(2,'usercp/myaccount',3);
		} catch (Exception $ex) {
			message('error', lang('error_20',true));
		}
		
	}

	public static function cPasswordProcess($userid, $pincode, $password, $new_password, $confirm_new_password) {


		if(!check_value($userid)) throw new Exception(lang('error_4',true));
		if(!check_value($pincode)) throw new Exception(lang('error_4',true));
		if(!check_value($password)) throw new Exception(lang('error_4',true));
		if(!check_value($new_password)) throw new Exception(lang('error_4',true));
		if(!check_value($confirm_new_password)) throw new Exception(lang('error_4',true));
		if(!Validator::AlphaNumeric($new_password))  throw new Exception(lang('error_75',true));
		if(!Validator::PasswordLength($new_password)) throw new Exception(lang('error_7',true));
		if($new_password != $confirm_new_password) throw new Exception(lang('error_8',true));
		

		# show account data
		$accountData = glenox::accountInformation($userid);

		# check user credentials
		if(!glenox::validateUser($accountData['UserID'], $password)) throw new Exception(lang('error_13',true));
		
		# check if user pincode is correct
		if(!glenox::accountPincode($userid,$pincode)) throw new Exception(lang('error_60',true));
		
		if(!is_array($accountData)) throw new Exception(lang('error_21',true));
		
		# update password
		if(!glenox::changePassword($userid, $accountData['UserID'], $new_password)) throw new Exception(lang('error_29',true));

		# success message
		message('success', lang('success_5',true));
		redirect(2,'usercp/myaccount',3);
		
	}
	
	public function changePasswordVerificationProcess($user_id, $auth_code) {
		if(!check_value($user_id)) throw new Exception(lang('error_24',true));
		if(!check_value($auth_code)) throw new Exception(lang('error_24',true));
		
		$userid = Decode_id($user_id);
		$authcode = Decode_id($auth_code);
		
		if(!Validator::UnsignedNumber($userid)) throw new Exception(lang('error_25',true));
		if(!Validator::UnsignedNumber($authcode)) throw new Exception(lang('error_25',true));
		
		$result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM ChangePass WHERE user_id = ?", array($userid));
		if(!is_array($result)) throw new Exception(lang('error_25',true));
		
		# load changepw configs
		$mypassCfg = loadConfigurations('usercp.mypassword');
		$request_timeout = $mypassCfg['change_password_request_timeout'] * 3600;
		$request_date = $result['request_date'] + $request_timeout;
		
		# check request data
		if($request_date < time()) throw new Exception(lang('error_26',true));
		if($result['auth_code'] != $authcode) throw new Exception(lang('error_27',true));
		
		# account data
		$accountData = glenox::accountInformation($userid);
		$username = $accountData['UserID'];
		$new_password = Decode($result['new_password']);
		
		# check online status
		if(glenox::accountOnline($username)) throw new Exception(lang('error_14',true));
		
		# update password
		if(!glenox::changePassword($userid, $username, $new_password)) throw new Exception(lang('error_29',true));
		
		# send email
		try {
			$email = new Email();
			$email->setTemplate('CHANGE_PASSWORD');
			$email->addVariable('{USERNAME}', $username);
			$email->addVariable('{NEW_PASSWORD}', $new_password);
			$email->addAddress($accountData['UserEmail']);
			$email->send();

		} catch (Exception $ex) {}
		
		# clear password change request
		$this->removePasswordChangeRequest($userid);
		
		# success message
		//message('success', lang('success_5',true));
		//redirect(2,'usercp/myaccount',3);
		
	}

	public function removePasswordChangeRequest($userid) {
		$result = glenox::DB(config('SQL_RANPANEL'))->query("DELETE FROM ChangePass WHERE user_id = ?", array($userid));
		if($result) return true;
		return;
	}

	private function forlogPass($user_id,$ip_address){

		$data = array(
				$user_id,
				$ip_address,
				time()
			);

    	$query = "INSERT INTO forgot_log(user_id,ip_address,request_date) VALUES (?,?,?)";
  			
  		$log = glenox::DB(config('SQL_RANPANEL'))->query($query, $data);

  		if(!$log) throw new Exception('System error cause log in failed!');

	}
	
	public function passwordRecoveryProcess($user_email,$username, $ip_address) {
		if(!check_value($user_email)) throw new Exception(lang('error_30',true));
		if(!check_value($username)) throw new Exception(lang('error_30',true));
		if(!check_value($ip_address)) throw new Exception(lang('error_30',true));
		if(!Validator::Email($user_email)) throw new Exception(lang('error_30',true));
		if(!Validator::Ip($ip_address)) throw new Exception(lang('error_30',true));
		
		if(!glenox::emailExists($user_email)) throw new Exception(lang('error_30',true));
		
		
		$user_id = glenox::retrieveAccPass($user_email,$username);

		if(!check_value($user_id)) throw new Exception(lang('error_76',true));
		
		$accountData = glenox::accountInformation($user_id);
		if(!is_array($accountData)) throw new Exception(lang('error_23',true));

		#Insrt Log
		$result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM forgot_log WHERE user_id = ? AND ip_address = ?", array($accountData['UserNum'],$ip_address));
		if(is_array($result)) {
			$request_timeout = 24 * 3600;
			$request_date = $result['request_date'] + $request_timeout;
			if($request_date > time()) throw new Exception('Your already request for forgot password! Please wait your email!');
			
			glenox::DB(config('SQL_RANPANEL'))->query("UPDATE forgot_log SET request_date = ? WHERE user_id = ?", array(time(),$accountData['UserNum']));
		
		} else {

			$this->forlogPass($accountData['UserNum'],$ip_address);
		}
		
		# Account Recovery Code
		$arc = glenox::generateAccountRecoveryCode($accountData['UserNum'], $accountData['UserID']);

		# Account Recovery URL
		$aru = $this->generateAccountRecoveryLink($accountData['UserNum'], $accountData['UserEmail'], $arc);
		
		# send email
		try {
			$email = new Email();
			$email->setTemplate('PASSWORD_RECOVERY_REQUEST');
			$email->addVariable('{USERNAME}', $accountData['UserID']);
			$email->addVariable('{DATE}', date("Y-m-d @ h:i a"));
			$email->addVariable('{IP_ADDRESS}', $ip_address);
			$email->addVariable('{LINK}', $aru);
			$email->addAddress($accountData['UserEmail']);
			$email->send();
			
			message('success', lang('success_6',true));
			redirect(2,'usercp/myaccount',3);
		} catch (Exception $ex) {
			throw new Exception(lang('error_23',true));
		}
	}

	
	public function passwordRecoveryVerificationProcess($ui, $ue, $key) {
		if(!check_value($ui)) throw new Exception(lang('error_31',true));
		if(!check_value($ue)) throw new Exception(lang('error_31',true));
		if(!check_value($key)) throw new Exception(lang('error_31',true));
		
		$user_id = Decode($ui); // decoded user id
		if(!Validator::UnsignedNumber($user_id)) throw new Exception(lang('error_31',true));
		
		$user_email = Decode($ue); // decoded email address
		if(!glenox::emailExists($user_email)) throw new Exception(lang('error_31',true));
		
		$accountData = glenox::accountInformation($user_id);
		if(!is_array($accountData)) throw new Exception(lang('error_31',true));
		
		$username = $accountData['UserID'];
		$gen_key = glenox::generateAccountRecoveryCode($user_id, $username);
		
		# compare keys
		if($key != $gen_key) throw new Exception(lang('error_31',true));
		
		# update user password
		$new_password = rand(11111111,99999999);
		$update_pass = glenox::changePassword($user_id, $username, $new_password);
		if(!$update_pass) throw new Exception(lang('error_23',true));

		try {
			$email = new Email();
			$email->setTemplate('PASSWORD_RECOVERY_COMPLETED');
			$email->addVariable('{USERNAME}', $username);
			$email->addVariable('{NEW_PASSWORD}', $new_password);
			$email->addAddress($accountData['UserEmail']);
			$email->send();
			
			message('success', lang('success_7',true));
		} catch (Exception $ex) {
			throw new Exception(lang('error_23',true));
		}
	}
	
	public function masterKeyRecoveryProcess($user_id) {
		if(!check_value($user_id)) throw new Exception(lang('error_23',true));
		if(check_value($_COOKIE['masterkey'])) throw new Exception(lang('error_50',true));
		
		$accountData = glenox::accountInformation($user_id);
		if(!check_value($accountData['UserPass2'])) throw new Exception(lang('error_49',true));
		
		if(glenox::accountOnline($accountData['UserID'])) throw new Exception(lang('error_14',true));
		
		try {
			$email = new Email();
			$email->setTemplate('MASTER_KEY_RECOVERY');
			$email->addVariable('{USERNAME}', $accountData['UserID']);
			$email->addVariable('{CURRENT_MASTERKEY}', $accountData['Upass']);
			$email->addAddress($accountData['UserEmail']);
			$email->send();
			
			message('success', lang('success_16',true));
			setcookie("masterkey", $accountData['UserID'], time()+3600);  /* expire in 1 hour */
		} catch (Exception $ex) {
			throw new Exception(lang('error_23',true));
		}
	}
	// Change Emaill //
	public static function changeEmailAddress($accountId, $pincode, $newEmail, $ipAddress) {
		if(!check_value($accountId)) throw new Exception(lang('error_41',true));
		if(!check_value($newEmail)) throw new Exception(lang('error_41',true));
		if(!check_value($pincode)) throw new Exception(lang('error_41',true));
		if(!check_value($ipAddress)) throw new Exception(lang('error_41',true));
		if(!Validator::Ip($ipAddress)) throw new Exception(lang('error_21',true));
		if(!Validator::Email($newEmail)) throw new Exception(lang('error_9',true));
			

		# checkng if meron email
		if(glenox::emailExists($newEmail)) throw new Exception(lang('error_11',true));
		
		# account info
		$accountInfo = glenox::accountInformation($accountId);
		if(!is_array($accountInfo)) throw new Exception(lang('error_21',true));

		#pincode check
		if(!glenox::accountPincode($accountId, $pincode)) throw new Exception("Pincode is incorrect",true);
		

		$myemailCfg = loadConfigurations('usercp.myemail');
		if($myemailCfg['require_verification']) {
			# requires verification
			$userName = $accountInfo['UserID'];
			$userEmail = $accountInfo['UserEmail'];
			$requestDate = strtotime(date("m/d/Y 23:59"));
			$key = md5(md5($userName).md5($userEmail).md5($requestDate).md5($newEmail));
			$verificationLink = __BASE_URL__.'verifyemail/?op='.Encode_id(3).'&uid='.Encode_id($accountId).'&email='.$newEmail.'&key='.$key;
			
			# send verification email
			$sendEmail = self::changeEmailVerificationMail($userName, $userEmail, $newEmail, $verificationLink, $ipAddress);
			if(!$sendEmail) throw new Exception(lang('error_21',true));

			message('success', lang('success_19',true));
		} else {
			# no verification required
			if(!glenox::updateEmail($accountId, $newEmail)) throw new Exception(lang('error_21',true));
			message('success', lang('success_20',true));
		}
	}
	
	public function changeEmailVerificationProcess($encodedId, $newEmail, $encryptedKey) {
		$userId = Decode_id($encodedId);
		if(!Validator::UnsignedNumber($userId)) throw new Exception(lang('error_21',true));
		if(!Validator::Email($newEmail)) throw new Exception(lang('error_21',true));
		
		# check if email already in use
		if(glenox::emailExists($newEmail)) throw new Exception(lang('error_11',true));
		
		# account info
		$accountInfo = glenox::accountInformation($userId);
		if(!is_array($accountInfo)) throw new Exception(lang('error_21',true));
		
		# check key
		$requestDate = strtotime(date("m/d/Y 23:59"));
		$key = md5(md5($accountInfo['UserID']).md5($accountInfo['UserEmail']).md5($requestDate).md5($newEmail));
		if($key != $encryptedKey) throw new Exception(lang('error_21',true));
		
		# change email
		if(!glenox::updateEmail($userId, $newEmail)) throw new Exception(lang('error_21',true));
	}

	public function verifyRegistrationProcess($username, $key) {
		$verifyKey = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM reg_account WHERE registration_account = ? AND registration_key = ?", array($username,$key));
		if(!is_array($verifyKey)) throw new Exception(lang('error_25',true));
		
		# load registration configs
		$regCfg = loadConfigurations('register');
		
		$pincode = glenox::generate_pin();
		
		$email = $verifyKey['registration_email'];

		if(glenox::MD5Hash()) {

				$pass = glenox::CRYPTMD5($verifyKey['registration_password']);
				$pass2 = glenox::CRYPTMD5($pincode);
		}
		
		# insert data
		$data = array(
			'username' => $username,
			'userid'   => $username,
			'password' => $pass,
			'password2'=> $pass2,
			'email' => $email,
			'pincode' => $pincode
		);
		
		# query
		if(glenox::MD5Hash()) {
			$query = "INSERT INTO UserInfo (UserName, UserID, UserPass, UserPass2, UserEmail, UPass) VALUES (:username, :userid, :password, :password2, :email, :pincode)";
		} else {
			$query = "INSERT INTO UserInfo (UserName, UserID, UserPass, UserPass2, UserEmail, UPass) VALUES (:username, :userid, :password, :password2, :email, :pincode)";
		}
		
		# register account
		$result = glenox::DB('RanUser')->query($query, $data);
		if(!$result) throw new Exception(lang('error_22',true));
		
		# delete verification request
		$this->deleteRegistrationVerification($username);
		
		# send welcome email
		if($regCfg['send_welcome_email']) {
			$this->sendWelcomeEmail($verifyKey['registration_account'],$verifyKey['registration_email'],$pincode);
		}
		
		# success message
		message('success', lang('success_1',true));
		
		# redirect to login (5 seconds)
		redirect(2,'/',5);
	}
	
	private function sendRegistrationVerificationEmail($username, $account_email, $key) {
		$verificationLink = __BASE_URL__.'verifyemail/?op='.Encode_id(2).'&user='.Encode($username).'&key='.$key;
		//var_dump($verificationLink);
		try {
			$email = new Email();
			$email->setTemplate('WELCOME_EMAIL_VERIFICATION');
			$email->addVariable('{USERNAME}', $username);
			$email->addVariable('{LINK}', $verificationLink);
			$email->addAddress($account_email);
			$email->send();
		} catch (Exception $ex) {
			throw new Exception($ex);
		}
	}
	
	private function sendWelcomeEmail($username,$address,$pin) {
		try {
			$email = new Email();
			$email->setTemplate('WELCOME_EMAIL');
			$email->addVariable('{USERNAME}', $username);
			$email->addVariable('{PINCODE}', $pin);
			$email->addAddress($address);
			$email->send();
		} catch (Exception $ex) {
			// do nuthin u.u
		}
	}
	
	private function createRegistrationVerification($username,$password,$email) {

		if(!check_value($username)) return;
		if(!check_value($password)) return;
		if(!check_value($email)) return;
		
		$key = uniqid();
		$data = array(
			$username,
			$password,
			$email,
			time(),
			$_SERVER['REMOTE_ADDR'],
			$key
		);


		
		$query = "INSERT INTO reg_account (registration_account,registration_password,registration_email,registration_date,registration_ip,registration_key) VALUES (?,?,?,?,?,?)";
		
		$result = glenox::DB(config('SQL_RANPANEL'))->query($query, $data);

		if(!$result) return;
		return $key;
	}
	
	private function deleteRegistrationVerification($username) {
		if(!check_value($username)) return;
		$delete = glenox::DB(config('SQL_RANPANEL'))->query("DELETE FROM reg_account WHERE registration_account = ?", array($username));
		if($delete) return true;
		return;
	}

	private function checkUsernameEVS($username) {
		if(!check_value($username)) return;
		$result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM reg_account WHERE registration_account = ?", array($username));
		
		$configs = loadConfigurations('register');
		if(!is_array($configs)) return;
		
		$timelimit = $result['registration_date']+$configs['verification_timelimit']*60*60;
		if($timelimit > time()) return true;
		
		$this->deleteRegistrationVerification($username);
		return false;
	}

	private function checkEmailEVS($email) {
		if(!check_value($email)) return;
		$result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM reg_account WHERE registration_email = ?", array($email));
		
		$configs = loadConfigurations('register');
		if(!is_array($configs)) return;
		
		$timelimit = $result['registration_date']+$configs['verification_timelimit']*60*60;
		if($timelimit > time()) return true;
		
		$this->deleteRegistrationVerification($result['registration_account']);
		return false;
	}
	
	private static function changeEmailVerificationMail($userName, $emailAddress, $newEmail, $verificationLink, $ipAddress) {
		try {
			$email = new Email();
			$email->setTemplate('CHANGE_EMAIL_VERIFICATION');
			$email->addVariable('{USERNAME}', $userName);
			$email->addVariable('{IP_ADDRESS}', $ipAddress);
			$email->addVariable('{NEW_EMAIL}', $newEmail);
			$email->addVariable('{LINK}', $verificationLink);
			$email->addAddress($emailAddress);
			$email->send();
			
			return true;
		} catch (Exception $ex) {
			return;
		}
	}
	
	private function generateAccountRecoveryLink($userid,$email,$recovery_code) {
		if(!check_value($userid)) return;
		if(!check_value($recovery_code)) return;
		
		$build_url = __BASE_URL__;
		$build_url .= 'forgotpassword/';
		$build_url .= '?ui=';
		$build_url .= Encode($userid);
		$build_url .= '&ue=';
		$build_url .= Encode($email);
		$build_url .= '&key=';
		$build_url .= $recovery_code;
		return $build_url;
	}

	#gametime to vpoints
	public static function gtopProccess($userid,$gt_m,$gt_p) {
       
        if(!Validator::Number($userid)) return;

        $gametimeinfo = glenox::accountInformation($userid);



        $Minutes=($gametimeinfo['Gametime3']) / ($gt_m);
        $hours = (int)$Minutes;
        $sagot1=$hours*$gt_m;
        $sagot2=$hours*$gt_p;

        if ($gametimeinfo['Gametime3']>=$gt_m) {
			
                $result = self::Proccess($userid,$sagot1,$sagot2);

                if ($result) {
                    message('success', 'Converted successfully!');
                }
                
            } else {

                    message('error', 'Your account doesnt have enough Game Time to convert.');        
            }

        

    }

    private static function Proccess($userid,$sagot1,$sagot2){
        
        if(!Validator::Number($userid)) return;

        $result = glenox::DB('RanUser')->query("UPDATE UserInfo SET Gametime3 = Gametime3- ?, UserPoint2 = UserPoint2+? WHERE UserNum = ?", array($sagot1, $sagot2, $userid));
        if($result) return true;
        return;
    }

    #top up code
    public static function topupProccess($userid,$pin,$code){
    	if(!check_value($userid)) throw new Exception(lang('error_4',true));
    	if(!check_value($pin)) throw new Exception(lang('error_4',true));
    	if(!check_value($code)) throw new Exception(lang('error_4',true));

    	$topup = self::gettopup($pin,$code);

    	if($topup) {

    		$id = $topup['top_id'];
    		$value = $topup['top_value'];

    		//
    		$data = array(
				$pin,
				$code,
				$userid,
				$value,
				$_SERVER['REMOTE_ADDR'],
				time(),
			);
				// Log topup
				if(ranconfig('topup_log')) {
					self::InsertTopuplog($data);
				}
    			$update = glenox::DB('RanUser')->query("UPDATE UserInfo SET UserPoint = UserPoint+? WHERE UserNum = ?", array($value,$userid));
    		
    			if(!$update) throw new Exception('User update failed!');

    			if(self::DeleteTopupCode($id)){
    				 message('success', 'Topup successfully!');
    				 redirect(2,'usercp/myaccount',5);
    			} else {
    				message('error', 'Top up failed!');
    			}
  			
    	}
    }

    private static function DeleteTopupCode($id){

    	if(!check_value($id)) return;

		$delete = glenox::DB(config('SQL_RANPANEL'))->query("DELETE FROM topup WHERE top_id = ?", array($id));

		if($delete) return true;
		return;
    }

    private static function InsertTopuplog($data){

    	$query = "INSERT INTO topup_log (toplog_pin,toplog_code,toplog_usernum,toplog_value,toplog_ip,toplog_date)	VALUES (?,?,?,?,?,?)";
  			
  		$log = glenox::DB(config('SQL_RANPANEL'))->query($query, $data);

  		if(!$log) throw new Exception('System error cause log in failed!');
  		return true;
    }
    private static function gettopup($pin,$code){

    	if(!check_value($pin)) return;
    	if(!check_value($code)) return;

    	$_pin = encrypt($pin);
    	$_code = encrypt($code);

    	$result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM topup WHERE top_pin = ? AND top_code = ?", array($_pin,$_code));

    	if(is_array($result)) return $result;
    	message('error', 'Top up failed!');

    }

    #end of top up code! :)

    private function TopupCodeGen($value,$number=1){

    	$pin = Encode_id($this-gen_pin());
    	$code = Encode_id($this->gen_code());
    	$date = time();

    	for ($i = 0; $i < $number; $i++) {

    		$result = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO topup (top_pin,top_code,top_value,top_date) VALUES (?,?,?)", array($pin,$code,$date,$value));

    	}

    	if($result) return true;


    }
	
}