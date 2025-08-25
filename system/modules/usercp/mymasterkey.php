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

if(!isLoggedIn()) redirect(1,'login');
if(config('server_files', true) != 'MUE') redirect();

echo '<div class="page-title"><span>'.lang('module_titles_txt_24',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	if(check_value($_POST['submit'])) {
		try {
			$Account = new Account($dB, $dB2);
			$Account->masterKeyRecoveryProcess($_SESSION['userid']);
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-xs-8 col-xs-offset-2 text-center" style="margin-top:30px;">';
		echo '<form action="" method="post">';
			echo '<button name="submit" value="submit" class="btn btn-primary" >'.lang('masterkey_txt_1',true).'</button>';
		echo '</form>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}