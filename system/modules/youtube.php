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
 echo '<div class="header"><h2>Youtube LIVE</h2></div>';
try {
	
	# news module active?
	if(!ranconfig('active')) throw new Exception(lang('error_47',true));
    
    $url = __BASE_URL__.'youtube/';

    echo '<div id="post_wrapper">';
            echo '<br /><br />';
            
            echo '<center><iframe width="560" height="315" src="'.config('youtube_link').'" frameborder="0" allowfullscreen></iframe><center>';
            
            echo '<br /><br />';

            echo '<div class="fb-comments" data-href="'.$url.'" data-width="560" data-numposts="10"></div>';
    echo '</div>';

} catch(Exception $ex) {
	message('warning', $ex->getMessage());
}