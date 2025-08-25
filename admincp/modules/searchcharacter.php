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
<section class="content-header"><h1> Search Character </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<form class="form-inline" role="form" method="post">
	<div class="form-group">
		<input type="text" class="form-control" id="input_1" name="search_request" placeholder="Character name"/>
	</div>
	<button type="submit" class="btn btn-primary" name="search_character" value="ok">Search</button>
</form>
<br />
<?php
	if(check_value($_POST['search_character']) && check_value($_POST['search_request'])) {
		try {
			if(!Validator::AlphaNumeric($_POST['search_request'])) throw new Exception("The name entered must contain alpha-numeric characters only.");
			if(!Validator::Length($_POST['search_request'], 11, 2)) throw new Exception("The name can be 3 to 10 characters long.");
			
			$searchResults = $dB1->query_fetch("SELECT TOP 10 ChaNum,UserNum,ChaName FROM ChaInfo WHERE ChaName LIKE '%".$_POST['search_request']."%'");
			if(!$searchResults) throw new Exception("No results found.");
			
			if(is_array($searchResults)) {
				echo '<div class="row">';
				echo '<div class="col-md-6">';
				echo '<table class="table table-striped table-condensed table-hover">';
					echo '<thead>';
						echo '<tr>';
							echo '<th colspan="2">Search Results for <span style="color:red;"><i>'.$_POST['search_request'].'</i></span></th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
				foreach($searchResults as $character) {
					echo '<tr>';
						echo '<td>'.$character['ChaName'].'</td>';
						echo '<td style="text-align:right;">';
							echo '<a href="'.admincp_base("accountinfo&id=".$character['UserNum']).'" class="btn btn-xs btn-default">Account Information</a> ';
							echo '<a href="'.admincp_base("editcharacter&name=".$character['ChaNum']).'" class="btn btn-xs btn-warning">Edit Character</a>';
						echo '</td>';
					echo '</tr>';
				}
					echo '</tbody>';
				echo '</table>';
				echo '</div>';
				echo '<div class="col-md-6"></div>';
				echo '</div>';
			}
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
?>

</div>
	</div>
</section>