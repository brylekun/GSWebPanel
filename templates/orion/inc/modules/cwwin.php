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

$Rankings = new Rankings();
		

			
		$ranking_data = LoadCacheData('cw_winners.cache');
		
		$i = 0;

		foreach($ranking_data as $rdata) {
			$logo = $Rankings->CWSchoolWinner($rdata[1]);
			$ClubName = $rdata[0];
			
			$gumaster = $Rankings->getGuildLeader($rdata[3]);

			$gucount = $Rankings->getCountMember($rdata[3]);



			if($i>=1) {
						
				echo '<li>
				<div class="img">
					<img src="'.$logo.'" title="'.$ClubName.'"/><span class="overlay-link"></span>
				</div>
					<div class="info"> <a>'.$ClubName.'</a><br/>
					<small>Guild Leader : '.$gumaster.'</small> <br/>
					<small>Member : '.$gucount[0]['total'].'</small><br/>
					

					</div>
					<div class="clear"></div>
				</li>';


				
				
			}
			$i++;
		}