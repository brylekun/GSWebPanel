<?php
/**
 * Ran Panel
 * https://www.facebook.com/Parad0x25
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

class plugin_WMoney {

    public static function ProccessMoney($userid,$goldid){
        if(!check_value($userid)) return;
        if(!check_value($goldid)) return;
        if(!Validator::Number($userid)) throw new Exception('Invalid UserID');
        if(!Validator::Number($goldid)) throw new Exception('Invalid Gold');

        $moneyinfo = self::GetMoneyInfo($goldid);
        $userinfo = glenox::accountInformation($userid);

        if($userinfo['UserLoginState']!=1){
            if($moneyinfo['status']!=1) {
                if($userid!=$moneyinfo['UserNum']) {
                    if ($userinfo['UserPoint']>=$moneyinfo['EPValue']) {
                        if (self::RemoveEP($moneyinfo['EPValue'],$userid)){
                            if (self::InsertGold($moneyinfo['UserMoney'],$userinfo['UserNum'])){
                                if(self::UpdateStatus($userid,$goldid)){
                                    self::UpdateEP($moneyinfo['EPValue'],$moneyinfo['UserNum']);
                                    message('success', 'Finish... The gold is transfer to your Storage!');
                                }
                            } else {
                                message('error', 'Somethings missing! contact admin to fix!');
                            }
                        } else {
                            message('error', 'Somethings missing! contact admin to fix!');
                        }
                    } else {
                        message('error', 'Your EPoints is not enough!');
                    }
                } else {
                    message('error', 'Your not enable to buy your gold!');
                }
            } else {
                message('error', 'Gold not available!');
            }
        } else {
            message('error', 'Your account is online!');
        }
    }

    public static function PostMoney($userid,$gold,$ep,$ipaddress) {

       
        if(!check_value($userid)) return;
        if(!check_value($gold)) return;
        if(!check_value($ep)) return;

        if(!Validator::Number($userid)) throw new Exception('Invalid UserID');
        if(!Validator::Number($gold)) throw new Exception('Invalid Gold');
        if(!Validator::Number($ep)) throw new Exception('Invalid E-Points');
        
        $realgold = $gold*1000000;
        $userinfo = glenox::accountInformation($userid);
        $userGold = self::GetUserGold($userid);

        if($userinfo['UserLoginState']!=1){
            if($userGold['UserMoney']>=$realgold){
                if(self::RemoveGold($userid,$gold)){
                        self::InsertWebGold($userid,$userinfo['ChaName'],$ipaddress,$gold,$ep);
                        message('success', 'Finish... The gold is now post!');
                } else {
                    throw new Exception('Somethings error! contact admin for fix the problem');
                }
            } else {
                throw new Exception('Gold is not enough');
            }
        } else {
            throw new Exception('Your account is online!');
        }
    }


    public static function GetAllMoney(){

        $result = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM plugin_webmoney WHERE status = 0 ORDER BY UserMoney ASC");
        
        if(is_array($result)) return $result;
        return;

    }

    public static function GetAllMyMoney($userid){

        if(!check_value($userid)) return;
        if(!Validator::Number($userid)) throw new Exception('Invalid UserID');
        
        $result = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM plugin_webmoney WHERE UserNum = ? ORDER BY UserMoney ASC", array ($userid));
        
        if(is_array($result)) return $result;
        return;

    }

    public static function ConvertMoney($number) {
        $abbrevs = array(6 => " T", 3 => " B", 0 => " M");
        foreach($abbrevs as $exponent => $abbrev) {
            if($number >= pow(10, $exponent)) {
                $display_num = $number / pow(10, $exponent);
                $decimals = ($exponent >= 3 && round($display_num) < 100) ? 1 : 0;
                return number_format($display_num,$decimals) . $abbrev;
            }
        }
    }
    
    public static function GetCharALL($userid){
		
        if(!check_value($userid)) return;
        
        $result = glenox::DB('RanGame1')->query_fetch("SELECT ChaNum,ChaName,ChaOnline FROM ChaInfo WHERE UserNum = ? AND ChaDeleted !=1", array ($userid));
        if(is_array($result)) return $result;
        return;
					
    }

    public static function ClaimMoney($userid,$goldid){

        if(!check_value($userid)) return;
        if(!check_value($goldid)) return;

        if(!Validator::Number($userid)) throw new Exception('Invalid UserID');
        if(!Validator::Number($goldid)) throw new Exception('Invalid GoldID');

        $moneyinfo = self::GetMoneyInfo($goldid);

        $userinfo = glenox::accountInformation($userid);

        if($userinfo['UserLoginState']!=1) {
            if($moneyinfo['status']!=1) {
                if(self::InsertGold($moneyinfo['UserMoney'],$userinfo['UserNum'])) {
                        self::SafeDeleteGold($moneyinfo['MoneyNum']);
                        message('success', 'Great... Your gold is restore from web market!');
                } else {
                    throw new Exception('Somethings error! contact admin for fix the problem');
                }
            } else {
                message('error', 'Gold is not available!');
            }
        } else {
            message('error', 'Your account is online!');
        }
        



    }

    public static function GetUserMoney($userid){
		
        if(!check_value($userid)) return;
        $result = glenox::DB('RanGame1')->query_fetch_single("SELECT UserMoney,SGNum FROM UserInven WHERE UserNum = ?", array ($userid));
        if(is_array($result)) return $result;
        return;
					
    }

    // ** END OF PUBLIC ** //
    private static function SafeDeleteGold($MoneyNum){

        $result = glenox::DB(config('SQL_RANPANEL'))->query("DELETE FROM plugin_webmoney WHERE MoneyNum = ? AND status != 1", array($MoneyNum));
        if($result) return true;
        return;

    }
    private static function InsertWebGold($userid,$chaname,$ipaddress,$gold,$ep){
        
        $data = array(
			$userid,
			$chaname,
			$ipaddress,
			$gold,
			$ep
		);

		$query = "INSERT INTO plugin_webmoney (UserNum,ChaName,UserIPaddress,UserMoney,EPValue) VALUES (?,?,?,?,?)";
		
        $result = glenox::DB(config('SQL_RANPANEL'))->query($query, $data);
        
        if(!$result) return true;
        return;
    }

    private static function RemoveGold($userid,$gold){
       
        if(!check_value($userid)) return;
        if(!check_value($gold)) return;
        $realgold = $gold*1000000;
        $result = glenox::DB('RanGame1')->query("UPDATE UserInven SET UserMoney = UserMoney-? WHERE UserNum = ?", array($realgold, $userid));
        if($result) return true;
        return;

    }

    private static function GetUserGold($userid){
		
        if(!check_value($userid)) return;
        $result = glenox::DB('RanGame1')->query_fetch_single("SELECT * FROM UserInven WHERE UserNum = ?", array ($userid));
        if(is_array($result)) return $result;
        return;
					
    }

    private static function GetMoneyInfo($goldid){
        
        $result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM plugin_webmoney WHERE MoneyNum = ?",array($goldid));

        if(is_array($result)) return $result;
        return;

    }

    private static function UpdateStatus($userid,$goldid){
        $result = glenox::DB(config('SQL_RANPANEL'))->query("UPDATE plugin_webmoney SET buyUserNum = ?, status = 1, dateBuy = ? WHERE MoneyNum = ?", array($userid,time(),$goldid));
        if($result) return true;
        return;
    }

    private static function RemoveEP($price,$userid){

        $result = glenox::DB('RanUser')->query("UPDATE UserInfo SET UserPoint = UserPoint-? WHERE UserNum = ?", array($price, $userid));
        if($result) return true;
        return;
    }

    private static function UpdateEP($price,$userid){
        $result = glenox::DB('RanUser')->query("UPDATE UserInfo SET UserPoint = UserPoint+? WHERE UserNum = ?", array($price, $userid));
        if($result) return true;
        return;
    }

    private static function InsertGold($gold,$usernum){
        $realgold = $gold*1000000;
        $update = glenox::DB('RanGame1')->query("UPDATE UserInven SET UserMoney = UserMoney+? WHERE UserNum = ?", array($realgold,$usernum));
        if($update) return true;
        return;
    }
   

}