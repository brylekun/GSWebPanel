<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 3.0.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

class login {
	
	private $_config;
	private $loginConfigs;
	
	function __construct() {
		

		$this->db3 = glenox::DB("RanUser");
		$this->db4 = glenox::DB(config('SQL_RANPANEL'));

		
		$loginConfigs = loadConfigurations('login');
		if(!is_array($loginConfigs)) throw new Exception('Login configurations missing.');
		$this->_config = $loginConfigs;
	}
	
	public function isLoggedIN() {
		if(!$_SESSION['valid']) return;
		if(!check_value($_SESSION['userid'])) return;
		if(!check_value($_SESSION['username'])) return;
		
		if(!glenox::checkActiveSession($_SESSION['userid'], session_id())) {
			# session is inactive -> logout
			$this->logout();
			return;
		}
		
		# update session time
		$this->updateActiveSessionTime($_SESSION['userid']);
		
		# no session timeout
		if(!$this->_config['enable_session_timeout']) return true;
		
		# session timeout is enabled
		if(!$this->isSessionActive($_SESSION['timeout'])) {
			# session timed out -> logout
			$this->logout();
			return;
		}
		
		# update session data
		$_SESSION['timeout'] = time();
		
		return true;
	}
	
	public function validateLogin($username, $password) {
		
		if(!check_value($username)) throw new Exception(lang('error_4',true));
		if(!check_value($password)) throw new Exception(lang('error_4',true));
		if(!$this->canLogin($_SERVER['REMOTE_ADDR'])) throw new Exception(lang('error_3',true));
		if(!glenox::userExists($username)) throw new Exception(lang('error_2',true));
		if(glenox::validateUser($username,$password)) {
			# login success
			
			$this->removeFailedLogins($_SERVER['REMOTE_ADDR']);

			session_regenerate_id();
			$_SESSION['valid'] = true;
			$_SESSION['timeout'] = time();
			$_SESSION['userid'] = glenox::retrieveUserID($username);
			$_SESSION['username'] = $username;
			
			// ACTIVE SESSIONS
			$this->deleteActiveSession($_SESSION['userid']);
			$this->addActiveSession($_SESSION['userid'], $_SERVER['REMOTE_ADDR']);
			
			# redirect to usercp
			redirect(1,'usercp/');
			
		} else {
			# failed login
			$this->addFailedLogin($username,$_SERVER['REMOTE_ADDR']);
			message('error', lang('error_3',true));
			message('warning', langf('login_txt_5', array($this->checkFailedLogins($_SERVER['REMOTE_ADDR']), $this->_config['max_login_attempts'], $this->_config['max_login_attempts'])));
		}
	}
	
	public function canLogin($ipaddress) {
		if(!Validator::Ip($ipaddress)) return;
		$failedLogins = $this->checkFailedLogins($ipaddress);
		if($failedLogins < $this->_config['max_login_attempts']) return true;
		
		$result = $this->db4->query_fetch_single("SELECT * FROM Login WHERE ip_address = ? ORDER BY id DESC", array($ipaddress));
		
		if(!is_array($result)) return true;
		if(time() < $result['unlock_timestamp']) return;
		
		$this->removeFailedLogins($ipaddress);

		return true;
	}
	
	public function checkFailedLogins($ipaddress) {
		if(!Validator::Ip($ipaddress)) return;
		$result = $this->db4->query_fetch_single("SELECT * FROM Login WHERE ip_address = ? ORDER BY id DESC", array($ipaddress));
		if(!is_array($result)) return;
		return $result['failed_attempts'];
	}
	
	public function addFailedLogin($username, $ipaddress) {
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		if(!Validator::Ip($ipaddress)) return;
		if(!glenox::userExists($username)) return;
		
		$failedLogins = $this->checkFailedLogins($ipaddress);
		$timeout = time()+$this->_config['failed_login_timeout']*60;
		
		if($failedLogins >= 1) {
			# update
			if(($failedLogins+1) >= $this->_config['max_login_attempts']) {
				# max failed attemps -> block
				$this->db4->query("UPDATE Login SET username = ?, ip_address = ?, failed_attempts = failed_attempts + 1, unlock_timestamp = ?, timestamp = ? WHERE ip_address = ?", array($username, $ipaddress, $timeout, time(), $ipaddress));
			} else {
				$this->db4->query("UPDATE Login SET username = ?, ip_address = ?, failed_attempts = failed_attempts + 1, timestamp = ? WHERE ip_address = ?", array($username, $ipaddress, time(), $ipaddress));
			}
		} else {
			# insert
			$data = array($username, $ipaddress, 0, 1, time());
			$this->db4->query("INSERT INTO Login (username, ip_address, unlock_timestamp, failed_attempts, timestamp) VALUES (?, ?, ?, ?, ?)", $data);
		}
	
	}
	
	public function removeFailedLogins($ip) {
		if(!Validator::Ip($ip)) return;

		$this->db4->query("DELETE FROM Login WHERE ip_address = ?", array($ip));
	}
	
	public function isSessionActive($session_timeout) {
		if(!check_value($session_timeout)) return;
		$offset = time() - $session_timeout;
		if($offset > $this->_config['session_timeout']) return;
		return true;
	}
	
	public function logout() {
		$_SESSION = array();
		session_destroy();
		redirect();
	}
	
	private function deleteActiveSession($userid) {
		$this->db4->query("DELETE FROM ActiveSessions WHERE session_user_id = ?", array($userid));
	}
	
	private function addActiveSession($userid,$ipaddress) {
		$add = $this->db4->query("INSERT INTO ActiveSessions (session_user_id,session_id,session_ip,session_time) VALUES (?,?,?,?) ", array($userid,session_id(),$ipaddress,time()));
		if(!$add) return;
		return true;
	}
	
	private function updateActiveSessionTime($userid) {
		$update = $this->db4->query("UPDATE ActiveSessions SET session_time = ? WHERE session_user_id = ?", array(time(),$userid));
		if(!$update) return;
		return true;
	}



}