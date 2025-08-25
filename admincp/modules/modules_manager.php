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

$Modules = array(
	'_global' => array(
		array('News','news'),
		array('Login','login'),
		array('Register','register'),
		array('Downloads','downloads'),
		
		array('Rankings','rankings'),
		array('Club war','clubwar'),
		array('Email System','email'),
		array('Profiles','profiles'),
		array('Forgot Password','forgotpassword'),
		array('Paypal','paypal'),
		array('Remittance','remittance'),
		
	),
	'_usercp' => array(
		array('My Account','myaccount'),
		array('Change Password','mypassword'),
		array('Change Email','myemail'),
		array('Change School','changeschool'),
		array('Convert Gametime','gametime'),
		array('Convert EP to VP','eptovp'),
		array('Convert VP to EP','vptoep'),
		array('Vote','vote'),
		array('Account fix','accfix'),
		array('EP-Shop','eshop'),
		array('VP-Shop','vshop'),
		

	),
);
echo '<section class="content-header"><h1> Module Manager </h1></section>';
echo '<section class="content">';
echo '	<div class="row">';
echo '		<div class="col-xs-12">';
	
	echo '<div class="col-md-3">';
	  echo '<div class="box">';
	    echo '<div class="box-header with-border">';
           echo '<h3 class="box-title">Global</h3>';
        echo '</div>';

		echo '<div class="box-body">';
		  echo '<table class="table table-bordered">';
			echo '<tbody><tr>';
			echo '<th style="width: 10px">#</th>';
            echo '<th>Module</th>';
            echo '</tr>';
            $i=1;
			foreach($Modules['_global'] as $moduleList) {
				echo '<tr>';
					echo '<td>'.$i.'</td>';
				echo '<td><a href="'.admincp_base("modules_manager&config=".$moduleList[1]).'">'.$moduleList[0].'</a></td>';
				echo '</tr>';
				$i++;
			}
			
		echo '</tbody></table></div>';
	echo '</div>';
	
	// end 

	  echo '<div class="box">';
	    echo '<div class="box-header with-border">';
           echo '<h3 class="box-title">User Module</h3>';
        echo '</div>';

	       echo '<div class="box-body">';
		    echo '<table class="table table-bordered">';
			echo '<tbody><tr>';
			echo '<th style="width: 10px">#</th>';
            echo '<th>Module</th>';
            echo '</tr>';
            $i=1;
			foreach($Modules['_usercp'] as $moduleList) {
				echo '<tr>';
					echo '<td>'.$i.'</td>';
				echo '<td><a href="'.admincp_base("modules_manager&config=".$moduleList[1]).'">'.$moduleList[0].'</a></td>';
				echo '</tr>';
				$i++;
			}
			echo '</tbody></table></div>';
	echo '</div>';
		
	
echo '</div>';

if(check_value($_GET['config'])) {
	$filePath = __PATH_ADMINCP_MODULES__.'mconfig/'.$_GET['config'].'.php';
	if(file_exists($filePath)) {
		include($filePath);
	} else {
		message('error','Invalid module.');
	}
}

echo '</div>
	</div>
</section>';