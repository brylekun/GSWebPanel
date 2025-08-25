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
echo '<div class="header"><h2>Web Money Shop</h2></div>';			
			

		if(plugins::gotEnabledPlugins()) {
			

			if(check_value($_POST['submit'])) {	
				
				try {

					plugin_WMoney::ProccessMoney($_SESSION['userid'],Decode_id($_POST['GoldNum']));

				} catch (Exception $ex ) {

					message('error', $ex->getMessage());
					redirect(2,'usercp/myaccount/',3);
			
				}

			}

			$WebMoney = plugin_WMoney::GetAllMoney();
			
			
			echo '<div id="post_wrapper">';
			echo '<br /><br />';
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
				echo '<td>
						<input type=hidden name="GoldNum" value="'.Encode_id($Money['MoneyNum']).'">
						<button name="submit" value="submit" class="btn btn-success" onclick="return confirm(\'You want to buy this Gold?\')" ><span class="button-left"><span class="button-right">Buy</span></span></button>
					</td>';
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

?>