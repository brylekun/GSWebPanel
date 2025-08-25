<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */
?>
<section class="content-header"><h1> Search Ban </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<form class="form-inline" role="form" method="post">
	<div class="form-group">
		<input type="text" class="form-control" id="input_1" name="search_request" placeholder="Account username"/>
	</div>
	<button type="submit" class="btn btn-primary" name="search_ban" value="ok">Search</button>
</form>
<br />
<?php
	
	if(check_value($_POST['search_request'])) {
		try {
			$search = glenox::DB('RanUser')->query_fetch("SELECT TOP 25 * FROM UserInfo WHERE UserBlock = 1 AND UserID LIKE '%".$_POST['search_request']."%'");
			if(is_array($search)) {
				echo '<div class="row">';
				echo '<div class="col-md-12">';
				echo '<table class="table table-striped table-condensed table-hover">';
					echo '<thead>';
						echo '<tr>';
							echo '<th colspan="6">Search Results for <span style="color:red;"><i>'.$_POST['search_request'].'</i></span></th>';
						echo '</tr>';
					echo '</thead>';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Account</th>';
							echo '<th>Type</th>';
							echo '<th>Expired Date (Y-m-d)</th>';
							echo '<th></th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($search as $ban) {
						$banType = ($ban['UserBlockDate'] == "2099-01-01 00:00:00.000" ? '<span class="label label-danger">Permanent</span>' : '<span class="label label-info">Temporal</span>');
						echo '<tr>';
							echo '<td><a href="'.admincp_base("accountinfo&id=".$ban['UserNum']).'">'.$ban['UserID'].'</a></td>';
							echo '<td>'.$banType.'</td>';
							echo '<td>'.substr($ban['UserBlockDate'],0,10).'</td>';
							echo '<td style="text-align:right;"><a href="#" class="btn btn-default btn-xs" title="'.$ban['ban_reason'].'">Reason</a> <a href="index.php?module=latestbans&liftban='.$ban['id'].'" class="btn btn-danger btn-xs">Lift Ban</a></td>';
						echo '</tr>';
					}
					echo '</tbody>';
				echo '</table>';
				echo '</div>';
				echo '</div>';
			} else {
				throw new Exception("No results found.");
			}
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
?>

</div>
	</div>
</section>
