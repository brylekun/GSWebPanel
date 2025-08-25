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
	
		$ranking_data = LoadCacheData('rankings_top10.cache');
		
		$i = 0;
		foreach($ranking_data as $rdata) {
			//$school = $Character->CharacterSchool($rdata[5]);
			//echo $school;
			$class = glenox::CharClass($rdata[3]);
			$className = glenox::GenerateCharacterClass($rdata[3]);
			$school = glenox::CharacterSchool($rdata[5]);
	
			if($i>=1) {

				echo '<li>
						<div class="img">
							<img src="'.$class.'" title="'.$className.'"/><span class="overlay-link"></span>
						</div>
        					<div class="info">('.$i.') <a>'.$rdata[1].'</a><br/>
          <small>'.$school.'</small><br/>
		 <small>Level: '.$rdata[2].' - Kill: '.$rdata[7].' - Death: '.$rdata[8].'</small><br/>
		

		</div>
		<div class="clear"></div>
      </li>';
				
				
				
			}
			$i++;
		}