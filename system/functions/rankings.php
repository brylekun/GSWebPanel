<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 3.0.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

class Rankings {
    
    private $_results;
    private $_excludedCharacters;
    
    function __construct() {

        $this->config = ran_Configs();
        
        loadModuleConfigs('rankings');
        $this->_results = (check_value(ranconfig('rankings_results')) ? ranconfig('rankings_results') : 25);
        
        $excludedCharacters = explode(",", ranconfig('rankings_excluded_characters'));
        $this->_excludedCharacters = $excludedCharacters;
    }

    public function UpdateRankingCache($ranking_type) {
        global $config;
        loadModuleConfigs('rankings');
        switch($ranking_type) {
            case 'all':
                $this-> _all();
            break;
            case 'brawler':
               $this->_brawler();
            break;
            case 'swords':
                $this->_swords();
            break;
            case 'archer':
                $this->_archer();
            break;
            case 'shaman':
                $this->_shaman();
            break;
            case 'ex3m':
               $this->_ex3m();
            break;
            case 'gunner':
               $this->_gunner();
            break;
            case 'assassin':
               $this->_assassin();
            break;
            case 'magician':
                $this->_magician();
            break;
            case 'shaper':
               $this->_shaper();
            break;
            case 'top10':
               $this->_top10();
            break;
            case 'toprich':
               $this->_toprich();
            break;
            case 'toplevel':
                $this->_topLEVELUP();
            break;
            case 'toponline':
                $this->_topOnline();
            break;
            case 'cwwinner':
                $this->_cwwinner();
            break;
            default:
            break;
        }
    }
    

    public function _cwwinner() {

       $result = glenox::DB('RanGame1')->query_fetch("SELECT I.GuName,R.RegionID,R.RegionTax,R.GuNum FROM GuildInfo I, GuildRegion R WHERE I.GuNum = R.GuNum");
       if(!is_array($result)) return;
       $cacheDATA = BuildCacheData($result);
       UpdateCache('cw_winners.cache',$cacheDATA);

    }


    public static function _top10() {
       $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP 10 P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.UserNum=U.UserNum  AND    U.UserType=1 AND P.ChaDeleted!=1 ORDER BY /*P.ChaReborn DESC, P.ChaLevel DESC,*/  P.ChaPkWin DESC/* , P.ChaPkLoss ASC*/ "); //dale modify
       if(!is_array($result)) return;
       $cacheDATA = BuildCacheData($result);
       UpdateCache('rankings_top10.cache',$cacheDATA);
    }

    public function CWSchoolWinner($code=0) {
        global $custom;
        $school = __PATH_TEMPLATE_IMG__ . 'school/' . $custom['cw_school'][$code][1];
        return $school;
    }

    public function getGuildLeader($gunum){

        $guildData = glenox::DB('RanGame1')->query_fetch_single("SELECT ChaNum FROM GuildInfo WHERE GuNum = ?", array($gunum));
        
        $gumaster = glenox::DB('RanGame1')->query_fetch_single("SELECT ChaName FROM ChaInfo WHERE ChaNum = ?", array($guildData['ChaNum']));
        
        return $gumaster['ChaName'];

    }

    public function getCountMember($gunum){

        // Members
        $guildMembers = glenox::DB('RanGame1')->query_fetch("SELECT COUNT(*) AS 'total' FROM ChaInfo WHERE GuNum = ?", array($gunum));

        return $guildMembers;
    }
    
    private function _all() {
        $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.UserNum=U.UserNum AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
        if(!is_array($result)) return;
        $cacheDATA = BuildCacheData($result);
        UpdateCache('rankings_all.cache',$cacheDATA);
    }

    private function _brawler() {      
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('1', '64') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
             if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_brawler.cache',$cacheDATA);
    }
    private function _swords() {      
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('2', '128') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
             if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_swords.cache',$cacheDATA);
    }
    private function _archer() {      
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('256', '4') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
             if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_archer.cache',$cacheDATA);
    }
    private function _shaman() {    
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('8', '512') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
             if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_shaman.cache',$cacheDATA);
    }
    private function _ex3m(){
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('16', '32') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
            if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_ex3m.cache',$cacheDATA);
    }
    private function _gunner(){
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('1024', '2048') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
            if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_gunner.cache',$cacheDATA);
    }
    private function _assassin(){
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('4096', '8192') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
            if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_assassin.cache',$cacheDATA);
    }
    private function _magician(){
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('16384', '32768') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
            if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_magician.cache',$cacheDATA);
    }
    private function _shaper(){
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.GuNum,P.ChaName,P.ChaLevel,P.ChaClass,P.ChaOnline,P.ChaSchool,P.ChaReborn,P.ChaPkWin,P.ChaPkLoss,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.ChaClass IN ('65536', '131072') AND P.UserNum=U.UserNum  AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaReborn DESC, P.ChaLevel DESC, P.ChaPkWin DESC, P.ChaPkLoss ASC");
            if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_shaper.cache',$cacheDATA);
    }
    private function _toprich(){
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.ChaName,P.ChaMoney+I.UserMoney,P.ChaClass,P.ChaNum FROM RanGame1.dbo.ChaInfo P, RanGame1.dbo.UserInven I, RanUser.dbo.UserInfo U WHERE U.UserNum=P.UserNum AND U.UserNum=I.UserNum AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaMoney+I.UserMoney DESC ");
            if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_toprich.cache',$cacheDATA);
    }

    private function _topLEVELUP(){
            $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.ChaNum,P.ChaName,P.ChaLevel,P.ChaSchool,P.ChaLastLevelUp FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.UserNum=U.UserNum AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY P.ChaLevel DESC, CAST(P.ChaLastLevelUp as datetime) ASC");
            if(!is_array($result)) return;
            $cacheDATA = BuildCacheData($result);
            UpdateCache('rankings_toplevel.cache',$cacheDATA);

    }

    private function _topOnline(){
        $result = glenox::DB('RanGame1')->query_fetch("SELECT TOP ".ranconfig('rankings_results')." P.ChaNum,P.ChaName,P.ChaSchool,U.Gametime3 FROM RanGame1.dbo.ChaInfo P, RanUser.dbo.UserInfo U WHERE P.UserNum=U.UserNum AND U.UserType=1 AND P.ChaDeleted!=1 ORDER BY U.Gametime3 DESC");
        if(!is_array($result)) return;
        $cacheDATA = BuildCacheData($result);
        UpdateCache('rankings_topOnline.cache',$cacheDATA);
    }
    
    public static function rankingsMenu() {
        $rankings_menu = array(
            //array(lang('rankings_txt_1',true),'all',ranconfig('rankings_enable_all')),
             array(lang('rankings_txt_2',true),'brawler',ranconfig('rankings_enable_brawler')),
             array(lang('rankings_txt_3',true),'swords',ranconfig('rankings_enable_swords')),
             array(lang('rankings_txt_4',true),'archer',ranconfig('rankings_enable_archer')),
             array(lang('rankings_txt_5',true),'shaman',ranconfig('rankings_enable_shaman')),
             array(lang('rankings_txt_32',true),'ex3m',ranconfig('rankings_enable_ex3m')),
             array(lang('rankings_txt_33',true),'gunner',ranconfig('rankings_enable_gunner')),
             array(lang('rankings_txt_34',true),'assassin',ranconfig('rankings_enable_assassin')),
             array(lang('rankings_txt_35',true),'magician',ranconfig('rankings_enable_magician')),
             array(lang('rankings_txt_36',true),'shaper',ranconfig('rankings_enable_shaper')),
             array(lang('rankings_txt_37',true),'toprich',ranconfig('rankings_enable_toprich')),
             array(lang('rankings_txt_39',true),'toplevel',ranconfig('rankings_enable_toplevel')),
             array('Top Online','toponline',ranconfig('rankings_enable_toponline')),
             
        );

        echo '<div class="characterList">';
        
        foreach($rankings_menu as $rm_item) {
            if($rm_item[2]) {
                if($_REQUEST['subpage'] == $rm_item[1]) {
                    echo ' <img src="'.__PATH_TEMPLATE_IMG__.'ranking/'.$rm_item[1].'_tab_on.gif" alt="'.$rm_item[0].'" title="'.$rm_item[0].'">';
                    //echo '';
                } else {
                    echo ' <a href="'.__PATH_MODULES_RANKINGS__.$rm_item[1].'/"><img src="'.__PATH_TEMPLATE_IMG__.'ranking/'.$rm_item[1].'_tab_off.gif" alt="'.$rm_item[0].'" title="'.$rm_item[0].'"></a>';
                }
            }
        }
        echo '</div>';
    }
    
    private function _rankingsExcludeChars() {
        if(!is_array($this->_excludedCharacters)) return;
        $return = array();
        foreach($this->_excludedCharacters as $characterName) {
            $return[] = "'".$characterName."'";
        }
        return implode(",", $return);
    }

}