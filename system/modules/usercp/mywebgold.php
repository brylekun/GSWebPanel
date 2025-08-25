<?php
/**
 * Ran Panel
 * https://www.facebook.com/Parad0x25
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

(!isLoggedIn()) ? redirect(1,'login') : null;
echo '<div class="header"><h2>My Web Gold</h2></div>';			
			

		if(plugins::gotEnabledPlugins()) {

           
            if(check_value($_POST['Gold_submit'])) {	

                
				try {
                    
					plugin_WMoney::PostMoney($_SESSION['userid'],$_POST['GoldText'],$_POST['EPText'],$_SERVER['REMOTE_ADDR']);

				} catch (Exception $ex ) {

					message('error', $ex->getMessage());
					redirect(2,'usercp/myaccount/',3);
			
				}

            }

            if(check_value($_POST['claim_submit'])) {

                try {

					plugin_WMoney::ClaimMoney($_SESSION['userid'],Decode_id($_POST['GoldNum']));

                } catch (Exception $ex ){
                    message('error', $ex->getMessage());
					redirect(2,'usercp/myaccount/',3);
                }
            }
            
			$getUserMoney = plugin_WMoney::GetUserMoney($_SESSION['userid']);

            echo '<div id="post_wrapper">';
            echo '<div class="changeschool">';
			echo '<h3>Note 1: value of 1 = 1M Gold!</h3>';
			echo '<h3>Note 2: Your Gold is : '.number_format($getUserMoney['UserMoney']).'</h3>';
            echo '<form method="post">';
            echo '<br /><br />';
            echo '<label>Your desire Gold : </label>';
            echo '<input type="number" class="form-control" id="Gold" min="1" Max="10000" name="GoldText" value="1" required>';
            echo '<br /><br />';
            echo '<label>How much :</label>';
            echo '<input type="number" class="form-control" id="EP" min="1" Max="10000" name="EPText" value="1" required>';
            echo '<br /><br />';
            echo '<button type="submit" name="Gold_submit" value="submit" class="btn btn-primary">Post Gold</button>';
            echo '</form>';
			
            echo '</div>';
            echo '</div>';


            $WebMoney = plugin_WMoney::GetAllMyMoney($_SESSION['userid']);

            echo '<div id="post_wrapper">';
			echo '<h1><center>My Gold Post</center></h1>';
			echo '<table id="webgold" class="display" style="width:95%">';
			  echo '<thead>';
				echo '<tr>';
				echo '<td style="font-weight:bold;">No.</td>';
				echo '<td style="font-weight:bold;">Seller Name</td>';
				echo '<td style="font-weight:bold;">Gold</td>';
				echo '<td style="font-weight:bold;">Price</td>';
				echo '<td style="font-weight:bold;">Action</td>';
				echo '</tr>';
			 echo '</thead>';
			$i = 0;
			echo '<tbody>';
			
			foreach ($WebMoney as $Money) {

			$a = plugin_WMoney::ConvertMoney($Money['UserMoney']);

			$i++;

			if($i>=0) {
			
				echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td>'.$Money['ChaName'].'</td>';
				echo '<td><a title="'.number_format($Money['UserMoney']).',000,000">'.$a.'</a></td>';
				echo '<td><a>'.number_format($Money['EPValue']).'-EP<a></td>';
				echo '<form class="form-horizontal" action="" method="post">';
                echo '<td>';
                    if ($Money['status']!=0) { 
                        echo '<span class="btn btn-default"><span class="button-left"><span class="button-right">SOLD</span></span></button>';
                    } else {
                        echo'<input type=hidden name="GoldNum" value="'.Encode_id($Money['MoneyNum']).'">
						    <button name="claim_submit" value="submit" class="btn btn-success" onclick="return confirm(\'You want to Claim this Gold?\')" ><span class="button-left"><span class="button-right">Claim</span></span></button>
					        </td>';
                    }
				
				echo '</form>';
				echo '</tr>';
				
			}
			
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';

        } else {
            message('error', lang('error_47',true));
        }