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
?>
<section class="content-header"><h1> Find accounts from IP </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<form class="form-inline" role="form" method="post">
	<div class="form-group">
		<input type="text" class="form-control" id="input_1" name="ip_address" placeholder="Ip Address"/>
	</div>
	<button type="submit" class="btn btn-primary" name="search_ip" value="ok">Search</button>
</form>
<br />
<?php
if(check_value($_POST['ip_address'])) {
	try {
		if(!Validator::Ip($_POST['ip_address'])) throw new Exception("You have entered an invalid IP address.");
		
		echo '<h4>Search results for <span style="color:red;font-weight:bold;"><i>'.$_POST['ip_address'].'</i></span>:</h4>';
		echo '<div class="row">';
			
			echo '<div class="col-md-6">';
				echo '<div class="panel panel-primary">';
				echo '<div class="panel-heading">Log IP</div>';
				echo '<div class="panel-body">';

					$membStatData = glenox::DB('RanUser')->query_fetch("SELECT UserNum,UserID FROM LogLogin WHERE LogIpAddress = ? AND LogInOut = 1", array($_POST['ip_address']));
					if(is_array($membStatData)) {
						echo '<table class="table table-no-border table-hover">';
							foreach($membStatData as $membStatUser) {
								echo '<tr>';
									echo '<td>'.$membStatUser['UserID'].'</td>';
									echo '<td style="text-align:right;"><a href="'.admincp_base("accountinfo&id=".$membStatUser['UserNum']).'" class="btn btn-xs btn-default">Account Information</a></td>';
								echo '</tr>';
							}
							echo '</table>';
					} else {
						message('warning', 'No accounts found linked to this Ip.', ' ');
					}
				echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}
?>

</div>
	</div>
</section>