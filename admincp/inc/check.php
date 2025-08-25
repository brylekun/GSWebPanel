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

$configError = array();
$writablePaths = array(
	'cache/',
	'cache/news/',
	'cache/profiles/guilds/',
	'cache/profiles/players/',
	'cache/cron.cache',
	'cache/downloads.cache',
	'cache/news.cache',
	'cache/plugins.cache',
	'cache/server_info.cache',
	'config/email.xml',
	'config/navbar.json',
	'config/usercp.json',
	'config/config.json',
	'config/modules/donation.paymentwall.xml',
	'config/modules/donation.paypal.xml',
	'config/modules/donation.superrewards.xml',
	'config/modules/donation.remittance.xml',
	'config/modules/donation.xml',
	'config/modules/downloads.xml',
	'config/modules/forgotpassword.xml',
	'config/modules/login.xml',
	'config/modules/news.xml',
	'config/modules/profiles.xml',
	'config/modules/rankings.xml',
	'config/modules/register.xml',
	# user cp module #
	'config/modules/usercp.accfix.xml',
	'config/modules/usercp.myaccount.xml',
	'config/modules/usercp.myemail.xml',
	'config/modules/usercp.mymasterkey.xml',
	'config/modules/usercp.mypassword.xml',
	'config/modules/usercp.vote.xml',
);

// File permission check
foreach($writablePaths as $thisPath) {
	if(file_exists(__PATH_SYSTEM__ . $thisPath)) {
		if(!is_writable(__PATH_SYSTEM__ . $thisPath)) {
			$configError[] = "<span style=\"color:#aaaaaa;\">[Permission Error]</span> " . $thisPath . " <span style=\"color:red;\">(file must be writable)</span>";
		}
	} else {
		$configError[] = "<span style=\"color:#aaaaaa;\">[Not Found]</span> " . $thisPath. " <span style=\"color:orange;\">(re-upload file)</span>";
	}
}

// Encryption hash check
if(!check_value($config['encryption_hash'])) {
	$configError[] = "<span style=\"color:#aaaaaa;\">[Configuration]</span> encryption_hash <span style=\"color:green;\">(must be configured)</span>";
} else {
	if(!in_array(strlen($config['encryption_hash']), array(16,24,32))) {
		$configError[] = "<span style=\"color:#aaaaaa;\">[Configuration]</span> encryption_hash <span style=\"color:green;\">(must have 16, 24 or 32 characters)</span>";
	}
}

// Check cURL
if(!function_exists('curl_version')) $configError[] = "<span style=\"color:#aaaaaa;\">[PHP]</span> <span style=\"color:green;\">curl not loaded (WebEngine required cURL)</span>";

if(count($configError) >= 1) {
	throw new Exception("<strong>The following errors ocurred:</strong><br /><br />" . implode("<br />", $configError));
}