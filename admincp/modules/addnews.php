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
<section class="content-header"><h1> Add news </h1></section>
	<section class="content">
      <div class="row">
		<div class="col-xs-12">
<?php
$News = new News();
loadModuleConfigs('news');

// Check if News cache folder is writable
if($News->isNewsDirWritable()) {
	
	// Add news process::
	if(check_value($_POST['news_submit'])) {
		$News->addNews($_POST['news_title'],$_POST['news_content'],$_POST['news_author'],$_POST['news_comments']);
		$News->cacheNews();
		$News->updateNewsCacheIndex();
	}
	
	// Cache news process::
	if(check_value($_REQUEST['cache']) && $_REQUEST['cache'] == 1) {
		$cacheNews = $News->cacheNews();
		if($cacheNews) {
			message('success','News successfully cached!');
		} else {
			message('error','Unknown error');
		}
	}
	
?>
	<form role="form" method="post">
		<div class="form-group">
			<label for="input_1">Title:</label>
			<input type="text" class="form-control" id="input_1" name="news_title" />
		</div>
		<div class="form-group">
			<label for="news_content"></label>
			<textarea name="news_content" id="news_content"></textarea>
		</div>
		<div class="form-group">
			<label for="input_2">Author:</label>
			<input type="text" class="form-control" id="input_2" name="news_author" value="Administrator"/>
		</div>
		<?php if(ranconfig('news_enable_comment_system')) { ?>
		<div class="form-group">
			<label for="input_3">Allow Facebook Comments:</label>
			<div class="radio">
				<label><input type="radio" name="news_comments" id="input_3" value="1" checked> Yes</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="news_comments" id="input_3" value="0"> No</label>
			</div>
		</div>
			
		<?php } else { ?>
			<input type="hidden" name="news_comments" value="0"/>
		<?php }?>

		<button type="submit" class="btn btn-large btn-block btn-success" name="news_submit" value="ok">Publish</button>
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
	message('error','The news cache folder is not writable.');
}
?>
		</div>
	</div>
</section>