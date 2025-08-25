<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 8/6/2017
 */

class plugin_ticket {

    
    public function GetAllTicket($userid){
        if(!check_value($userid)) return;
        if(!Validator::Number($userid)) throw new Exception('Invalid UserID');
        
        $result = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM plugin_ticket WHERE usernum = ? ORDER BY ticketnum ASC",array($userid));
        
        if(is_array($result)) return $result;
        return;

    }
    
    public function ShowTicket($input,$userid){
        if(!check_value($userid)) return;
        $ticketData = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT * FROM plugin_ticket WHERE ticketnum = ? AND usernum = ?", array($input, $userid));
        if(!$ticketData) throw new Exception('Invalid Request.');
        
        return $ticketData;
    }

    public function ShowReply($input){
        $ticketData = glenox::DB(config('SQL_RANPANEL'))->query_fetch("SELECT * FROM plugin_ticketreply WHERE ticketnum = ?", array($input));
        return $ticketData;
    }
    public function checkStatus($input,$userid){
        $result = glenox::DB(config('SQL_RANPANEL'))->query_fetch_single("SELECT ticket_status FROM plugin_ticket WHERE ticketnum = ? AND usernum = ?",array($input,$userid));
        return $result['ticket_status'];
    }
    public function ReplyTicket($input,$userid,$content){
        if(!check_value($userid)) return;
        if(!check_value($content)) return;
        if(!Validator::Number($userid)) throw new Exception('Invalid UserID');
          if($this->checkStatus($input,$userid)!=4){
            if($this->checkContent($content)) {

                $ticket_data = array(
                    $input,
                    $userid,
                    htmlentities($content),
                    time()
                );
                $add_ticket = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO plugin_ticketreply (ticketnum,usernum,reply_msg,reply_time) VALUES (?,?,?,?)", $ticket_data);
                glenox::DB(config('SQL_RANPANEL'))->query("UPDATE plugin_ticket SET ticket_status = 3 WHERE ticketnum = ?",array($input));
                if($add_ticket) {
                    // success message
                    redirect(2,'ticket/reply/?req='.Encode_id($input),1);
                    
                } else {
                    message('error', 'There has been an unexpected error, contact the Administrator.');
                }
            }  else {
                message('error','The reply content must be at least 4 characters long.');
            }
        } else {
            message('error','The ticket Status is Done! Can\'t reply this ticket!');
            redirect(2,'ticket/all/',1);
        }




    }
    public function PostNewTicket($userid,$title,$content){
        if(!check_value($userid)) return;
        if(!check_value($title)) return;
        if(!check_value($content)) return;
        if(!Validator::Number($userid)) throw new Exception('Invalid UserID');

        if($this->checkTitle($title)) {
            if($this->checkContent($content)) {

                $ticket_data = array(
                    $userid,
                    $title,
                    htmlentities($content),
                    time()
                );
                $add_ticket = glenox::DB(config('SQL_RANPANEL'))->query("INSERT INTO plugin_ticket (usernum,ticket_title,ticket_content,ticket_time) VALUES (?,?,?,?)", $ticket_data);
                
                if($add_ticket) {
                    // success message
                    message('success', 'ticket successfully added!');
                    redirect(2,$_REQUEST['page'].'/all/',3);
                } else {
                    message('error', 'There has been an unexpected error, contact the Administrator.');
                }
            }  else {
                message('error','The ticket content must be at least 4 characters long.');
            }
        } else {
            message('error', 'Ticket title can have a minimum of 4 characters and maximum of 20.');
        }
        



    }

    private function checkTitle($title) {
		if(check_value($title)) {
			if(strlen($title) < 4 || strlen($title) > 20) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
    }
    
    private function checkContent($content) {
		if(check_value($content)) {
			if(strlen($content) < 4) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
    public function ChangeStatus(){
       return $change['status'] = array(
                1 => array('Pending'),
                2 => array('Verify'),
                3 => array('Replied'),
                4 => array('Done')
            );
    }

    

}