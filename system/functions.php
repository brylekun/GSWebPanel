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

function check_value($value) {
	if((@count($value)>0 and !@empty($value) and @isset($value)) || $value=='0') {
		return true;
	}
}

function redirect($type = 1, $location = null, $delay = 0) {
	if(!check_value($location)) {
		$to = __BASE_URL__;
	} else {
		$to = __BASE_URL__ . $location;
		
		if($location == 'login') {
			$_SESSION['login_last_location'] = $_REQUEST['page'].'/';
			if(check_value($_REQUEST['subpage'])) {
				$_SESSION['login_last_location'] .= $_REQUEST['subpage'].'/';
			}
		}
	}
	//var_dump($to);
	switch($type) {
		default:
			header('Location: '.$to.'');
			die();
		break;
		case 1:
			header('Location: '.$to.'');
			die();
		break;
		case 2:
			echo '<meta http-equiv="REFRESH" content="'.$delay.';url='.$to.'">';
		break;
		case 3:
			header('Location: '.$location.'');
			die();
		break;
	}
}

function isLoggedIn() {
	$login = new login();
	if($login->isLoggedIN()) return true;
	return;
}

function logOutUser() {
	$login = new login();
	$login->logout();
}
//<script>alertify.info("'.$message.'");</script>
/*function message($type='info', $message="") {
	switch($type) {
		case 'error':
			$class = 'error';
		break;
		case 'success':
			$class = 'success';
		break;
		case 'warning':
			$class = 'log';
		break;
		default:
			$class = 'log';
		break;
	}
	
	echo '<script>alertify.'.$class.'("'.$message.'");</script>';
}*/
function message($type='info', $message="") {
	switch($type) {
		case 'error':
			$class = 'error';
			$title = 'Opps!';
		break;
		case 'success':
			$class = 'success';
			$title = 'Good job!';
		break;
		case 'warning':
			$class = 'log';
			$title = 'Info';
		break;
		default:
			$title = 'Info';
			$class = 'log';
		break;
	}
	echo '<script>swal("'.$title.'", "'.$message.'", "'.$class.'");</script>';
}

function lang($phrase, $return=true) {
	global $lang;
	$result = $lang[$phrase];
	//if(!$result) $result = 'ERROR';
	
	if(config('language_debug',true)) {
		if($return) {
			return '<span title="'.$phrase.'" alt="'.$phrase.'">'.$result.'</span>';
		} else {
			echo '<span title="'.$phrase.'" alt="'.$phrase.'">'.$result.'</span>';
		}
	} else {
		if($return) {
			return $result;
		} else {
			echo $result;
		}
	}
}

function langf($phrase, $args=array(), $print=false) {
	global $lang;
	$result = @vsprintf($lang[$phrase], $args);
	if(!$result) $result = 'ERROR';
	
	if(config('language_debug',true)) {
		if($print) {
			echo '<span title="'.$phrase.'" alt="'.$phrase.'">'.$result.'</span>';
		} else {
			return '<span title="'.$phrase.'" alt="'.$phrase.'">'.$result.'</span>';
		}
	} else {
		if($print) {
			echo $result;
		} else {
			return $result;
		}
	}
}

# to be removed
function Encode($txt) {
	return $txt;
}

# to be removed
function Decode($txt) {
	return $txt;
}

function debug($value) {
	var_dump($value);
}

function canAccessAdminCP($username) {
	if(!check_value($username)) return;
	if(array_key_exists($username, config('admins',true))) return true;
	return false;
}

function encrypt_email($email)
{
		$em   = explode("@",$email);
		$name = implode(array_slice($em, 0, count($em)-1), '@');
		$len  = floor(strlen($name)/2);

		return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
}

function ranpanel_id($var, $action='encode') {
	$base_chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; // wag mo doublehin :)
	
	for ($n = 0; $n<strlen($base_chars); $n++) {
		$i[] = substr( $base_chars,$n ,1);
    }
 
    $passhash = hash('sha256',config('encryption_hash',true));
    $passhash = (strlen($passhash) < strlen($base_chars)) ? hash('sha512',config('encryption_hash',true)) : $passhash;
 
    for ($n=0; $n < strlen($base_chars); $n++) {
		$p[] =  substr($passhash, $n ,1);
    }
 
    array_multisort($p, SORT_DESC, $i);
    $base_chars = implode($i);
	
	switch($action) {
		case 'encode':
			$string = '';
			$len = strlen($base_chars);
			while($var >= $len) {
				$mod = bcmod($var, $len);
				$var = bcdiv($var, $len);
				$string = $base_chars[$mod].$string;
			}
			return $base_chars[$var] . $string;
		break;
		case 'decode':
			$integer = 0;
			$var = strrev($var );
			$baselen = strlen( $base_chars );
			$inputlen = strlen( $var );
			for ($i = 0; $i < $inputlen; $i++) {
				$index = strpos($base_chars, $var[$i] );
				$integer = bcadd($integer, bcmul($index, bcpow($baselen, $i)));
			}
			return $integer;
		break;
	}
}

function Encode_id($id) {
	return ranpanel_id($id,'encode');
}

function Decode_id($id) {
	return ranpanel_id($id,'decode');
}

function BuildCacheData($data_array) {
	$result = null;
	if(is_array($data_array)) {
		foreach($data_array as $row) {
			$count = count($row);
			$i = 1;
			foreach($row as $data) {
				$result .= $data;
				if($i < $count) {
					$result .= '¦';
				}
				$i++;
			}
			$result .= "\n";
		}
		return $result;
	} else {
		return null;
	}
}

function UpdateCache($file_name, $data) {
	$file = __PATH_CACHE__.$file_name;
	if(!file_exists($file)) return;
	if(!is_writable($file)) return;
	
	$fp = fopen($file, 'w');
	fwrite($fp, time()."\n");
	fwrite($fp, $data);
	//var_dump($data);
	fclose($fp);
	return true;
}

function LoadCacheData($file_name) {
	$file = __PATH_CACHE__.$file_name;
	if(!file_exists($file)) return;
	if(!is_readable($file)) return;
	
	$cache_file = file_get_contents($file);
	$file_lanes = explode("\n",$cache_file);
	$nlines = count($file_lanes);
	for($i=0; $i<$nlines; $i++) {
		if(check_value($file_lanes[$i])) {
			$line_data[$i] = explode("¦",$file_lanes[$i]);
		}
	}
	return $line_data;
}

function sec_to_hms($input_seconds=0) {
	$result = sec_to_dhms($input_seconds);
	if(!is_array($result)) return array(0,0,0);
	return array((($result[0]*24)+$result[1]), $result[2], $result[3]);
}

function sec_to_dhms($input_seconds=0) {
	if($input_seconds < 1) return array(0,0,0,0);
	$days_module = $input_seconds % 86400;
	$days = ($input_seconds-$days_module)/86400;
	$hours_module = $days_module % 3600;
	$hours = ($days_module-$hours_module)/3600;
	$minutes_module = $hours_module % 60;
	$minutes = ($hours_module-$minutes_module)/60;
	$seconds = $minutes_module;
	return array($days,$hours,$minutes,$seconds);
}

/*
 * Calculates the exact time left before the next Club War battle.
 * Configs:
 * 		- cw_battle_day: Values: 1(Monday) to 7(Sunday)
 * 		- cw_battle_time: Value: h:m:s (in 24 hour format!)
 * 		- cw_battle_duration: Value: numeric (time in minutes!)
 * 
*/
function clubwar_CalculateTimeLeft() {
	loadModuleConfigs('clubwar');
	$weekDays = array("", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	$battleDay = $weekDays[ranconfig('cw_battle_day')];
	$today = date("l");
	$battleTime = ranconfig('cw_battle_time');
	$battleDate = strtotime("next $battleDay $battleTime");
	$timeOffset = $battleDate - time();
	if($today == $battleDay) {
		$currentTime = strtotime(date("H:i:s"));
		$battleTimeToday = strtotime($battleTime);
		$timeOffsetToday = $battleTimeToday - time();
		if($battleTimeToday > $currentTime) {
			// CW BATTLE IS TODAY
			return $timeOffsetToday;
		} else {
			$timeOffsetToday = $timeOffsetToday*(-1);
			if((ranconfig('cw_battle_duration')*60) > $timeOffsetToday) {
				// CW BATTLE IN PROGRESS
				return;
			} else {
				// CW BATTLE IS ON NEXT DATE
				return $timeOffset;
			}
		}
	} else {
		// CW BATTLE IS ON NEXT DATE
		return $timeOffset;
	}
}

function listCronFiles($selected="") {
	$dir = opendir(__PATH_CRON__);
	while(($file = readdir($dir)) !== false) {
		if(filetype(__PATH_CRON__ . $file) == "file" && $file != ".htaccess" && $file != "cron.php") {
			
			if(check_value($selected) && $selected == $file) {
				$return[] = "<option value=\"$file\" selected=\"selected\">$file</option>";
			} else {
				$return[] = "<option value=\"$file\">$file</option>";
			}
		}
	}
	closedir($dir);
	return join('', $return);
}

function cronFileAlreadyExists($cron_file) {
	$check = glenox::DB('RanPanel')->query_fetch_single("SELECT * FROM cron WHERE cron_file_run = ?", array($cron_file));
	if(!is_array($check)) {
		return true;
	}
}

function addCron($cron_times) {

	if(check_value($_POST['cron_name']) && check_value($_POST['cron_file']) && check_value($_POST['cron_time'])) {
		
		$filePath = __PATH_CRON__.$_POST['cron_file'];

		// Check Cron File Exists
		if(!file_exists($filePath)) {
			message('error','The selected file doesn\'t exist.');
			return;
		}
		// Check Cron File Databse
		if(!cronFileAlreadyExists($_POST['cron_file'])) {
			message('error','A cron job with the same file already exists.');
			return;
		}
		// Check Cron Time
		if(!array_key_exists($_POST['cron_time'], $cron_times)) {
			message('error','The selected cron time doesn\'t exist.');
			return;
		}
		
		$sql_data = array(
			$_POST['cron_name'],
			$_POST['cron_description'],
			$_POST['cron_file'],
			$cron_times[$_POST['cron_time']],
			1,
			md5_file($filePath)
		);
		
		$query = glenox::DB('RanPanel')->query("INSERT INTO cron (cron_name, cron_description, cron_file_run, cron_run_time, cron_status, cron_file_md5) VALUES (?, ?, ?, ?, ?, ?)", $sql_data);
		if($query) {
		
			// UPDATE CACHE
			updateCronCache();
			
			message('success','Cron job successfully added!');
		} else {
			message('error','Could not add cron job.');
		}
		
	} else {
		message('error','Please complete all the required fields.');
	}
}

function updateCronLastRun($file) {
	$update = glenox::DB('RanPanel')->query("UPDATE cron SET cron_last_run = ? WHERE cron_file_run = ?", array(time(), $file));
	if($update) {
		// UPDATE CACHE
		updateCronCache();
	}
}

function updateCronCache() {

	$cacheDATA = BuildCacheData(glenox::DB('RanPanel')->query_fetch("SELECT * FROM cron"));
	UpdateCache('cron.cache',$cacheDATA);
}

function getCronJobDATA($id) {
	$result = glenox::DB('RanPanel')->query_fetch_single("SELECT * FROM cron WHERE cron_id = ?", array($id));
	if(is_array($result)) {
		return $result;
	}
}

function deleteCronJob($id) {
	$cronDATA = getCronJobDATA($id);
	if(is_array($cronDATA)) {
		if($cronDATA['cron_protected']) {
			message('error','This cron job is protected therefore cannot be deleted.');
			return;
		}
		$delete = glenox::DB('RanPanel')->query("DELETE FROM cron WHERE cron_id = ?", array($id));
		if($delete) {
			message('success','Cron job "<strong>'.$cronDATA['cron_name'].'</strong>" successfully deteled!');
			updateCronCache();
		} else {
			message('error','Could not delete cron job.');
		}
	} else {
		message('error','Could not find cron job.');
	}
}

function togglestatusCronJob($id) {
	$cronDATA = getCronJobDATA($id);
	if(is_array($cronDATA)) {
		if($cronDATA['cron_status'] == 1) {
			$status = 0;
		} else {
			$status = 1;
		}
		$toggle = glenox::DB('RanPanel')->query("UPDATE cron SET cron_status = ? WHERE cron_id = ?", array($status, $id));
		if($toggle) {
			message('success','Cron job "<strong>'.$cronDATA['cron_name'].'</strong>" status successfully changed!');
			updateCronCache();
		} else {
			message('error','Could not update cron job.');
		}
	} else {
		message('error','Could not find cron job.');
	}
}

function editCronJob($id,$name,$desc,$file,$time,$cron_times,$current_file) {
	if(check_value($name) && check_value($file) && check_value($time)) {
		$filePath = __PATH_CRON__.$file;

		// Check Cron File Exists
		if(!file_exists($filePath)) {
			message('error','The selected file doesn\'t exist.');
			return;
		}
		// Check Cron File Databse
		if($file != $current_file) {
			if(!cronFileAlreadyExists($file)) {
				message('error','A cron job with the same file already exists.');
				return;
			}
		}
		// Check Cron Time
		if(!array_key_exists($time, $cron_times)) {
			message('error','The selected cron time doesn\'t exist.');
			return;
		}

		$query = glenox::DB('RanPanel')->query("UPDATE cron SET cron_name = ?, cron_description = ?, cron_file_run = ?, cron_run_time = ? WHERE cron_id = ?", array($name, $desc, $file, $cron_times[$time], $id));
		if($query) {
		
			// UPDATE CACHE
			updateCronCache();
			
			message('success','Cron job successfully updated!');
		} else {
			message('error','Could not edit cron job.');
		}
	} else {
		message('error','You must fill all the required fields.');
	}
}

function getGensRank($id=0) {
	global $custom;
	if(!is_array($custom['gens_ranks'])) return 'None';
	if(!array_key_exists($id, $custom['gens_ranks'])) return 'None';
	return $custom['gens_ranks'][$id];
}

function ran_Configs() {
	if(!file_exists(__PATH_CONFIGS__ . 'config.json')) throw new Exception('RanPanel configuration file doesn\'t exist, please reupload the website files.');
	
	$ran_Configs = file_get_contents(__PATH_CONFIGS__ . 'config.json');
	if(!check_value($ran_Configs)) throw new Exception('RanPanel configuration file is empty, please run the installation script.');
	
	return json_decode($ran_Configs, true);
}

function config($config_name, $return = true) {
	global $config;
	return $config[$config_name];
}

function convertXML($object) {
	return json_decode(json_encode($object), true);
}

function loadModuleConfigs($module) {
	global $mconfig;
	if(moduleConfigExists($module)) {
		$xml = simplexml_load_file(__PATH_MODULE_CONFIGS__.$module.'.xml');
		$mconfig = array();
		if($xml) {
			$moduleCONFIGS = convertXML($xml->children());
			$mconfig = $moduleCONFIGS;
		}
	}
}

function moduleConfigExists($module) {
	if(file_exists(__PATH_MODULE_CONFIGS__.$module.'.xml')) {
		return true;
	}
}

function globalConfigExists($config_file) {
	if(file_exists(__PATH_CONFIGS__.$config_file.'.xml')) {
		return true;
	}
}

function ranconfig($configuration) {
	global $mconfig;
	if(@array_key_exists($configuration, $mconfig)) {
		return $mconfig[$configuration];
	} else {
		return null;
	}
}

function gconfig($config_file,$return=true) {
	global $gconfig;
	if(globalConfigExists($config_file)) {
		$xml = simplexml_load_file(__PATH_CONFIGS__.$config_file.'.xml');
		$gconfig = array();
		if($xml) {
			$globalCONFIGS = convertXML($xml->children());
			if($return) {
				return $globalCONFIGS;
			} else {
				$gconfig = $globalCONFIGS;
			}
		}
	}
}

function loadConfigurations($file) {
	if(!check_value($file)) return;
	if(!moduleConfigExists($file)) return;
	$xml = simplexml_load_file(__PATH_MODULE_CONFIGS__ . $file . '.xml');
	if($xml) return convertXML($xml->children());
	return;
}

function loadConfig($name="config") {
	if(!check_value($name)) return;
	if(!file_exists(__PATH_CONFIGS__ . $name . '.json')) return;
	$cfg = file_get_contents(__PATH_CONFIGS__ . $name . '.json');
	if(!check_value($cfg)) return;
	return json_decode($cfg, true);
}

//encryption algorithm
function encrypt($plaintext)
    {

        return base64_encode($plaintext);

    }
	//decryption method//
 function decrypt($crypttext)
    {

        $plaintext = base64_decode($crypttext);
        
        return trim($plaintext);
    }