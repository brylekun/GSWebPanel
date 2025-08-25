<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

function templateDisplayCWBanner() {
	loadModuleConfigs('clubwar');
	if(!ranconfig('active')) return;
	if(!ranconfig('enable_banner')) return;
	
	$ranking_data = LoadCacheData('club_war.cache');
	if(!is_array($ranking_data)) return;
	
	$Rankings = new Rankings();
	
	$cw = clubwar_CalculateTimeLeft();
	if(!check_value($cs)) return;
	$timeleft = sec_to_hms($cw);
	
	echo '<div id="castle-siege">';
		echo '<table cellspacing="0" cellpadding="0">';
			echo '<tr>';
				echo '<td class="cs-guild-info">';
					echo '<span class="cs-guild-title">'.$ranking_data[1][0].'</span><br />';
					echo '<span>'.lang('cwbanner_txt_1',true).'</span>';
				echo '</td>';
				echo '<td>';
					echo lang('csbanner_txt_2',true).'<br />';
					echo '<span class="cs-timeleft" id="cscountdown">';
						echo $timeleft[0] . '<span>h</span> ';
						echo $timeleft[1] . '<span>m</span> ';
						echo $timeleft[2] . '<span>s</span> ';
					echo '</span>';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	echo '</div>';
}

function templateBuildNavbar() {
	$cfg = loadConfig('navbar');
	if(!is_array($cfg)) return;
	//var_dump($cfg);
	//echo '<ul>';

	foreach($cfg as $element) {
		//var_dump($element);
		if(!is_array($element)) continue;
		
		# active
		if(!$element['active']) continue;
		
		# type
		$link = ($element['type'] == 'internal' ? __BASE_URL__ . $element['link'] : $element['link']);
		
		# title
		$title = (check_value(lang($element['phrase'], true)) ? lang($element['phrase'], true) : $element['phrase']);
		
		# visibility
		if($element['visibility'] == 'guest') if(isLoggedIn()) continue;
		if($element['visibility'] == 'user') if(!isLoggedIn()) continue;
		
		# print
		if($element['newtab']) {
			echo '<li><a href="'.$link.'" target="_blank">'.$title.'</a></li>';
		} else {
			echo '<li><a href="'.$link.'">'.$title.'</a></li>';
		}
	}
	if(isLoggedIn() && canAccessAdminCP($_SESSION['username'])) {
			echo '<li><a href="'.__PATH_ADMINCP_HOME__.'index.php" target="_blank">AdminCP</a></li>';
		}

}

function templateBuildUsercp() {

	$cfg = loadConfig('usercp');
	if(!is_array($cfg)) return;


		$accountInfo = glenox::accountInformation($_SESSION['userid']);
		echo '<div class="review"><ul><div class="header"> Hi, '.$accountInfo['UserID'].' </div>';
		echo '<li>
					<div class="img">
							<img src="'.__PATH_TEMPLATE_IMG__.'user/user.png" title="'.$className.'"/><span class="overlay-link"></span>
						</div>
				<div class="info"><a>EPoints</a> : '.$accountInfo['UserPoint'].'<br />
			  	<a>VPoints </a> : '.$accountInfo['UserPoint2'].' <br />
			  		<a href="'.__BASE_URL__.'logout">Logout</a>
			  	</div>

			 <div class="clear"></div>
      </li></ul></div>';
	echo ' <div class="right_navi"><div class="latest_text"><h1> Menu </h1></div><ul>';

	foreach($cfg as $element) {
		if(!is_array($element)) continue;
		
		# active
		if(!$element['active']) continue;
		
		# type
		$link = ($element['type'] == 'internal' ? __BASE_URL__ . $element['link'] : $element['link']);
		
		# title
		$title = (lang($element['phrase'], true) ? lang($element['phrase'], true) : $element['phrase']);
		
		# icon
		$icon = (check_value($element['icon']) ? __PATH_TEMPLATE_IMG__ . 'icons/' . $element['icon'] : __PATH_TEMPLATE_IMG__ . 'icons/usercp_default.png');
		
		# visibility
		if($element['visibility'] == 'guest') if(isLoggedIn()) continue;
		if($element['visibility'] == 'user') if(!isLoggedIn()) continue;
		
		# print
		if($element['newtab']) {
			echo '<li class="cat-item"><a href="'.$link.'" target="_blank">'.$title.'</a></li>';
		} else {
			echo '<li class="cat-item"><a href="'.$link.'" class="link-glenox">'.$title.'</a></li>';
		}
	}
	echo '</ul></div>';
}

function templateBuildLogin(){
	try {

	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
			
			// Login Process
		if(check_value($_POST['Login_submit'])) {
			try {
				$userLogin = new login();
				
				$userLogin->validateLogin($_POST['Login_user'],$_POST['Login_pass']);
			} catch (Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
	 	echo '<form action="" method="post">';
	 		echo '<div class="form_left">';
	 			echo '<label class="lblLogin">Username </label>';
	 			echo '<input type="text" class="LogIN" id="Login1" name="Login_user" required>';
	 			echo '<label class="lblLogin">Password </label>';
	 			echo '<input type="password" class="LogIN" id="Login2" name="Login_pass" required>';
	 				echo '<label><span id="helpBlock" class="help-block"><a href="'.__BASE_URL__.'forgotpassword/">'.lang('login_txt_4',true).'</a></span><label>';
	 				echo '<button type="submit" name="Login_submit" class="btn btn-primary" value="submit">'.lang('login_txt_3',true).'</button>';
			echo '</div><div class="clear"></div>';
		echo '</form>';

	} catch(Exception $ex) {
	message('error', $ex->getMessage());
	}
}

function templateBuildNews(){

	$News = new News();
	$cachedNews = LoadCacheData('news.cache');
	if(!is_array($cachedNews)) throw new Exception('There are no news to display.');
	
	# single news
	$requestedNewsId = $_GET['subpage'];
	if(check_value($requestedNewsId)) {
		$showSingleNews = true;
		$newsID = Decode_id($requestedNewsId);
	}
	
	# news list
	$i = 0;
	foreach(array_slice($cachedNews, 1) as $newsArticle) {
		
		
		if($showSingleNews) if($newsArticle[0] != $newsID) continue;
		
		if($i > ranconfig('news_list_limit')) continue;
		
		$news_id = $newsArticle[0];
		$news_title = $newsArticle[1];
		$news_author = $newsArticle[2];
		$news_date = $newsArticle[3];
		$news_comments = $newsArticle[4];
		$news_url = __BASE_URL__.'news/'.Encode_id($news_id).'/';
		$loadNewsCache = $News->LoadCachedNews($news_id);
		
		echo '';
			echo '<div id="post_wrapper">
        				<div id="header">';
				echo '<li>';
					echo '<div class="info">
                  			<h2><a>'.$news_title.'</a></h2>';
                 echo '<div class="date_n_author">'.date("F j, Y",$news_date).'        , by ' . $news_author . '</div>';
					//var_dump($showSingleNews);
                 echo '</div>';
                 echo '<div id="body">';
					echo ($showSingleNews) ? '<p>'.$loadNewsCache.'</p>' : '<p>'.strip_tags(substr($loadNewsCache, 0, 300)).'...</p> <a href="'.$news_url.'"><br /><span class="label label-danger">Read more</span></a>';
					 //echo $loadNewsCache;
				 echo '</div>';
					echo '</div><div class="clear"> </div>';
				echo '<div class="col-xs-6 nopadding">';
					if(ranconfig('news_enable_like_button')) echo '<div class="fb-like" data-href="'.$news_url.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>';
				echo '</div>';

				
		# facebook comments
		if($showSingleNews && $news_comments && ranconfig('news_enable_comment_system')) {
			echo '<div class="fb-comments" data-href="'.$news_url.'" data-width="560" data-numposts="10"></div>';
		}
		echo ' </li>';
					echo '</div><div class="clear"></div>';
		
		
		$i++;
	}
}

function templateBuildRegister(){


//echo '<h3>'.lang('module_titles_txt_1',true).'</h3><hr>';
	echo '<div id="post_wrapper">';
		echo '<div class="alignLeft"><form action="" method="post">';
				echo '<label>'.lang('register_txt_1',true).'</label>';
					echo '<input type="text" class="form-control" id="Registration1" name="Register_user" required>';
					echo '<span id="helpBlock" class="help-block">'.lang('register_txt_6',true).'</span>';
				echo '<label>'.lang('register_txt_2',true).'</label>';
					echo '<input type="password"  class="form-control" id="Registration2" name="Register_pwd" required>';
					echo '<span id="helpBlock" class="help-block">'.lang('register_txt_7',true).'</span>';
				echo '<label>'.lang('register_txt_3',true).'</label>';
					echo '<input type="password" class="form-control" id="Registration3" name="Register_pwdc" required>';
					echo '<span id="helpBlock" class="help-block">'.lang('register_txt_8',true).'</span>';
				echo '<label>'.lang('register_txt_4',true).'</label>';
					echo '<input type="text" class="form-control" id="Registration4" name="Register_email" required>';
					echo '<span id="helpBlock" class="help-block">'.lang('register_txt_9',true).'</span>';
					
					if(ranconfig('register_enable_recaptcha')) {
						# recaptcha v2
						echo '<div class="form-group">';
							echo '<div class="col-sm-offset-4 col-sm-8">';
								echo '<div class="g-recaptcha" data-sitekey="'.ranconfig('register_recaptcha_public_key').'"></div>';
							echo '</div>';
						echo '</div>';
						echo '<script src=\'https://www.google.com/recaptcha/api.js\'></script>';
					}
			
					echo '<br /><br />';
					echo langf('register_txt_10', array(__BASE_URL__.'tos'));
					echo '<br /><br />';
					echo '<button type="submit" name="Register_submit" value="submit" class="btn btn-primary">'.lang('register_txt_5',true).'</button>';
		echo '</form></div>';
	echo '</div>';

}

function templateBuildDownload(){


	$downloads = ranconfig('downloads');
			// LOAD DOWNLOADS CACHE
			$downloadsCACHE = LoadCacheData('downloads.cache');
			
			// Build Data Arrays
			$downloadCLIENTS = array();
			$downloadPATCHES = array();
			$downloadTOOLS = array();
			
			foreach($downloadsCACHE as $key => $tempDownloadsData) {
				if($key > 0) {
					switch($tempDownloadsData[5]) {
						case 1:
							$downloadCLIENTS[] = $tempDownloadsData;
						break;
						case 2:
							$downloadPATCHES[] = $tempDownloadsData;
						break;
						case 3:
							$downloadTOOLS[] = $tempDownloadsData;
						break;
					}
				}
			}
			
			function showDownloads($downloadData,$title="") {
				echo '<div id="post_wrapper">';
				if(is_array($downloadData)) {
					
						echo '<table class="table">
							<tr>
								<th>'.$title.'</th>
							</tr>
							<tr>
								<td>'.lang('downloads_txt_1',true).'</td>
								<td>'.lang('downloads_txt_2',true).'</td>
								<td>'.lang('downloads_txt_3',true).'</td>
								<td></td>
							</tr>';
							foreach($downloadData as $thisDownload) {
								echo '
								<tr>
									<td>'.$thisDownload[1].'</td>
									<td>'.$thisDownload[4].'</td>
									<td>'.round($thisDownload[3],2).' '.lang('downloads_txt_4',true).'</td>
									<td><a href="'.$thisDownload[2].'" target="_blank"><button class="btn btn-primary" title="'.lang('downloads_txt_5',true).'" alt="'.lang('downloads_txt_5',true).'">Download</button></a></td>
								</tr>';
							}
						echo '
						</table>';
					
				}
				echo '</div>';
			}
			
			
			// CLIENT DOWNLOADS
			if(ranconfig('show_client_downloads')) {
				showDownloads($downloadCLIENTS,lang('downloads_txt_6',true));
			}
			
			// PATCH DOWNLOADS
			if(ranconfig('show_patch_downloads')) {
				showDownloads($downloadPATCHES,lang('downloads_txt_7',true));
			}
			
			// TOOL DOWNLOADS
			if(ranconfig('show_tool_downloads')) {
				showDownloads($downloadTOOLS,lang('downloads_txt_8',true));
			}
}

function templateBuildDonation(){

	
	
			echo '<div id="post_wrapper">';
				echo '<div class="glenox">';
			echo '<a href="'.__BASE_URL__.'donation/paypal/" class="thumbnail"><img src="'.__PATH_TEMPLATE_IMG__.'donation/paypal.jpg"></a>';
			echo '				';
			echo '<a href="'.__BASE_URL__.'donation/remittance/" class="thumbnail"><img src="'.__PATH_TEMPLATE_IMG__.'donation/remittance.jpg"></a>';
		/*echo '<div class="col-xs-4">';
			echo '<a href="'.__BASE_URL__.'donation/paymentwall/" class="thumbnail"><img src="'.__PATH_TEMPLATE_IMG__.'donation/paymentwall.jpg"></a>';
		echo '</div>';
		*/
				echo '</div>';
	echo '</div>';
}

function templateBuildMyAccount(){

echo '<div class="header"><h2>'.lang('module_titles_txt_4',true).'</h2></div>';
		// Retrieve Account Information
		//$account = new template();
		//$Character = new Character();
		$accountInfo = glenox::accountInformation($_SESSION['userid']);

		# account online status
		$onlineStatus = (glenox::accountOnline($_SESSION['userid']==1) ? '<span class="label label-success">'.lang('myaccount_txt_9',true).'</span>' : '<span class="label label-danger">'.lang('myaccount_txt_10',true).'</span>');
		/* account online status */
				
		# account status
		$accountStatus = ($accountInfo['UserBlock'] == 1 ? '<span class="label label-danger">'.lang('myaccount_txt_8',true).'</span>' : '<span class="label label-default">'.lang('myaccount_txt_7',true).'</span>');

		$encrypPin = ($accountInfo['isPinChange'] == 1) ? '**********':''.$accountInfo['Upass'].'';

		# characters info
		
		$AccountCharacters = glenox::AccountCharacter($_SESSION['userid']);
		echo '<div id="post_wrapper">';
			echo '<table class="table">';
					echo '<tr>';
						echo '<td>'.lang('myaccount_txt_1',true).'</td>';
						echo '<td>'.$accountStatus.'</td>';
					echo '</tr>';
					
					echo '<tr>';
						echo '<td>'.lang('myaccount_txt_2',true).'</td>';
						echo '<td>'.$accountInfo['UserID'].'</td>';
					echo '</tr>';
					
					echo '<tr>';
						echo '<td>'.lang('myaccount_txt_3',true).'</td>';
						echo '<td>'.encrypt_email($accountInfo['UserEmail']).' <a href="'.__BASE_URL__.'usercp/myemail/"><button class="btn btn-primary">'.lang('myaccount_txt_6',true).'</button></a></td>';
					echo '</tr>';
					
					echo '<tr>';
						echo '<td>'.lang('myaccount_txt_4',true).'</td>';
						echo '<td>********** <a href="'.__BASE_URL__.'usercp/mypassword/"><button class="btn btn-primary">'.lang('myaccount_txt_6',true).'</button></a></td>';
					echo '</tr>';
					
					echo '<tr>';
						echo '<td>'.lang('myaccount_txt_5',true).'</td>';
						echo '<td>'.$onlineStatus.'</td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td>'.lang('myaccount_txt_11',true).'</td>';
						echo '<td>'.$encrypPin.'</td>';
					echo '</tr>';

					echo '<tr valign="top">';
						echo '<td>'.lang('myaccount_txt_15',true).'</td>';
						echo '<td>';
							if(is_array($AccountCharacters)) {
								foreach($AccountCharacters as $char) {

									echo '<a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($char["ChaNum"]).'">'.$char["ChaName"].'</a><br />';
								}
							} else {
								lang('myaccount_txt_16', false);
							}
						echo '</td>';
					echo '</tr>';
				echo '</table>';
			echo '</div>';
}


function templateBuildChangeSchool(){

	echo '<div class="header"><h2>'.lang('module_titles_txt_29',true).'</h2></div>';

				$SchoolInfo = new Changeschool();

				if(glenox::GetAllChar($_SESSION['userid'])){
			
				
				$vp = ranconfig('costvp');
				$ep = ranconfig('costep');


				$UserInfo = $SchoolInfo->checkActiveSession($_SESSION['userid'],session_id());

					try {
							if(check_value($_POST['submit'])) {

								$Status = glenox::CheckStatus(Decode_id($_POST['ChaInfo']));

								if($Status['ChaOnline'] != 1){
										$SchoolInfo->Proccess(Decode_id($_POST['ChaInfo']),Decode_id($_POST['SkulInfo']),$_SESSION['userid']);
								} else {
									message('error', lang('error_68',true));
								}

								
							}
						} catch (Exception $ex) {

						message('error', $ex->getMessage());

					}

				echo '<div id="post_wrapper">';
					echo '<div class="changeschool">';

						echo '<form action="" method="post">';
						if  ($vp != 0){
						echo'You need <b>'.$vp.'</b> vpoints to change school<br />';
						}
						if  ($ep != 0){
						echo'You need <b>'.$ep.'</b> epoints to change school<br />';
						}
						echo'
						
						<select name="ChaInfo">
						';
						foreach ($UserInfo as $SInfo) {
							
							echo '<option value='.Encode_id($SInfo['ChaNum']).'>'.$SInfo['ChaName'].'</option>';
							//echo $SInfo['ChaName'];
						}

						echo '</select>

						<div class="radio"><label><input type="radio" name="SkulInfo" value="'.Encode_id(0).'">SG</label></div>
						<div class="radio"><label><input type="radio" name="SkulInfo" value="'.Encode_id(1).'">MP</label></div>
						<div class="radio"><label><input type="radio" name="SkulInfo" value="'.Encode_id(2).'">PHNX</label></div>
							<button name="submit" value="submit" class="btn btn-success">'.lang('button_txt_2',true).'</button>
						</form>
					</div>
				</div>
						';
			} else {
				message('warning',lang('error_46',true));
			}
}

function templateBuileChangePass(){

	echo '<div id="post_wrapper">
			<div class="changepass">
			<form method="post">
				
					<div class="form-group form-group-sm">
						<label class="col-sm-4 control-label" for="formGroupInputSmall">Current Password</label>
						<div class="col-sm-4">
							<input class="form-control" type="password" name="password" maxlength="20" autofocus/>
						</div>
					</div>
					<br />
					<div class="form-group form-group-sm">
						<label class="col-sm-4 control-label" for="formGroupInputSmall">Pin Code</label>
						<div class="col-sm-4">
							<input class="form-control" type="text" name="pincode" maxlength="20" autofocus/>
						</div>
					</div>
					
					<br />
					<div class="form-group form-group-sm">
						<label class="col-sm-4 control-label" for="formGroupInputSmall">'.lang('changepassword_txt_2',true).'</label>
						<div class="col-sm-4">
							<input class="form-control" type="password" name="new_password" maxlength="20" />	
						</div>
					</div>
					<br />
					<div class="form-group form-group-sm">
						<label class="col-sm-4 control-label" for="formGroupInputSmall">'.lang('changepassword_txt_3',true).'</label>
						<div class="col-sm-4">
							<input class="form-control" type="password" name="confirm_new_password" maxlength="20" />	
						</div>
					</div>
					<tr>
						<br /><br />
						<td><button name="submit" value="submit" class="btn btn-success" >Change Password</button></td>
					</tr>
				
			</form></div></div>';
}

function templateBuildForgotPass(){

	echo '<div id="post_wrapper">';
		echo '<div class="forgotpass">';
			echo '<form class="form-horizontal" action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="Email" class="col-sm-4 control-label">Username</label>';
					echo '<div class="col-sm-8">';
						echo '<input type="text" class="form-control" id="UserID" name="UserID_current" required>';
					echo '</div>';
					echo '<label for="Email" class="col-sm-4 control-label">Email</label>';
					echo '<div class="col-sm-8">';
						echo '<input type="text" class="form-control" id="Email" name="Email_current" required>';
					echo '</div>';
				echo '</div>';
				echo '<br /><br />';
				echo '<div class="form-group">';
					echo '<div class="col-sm-offset-4 col-sm-8">';
						echo '<button type="submit" name="Email_submit" value="submit" class="btn btn-primary">Submit</button>';
					echo '</div>';
				echo '</div>';
			echo '</form>';

		echo '</div>';
	echo '</div>';

}

function templateBuildChangeEmail(){

	echo '<div id="post_wrapper">';
		echo '<div class="changeemail">';
				echo '<form class="form-horizontal" action="" method="post">';
					# pincode :)
					echo '<div class="form-group">';
						echo '<label for="Pin" class="col-sm-4 control-label">'.lang('changemail_txt_4',true).'</label>';
						echo '<div class="col-sm-8">';
							echo '<input type="text" class="form-control" id="Pin" name="PinCode">';
						echo '</div>';
					echo '</div>';
					# email
					echo '<div class="form-group">';
						echo '<label for="Email" class="col-sm-4 control-label">'.lang('changemail_txt_1',true).'</label>';
						echo '<div class="col-sm-8">';
							echo '<input type="text" class="form-control" id="Email" name="Email_newemail">';
						echo '</div>';
					echo '</div>';
					echo '<br /><br />';
					echo '<div class="form-group">';
						echo '<div class="col-sm-offset-4 col-sm-8">';
							echo '<button type="submit" name="Email_submit" value="submit" class="btn btn-primary">'.lang('changemail_txt_1',true).'</button>';
						echo '</div>';
					echo '</div>';
				echo '</form>';
		echo '</div>';
	echo '</div>';
}

function templateBuildGameTime($Minutes,$hours,$MinutesTotal,$ups,$gt_m,$gt_p){


	echo '<div id="post_wrapper">
		<div class="gametime">
				<center>
				<b>
					Collected Online Time : '.$hours.' Hour(s) '.$MinutesTotal.' Minute(s)<br>
					At this time you will get ( '.$ups.' ) V-Points<br>
					<br>'.$gt_m.' Minute(s) = '.$gt_p.' V-Point(s)
				</b>
				<br /><br />
				<form action="" method="post">
				<button name="submit" value="submit" class="btn btn-success"><span class="button-left"><span class="button-right">'.lang('button_txt_1',true).'</span></span></button>
				</form>
				</center></div></div>';
}

function templateBuildRankingsALL(){

		

		
		$ranking_data = LoadCacheData('rankings_all.cache');

		//rankings::_top10();
		
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildName.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsBrawler(){

		
		$ranking_data = LoadCacheData('rankings_brawler.cache');
		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsSwords(){

		

		$ranking_data = LoadCacheData('rankings_swords.cache');

		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsArcher(){

		

		$ranking_data = LoadCacheData('rankings_archer.cache');
		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsShaman(){


		$ranking_data = LoadCacheData('rankings_shaman.cache');
		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsGunner(){


		$ranking_data = LoadCacheData('rankings_gunner.cache');
		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsEx3m(){


		$ranking_data = LoadCacheData('rankings_ex3m.cache');
		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsAssassin(){


		$ranking_data = LoadCacheData('rankings_assassin.cache');
		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsMagician(){


		$ranking_data = LoadCacheData('rankings_magician.cache');
		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}

function templateBuildRankingsShaper(){


		$ranking_data = LoadCacheData('rankings_shaper.cache');
		//rankings::_top10();
		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';
		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		if (ranconfig('rankings_show_reborn')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_22',true).'</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		if (ranconfig('rankings_show_kills')){
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_14',true).'</td>';
		}
		
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_21',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[5]);
			$schoolImg = glenox::CharSchoolImages($rdata[5]);
			
			//$GuildLogo = $Character->BuildGuildLogo($rdata[0])? $Character->BuildGuildLogo($rdata[0]) : "No Guild";
			$GuildName = glenox::BuildGuildName($rdata[0])? glenox::BuildGuildName($rdata[0]) : "No Guild";


			//echo $GuildLogo;
			//var_dump($GuildLogo);
			$class = glenox::GenerateCharacterClass($rdata[3]);

			//$GuildLogo = $Rankings->DoBinaryToImage();
			
			$online = ($rdata[4])? '<span class="label label-success"> Online </span>' : '<span class="label label-danger"> Offline </span>';
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td>'.$class.'</td>';
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[9]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td>'.$GuildLogo.'</td>'; #Guild Logo
				if (ranconfig('rankings_show_reborn')){
					echo '<td>'.$rdata[6].'</td>';
				}
				
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				if (ranconfig('rankings_show_kills')){
					echo '<td>'.$rdata[7].'</td>';
				}
				
				echo '<td>'.$online.'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}
function templateBuildRankingsRich(){

		$ranking_data = LoadCacheData('rankings_toprich.cache');

		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';

		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';

		echo '<td style="font-weight:bold;">'.lang('rankings_txt_38',true).'</td>';


		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[2]).'">'.$rdata[0].'</a></td>';
				
				echo '<td>'.number_format($rdata[1]).'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}
function templateBuildRankingsLevel(){


		$ranking_data = LoadCacheData('rankings_toplevel.cache');

		echo '<div id="post_wrapper">';
		echo '<center><br />';
			rankings::rankingsMenu();
		echo '</center>';

		echo '<table class="table table-hover">';
		echo '<tr>';
		if(ranconfig('rankings_show_place_number')) {
			echo '<td style="font-weight:bold;">No.</td>';
		}
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_12',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
		echo '<td style="font-weight:bold;">'.lang('rankings_txt_39',true).'</td>';

		echo '</tr>';
		$i = 0;
		foreach($ranking_data as $rdata) {
			$school = glenox::CharacterSchool($rdata[3]);
			$schoolImg = glenox::CharSchoolImages($rdata[3]);
			
			if($i>=1) {
				echo '<tr>';
				if(ranconfig('rankings_show_place_number')) {
					echo '<td>'.$i.'</td>';
				}
				echo '<td><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($rdata[0]).'">'.$rdata[1].'</a></td>';
				echo '<td>'.$rdata[2].'</td>';
				echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
				echo '<td>'.$rdata[4].'</td>';
				echo '</tr>';
			}
			$i++;
		}
		echo '</table>';
		
		if(ranconfig('rankings_show_date')) {
			echo '<div class="rankings-update-time">';
			echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
			echo '</div>';
		}
		echo '</div>';

}
function templateBuildRankingsOnline(){


	$ranking_data = LoadCacheData('rankings_toponline.cache');

	echo '<div id="post_wrapper">';
	echo '<center><br />';
		rankings::rankingsMenu();
	echo '</center>';

	echo '<table class="table table-hover">';
	echo '<tr>';
	if(ranconfig('rankings_show_place_number')) {
		echo '<td style="font-weight:bold;">No.</td>';
	}
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_13',true).'</td>';
	echo '<td style="font-weight:bold;">Online Time</td>';

	echo '</tr>';
	$i = 0;
	foreach($ranking_data as $rdata) {
		$school = glenox::CharacterSchool($rdata[2]);
		$schoolImg = glenox::CharSchoolImages($rdata[2]);

		$hours = floor($rdata[3] / 60);

		$MinutesTotal = $rdata[3]-(60*$hours);
		//
		$day = floor(($rdata[3] / 60)/24);
		if ($hours >24){
			$hoursTotal = $hours -24;	
		} else {
			$hoursTotal = $hours;
		}
		
		
		if($i>=1) {
			echo '<tr>';
			if(ranconfig('rankings_show_place_number')) {
				echo '<td>'.$i.'</td>';
			}
			echo '<td>'.$rdata[1].'</td>';
			echo '<td><img src="'.$schoolImg.'" title="'.$school.'" alt="'.$school.'"/></td>';
			echo '<td>'.$day.' Day(s) '.$hoursTotal.' Hour(s) '.$MinutesTotal.' Minute(s)</td>';
			echo '</tr>';
		}
		$i++;
	}
	echo '</table>';
	
	if(ranconfig('rankings_show_date')) {
		echo '<div class="rankings-update-time">';
		echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
		echo '</div>';
	}
	echo '</div>';

}
function templateBuildEshop($itemshop){

	echo '<div id="post_wrapper">';
		echo '<br /><br />';
			echo '<center><a href="'.__BASE_URL__.'vshop?ctg=all">ALL</a> | 
						  <a href="'.__BASE_URL__.'vshop?ctg=pet">PET</a> |
						  <a href="'.__BASE_URL__.'vshop?ctg=potion">POTION</a> |
						  <a href="'.__BASE_URL__.'vshop?ctg=other">OTHER</a> |
						  <a href="'.__BASE_URL__.'vshop?ctg=acce">ACCESSORIES</a> |
						  <a href="'.__BASE_URL__.'vshop?ctg=refines">REFINES</a></center>';

		if(check_value($_GET['ctg'])) {
				//var_dump($account);
				echo '<table class="table table-hover">';
				echo '<tr>';
				echo '<td style="font-weight:bold;">No.</td>';
				echo '<td style="font-weight:bold;">Item Name</td>';
				echo '<td style="font-weight:bold;">Price</td>';
				echo '<td style="font-weight:bold;">Stock</td>';
				echo '<td style="font-weight:bold;">Action</td>';
				echo '</tr>';
				$i = 0;
				$result = $itemshop->getItem($_GET['ctg']);
				foreach ($result as $IShop) {

				//var_dump(check_value($IShop['ItemImage']));
				$ItemImages = (check_value($IShop['ItemImage'])) ? $IShop['ItemImage'] : __PATH_TEMPLATE_IMG__."NoCache.png";
				
				$i++;
				if($i>=0) {
					echo '<tr>';
					echo '<td>'.$i.'</td>';
					echo '<td><div onmouseover="Tip(\'<img src=&quot;'.$ItemImages.'&quot;>\')" onmouseout="UnTip()">
								'.$IShop['ItemName'].'
								</div>
						</td>';
					echo '<td>EP-'.$IShop['ItemPrice'].'</td>';
					echo '<td>'.$IShop['Itemstock'].'</td>';
					echo '<form class="form-horizontal" action="" method="post">';	
					echo '<td>
							<input type=hidden name="ItemNum" value="'.Encode_id($IShop['ProductNum']).'">
							<button name="submit" value="submit" class="btn btn-success" onclick="return confirm(\'You want to buy this Item?\')" ><span class="button-left"><span class="button-right">Buy</span></span></button>
						</td>';
					echo '</form>';
					echo '</tr>';

				}
				
				}
				
				echo '</table>';
		} else {
			//message('error', 'Failed to view itemshop');
			redirect(2,'vshop?ctg=all',1);
	}
	echo '</div>';
}

function templateBuildVshop($itemshop){

	echo '<div id="post_wrapper">';
		echo '<br /><br />';
			echo '<center><a href="'.__BASE_URL__.'vshop?ctg=all">ALL</a> | 
						  <a href="'.__BASE_URL__.'vshop?ctg=pet">PET</a> |
						  <a href="'.__BASE_URL__.'vshop?ctg=potion">POTION</a> |
						  <a href="'.__BASE_URL__.'vshop?ctg=other">OTHER</a> |
						  <a href="'.__BASE_URL__.'vshop?ctg=acce">ACCESSORIES</a> |
						  <a href="'.__BASE_URL__.'vshop?ctg=refines">REFINES</a></center>';

		if(check_value($_GET['ctg'])) {
				
				echo '<table class="table table-hover">';
				echo '<tr>';
				echo '<td style="font-weight:bold;">No.</td>';
				echo '<td style="font-weight:bold;">Item Name</td>';
				echo '<td style="font-weight:bold;">Price</td>';
				echo '<td style="font-weight:bold;">Stock</td>';
				echo '<td style="font-weight:bold;">Action</td>';
				echo '</tr>';
				$i = 0;
				$result = $itemshop->getItem($_GET['ctg']);
				foreach ($result as $IShop) {

				//var_dump(check_value($IShop['ItemImage']));
				$ItemImages = (check_value($IShop['ItemImage'])) ? $IShop['ItemImage'] : __PATH_TEMPLATE_IMG__."NoCache.png";
				
				$i++;
				if($i>=0) {
					echo '<tr>';
					echo '<td>'.$i.'</td>';
					echo '<td><div onmouseover="Tip(\'<img src=&quot;'.$ItemImages.'&quot;>\')" onmouseout="UnTip()">
								'.$IShop['ItemName'].'
								</div>
						</td>';
					echo '<td>VP-'.$IShop['ItemPrice'].'</td>';
					echo '<td>'.$IShop['Itemstock'].'</td>';
					echo '<form class="form-horizontal" action="" method="post">';	
					echo '<td>
							<input type=hidden name="ItemNum" value="'.Encode_id($IShop['ProductNum']).'">
							<button name="submit" value="submit" class="btn btn-success" onclick="return confirm(\'You want to buy this Item?\')" ><span class="button-left"><span class="button-right">Buy</span></span></button>
						</td>';
					echo '</form>';
					echo '</tr>';

				}
				
				}
				
				echo '</table>';
		} else {
			//message('error', 'Failed to view itemshop');
			redirect(2,'vshop?ctg=all',1);
	}
	echo '</div>';
}

function templateBuildVPtoEP($convert,$rate){



			echo '<div id="post_wrapper">
				<div class="vptoep">
					VPoints '.$rate.' : 1 EPoints
					<br /><br />
					<form  class="form-horizontal" action="" method="post">
					<div class="form-group form-group-sm">
								V-Points : <input class="form-control" type="number" min="0" name="vp" maxlength="4" placeholder="0"/>	
						</div>
					<br />
					<button name="submit" value="submit" class="btn btn-success"><span class="button-left"><span class="button-right"> Convert </span></span></button>
					</form>
				</div>
				</div>';
}

function templateBuildEPtoVP($convert,$rate){


echo '<div id="post_wrapper">
	<div class="eptovp">
		EPoints 1 : '.$rate.' VPoints
		<br /><br />
		<form  class="form-horizontal" action="" method="post">
		<div class="form-group form-group-sm">

					E-Points : <input class="form-control" type="number" min="0" name="ep" maxlength="4" placeholder="0"/>	
			</div>
		<br />
		<button name="submit" value="submit" class="btn btn-success"><span class="button-left"><span class="button-right"> Convert </span></span></button>
		</form>
	</div>
	</div>';

}

function templateBuildtopup(){

echo '<div id="post_wrapper">
	<div class="topup">
			<form class="form-horizontal" action="" method="post">
				
					<div class="form-group form-group-sm">
						<label class="col-sm-4 control-label" for="formGroupInputSmall">Pin</label>
						<div class="col-sm-4">
							<input class="form-control" type="text" name="pin" maxlength="20" placeholder="Topup pin" autofocus/>
						</div>
					</div>
					<br />
					<div class="form-group form-group-sm">
						<label class="col-sm-4 control-label" for="formGroupInputSmall">Code</label>
						<div class="col-sm-4">
							<input class="form-control" type="text" name="code" maxlength="20" placeholder="Topup code" autofocus/>
						</div>
					</div>';
					echo '<br /><br />';
					if(ranconfig('topup_enable_recaptcha')) {
						# recaptcha v2
						echo '<div class="form-group">';
							echo '<div class="col-sm-offset-4 col-sm-4">';
								echo '<div class="g-recaptcha" data-sitekey="'.ranconfig('topup_recaptcha_public_key').'"></div>';
							echo '</div>';
						echo '</div>';
						echo '<script src=\'https://www.google.com/recaptcha/api.js\'></script>';
					}
					
					echo '<br />
				
					<tr>
						<td></td>
						<td><button name="submit" value="submit" class="btn btn-success" ><span class="button-left"><span class="button-right"> Submit</span></span></button></td>
					</tr>
				
			</form></div>
	</div>';

}

function templateBuildVote(){

echo '<div id="post_wrapper">';
	echo '<br /><br />';
	if(ranconfig('char_limit')){
				echo '<center><label>Character level to vote is '.ranconfig('char_limit').'</label></center>';
			}

	echo '<table class="table">
	<thead><tr><th></th><th></th><th></th></tr></thead>
		
				';
					
					$vote_sites = vote::retrieveVotesites();
					if(is_array($vote_sites)) {
						foreach($vote_sites as $thisVotesite) {
							echo '<form action="" method="post">';
							echo '<input type="hidden" name="voting_site_id" value="'.$thisVotesite['votesite_id'].'"/>';
							echo '<tr>';
							echo '<center><td>'.$thisVotesite['votesite_title'].'</td><td>'.$thisVotesite['votesite_reward'].'</td><td><button name="submit" value="submit" class="btn btn-success"><span class="button-left"><span class="button-right">'.lang('vfc_txt_3',true).'</span></span></button></td></center>';
							echo '</tr>';
							echo '</form>';
						}
					}
					
			echo '</table>';
	echo '</div>';
}

function templateBuildPlayer($cData,$custom){

loadModuleConfigs('rankings');

echo '<div id="post_wrapper">';
	echo '<br /><br />';
	echo '<div class="profiles_player_card '.$custom['character_class'][$cData[2]][1].'">';
							echo '<div class="profiles_player_content">';
								echo '<table class="profiles_player_table">';
									echo '<tr>';
										echo '<td class="cname">'.$cData[1].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td class="cclass">'.$custom['character_class'][$cData[2]][0].'</td>';
									echo '</tr>';
								echo '</table>';
								
								# info table
								echo '<table class="profiles_player_table profiles_player_table_info">';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_7',true).'</td>';
										echo '<td>'.$cData[3].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_22',true).'</td>';
										echo '<td>'.$custom['character_school'][$cData[16]][0].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_23',true).'</td>';
										echo '<td><img src="'.__PATH_TEMPLATE_IMG__.'school/'.$custom['character_school'][$cData[16]][1].'" width="16px" height="11px"/></td>';
									echo '</tr>';
									if(ranconfig('rankings_show_reborn')){
										echo '<tr>';
											echo '<td>'.lang('profiles_txt_8',true).'</td>';
											echo '<td>'.$cData[4].'</td>';
										echo '</tr>';
									}	
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_9',true).'</td>';
										echo '<td>'.$cData[11].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_9',true).'</td>';
										echo '<td>'.$cData[12].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_10',true).'</td>';
										echo '<td>'.$cData[5].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_11',true).'</td>';
										echo '<td>'.$cData[9].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_12',true).'</td>';
										echo '<td>'.$cData[8].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_13',true).'</td>';
										echo '<td>'.$cData[7].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_14',true).'</td>';
										echo '<td>'.$cData[6].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_15',true).'</td>';
										echo '<td>'.$cData[10].'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_16',true).'</td>';

										if($cData[14]) {
											echo '<td><a href="'.__BASE_URL__.'profile/guild/?req='.Encode_id($cData[14]).'" target="_blank">'.$cData[13].'</a></td>';
										} else {
											echo '<td class="isoffline">'.lang('profiles_txt_21',true).'</td>';
										}
									echo '</tr>';
									echo '<tr>';
										echo '<td>'.lang('profiles_txt_17',true).'</td>';
										if($cData[15]) {
											echo '<td class="isonline">'.lang('profiles_txt_18',true).'</td>';
										} else {
											echo '<td class="isoffline">'.lang('profiles_txt_19',true).'</td>';
										}
									echo '</tr>';
								echo '</table>';
							echo '</div>';
						echo '</div>';

			echo '</div>';
}

function templateBuildGuild($displayData,$guildMembers){

echo '<div id="post_wrapper">';
	echo '<br /><br />';
	echo '<table class="table">';
	echo '<tr>';
		echo '<td class="gname" colspan="3">'.$displayData['gname'].''.$displayData['glogo'].'</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td rowspan="4"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td>'.lang('profiles_txt_3',true).'</td>';
		echo '<td>'.$displayData['gmaster'].'</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td>'.lang('profiles_txt_4',true).'</td>';
		echo '<td>'.$displayData['gscore'].'</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td>'.lang('profiles_txt_5',true).'</td>';
		echo '<td>'.$displayData['gmembers'].'</td>';
	echo '</tr>';
echo '</table>';

echo '<table class="table">';
	echo '<tr>';
		echo '<td class="gmembs">'.lang('profiles_txt_6',true).'</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td class="memblist">';
		foreach($guildMembers as $gMember) {
			//var_dump($gMember['ChaName']);

				  if($gMember['ChaName'] != $displayData['gmaster']) {
						//echo '<div><a href="'.__BASE_URL__.'profile/player/?req='.$gMember.'" target="_blank">'.$gMember['ChaName'].'</a></div>';
						echo '<div><a href="'.__BASE_URL__.'profile/player/?req='.Encode_id($gMember['ChaNum']).'" target="_blank">'.$gMember['ChaName'].'</a></div>';
					}
		}
		echo '</td>';
	echo '</tr>';
echo '</table>';
echo '</div>';

}

function templateBuildPaypal(){

	echo '<div id="post_wrapper">';
	echo '<br /><br />';
	echo '	
			<div class="table">
			<img src="https://www.paypalobjects.com/webstatic/mktg/logo-center/PP_Acceptance_Marks_for_LogoCenter_266x142.png" />
				<div class="paypal-gateway-content">
					<div class="paypal-gateway-logo"></div>
					<div class="paypal-gateway-form">
						<div>';
						
							if(ranconfig('paypal_enable_sandbox')) {
								echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">';
							} else {
								echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
							}
							
							$order_id = md5(time());
							
							echo '
							<input type="hidden" name="cmd" value="_xclick" />
							<input type="hidden" name="business" value="'.ranconfig('paypal_email').'" />
							<input type="hidden" name="item_name" value="'.ranconfig('paypal_title').'" />
							<input type="hidden" name="item_number" value="'.$order_id.'" />
							<input type="hidden" name="currency_code" value="'.ranconfig('paypal_currency').'" />
							P <select name="amount" id="amount">
								<option value="0">Select amount</option>
								<option value="300">300</option>
								<option value="500">500</option>
								<option value="1000">1000</option>
								<option value="1500">1500</option>
							</select>'.ranconfig('paypal_currency').' = <span id="result">0</span> E-Points
							
						</div>
					</div>
					<br />
					<div class="paypal-gateway-continue">
						<input type="hidden" name="no_shipping" value="1" />
						<input type="hidden" name="shipping" value="0.00" />
						<input type="hidden" name="return" value="'.ranconfig('paypal_return_url').'" />
						<input type="hidden" name="cancel_return" value="'.ranconfig('paypal_return_url').'" />
						<input type="hidden" name="notify_url" value="'.ranconfig('paypal_notify_url').'" />
						<input type="hidden" name="custom" value="'.Encode($_SESSION['userid']).'" />
						<input type="hidden" name="no_note" value="1" />
						<input type="hidden" name="tax" value="0.00" />
						<input type="submit" class="btn btn-primary" name="submit" value="Buy Epoints" />
						</form>
					</div>
				</div>
			</div>
			</div>';
}