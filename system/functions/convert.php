<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

 class Convert {


 	#EP to VP converter
 	public static function eptovp($id,$rate,$ep){
 		if(!check_value($ep)) throw new Exception(lang('error_4',true));
 		if(!check_value($rate)) throw new Exception(lang('error_4',true)); 		
 		if(!check_value($id)) throw new Exception(lang('error_4',true)); 	
 		if(!Validator::Number($ep)) return;
 		if(!Validator::Number($rate)) return;
 		if(!Validator::Number($id)) return;

 		$getpoints = self::GetPoints($id);

 		if(is_array($getpoints)) {

 			if ($getpoints['UserPoint']>=$ep) {
	 			$vp = $ep*$rate;

	 			$result = glenox::DB('RanUser')->query("UPDATE UserInfo SET UserPoint=UserPoint-?,UserPoint2=UserPoint2+? WHERE UserNum = ?", array($ep,$vp,$id));
				if($result) {
					message('success', lang('success_23',true));
				} else {
					return;
				}
				
			} else {
				message('error', lang('error_21',true));   
			}

 		} else {
 			message('error', lang('error_25',true));
 			

 		}



 	}

 	#VP to EP converter
 	public static function vptoep($id,$rate,$vp){
 		if(!check_value($vp)) throw new Exception(lang('error_4',true));
 		if(!check_value($rate)) throw new Exception(lang('error_4',true)); 		
 		if(!check_value($id)) throw new Exception(lang('error_4',true)); 	
 		if(!Validator::Number($vp)) return;
 		if(!Validator::Number($rate)) return;
 		if(!Validator::Number($id)) return;

 		$getpoints = self::GetPoints($id);

 		if(is_array($getpoints)) {

 			if ($getpoints['UserPoint2']>=$vp) {
 				if ($vp>=$rate){

 					$ep = floor($vp/$rate);

		 			$result = glenox::DB('RanUser')->query("UPDATE UserInfo SET UserPoint2=UserPoint2-?,UserPoint=UserPoint+? WHERE UserNum = ?", array($vp,$ep,$id));
					if($result) {
						message('success', lang('success_23',true));
					} else {
						return;
					}
 				} else {
 					message('error', lang('error_74',true));   
 				}
			} else {
				message('error', lang('error_70',true));   
			}

 		} else {
 			message('error', lang('error_25',true));
 			

 		}



 	}

 	private static function GetPoints($id){

 		if(!Validator::Number($id)) return;
		$result = glenox::DB('RanUser')->query_fetch_single("SELECT UserPoint,UserPoint2 FROM UserInfo WHERE UserNum = ?", array($id));
		if(is_array($result)) return $result;
		return;

 	}

 }