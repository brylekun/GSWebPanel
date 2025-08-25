<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 11/29/2017
 */

class plugin_Changepin {

    private $_config;


    public static function cPinProcess($userid, $oldpin, $newpin, $password) {

       
        
        if(!check_value($userid)) throw new Exception(lang('error_4',true));
        if(!check_value($oldpin)) throw new Exception(lang('error_4',true));
        if(!check_value($newpin)) throw new Exception(lang('error_4',true));
        if(!check_value($password)) throw new Exception(lang('error_4',true));

        
        if(!Validator::AlphaNumeric($newpin))  throw new Exception('The pincode can only contain alpha-numeric characters.');
        if(!self::PincodeLength($newpin)) throw new Exception('The Pincode length can be 6 to 11 characters.');
        
        # load account data
        $accountData = glenox::accountInformation($userid);

        if(!is_array($accountData)) throw new Exception(lang('error_21',true));
        
        #Same Pincode
        if($accountData['Upass']!=$oldpin) throw new Exception('Pincode is incorrect!');
        #Same Pincode
        if($accountData['Upass']==$newpin) throw new Exception('New pincode is same in your old pincode!');

        # check user credentials
        if(!glenox::validateUser($accountData['UserID'], $password)) throw new Exception(lang('error_13',true));
        
        # update password
        if(!self::changePincode($userid, $accountData['UserID'], $newpin)) throw new Exception('Your pincode could not be changed, please contact the Administrator.');
        
    }

    private static function PincodeLength($string){
        if((strlen($string) < 6) || (strlen($string) > 12)) {
            return false;
        } else {
            return true;
        }
    }

    private static function changePincode($id,$username,$newpin) {
        if(!Validator::UnsignedNumber($id)) return;
        if(!Validator::UsernameLength($username)) return;
        if(!Validator::AlphaNumeric($username)) return;
        if(!self::PincodeLength($newpin)) return;
        if(!Validator::AlphaNumeric($newpin)) return;
        
        if(glenox::MD5Hash()) {
            $enc_newpin = glenox::CRYPTMD5($newpin);
            $data = array('userid' => $id, 'username' => $username, 'pincode' => $enc_newpin, 'upass' => $newpin);

            $query = "UPDATE UserInfo SET UserPass2 = :pincode, Upass = :upass, isPinChange = 1 WHERE UserNum = :userid AND UserID = :username";
        } else {
            $data = array('userid' => $id, 'username' => $username, 'pincode' => $newpin, 'upass' => $newpin);
            $query = "UPDATE UserInfo SET UserPass2 = :pincode, Upass = :upass, isPinChange = 1 WHERE UserNum = :userid AND UserID = :username";
        }

        $result = glenox::DB('RanUser')->query($query, $data);
        if($result) return true;
        return;

    }
   

}