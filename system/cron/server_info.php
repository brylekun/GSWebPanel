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

 // File Name
$file_name = basename(__FILE__);
// Gather Server Information

	# total accounts
	$totalAccounts = 0;
	$countAccounts = $dB3->query_fetch_single("SELECT COUNT(*) as totalAccounts FROM UserInfo");
	if(is_array($countAccounts)) $totalAccounts = $countAccounts['totalAccounts'];
	$serverInfo[] = $totalAccounts;
	
	# total characters
	$totalCharacters = 0;
	$countCharacters = $dB1->query_fetch_single("SELECT COUNT(*) as totalCharacters FROM ChaInfo");
	if(is_array($countCharacters)) $totalCharacters = $countCharacters['totalCharacters'];
	$serverInfo[] = $totalCharacters;

	# total SG
	$totalSG = 0;
	$countSG = $dB1->query_fetch_single("SELECT COUNT(*) as totalSG FROM ChaInfo WHERE ChaSchool='0' AND ChaDeleted!=1");
	if(is_array($countSG)) $totalSG = $countSG['totalSG'];
	$serverInfo[] = $totalSG;

	# total MP
	$totalMP = 0;
	$countMP = $dB1->query_fetch_single("SELECT COUNT(*) as totalMP FROM ChaInfo WHERE ChaSchool='1' AND ChaDeleted!=1");
	if(is_array($countMP)) $totalMP = $countMP['totalMP'];
	$serverInfo[] = $totalMP;

	# total PHNX
	$totalPHNX = 0;
	$countPHNX = $dB1->query_fetch_single("SELECT COUNT(*) as totalPHNX FROM ChaInfo WHERE ChaSchool='2' AND ChaDeleted!=1");
	if(is_array($countPHNX)) $totalPHNX = $countPHNX['totalPHNX'];
	$serverInfo[] = $totalPHNX;
	
	# total guilds
	$totalGuilds = 0;
	//$countGuilds = $dB1->query_fetch_single("SELECT COUNT(*) as totalGuilds FROM "._TBL_GUILD_);
	//if(is_array($countGuilds)) $totalGuilds = $countGuilds['totalGuilds'];
	$serverInfo[] = $totalGuilds;

	# total online
	$totalOnline = 0;
	$countOnline = $dB1->query_fetch_single("SELECT COUNT(*) as totalOnline FROM ChaInfo WHERE ChaOnline = 1");
	if(is_array($countOnline)) $totalOnline = $countOnline['totalOnline'];
	$serverInfo[] = $totalOnline;
	
if(is_array($serverInfo)) {
	$cacheDATA = implode("|",$serverInfo);
	UpdateCache('server_info.cache',$cacheDATA);
}

// UPDATE CRON
updateCronLastRun($file_name);