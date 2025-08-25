<?php
	// SIDEBAR SERVER INFORMATION
	$srvInfoCache = LoadCacheData('server_info.cache');
	if(is_array($srvInfoCache)) {
		$srvInfo = explode("|", $srvInfoCache[1][0]);
		echo '<ul>
        		    <li><a>Player Online : <font color="#33CC00">'.$srvInfo[6].'</font></a></li>
                    <li><a>Experience : X12</a></li>
		            <li><a>Gold Drop : X1</a></li>
                    <li><a>Drop : X8.5</a></li> 
                    <li><a>Episode : EP2/EP7</a></li>
                </ul>

		';

		
	}
?>