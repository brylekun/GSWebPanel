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
(!isLoggedIn()) ? redirect(1,'login') : null;

echo '<h3>'.lang('profiles_txt_1',true).'</h3><hr>';

			loadModuleConfigs('profiles');
			if(ranconfig('active')) {
				if(check_value(Decode_id($_GET['req']))) {
					try {
						$weProfiles = new weProfiles();
						$weProfiles->setType("guild");
						$weProfiles->setRequest(Decode_id($_GET['req']));
						$guildData = $weProfiles->data();
						
						$guildMembers = json_decode($guildData[5],true);

						$displayData = array(
							'gname' => $guildData[1],
							'glogo' => $weProfiles->BuildGuildLogo($guildData[2]),
							'gmaster' => $guildData[4],
							'gscore' => $guildData[3],
							'gmembers' => count($guildMembers),
						);
						
						templateBuildGuild($displayData,$guildMembers);

					} catch(Exception $e) {
						message('error', $e->getMessage());
					}
				} else {
					message('error', lang('error_25',true));
				}
			} else {
				message('error', lang('error_47',true));
			}
		?>