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
<section class="content-header"><h1> Edit Remitance </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<?php
//$CMS = new Cms();

// Check if News cache folder is writable
if(glenox::isNewsDirWritable()) {
	
	// Edit news process::
	if(check_value($_POST['news_submit'])) {
		glenox::cacheCMS('remitance',$_POST['news_content']);
	}

	$loadCmsCache = glenox::LoadCachedCms('remitance');
	
	// Load News
?>
		<form role="form" method="post">
			<div class="form-group">
				<label for="input_1">Title:</label>
				<label for="input_1"> remitance</label>
			</div>
			<div class="form-group">
				<label for="news_content"></label>
				<textarea name="news_content" id="news_content"><?php echo $loadCmsCache; ?></textarea>
			</div>

			<button type="submit" class="btn btn-large btn-block btn-success" name="news_submit" value="ok">Update CMS</button>
		</form>
		
		<script src="js/editor/ckeditor.js"></script>
		<script type="text/javascript">//<![CDATA[
			//CKEDITOR.replace('editor1');
			CKEDITOR.replace('news_content', {
				language: 'en',
				uiColor: '#f1f1f1'

			});
		//]]></script>
<?php	
} else {
	message('error','The cms cache folder is not writable.');
}

?>

</div>
	</div>
</section>