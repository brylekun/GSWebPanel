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

(!isLoggedIn()) ? redirect(1,'login') : null;


echo '<div class="header"><h2>'.lang('module_titles_txt_11',true).' &rarr; '.lang('module_titles_txt_21',true).'</h2></div>';
		
try {
	
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
			
			templateBuildPaypal();
	
?>

<script type="text/javascript">
	document.getElementById('amount').onchange = function(ev) {
	//$('#amount').change(function() {
	  //var ev = document.getElementById("amount");
	  var num = 0;
	  var c = 0;
	  var event = window.event || ev;
	  var code = (event.keyCode) ? event.keyCode : event.charCode;
	  for(num=0;num<this.value.length;num++) {
		c = this.value.charCodeAt(num);
		if(c<48 || c>57) {
		  document.getElementById('result').innerHTML = '0';
		  return false;
		}
	  }
	  num = parseInt(this.value);
	  if(isNaN(num)) {
		document.getElementById('result').innerHTML = '0';
	  } else {
		var result = (<?php echo ranconfig('paypal_conversion_rate'); ?>*num).toString();
		document.getElementById('result').innerHTML = result;
	  }
	}
</script>
	
<?php
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}

?>