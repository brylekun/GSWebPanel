<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

class glenox {
	private static $ranpanel;
	private static $regexPattern = '/[^a-zA-Z0-9\/\-\:\.\_]/';
	//private $encryptionHash;

	public static function Start() {
		global $lang,$custom;
		
		try {
			
			self::beginSession();

			# License
			# if(!self::ReadLicense()) throw new Exception('License is invalid!');

			# IP Blocking System
			if(config('ip_block_system_enable')) {
				
				if(self::isIpBlocked($_SERVER['REMOTE_ADDR'])) throw new Exception('Your IP address has been blocked.');
			}
			# Load Plugins
			

			# load language
			$loadLanguage = (check_value($_SESSION['language_display']) ? $_SESSION['language_display'] : config('language_default'));
			

			$loadLanguage = (config('language_switch_active',true) ? $loadLanguage : config('language_default'));
			
			
			if(!self::languageExists($loadLanguage)) throw new Exception('The chosen language cannot be loaded ('.$loadLanguage.').');
			include(__PATH_LANGUAGES__ . $loadLanguage . '/language.php');
			
			# access
			if(!defined('access') or !access) {

			} else {
				# check if template exists
				if(!self::templateExists(config('website_template'))) throw new Exception('The chosen template cannot be loaded ('.config('website_template').').');
				
				# load template
				include(__PATH_TEMPLATES__ . config('website_template') . '/index.php');
			}
			
		} catch(Exception $ex) {
			throw new Exception('[ERROR] ' . $ex->getMessage());
		}
		
	}

	public static function beginSession() {
		header( 'Cache-Control: no-store, no-cache, must-revalidate' ); # Clear Cache after submit
		session_name('Ran_Session'); # session name (change to your server name and uncomment)
		session_set_cookie_params(0, '/', __LICENSE_KEY___); # same session with and without www protocol (edit with your domain and uncomment)
		session_start();
		@ob_start();
	}

	public static function DB($database='') {
		switch($database) {
			case 'RanGame1':
				$db = new database(config('SQL_DB_HOST'), config('SQL_DB_PORT'), config('SQL_RANGAME1'), config('SQL_DB_USER'), config('SQL_DB_PASS'), config('SQL_PDO_DRIVER'));
				
				if($db->dead) {
                	if(config('error_reporting',true)) {
                		throw new Exception($db->error);
                	} else {
                		throw new Exception($db->error);
                	}
                }
				return $db;
				break;
			case 'RanShop':
				$db = new database(config('SQL_DB_HOST'), config('SQL_DB_PORT'), config('SQL_RANSHOP'), config('SQL_DB_USER'), config('SQL_DB_PASS'), config('SQL_PDO_DRIVER'));
				if($db->dead) {
                	if(config('error_reporting',true)) {
                		throw new Exception($db->error);
                	} else {
                		throw new Exception($db->error);
                	}
                }
				return $db;break;
			case 'RanUser':
				$db = new database(config('SQL_DB_HOST'), config('SQL_DB_PORT'), config('SQL_RANUSER'), config('SQL_DB_USER'), config('SQL_DB_PASS'), config('SQL_PDO_DRIVER'));
				if($db->dead) {
                	if(config('error_reporting',true)) {
                		throw new Exception($db->error);
                	} else {
                		throw new Exception($db->error);
                	}
                }
				return $db;break;
			case config('SQL_RANPANEL'):
				$db = new database(config('SQL_DB_HOST'), config('SQL_DB_PORT'), config('SQL_RANPANEL'), config('SQL_DB_USER'), config('SQL_DB_PASS'), config('SQL_PDO_DRIVER'));
				if($db->dead) {
                	if(config('error_reporting',true)) {
                		throw new Exception($db->error);
                	} else {
                		throw new Exception($db->error);
                	}
				}
				return $db;break;
			default:break;
				
		}
	}

	private static function ReadLicense(){
		if ((HTTP_HOST == __LICENSE_KEY___) || (HTTP_HOST == 'www.'.__LICENSE_KEY___)) return true;
		return false;
	}

	public static function loadModule($page = 'news',$subpage = 'home') {
		global $lang,$custom;
		try {
			
			$page = self::cleanRequest($page);
			$subpage = self::cleanRequest($subpage);
			
			$request = explode("/", $_GET['request']);
			if(is_array($request)) {
				for($i = 0; $i < count($request); $i++) {
					if(check_value($request[$i])) {
						if(check_value($request[$i+1])) {
							$_GET[$request[$i]] = filter_var($request[$i+1], FILTER_SANITIZE_STRING);
						} else {
							$_GET[$request[$i]] = NULL;
						}
					}
					$i++;
				}
			}
			
			if(!check_value($page)) { $page = 'news'; }
			
			if(!check_value($subpage)) {
				if(self::moduleExists($page)) {
					@loadModuleConfigs($page);
					include(__PATH_MODULES__ . $page . '.php');
				} else {
					self::module404();
				}
			} else {
				// HANDLE PAGE AS DIRECTORY (PATH)
				switch($page) {
					case 'news':
						if(self::moduleExists($page)) {
							@loadModuleConfigs($page);
							include(__PATH_MODULES__ . $page . '.php');
						} else {
							self::module404();
						}
					break;
					default:
						$path = $page.'/'.$subpage;
						if(self::moduleExists($path)) {
							$cnf = $page.'.'.$subpage;
							@loadModuleConfigs($cnf);
							include(__PATH_MODULES__ . $path . '.php');
						} else {
							self::module404();
						}
					break;
				}
			}
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}

	public static function loadAdminCPModule($module='home') {
		global $lang,$custom;
		
		
		$module = (check_value($module) ? $module : 'home');
		
		if(self::admincpmoduleExists($module)) {
			
			// admin access level
			$adminAccessLevel = config('admins',true);
			$accessLevel = $adminAccessLevel[$_SESSION['username']];
			
			// module access level
			$modulesAccessLevel = config('admincp_modules_access',true);
			if(is_array($modulesAccessLevel)) {
				if(array_key_exists($module, $modulesAccessLevel)) {
					if($accessLevel >= $modulesAccessLevel[$module]) {
						include(__PATH_ADMINCP_MODULES__.$module.'.php');
					} else {
						message('error','You do not have access to this module.');
					}
				} else {
					include(__PATH_ADMINCP_MODULES__.$module.'.php');
				}
			}
		} else {
			message('error','INVALID MODULE');
		}
	}

	private static function moduleExists($page) {
		if(file_exists(__PATH_MODULES__ . $page . '.php')) return true;
		return false;
	}

	private static function languageExists($language) {
		if(file_exists(__PATH_LANGUAGES__ . $language . '/language.php')) return true;
		return false;
	}

	private static function templateExists($template) {
		if(file_exists(__PATH_TEMPLATES__ . $template . '/index.php')) return true;
		return false;
	}

	private static function admincpmoduleExists($page) {
		if(file_exists(__PATH_ADMINCP_MODULES__ . $page . '.php')) return true;
		return false;
	}

	private static function displayTitle() {
		$websiteTitle = (check_value(lang('website_title',true)) && lang('website_title',true) != 'ERROR' ? lang('website_title',true) : config('website_title',true));
		echo $websiteTitle;
	}

	private static function cleanRequest($string) {
		return preg_replace(self::$regexPattern, '', $string);
	}
	// common //
	public static function encryptionHash(){
		return config('encryption_hash', true);
	}
	public static function MD5Hash(){
		return config('SQL_ENABLE_MD5', true);
	}
	public static function CRYPTMD5($a){
        return strtoupper(substr(md5($a),0,19));
	}
	
	public static function generate_pin() {
	    return substr(str_shuffle("123456789ABCDEFGHIJKLMNPQRSTVWXYZ"), 0, 6);
	}

	public static function gen_pin() {
	    $tokens = '123456789ABCDEFGHIJKLMNPQRSTVWXYZ';

		$serial = '';

		for ($i = 0; $i < 3; $i++) {
		    for ($j = 0; $j < 5; $j++) {
		        $serial .= $tokens[rand(0, 35)];
		    }

		    if ($i < 2) {
		        $serial .= '-';
		    }
		}

		return $serial;

	}
	
	public static function gen_code() {
	    return substr(str_shuffle("123456789ABCDEFGHIJKLMNPQRSTVWXYZ"), 0, 10);
	}

	public static function userExists($username) {
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		$result = self::DB('RanUser')->query_fetch_single("SELECT * FROM UserInfo WHERE UserID = ?", array($username));

		if(is_array($result)) return true;
		return;
	}

	public static function emailExists($email) {
		if(!Validator::Email($email)) return;
		$result = self::DB('RanUser')->query_fetch_single("SELECT UserNum FROM UserInfo WHERE UserEmail = ?", array($email));
		if(is_array($result)) return true;
		return;
	}

	public static function generateAccountRecoveryCode($userid,$username) {
		if(!check_value($userid)) return;
		if(!check_value($username)) return;
		return md5($userid . $username . self::encryptionHash(). date("m-d-Y"));
	}

	public static function validateUser($username,$password) {
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		if(!Validator::PasswordLength($password)) return;
		
		if(self::MD5Hash()) {
			$password = self::CRYPTMD5($password);
		}
		$data = array(
			'username' => $username,
			'password' => $password
		);

		if(self::MD5Hash()) {
			
			$query = "SELECT UserNum FROM UserInfo WHERE UserID = :username AND UserPass = :password";
		} else {
			$query = "SELECT UserNum FROM UserInfo WHERE UserID = :username AND UserPass = :password";
		}
		
		$result = self::DB('RanUser')->query_fetch_single($query, $data);
		if(is_array($result)) return true;
		return false;
	}

	public static function retrieveUserID($username) {
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		$result = self::DB('RanUser')->query_fetch_single("SELECT UserNum FROM UserInfo WHERE UserID = ?", array($username));
		if(is_array($result)) return $result['UserNum'];
		return;
	}

	public static function accountInformation($id) {
		if(!Validator::Number($id)) return;
		$result = self::DB('RanUser')->query_fetch_single("SELECT * FROM UserInfo WHERE UserNum = ?", array($id));
		if(is_array($result)) return $result;
		return;
	}
	public static function getSessionID(){

        $id = session_id();
        $result = glenox::DB('RanPanel')->query_fetch_single("SELECT session_user_id FROM ActiveSessions WHERE session_id = ?",array($id));
        if($result) return $result;
        return;
	}
	
	public static function accountOnline($id) {
		if(!Validator::Number($id)) return;
		$result = self::DB('RanUser')->query_fetch_single("SELECT UserNum FROM UserInfo WHERE UserNum = ? AND UserLoginState = 1", array($id));
		if($result) return true;
		return;
	}
	public static function AccountFix($userid){
		if(!Validator::Number($userid)) return;
		$result = self::DB('RanUser')->query_fetch_single("SELECT UserName,UserLoginState FROM UserInfo WHERE UserNum = ?", array($userid));
		
		if($result['UserLoginState']!=0){
			$update = self::DB('RanUser')->query("UPDATE UserInfo SET UserLoginState=0 WHERE UserNum = ?", array($userid));
			if($result) return true;
			return;
		}
	}
	public static function checkActiveSession($userid,$session_id) {
		$check = self::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM ActiveSessions WHERE session_user_id = ? AND session_id = ?", array($userid,$session_id));
		if(!is_array($check)) return;
		return true;
	}

	public static function hasActivePasswordChangeRequest($userid) {
		if(!check_value($userid)) return;
		
		$result = self::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM ChangePass WHERE user_id = ?", array($userid));
		if(!is_array($result)) return;
		
		$configs = loadConfigurations('usercp.mypassword');
		if(!is_array($configs)) return;
		
		$request_timeout = $configs['change_password_request_timeout'] * 3600;
		$request_date = $result['request_date'] + $request_timeout;
		if(time() < $request_date) return true;
		
		self::removePasswordChangeRequest($userid);
		return;
	}

	public static function removePasswordChangeRequest($userid) {
		$result = self::DB(config('SQL_RANPANEL'))->query("DELETE FROM ChangePass WHERE user_id = ?", array($userid));
		if($result) return true;
		return;
	}

	public static function accountPincode($id, $pin) {
		if(!check_value($pin)) throw new Exception(lang('error_4',true));
		if(!Validator::Number($id)) return;

		if(self::MD5Hash()) {
			$pincode = self::CRYPTMD5($pin);
			$result = self::DB('RanUser')->query_fetch_single("SELECT UserNum FROM UserInfo WHERE UserNum = ? AND UserPass2 = ?", array($id,$pincode));
			if($result) return true;
			return; 
		} else {
			$result = self::DB('RanUser')->query_fetch_single("SELECT UserNum FROM UserInfo WHERE UserNum = ? AND UserPass2 = ?", array($id,$pin));
			if($result) return true;
			return;
		}


	}

	public static function generatePasswordChangeVerificationURL($user_id,$auth_code) {
		$build_url = __BASE_URL__;
		$build_url .= 'verifyemail/';
		$build_url .= '?op='; // operation
		$build_url .= Encode_id(1);
		$build_url .= '&uid=';
		$build_url .= Encode_id($user_id);
		$build_url .= '&ac=';
		$build_url .= Encode_id($auth_code);
		return $build_url;
	}

	public static function addPasswordChangeRequest($userid,$new_password,$auth_code) {
		if(!check_value($userid)) return;
		if(!check_value($new_password)) return;
		if(!check_value($auth_code)) return;
		if(!Validator::PasswordLength($new_password)) return;
		
		$data = array(
			$userid,
			Encode($new_password),
			$auth_code,
			time()
		);
		
		$query = "INSERT INTO ChangePass (user_id,new_password,auth_code,request_date) VALUES (?, ?, ?, ?)";
		$result = self::DB(config('SQL_RANPANEL'))->query($query, $data);
		if($result) return true;
		return;
	}

	public static function cacheCMS($filename,$content) {
			if(self::isNewsDirWritable($filename)) {
					if (self::deleteCMSFiles($filename)) {
						$handle = fopen(__PATH_CMS_CACHE__ . $filename .".cache", "a");
						fwrite($handle, $content);
						fclose($handle);
						//return true;
						message('success', "[Remittance] Content Saved!");
			}
					
			} else {
				return false;
			}
	}

	public static function deleteCMSFiles($filename) {
		$files = glob(__PATH_CMS_CACHE__.$filename . ".cache");
		foreach($files as $file) {
			if(is_file($file)) {
				unlink($file);
				return true;
			} else {
				return false;
			}
		}
	}

	public static function isNewsDirWritable() {
		if(is_writable(__PATH_CMS_CACHE__)) {
			return true;
		} else {
			return false;
		}
	}

	public static function LoadCachedCms($filename) {
			
		$file = __PATH_CMS_CACHE__. $filename . '.cache';
		//var_dump($file);
		if(file_exists($file) && is_readable($file)) {
			return file_get_contents($file);

		} else {
			return false;
		}
		
	}

	// admin panel //

	public static function updateEmail($userid, $newemail) {
		if(!Validator::UnsignedNumber($userid)) return;
		if(!Validator::Email($newemail)) return;
		$result = glenox::DB('RanUser')->query("UPDATE UserInfo SET UserEmail = ? WHERE UserNum = ?", array($newemail, $userid));
		if($result) return true;
		return;
	}

	public static function changePassword($id,$username,$new_password) {
		//var_dump($id,$username,$new_password);
		if(!Validator::UnsignedNumber($id)) return;
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		if(!Validator::PasswordLength($new_password)) return;
		
		if(self::MD5Hash()) {
			$new_password = self::CRYPTMD5($new_password);
			$data = array('userid' => $id, 'username' => $username, 'password' => $new_password);
			$query = "UPDATE UserInfo SET UserPass = :password WHERE UserNum = :userid AND UserID = :username";
		} else {
			$data = array('userid' => $id, 'password' => $new_password);
			$query = "UPDATE UserInfo SET UserPass = :password WHERE UserNum = :userid";
		}

		$result = self::DB('RanUser')->query($query, $data);
		if($result) return true;
		return;
	}

	public static function UpdateEP($userid,$newep){
		if(!Validator::UnsignedNumber($userid)) return;
		//if(!Validator::Number($newep)) return;

		$result = self::DB('RanUser')->query("UPDATE UserInfo SET UserPoint +=? WHERE UserNum = ?", array($newep,$userid));
		if($result) return true;
		return;
	}

	public static function UpdateVP($userid,$newvp){
		if(!Validator::UnsignedNumber($userid)) return;
		//if(!Validator::Number($newvp)) return;

		$result = self::DB('RanUser')->query("UPDATE UserInfo SET UserPoint2 += ? WHERE UserNum = ?", array($newvp,$userid));
		if($result) return true;
		return;
	}

	public static function LogPoints($adminnum,$username,$userid,$name,$newpoints){

		if(!Validator::UnsignedNumber($adminnum)) return;
		if(!Validator::UnsignedNumber($userid)) return;
		//if(!Validator::Number($newpoints)) return;

		$data = array(
			$adminnum,
			$username,
			$userid,
			$name,
			$newpoints,
			time()
		);

		$query = "INSERT INTO LogPoints (LogAdminUserNum,LogUserName,LogUserNum,LogName,LogValue,LogTime) VALUES (?,?,?,?,?,?)";
		$result = self::DB('RanPanel')->query($query,$data);
		if($result) return true;
		return;

	}

	public static function retrieveAccountIPs($id) {
		if(!check_value($id)) return;
		$result = self::DB('RanUser')->query_fetch("SELECT TOP 3 LogIpAddress FROM LogLogin WHERE UserNum = ? AND LogInOut = 1 ORDER BY LogDate DESC", array($id));
		if(is_array($result)) return $result;
		return;
	}

	public static function retrieveAccPass($email,$username) {
		if(!self::emailExists($email)) return;
		$result = self::DB('RanUser')->query_fetch_single("SELECT UserNum FROM UserInfo WHERE UserEmail = ? AND UserID = ?", array($email,$username));
		if(is_array($result)) return $result['UserNum'];
		return;
	}

	public static function TopLog($UserNum){

		$result = self::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM topup_log WHERE toplog_usernum = ?", array ($UserNum));
		if($result) return $result;
		return;
	}

	// Character //

	public static function AccountCharacter($userid) {
		
		if(check_value($userid)) {
			if(!$error) {
				$result = glenox::DB('RanGame1')->query_fetch("SELECT ChaNum,ChaName FROM ChaInfo WHERE UserNum = ? AND ChaDeleted !=1", array($userid));
				
				if(is_array($result)) {
					return $result;
					
					
				} else {
					return '1';
				}
			} else {
				return '2';
			}
		} else {
			return '3';
		}
	}

	public static function GetAllChar($userid){
		
		if(check_value($userid)) {
			$result = self::DB('RanGame1')->query_fetch("SELECT ChaNum,UserNum,ChaName FROM ChaInfo WHERE UserNum = ? AND ChaDeleted !=1", array ($userid));
			if(is_array($result)) return $result;
			return;
						
		 }
	}

	public static function CheckStatus($chanum) {

		if(check_value($chanum)) {
			$check = self::DB('RanGame1')->query_fetch("SELECT ChaOnline FROM ChaInfo WHERE ChaNum = '$chanum'");
			//if ($check)? '1':'';
			return $check[0];

		}
		
	}


	public function CharacterData($userid) {
		
		if(check_value($usernum)) {
			$result = self::DB('RanGame1')->query_fetch_single("SELECT * FROM ChaInfo WHERE UserNum = '$userid'");
			if(is_array($result)) {
				return $result;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	public static function ChaInformation($userid) {
		
		if(check_value($userid)) {
			$result = self::DB('RanGame1')->query_fetch("SELECT * FROM ChaInfo WHERE UserNum = ?", array($userid));
				
				if(is_array($result)) {
					return $result;
				} else {
					return '1';
				}
		} else {
			return '3';
		}
	}


	
	
	
	function CharacterExists($chanum) {
		global $dB1;
		if(check_value($chanum)) {
			$check = $this->db1->query_fetch_single("SELECT * FROM ChaInfo WHERE ChaNum = '$chanum'");
			if(is_array($check)) {
				return true;
			}
		}
	}
	
	function hasRequiredLevel($level) {
		if(check_value($level) && $level >= 1) {
			if($level >= ranconfig('resets_required_level')) {
				return true;
			}
		}
	}

	function CharacterBelongsToAccount($chanum,$userid) {
		if(check_value($chanum) && check_value($userid)) {
			if(!$error) {
				$characterData = $this->CharacterData($chanum);
				if(is_array($characterData)) {
					if($characterData['UserNum'] == $userid) {
						return true;
					}
				}
			}
		}
	}
	
	static function GenerateCharacterClass($code=0) {
		global $custom;
		$name = $custom['character_class'][$code][0];
		return $name;
	}
	static function CharClass($code=0) {
		global $custom;
		$image = __PATH_TEMPLATE_IMG__ . 'class/' . $custom['character_class'][$code][1].'.jpg';
		return $image;
	}
	static function CharSchoolImages($code=0) {
		global $custom;
		$image = __PATH_TEMPLATE_IMG__ . 'school/' . $custom['character_school'][$code][1];
		return $image;
	}
	static function CharacterSchool($code=0) {
		global $custom;
		$school = $custom['character_school'][$code][0];
		return $school;
	}


	/*function BuildGuildLogo($GuNum){
			if(check_value($GuNum)) {
				$result = $this->db1->query_fetch_single("SELECT GuMarkImage FROM GuildInfo WHERE GuNum = ?", array($GuNum));
				//var_dump($result);
				//return $result;
				if(!$result) return;
				if(is_array($result)){
				foreach(array($result) as $hh){
				     //var_dump($hh);
				     return $this->DoBinaryToImage($hh['GuMarkImage']);
				     
				  }
			}
		}

	}*/

	static function BuildGuildName($GuNum){
		if(check_value($GuNum)) {
			$result =self::DB('RanGame1')->query_fetch_single("SELECT GuName FROM GuildInfo WHERE GuNum = ?", array($GuNum));
			if($result) return $result['GuName'];
			return;
		}
	}

	/*function DoBinaryToImage( $binary )
	{
		$strPrintImage = "";
		if($binary!="00"){
			$line = 0;
			$strPrintImage .= '<div style="margin:0px auto;padding: 0px 0px 0px 5px;">';
			$strPrintImage .= '<table border="0" style="style:none;" cellpadding="0" cellspacing="0" width="16" height="11">';
			for( $m = 0 ; $m < 11 ; $m ++ )
			{
				$strPrintImage .= "<tr>";
				for( $n = 0 ; $n < 16 ; $n ++ )
				{
					$offset = $line*8*16+$n*8;
					$color = substr($binary,$offset+4,2).substr($binary,$offset+2,2).substr( $binary,$offset, 2 );
					$strPrintImage .= "<td style='width:1px;height:1px;background-color:#".$color."'></td>";
				}
				$strPrintImage .= "</tr>";
				$line++;
			}
			$strPrintImage .= "</table>";
			$strPrintImage .= '</div>';
		} else {
			$strPrintImage .= "?";
		}
		return $strPrintImage;
	}*/

	public static function NewRegister(){

		return self::DB('RanUser')->query_fetch("SELECT TOP 5 UserNum,UserID,UserEmail FROM UserInfo ORDER BY UserNum DESC");
	}

	public static function isIpBlocked($ip) {
		if(!Validator::Ip($ip)) return true; // automatically block ip if invalid
		$result = self::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM Blocked_IP WHERE block_ip = ?", array($ip));
		if(is_array($result)) return true;
		return;
	}

	public static function ShopLog($UserName){

		$result = self::DB('RanShop')->query_fetch("SELECT A.*,B.ItemName FROM ShopPurchase A, ShopItemMap B WHERE A.ProductNum = B.ProductNum AND A.UserUID = ?", array ($UserName));
		if($result) return $result;
		return;
	}


	public static function blockIpAddress($ip,$user) {
		if(!check_value($user)) return;
		if(!Validator::Ip($ip)) return;
		if(self::isIpBlocked($ip)) return;
		$result = self::DB(config('SQL_RANPANEL'))->query("INSERT INTO Blocked_IP (block_ip,block_by,block_date) VALUES (?,?,?)", array($ip,$user,time()));
		if($result) return true;
	}

	public static function retrieveBlockedIPs() {
		return self::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM Blocked_IP ORDER BY id DESC");
	}

	public static function unblockIpAddress($id) {
		if(!check_value($id)) return;
		$result = self::DB(config('SQL_RANPANEL'))->query("DELETE FROM Blocked_IP WHERE id = ?", array($id));
		if($result) return true;
		return;
	}
	private static function module404() {
		redirect();
	}

	public static function addCredits($input,$usernum) {
		
		if(!Validator::UnsignedNumber($input)) throw new Exception("The amount of ep to add must be an unsigned number.");
		//if(!$this->_identifier) throw new Exception("You have not set the user identifier.");
		
		// build query
		$data = array(
			'credits' => $input,
			'identifier' => $usernum
		);

		$query = "UPDATE UserInfo SET UserPoint = UserPoint + :credits WHERE UserNum = :identifier";
		
		// add ep
		$addEP = self::DB('RanUser')->query($query, $data);

		if(!$addCredits) throw new Exception("There was an error adding the credits");
		
		//$this->_addLog($config['config_title'], $input, "add");
	}

	public static function paypal_transaction($transaction_id,$user_id,$payment_amount,$paypal_email,$order_id) {
		if(!check_value($transaction_id)) return;
		if(!check_value($user_id)) return;
		if(!check_value($payment_amount)) return;
		if(!check_value($paypal_email)) return;
		if(!check_value($order_id)) return;
		if(!Validator::UnsignedNumber($user_id)) return;
		
		$data = array(
			$transaction_id,
			$user_id,
			$payment_amount,
			$paypal_email,
			time(),
			1,
			$order_id
		);
		
		$query = "INSERT INTO paypal_trans (transaction_id, user_id, payment_amount, paypal_email, transaction_date, transaction_status, order_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$result = self::DB(config('SQL_RANPANEL'))->query($query, $data);
		if($result) return true;
		return;
	}

	public static function blockAccount($userid) {
		if(!check_value($userid)) return;
		if(!Validator::UnsignedNumber($userid)) return;
		$result = self::DB('RanUser')->query("UPDATE UserInfo SET UserBlock = ? WHERE UserNum = ?", array(1, $userid));
		if($result) return true;
		return;
	}

	public static function paypal_transaction_reversed_updatestatus($order_id) {
		if(check_value($order_id)) return;
		$result = self::DB(config('SQL_RANPANEL'))->query("UPDATE paypal_trans SET transaction_status = ? WHERE order_id = ?", array(0, $order_id));
		if($result) return true;
		return;
	}
	
}