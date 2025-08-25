<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

class Changeschool {

    private $_config;
    # self invoke for ranshop.

    function __construct(){

        $CDConfigs = loadConfigurations('usercp.changeschool');
        if(!is_array($CDConfigs)) throw new Exception('Change school configurations missing.');
        $this->_config = $CDConfigs;
    }
    public function Proccess($ChaID,$SkulID,$UserNum) {
        
        
         $ep = $this->_config['costep'];
         $vp = $this->_config['costvp'];

         if(!Validator::Number($ChaID)) throw new Exception('Invalid Character');
         if(!Validator::Number($SkulID)) throw new Exception('Invalid School');
         if(!Validator::Number($UserNum)) throw new Exception('Invalid User!');
         if(!Validator::Number($vp))  throw new Exception('Invalid Vote-Points');
         if(!Validator::Number($ep)) throw new Exception('Invalid E-Points');

        $AccountInfo = $this->GetAccount($UserNum);
        $david = $this->GetSchool($ChaID);

        if (check_value($ChaID) && check_value($SkulID) && check_value($UserNum)) {
            

            //var_dump($david['ChaSchool']);
            if ($this->_config['cooldown']) {
                if ($this->isCooldown($ChaID)) {
                    if ($david['ChaSchool'] != $SkulID) {
                        if ($AccountInfo['UserPoint2'] >= $vp){
                            if ($AccountInfo['UserPoint'] >= $ep){
                                $Done = $this->CostVP($UserNum,$vp,$ep);
                                    if ($Done) {
                                        $this->SetCooldown($ChaID,$SkulID);
                                        $result =  glenox::DB('RanGame1')->query("UPDATE ChaInfo SET ChaSchool = ? WHERE UserNum = ? AND ChaNum = ?", array($SkulID, $UserNum, $ChaID));
                                        
                                        if($result) message('success','Change School Success');
                                        return;
                                    }
                            } else {
                                message('error','Not enough E-points');
                            }
                        } else {
                            message('error','Not enough V-points');
                        }
                    } else {
                        message('error','Your are already in this school!');
                    }
                } else {
                    message('error','Change school cooldown time! Remain time :<b>'.$this->remain.'</b> hour(s)!');
                }
            } else {
                if ($david['ChaSchool'] != $SkulID) {
                    if ($AccountInfo['UserPoint2'] >= $vp){
                        if ($AccountInfo['UserPoint'] >= $ep){
                            $Done = $this->CostVP($UserNum,$vp,$ep);
                                if ($Done) {
                                    
                                    $result =  glenox::DB('RanGame1')->query("UPDATE ChaInfo SET ChaSchool = ? WHERE UserNum = ? AND ChaNum = ?", array($SkulID, $UserNum, $ChaID));
                                    
                                    if($result) message('success','Change School Success');
                                    return;
                                }
                        } else {
                            message('error','Not enough E-points');
                        }
                    } else {
                        message('error','Not enough V-points');
                    }
                } else {
                    message('error','Your are already in this school!');
                }

            }
            
        }
            
    }

    public function checkActiveSession($userid,$session_id) {

        if(check_value($userid) && check_value($session_id)) {
            $check =  glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM ActiveSessions WHERE session_user_id = ? AND session_id = ?", array($userid,$session_id));
            if($check && is_array($check)) {
            //var_dump($check);
                $david = glenox::GetAllChar($check['session_user_id']);
                return $david;
            }
        }
    }
    
    private function CostVP($UserNum,$vpoints,$epoints){

        $result =  glenox::DB('RanUser')->query("UPDATE UserInfo SET UserPoint = UserPoint - ?, UserPoint2 = UserPoint2 - ? WHERE UserNum = ?", array($epoints,$vpoints,$UserNum));
        if($result) return true;
        return;
    }
    private function GetAccount($id) {

        if(!Validator::Number($id)) return;
        $result =  glenox::DB('RanUser')->query_fetch_single("SELECT * FROM UserInfo WHERE UserNum = ?", array($id));
        if(is_array($result)) return $result;
        return;
    }
    private function GetSchool($ChaNum){

        if(check_value($ChaNum)) {
                
            $result =  glenox::DB('RanGame1')->query_fetch_single("SELECT ChaSchool FROM ChaInfo WHERE ChaNum = ? AND ChaDeleted !=1", array ($ChaNum));
            if($result) return $result;
            return;
                
         }

        
    }

    private function CheckCooldown($ChaNum){

        if(!check_value($ChaNum)) return;
        

        $result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM change_school WHERE chanum = ?", array($ChaNum));


        if(is_array($result)) return true;
        return;

       
    }

    private function SetCooldown($ChaNum,$school){

        if(!check_value($ChaNum)) return;
        if(!check_value($school)) return;

        if (!$this->CheckCooldown($ChaNum)) {

            $result = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO change_school (chanum,changedate,chaschool) VALUES (?,?,?)", array ($ChaNum,time(),$school));
        } else {
            $result = glenox::DB(config('SQL_RANPANEL'))->query("UPDATE change_school SET changedate = ?, chaschool = ? WHERE chanum = ?", array (time(),$school,$ChaNum));
        }
        
        if($result) return true;
        return;
    }

     private function isCooldown($ChaNum){

        if(!check_value($ChaNum)) return;
        

        $result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM change_school WHERE chanum = ?", array($ChaNum));


        if(is_array($result)) {

            $timeout = $result['changedate']; //galing  sa db
            $offset = time() - $timeout;    
            $cd = $this->_config['cooldowntime']*3600;
            $this->remain = round(($cd - $offset)/3600,0);

            if ($offset > $cd) return true;
            return;
        } else {
            return true;
        }

       
    }
   

}