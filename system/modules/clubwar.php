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

echo '<h3>'.lang('module_titles_txt_29',true).'</h3><hr>';

try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
	
	$castleData = LoadCacheData('club_war.cache');
	
	echo '<div class="csinfo_container">';
		echo '<div class="csinfo_content">';
			echo '<div class="csinfo_ginfo">';
				echo '<table>';
					echo '<tr><td>'.lang('clubwar_txt_2',true).'</td><td><a href="'.__BASE_URL__.'profile/guild/req/'.$castleData[1][0].'" target="_blank">'.$castleData[1][0].'</a></td></tr>';
					echo '<tr><td>'.lang('clubwar_txt_3',true).'</td><td>'.number_format(round($castleData[1][2])).'</td></tr>';
					echo '<tr><td>'.lang('clubwar_txt_4',true).'</td><td>'.$castleData[1][3].'</td></tr>';
					echo '<tr><td>'.lang('clubwar_txt_5',true).'</td><td>'.$castleData[1][4].'</td></tr>';
					echo '<tr><td>'.lang('clubwar_txt_6',true).'</td><td>'.$castleData[1][5].'</td></tr>';
				echo '</table>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	if(is_array($castleData[2])) {
		echo '<div class="page-title"><span>'.lang('castlesiege_txt_7',true).'</span></div>';
		echo '<ul class="csinfo_glist">';
			foreach($castleData[2] as $guild) {
				echo '<li><a href="'.__BASE_URL__.'profile/guild/?req='.$guild.'" target="_blank">'.$guild.'</a></li>';
			}
		echo '</ul>';
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}