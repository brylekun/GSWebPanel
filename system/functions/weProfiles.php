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

class weProfiles {
	
	private $_request;
	private $_type;
	
	private $_reqMaxLen;
	private $_guildsCachePath;
	private $_playersCachePath;
	private $_cacheUpdateTime;
	
	private $_fileData;
	
	function __construct() {
		
		# settings
		$this->_guildsCachePath = __PATH_CACHE__ . 'profiles/guilds/';
		$this->_playersCachePath = __PATH_CACHE__ . 'profiles/players/';
		$this->_cacheUpdateTime = 3600;
		
		# check cache directories
		$this->checkCacheDir($this->_guildsCachePath);
		$this->checkCacheDir($this->_playersCachePath);
		
	}
	
	public function setType($input) {
		switch($input) {
			case "guild":
				$this->_type = "guild";
				$this->_reqMaxLen = 8;
				break;
			default:
				$this->_type = "player";
				$this->_reqMaxLen = 10;
		}
	}
	
	public function setRequest($input) {
		//var_dump($input);
		if(!Validator::Number($input)) throw new Exception(lang('error_25',true));
		$this->_request = $input;
	}
	
	private function checkCacheDir($path) {
		if(check_value($path)) {
			if(!file_exists($path) || !is_dir($path)) {
				if(config('error_reporting',true)) {
					throw new Exception("Invalid cache directory ($path)");
				} else {
					throw new Exception(lang('error_21',true));
				}
			} else {
				if(!is_writable($path)) {
					if(config('error_reporting',true)) {
						throw new Exception("The cache directory is not writable ($path)");
					} else {
						throw new Exception(lang('error_21',true));
					}
				}
			}
		}
	}
	
	private function checkCache() {
		switch($this->_type) {
			case "guild":
				$reqFile = $this->_guildsCachePath . strtolower($this->_request) . '.cache';
				if(!file_exists($reqFile)) {
					$this->cacheGuildData();
				}
				$fileData = file_get_contents($reqFile);
				$fileData = explode("|", $fileData);
				if(is_array($fileData)) {
					if(time() > ($fileData[0]+$this->_cacheUpdateTime)) {
						$this->cacheGuildData();
					}
				} else {
					throw new Exception(lang('error_21',true));
				}
				$this->_fileData = file_get_contents($reqFile);
				break;
			default:
				$reqFile = $this->_playersCachePath . strtolower($this->_request) . '.cache';
				if(!file_exists($reqFile)) {
					$this->cachePlayerData();
				}
				$fileData = file_get_contents($reqFile);
				$fileData = explode("|", $fileData);
				if(is_array($fileData)) {
					if(time() > ($fileData[0]+$this->_cacheUpdateTime)) {
						$this->cachePlayerData();
					}
				} else {
					throw new Exception(lang('error_21',true));
				}
				$this->_fileData = file_get_contents($reqFile);
		}
	}
	
	private function cacheGuildData() {
		// General Data
		$guildData = glenox::DB('RanGame1')->query_fetch_single("SELECT * FROM GuildInfo WHERE GuNum = ?", array($this->_request));
		if(!$guildData) throw new Exception(lang('error_25',true));
		
		//Guild Master

		$gumaster = glenox::DB('RanGame1')->query_fetch_single("SELECT ChaName FROM ChaInfo WHERE ChaNum = ?", array($guildData['ChaNum']));
		if(!$guildData) throw new Exception(lang('error_25',true));
		// Members
		$guildMembers = glenox::DB('RanGame1')->query_fetch("SELECT ChaNum,ChaName FROM ChaInfo WHERE GuNum = ?", array($this->_request));
		if(!$guildMembers) throw new Exception(lang('error_25',true));
		$members = array();
		foreach($guildMembers as $gmember) {
			$members[] = $gmember;
		}
		//$gmembers_str = implode(",", $members);
		$gmembers_str = json_encode($members);
		
		//var_dump($gmembers_str);
		//$gulogo = BuildGuildLogo($this->_request);
		// Cache
		$data = array(
			time(),
			$guildData['GuName'],
			$guildData['GuNum'],
			$guildData['GuBattleWin'],
			$gumaster['ChaName'],
			$gmembers_str,
			$chaNum_str
		);
		
		// Cache Ready Data
		$cacheData = implode("|", $data);
		
		// Update Cache File
		$reqFile = $this->_guildsCachePath . strtolower($this->_request) . '.cache';
		$fp = fopen($reqFile, 'w+');
		fwrite($fp, $cacheData);
		fclose($fp);
	}
	
	private function cachePlayerData() {
		// general player data
		$playerData = glenox::DB('RanGame1')->query_fetch_single("SELECT * FROM ChaInfo WHERE ChaNum = ?", array($this->_request));
		if(!$playerData) throw new Exception(lang('error_25',true));
		
		//var_dump($playerData['GuNum']);
		// guild data
		$guild = "";
		$guildData = glenox::DB('RanGame1')->query_fetch_single("SELECT * FROM GuildInfo WHERE GuNum = ?", array($playerData['GuNum']));
		if($guildData) $gunum = $guildData['GuNum'];
		if($guildData) $guname = $guildData['GuName'];
		
		// online status
		$status = 0;
		if(glenox::accountOnline($playerData['ChaOnline'])) {
			$status = 1;
		}
		
		// Cache
		$data = array(
			time(),
			$playerData['ChaName'], #1
			$playerData['ChaClass'], #2
			$playerData['ChaLevel'], #3
			$playerData['ChaReborn'], #4
			$playerData['ChaPower'],   //Pow #5
			$playerData['ChaStrong'],   //Stm #6
			$playerData['ChaStrength'], //Vit #7
			$playerData['ChaSpirit'],  // #8
			$playerData['ChaDex'], #9
			$playerData['ChaStRemain'], #10
			$playerData['ChaPkWin'], #11
			$playerData['ChaPkLoss'], #12
			$guname, #13
			$gunum, #14
			$status, #15
			$playerData['ChaSchool'] #16
		);
		
		// Cache Ready Data
		$cacheData = implode("|", $data);
		
		// Update Cache File
		$reqFile = $this->_playersCachePath . strtolower($this->_request) . '.cache';
		$fp = fopen($reqFile, 'w+');
		fwrite($fp, $cacheData);
		fclose($fp);
	}
	
	function BuildGuildLogo($GuNum){
		if(check_value($GuNum)) {
			$result = glenox::DB('RanGame1')->query_fetch_single("SELECT GuMarkImage FROM GuildInfo WHERE GuNum = ?", array($GuNum));
			if(!$result) return;
			if(is_array($result)){
			foreach(array($result) as $hh){
			     return $this->DoBinaryToImage($hh['GuMarkImage']);
			     
			  }
		}
	}

	}

	function DoBinaryToImage( $binary )
	{
		$strPrintImage = "";
		if($binary!="00"){
			$line = 0;
			$strPrintImage .= '<div style="padding: 0px 0px 20px 470px;">';
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
	}
	
	public function data() {
		if(!check_value($this->_type)) throw new Exception(lang('error_21',true));
		if(!check_value($this->_request)) throw new Exception(lang('error_21',true));
		$this->checkCache();
		return(explode("|", $this->_fileData));
	}
	
}