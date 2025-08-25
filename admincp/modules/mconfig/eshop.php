<section class="content-header"><h1> EP-Shop Settings </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-9">
<?php
#add item
$itemshop = new eshop();
if(check_value($_POST['newitem_submit'])) {
	try {
		if(!check_value($_FILES["fileToUpload"])) throw new Exception('Invalid proccess');
		$itemshop->AddItemShop($_FILES["fileToUpload"]);
		unset($_POST['newitem_submit']);
		message('success', 'Success insert new item! wait 5 second to refresh');
		redirect(2,'admincp/index.php?module=modules_manager&config=eshop',5);
	} catch(Exception $ex) {
			message('error', $ex->getMessage());
	}
	
}
#edit item
if(check_value($_POST['update_submit'])) {
	try {
	if(!check_value($_POST['ItemNum'])) throw new Exception('Invalid proccess');
	if(!check_value($_POST['item_name'])) throw new Exception('Invalid proccess');
	if(!check_value($_POST['item_price'])) throw new Exception('Invalid proccess');
	if(!check_value($_POST['item_stock'])) throw new Exception('Invalid proccess');
	
	$itemshop->EditItemShop($_POST['ItemNum'],$_POST['item_name'],$_POST['item_price'],$_POST['item_stock'],$_POST['item_status'],$_POST['item_ctg'],$_POST['item_images']);
	message('success', 'Changes successfully saved! wait 5 second to refresh');
	redirect(2,'admincp/index.php?module=modules_manager&config=eshop',5);
	} catch(Exception $ex) {
			message('error', $ex->getMessage());
	}
}
#delete item 
if(check_value($_GET['delete'])) {
	try {
		if(!check_value($_GET['delete'])) throw new Exception('Invalid id.');

		$itemshop->DelItemShop($_GET['delete']);
		unset($_POST['ItemNum']);
		message('success', 'Item has been delete! wait 5 second to refresh');
		redirect(2,'admincp/index.php?module=modules_manager&config=eshop',5);
	} catch(Exception $ex) {
			message('error', $ex->getMessage());
	}
}

			
			$result = $itemshop->AdminGetItem();

			echo '<table class="table table-condensed table-bordered table-hover table-striped">';
			echo '<thead>';
				
				echo '<tr>';
					echo '<th></th>';
					echo '<th>Item Main</th>';
					echo '<th>Item Sub</th>';
					echo '<th>Item Name</th>';
					echo '<th>Price</th>';
					echo '<th>Stock</th>';
					echo '<th>Status</th>';
					echo '<th>Category';
					echo '<th>Images</th>';
					echo '<th></th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach($result as $id => $IShop) {

				echo '<form action="" method="post">';
				echo '<input type=hidden name="ItemNum" value="'.$IShop['ProductNum'].'">';
				echo '<tr>';

						echo '<td class="text-center" style="vertical-align:middle;"><a href="?module=modules_manager&config=eshop&delete='.$IShop['ProductNum'].'" onclick="return confirm(\'Are you sure! you want to delete this item?\')" class="btn btn-danger btn-xs"><span class="fa fa-times" aria-hidden="true"></span></a></td>';
						// item Main / Sub
						echo '<td style="max-width:50px;">'.$IShop['ItemMain'].'</td>';
						echo '<td style="max-width:50px;">'.$IShop['ItemSub'].'</td>';
						//
						echo '<td><input type="text" name="item_name" class="form-control" value="'.$IShop['ItemName'].'"/></td>';
						echo '<td style="max-width:120px;"><input type="text" name="item_price" class="form-control" value="'.$IShop['ItemPrice'].'"/></td>';
						echo '<td style="max-width:120px;"><input type="text" name="item_stock" class="form-control" value="'.$IShop['Itemstock'].'"/></td>';

						echo '<td class="text-center" style="vertical-align:middle;">';
							echo '<label class="radio-inline">';
								echo '<input type="radio" name="item_status" value="1" '.($IShop['ItemStatus'] ? 'checked' : '').'> Show';
							echo '</label>';
							echo '<label class="radio-inline">';
								echo '<input type="radio" name="item_status" value="0" '.(!$IShop['ItemStatus'] ? 'checked' : '').'> Hide';
							echo '</label>';
						echo '</td>';
							echo '<td>';
								echo '<select name="item_ctg" class="form-control">';
									echo '<option value="0" '.($IShop['Category'] == '0' ? 'selected' : '').'>All</option>';
									echo '<option value="1" '.($IShop['Category'] == '1' ? 'selected' : '').'>Pet</option>';
									echo '<option value="2" '.($IShop['Category'] == '2' ? 'selected' : '').'>Potion</option>';
									echo '<option value="3" '.($IShop['Category'] == '3' ? 'selected' : '').'>Clothing</option>';
									echo '<option value="4" '.($IShop['Category'] == '4' ? 'selected' : '').'>Others</option>';
									echo '<option value="5" '.($IShop['Category'] == '5' ? 'selected' : '').'>Accesroies</option>';
									echo '<option value="6" '.($IShop['Category'] == '6' ? 'selected' : '').'>Refined</option>';
								echo '</select>';
							echo '</td>';
						echo '<td><input type="text" name="item_images" class="form-control" value="'.$IShop['ItemImage'].'"/></td>';

						
						echo '<td class="text-center" style="vertical-align:middle;"><button type="submit" name="update_submit" value="ok" class="btn btn-primary">Save</button></td>';
			echo '</tr>';
			echo '</form>';
			}

				# new item to... mga dre.
 
			echo '<form action="" method="post" enctype="multipart/form-data">';
				echo '<tr><th colspan="9" class="text-center"><br /><br />Add New Item</th></tr>';
			echo '<tr>';
				echo '<td></td>';
				echo '<td colspan="3">Select text to upload:</td>';
				echo '<td colspan="4"><input type="file" name="fileToUpload"></td>';
				

			echo '<td class="text-center" style="vertical-align:middle;"><button type="submit" name="newitem_submit" value="ok" class="btn btn-success">Add</button></td>';
			echo '</tr>';
			echo '</form>';

			
				echo '</tbody>';
	echo '</table>';
?>


</div>
	</div>
</section>