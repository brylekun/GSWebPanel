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

class Vote {

	public static function voteProcess($userid,$userip,$votesiteid) {
		if(check_value($userid) && check_value($userip) && check_value($votesiteid)) {
			if(!Validator::Number($votesiteid)) { $error = true; }
			if(!Validator::Number($userid)) { $error = true; }
			if(!Validator::Ip($userip)) { $error = true; }
			if(!$error) {

				if(self::siteExists($votesiteid)) {
					if(self::canUserVote($userid,$votesiteid)) {
						if(self::MaxLevel($userid)) {
						
							// Retrieve Account Information
							//echo $userid;
							$accountInfo = glenox::accountInformation($userid);
							
							
							
							// Retrieve votesite data
							$voteSite = self::retrieveVotesites($votesiteid);
							
							if(!is_array($voteSite)) {
								message('error', lang('error_23',true));
								return;
							}
							
							$voteLink = $voteSite['votesite_link'];

							$creditsReward = $voteSite['votesite_reward'];
							
							// Give Reward
							$reward = self::rewardUser($userid,$creditsReward);
							
							//var_dump($reward);
							
							
							if($reward) {
								// Add Vote Record
								$add = self::addRecord($userid,$userip,$votesiteid);
								
								if($add) {
									// Log Vote
									if(ranconfig('vote_save_logs')) {
										self::logVote($votesiteid,$userid);
									}
									// Redirect
									header("Location: ".$voteLink."");

								} else {
									// unknown error
									message('error', lang('error_23',true));
								}
							} else {
								// unknown error
								message('error', lang('error_23',true));
							}
							
						} else {
							// max level
							$level = ranconfig('char_limit');
							message('error', 'Your character did not match the character level <b>'.$level. '</b>!');
						}
					} else {
						// user id already voted
						message('error', lang('error_15',true));
					}
				} else {
					// vote site doesnt exist
					message('error', lang('error_23',true));
				}
			} else {
				// unknown error
				message('error', lang('error_23',true));
			}
		} else {
			// unknown error
			message('error', lang('error_23',true));
		}
	}

	private static function canUserVote($userid,$votesiteid) {
		$check = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM votes WHERE user_id = '$userid' AND vote_site_id = '$votesiteid'");
		if(is_array($check)) {
			if(self::timePassed($check['timestamp'])) {
				$remove = self::removeRecord($check['id']);
				if($remove) {
					return true;
				}
			}
		} else {
			return true;
		}
	}
	
	private function canIPVote($ip,$votesiteid) {
		$check = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM votes WHERE user_ip = '$ip' AND vote_site_id = '$votesiteid'");
		if(is_array($check)) {
			if(self::timePassed($check['timestamp'])) {
				$remove = self::removeRecord($check['id']);
				if($remove) {
					return true;
				}
			}
		} else {
			return true;
		}
	}
	private static function MaxLevel($id){
		if(!Validator::Number($id)) return;
		
		$maxlevel = ranconfig('char_limit');
		$result = glenox::DB('RanGame1')->query_fetch_single("SELECT ChaName,ChaLevel FROM ChaInfo WHERE UserNum = ? AND ChaLevel >= ? AND ChaDeleted != 1", array($id,$maxlevel));	
		
		if($result) {
			return true;
		}
	}
	
	private static function addRecord($userid,$userip,$votesiteid) {
		if(is_array(glenox::accountInformation($userid))) {
			if(self::siteExists($votesiteid)) {
				$voteSiteInfo = self::retrieveVotesites($votesiteid);
				$timestamp = time() + $voteSiteInfo['votesite_time']*60*60;
				$data = array(
					$userid,
					$userip,
					$votesiteid,
					$timestamp
				);
				$add = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO votes (user_id, user_ip, vote_site_id, timestamp) VALUES (?, ?, ?, ?)", $data);
				
				if($add) {
					return true;
				}
			}
		}
	}
	
	static function removeRecord($id) {
		glenox::DB(config('SQL_RANPANEL'))->query("DELETE FROM votes WHERE id = '$id'");
		return true;
	}
	
	static function timePassed($timestamp) {
		if(time() > $timestamp) {
			return true;
		}
	}
	
	private static function siteExists($id) {
		if(check_value($id)) {
			$check = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM vote_sites WHERE votesite_id = ?", array($id));
			
			if($check && is_array($check)) {
				return true;
			}
		}
	}
	
	static function logVote($site_id,$user_id) {
		$add_data = array(
			$user_id,
			$site_id,
			time()
		);
		$add_log = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO vote_logs (user_id,votesite_id,timestamp) VALUES (?,?,?)", $add_data);
		if($add_log) {
			return true;
		}
	}
	
	private static function rewardUser($userid,$credits) {
		if(!Validator::Number($userid)) return;

		$result = glenox::DB('RanUser')->query("UPDATE UserInfo SET UserPoint2 = UserPoint2+? WHERE UserNum = ?", array($credits, $userid));
		
		if($result) return true;
		return;
	}
	
	public static function addVotesite($title,$link,$reward,$time) {
		$result = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO VOTE_SITES (votesite_title,votesite_link,votesite_reward,votesite_time) VALUES (?,?,?,?)", array($title,$link,$reward,$time));
		if($result) {
			return true;
		}
	}
	
	public static function deleteVotesite($id) {
		if(self::siteExists($id)) {
			$result = glenox::DB(config('SQL_RANPANEL'))->query("DELETE FROM VOTE_SITES WHERE votesite_id = ?", array($id));
			if($result) {
				return $result;
			}
		}
	}
	
	public static function retrieveVotesites($id=null) {
		if(check_value($id)) {
			return glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM VOTE_SITES WHERE votesite_id = ?", array($id));
		} else {
			return glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM VOTE_SITES ORDER BY votesite_id ASC");
		}
	}

}

?>