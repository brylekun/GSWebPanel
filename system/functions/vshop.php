<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 9/21/2016
 */

class Vshop {

    # self invoke for ranshop.

    function __construct(){

        $this->db2 = glenox::DB('RanShop');
        $this->db3 = glenox::DB('RanUser');

    }
    public function proccessItem($userid,$ItemNum){

        if(!check_value($userid)) return;
        if(!check_value($ItemNum)) return;
        $accountInfo = $this->GetAccount($userid);
        $getPInfo = $this->getProductInfo($ItemNum);
        $this->epoints = $getPInfo['ItemPrice'];
        $this->_username = $accountInfo['UserID'];
        $this->_epoints = $accountInfo['UserPoint2'];
        $real = $this->getSessionID();
        if($real['session_user_id']==$userid){
            if ($getPInfo['Itemstock']>0) {
                if ($this->checkVotePoint()){
                    if ($this->subractCharacterPoint($accountInfo['UserNum'],$this->epoints)){
                        $this->updateStock($ItemNum);
                        $purkey = $this->insertItem($ItemNum);
                        $this->LogPurchase($this->_username,$purkey,$ItemNum,$this->epoints,$getPInfo['ItemName']);
                        message('success', lang('success_22',true));
                        # redirect to login (5 seconds)
                         redirect(2,'vshop?ctg=all',1);
                    } else {
                        message('error', lang('error_67',true));
                    }
                } else {
                     message('error', lang('error_73',true));
                     //redirect(2,'vshop/',1);
                }
            } else {
                message('error', lang('error_72',true));
                 //redirect(2,'vshop/',1);
            }
        } else {
            message('error', 'Invalid Account!');
        }
    }

    public function getItem($ctg=0){
    
        switch ($ctg) {
            case 'pet':
                $SQL = "SELECT * FROM ShopItemMap WHERE PremiumItem = 0 AND Category = 1 AND ItemStatus != 0";
                break;
            case 'potion':
                $SQL = "SELECT * FROM ShopItemMap WHERE PremiumItem = 0 AND Category = 2 AND ItemStatus != 0";
                break;
            case 'cloth':
                $SQL = "SELECT * FROM ShopItemMap WHERE PremiumItem = 0 AND Category = 3 AND ItemStatus != 0";
                break;
            case 'other':
                $SQL = "SELECT * FROM ShopItemMap WHERE PremiumItem = 0 AND Category = 4 AND ItemStatus != 0";
                break;
            case 'acce':
                $SQL = "SELECT * FROM ShopItemMap WHERE PremiumItem = 0 AND Category = 5 AND ItemStatus != 0";
                break;
            case 'refines':
                $SQL = "SELECT * FROM ShopItemMap WHERE PremiumItem = 0 AND Category = 6 AND ItemStatus != 0";
                break;
            default:
                $SQL = "SELECT * FROM ShopItemMap WHERE PremiumItem = 0 AND ItemStatus != 0";
                break;
        }
        $check = $this->db2->query_fetch($SQL);
        if($check) {
            return $check;

        }
    }
    
    public function GetEP($id) {
       
        if(!Validator::Number($id)) return;
        $result = $this->db3->query_fetch_single("SELECT UserPoint2 FROM UserInfo WHERE UserNum = ?", array($id));
        if($result) return $result;
    }

    private function getSessionID(){

        $id = session_id();
        $result = glenox::DB('RanPanel')->query_fetch_single("SELECT session_user_id FROM ActiveSessions WHERE session_id = ?",array($id));
        if($result) return $result;
        return;
    }

    private function GetAccount($id) {
        
        if(!Validator::Number($id)) return;
        $result = $this->db3->query_fetch_single("SELECT * FROM UserInfo WHERE UserNum = ?", array($id));
        if(is_array($result)) return $result;
        return;
    }

    private function LogPurchase($username,$purkey,$itemnum,$price,$name){

        $data = array(
            $username,
            $purkey,
            $itemnum,
            'VP',
            $price,
            time(),
            $name
        );

        $query = "INSERT INTO LogPurchase (LogUserUID,LogPurKey,LogProductNum,LogName,LogPurPrice,LogTime,LogItemName) VALUES (?,?,?,?,?,?,?)";
        $result = glenox::DB('RanPanel')->query($query,$data);
        if($result) return true;

    }

    private function subractCharacterPoint($id,$itemprice){
        
        if(!Validator::Number($id)) return;
        if(!Validator::Number($itemprice)) return;

        $result = $this->db3->query("UPDATE UserInfo SET UserPoint2 = UserPoint2-? WHERE UserNum = ?", array($itemprice, $id));
        if($result) return true;
        return;
    }

    private function checkVotePoint(){
        
        if($this->_epoints>=$this->epoints){
            return true;
        }else{
            return false;
        }
    }

    private function updateStock($id){


        if(!Validator::Number($id)) return;
        $result = $this->db2->query("UPDATE ShopItemMap SET Itemstock=Itemstock-1 WHERE ProductNum = ?", array($id));
        if($result) return true;
        return;
       
    }

    private function getProductInfo($param){

            if(!Validator::Number($param)) return;
            $pinfo = $this->db2->query_fetch_single("SELECT ItemName,ItemPrice,Itemstock FROM ShopItemMap WHERE ProductNum = ?", array($param));
            if(is_array($pinfo)) return $pinfo;
            return;
        
    }

    private function insertItem($pid){
        
        $result = $this->db2->query_fetch("INSERT INTO ShopPurchase (UserUID,ProductNum,PurPrice,PurFlag,PurLog) OUTPUT Inserted.PurKey VALUES (?,?,?,?,?)", array($this->_username,$pid,$this->epoints,'0','Web'));
        return $result[0]['PurKey'];
        
    }

    // add new item in itemshop // :)
    public function AdminGetItem(){
         #admin
        $check = $this->db2->query_fetch($SQL = "SELECT * FROM ShopItemMap WHERE PremiumItem = 0");
        if($check) {
            return $check;

        }
    }

    public function AddItemShop($file){
        //var_dump($file);
        $target_dir = __PATH_SYSTEM__."temp/";
        $target_file = $target_dir . "item.txt";
        
        if ($file["size"] > 500000) {
           message('error', 'Sorry, your file is too large.');
        } else if (move_uploaded_file($file["tmp_name"], $target_file)) {
             //message('success', 'Success!');
            $fp = fopen($target_file, "r");
            $line = array();
            while(!feof($fp)){
                $line[] = fgets($fp);
            }
                fclose($fp);
                $item = array();
                foreach($line as $itm){
                    $find = substr($itm,0,3);
                    if ($find == "IN_") {
                        $mid = substr($itm,3,3);
                        $sid = substr($itm,7,3);
                        $so = explode("\t",$itm);
                        //var_dump($so[1]);
                        $result = $this->db2->query("INSERT INTO ShopItemMap (ItemMain,ItemSub,ItemName,ItemPrice,Itemstock,Category,PremiumItem) VALUES (?,?,?,?,?,?,?)", array($mid,$sid,$so[1],0,0,0,0));

                    }
                   
                }
                
        }

    }

    public function EditItemShop($pnum,$name,$price,$stock,$status,$ctg,$url){
        if((!check_value($name)) || (!check_value($price)) || (!check_value($stock))) return;

         $result = $this->db2->query("UPDATE ShopItemMap SET ItemName = ?, ItemPrice = ?, Itemstock = ?, Category = ?, ItemStatus = ?, ItemImage = ? WHERE ProductNum = ?", array($name,$price,$stock,$ctg,$status,$url,$pnum));
       
        if($result) return true;
        return;


    }

    public function DelItemShop($pnum){
         $result = $this->db2->query("DELETE FROM ShopItemMap WHERE ProductNum = ?", array($pnum));
        if($result) return true;
        return;
    }
   

}