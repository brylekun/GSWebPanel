<?php
/**
 * Ran Panel
 * http://glen-soft.net/
 * 
 * @version 2.0.0
 * @author Dev Glenox <025glenox025@gmail.com>
 * @copyright (c) 2016, Dev Glenox Free Ran Panel
 * @license http://glen-soft.net/license.html
 * @build 9/21/2016
 */

if(!defined('access') or !access) die();
include('inc/template.functions.php');
?>
<!DOCTYPE html>
<html lang="en-US">
<head>

<meta charset="utf-8"/>
<title><?php glenox::displayTitle(); ?></title>
<meta name="author" content="Dev Glenox"/>
<link href='<?=__PATH_TEMPLATE__?>favico.png' rel='shortcut icon' type='logo.png'/>
<meta name="description" content="<?php config('website_meta_keywords'); ?>"/>
<meta name="keywords" content="<?php config('website_meta_keywords'); ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/general.css" />
<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/buddypress.css" />
<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/bbpress.css" />
<link href="//fonts.googleapis.com/css?family=Oswald:400,700,300" rel="stylesheet" type="text/css" />
<!-- Included CSS Files -->
<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/profile.css">
<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/red/main.css" />
<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/red/devices.css" />
<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/red/paralax_slider.css" />
<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/red/jquery.fancybox.css?v=2.1.2" type="text/css"  media="screen" />
<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/red/bxSlider.css" />
    <!-- function for error / success msg -->
    <script src="https://code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script> 
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script> 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- end of function -->
    <link type="text/css" href="<?=__PATH_TEMPLATE__?>css/jx.bar.css" rel="stylesheet" />
    <script type="text/javascript" src="<?=__PATH_TEMPLATE__?>js/jquery.jixedbar.min.js"></script>

<style>


html{
background-color:#000000;
}
body{
        
	background:url(<?=__PATH_TEMPLATE__?>images/web_concept_BG_10.jpg) no-repeat center top;
}

</style>
<style type="text/css">
img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 .07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}
</style>
<link rel='stylesheet' id='layerslider-css'  href='<?=__PATH_TEMPLATE__?>plugins/layerslider.css?ver=5.6.8' type='text/css' media='all' />

<link rel='stylesheet' id='orizon_mytheme_style-style-css'  href='<?=__PATH_TEMPLATE__?>style.css?ver=20140401' type='text/css' media='all' />
<link rel='stylesheet' id='isotopegallery_css-css'  href='<?=__PATH_TEMPLATE__?>plugins/isotopegallery.css?ver=4.8.2' type='text/css' media='all' />
<script type='text/javascript' src='<?=__PATH_TEMPLATE__?>plugins/greensock.js?ver=1.11.8'></script>
<script type='text/javascript' src='<?=__PATH_TEMPLATE__?>js/jquery.js?ver=1.12.4'></script>



<script src='https://www.google.com/recaptcha/api.js'></script>
     <script type="text/javascript">
            var currenttime = '<?php echo date("F j, Y H:i:s"); ?>';
            var montharray = new Array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sept","Oct","Nov","Dec");
            var serverdate = new Date(currenttime);

            function padlength(what) {
                var output = (what.toString().length==1) ? "0" + what : what;
                return output;
            }

            function displaytime() {
                serverdate.setSeconds(serverdate.getSeconds()+1);
                var datestring = montharray[serverdate.getMonth()] + " "+ padlength(serverdate.getDate()) + ", " + serverdate.getFullYear();
                var timestring = padlength(serverdate.getHours()) + ":" + padlength(serverdate.getMinutes()) + ":" + padlength(serverdate.getSeconds());
                document.getElementById("serverdate").innerHTML = "" + datestring;
                document.getElementById("servertime").innerHTML = "" + timestring;
            }

            window.onload = function() {
                setInterval("displaytime()", 1000);
                //setInterval("displayCountdown()", 1000);
            }
           
            $(document).ready(function() {
                $('#webgold').DataTable();
            } );
             
        </script>
      
</head>
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : 'facebook-developer-app-id',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v2.11'
    });
  };
(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<link rel="stylesheet" href="<?=__PATH_TEMPLATE__?>css/red/post.css" type="text/css" media="screen" title="no title" charset="utf-8" />

<body  class="home blog">


<!-- Your customer chat code -->


<div class="fb-customerchat"
  attribution="setup_tool"
  page_id="1880578322258471">
</div>
<!--********************************************* Main wrapper Start *********************************************-->
<div id="main_wrapper">
<!--********************************************* Main_in Start *********************************************-->

<div id="logo">
    <div class="rantime">
  <div class="timer"></div>
  <div class="time">
  <span style="font-size:11px">&nbsp;<span id="serverdate" class="lblLogin"></span>
  <span id="rantime" class="hour"><span id="servertime" class="lblLogin"></span>
  </div>
</div>

      <a href="#"><img class="imgLogo" alt="alt_example" src="<?=__PATH_TEMPLATE__?>css/red/images/logo.png"></a>
    <div id="social_ctn">
        <a class="social_t"><img alt="alt_example" src="<?=__PATH_TEMPLATE__?>css/red/images/social_tleft.png"></a>
            <a href="https://www.facebook.com/<?=config('fb_link',true)?>/" id="facebook"><img alt="alt_example" src="<?=__PATH_TEMPLATE__?>css/red/images/blank.gif" width="100%" height="37px"></a>
            <a href="#" id="you_tube"><img alt="alt_example" src="<?=__PATH_TEMPLATE__?>css/red/images/blank.gif" width="100%" height="37px"></a>
        <a class="social_t"><img alt="alt_example" src="<?=__PATH_TEMPLATE__?>css/red/images/social_tright.png"></a>
    </div>

</div>
<div id="main_in">
<!--********************************************* Mainmenu Start *********************************************-->
<div id="menu_wrapper">


  <div id="menu_left"></div>
     <ul id="menu">
     
          <div class="menu-main-container">
            <ul id="menu-main" class="menu" >
            
                  <?php templateBuildNavbar()?>
            </ul>
          </div>  
</ul>
     <a href="#" id="pull">Menu</a>
  <div id="menu_right"></div>
  <div class="clear"></div>
</div>

<div id="hot_news">


    </div>    <div id="main_news_wrapper">
      <div id="row">
        <!-- Left wrapper Start -->
        <div id="left_wrapper">

        <div id="o2content"></div>

          <?php glenox::loadModule($_REQUEST['page'],$_REQUEST['subpage']); ?>

      </div>
<!-- Left wrapper end -->
<!-- Start RIGHT -->
<div id="right_wrapper">
               

	
   <?php if(!isLoggedIn()) { ?>
    <div class="header"> Member Panel</div>
    <ul>

        <?php templateBuildLogin()?>
      
             
    </ul>
  <?php } ?>
  <?php if(isLoggedIn()) { ?>
    

         <?php templateBuildUsercp()?>
      
             
    
     <?php } ?>
	
  <div class="review">
    <div class="header"> Player Ranking</div>
    <ul>

        <?php include('inc/modules/top10.php'); ?>
      
             
    </ul>
  </div>

<div class="review">
  <div class="header"> CW Winner</div>
    <ul>

        <?php include('inc/modules/cwwin.php'); ?>
      
             
    </ul>
  </div>

    <div class="container-box col-lg-12 shadow container-box-margin-bottom">
   <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- RanPanel -->
    <ins class="adsbygoogle"
         style="display:inline-block;width:300px;height:250px"
         data-ad-client="ca-pub-2528887855630215"
         data-ad-slot="8729971418"></ins>
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
  </div>

          <div class="right_navi">
            <div class="latest_text">
              <h1>Follow us on facebook</h1>
            </div>			
            <div class="textwidget">

                  <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F<?=config('fb_link',true)?>&amp;width=292&amp;height=290&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=true&amp;" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:270px;" allowtransparency="true"></iframe>
            </div>
		          <div class="bootom_image"></div>
          </div>
</div>


<!-- END RIGHT -->
                   <div class="clear"></div>
      </div>
    </div>    <div class="bottom_shadow"></div>


     <div class="footer">
          <div class="copyright">

            <?php include('inc/modules/footer.php'); ?>
          </div>
        </div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#menu li').bind('mouseover', openSubMenu);
        $('#menu > li').bind('mouseout', closeSubMenu);

        function openSubMenu() {
            $(this).find('ul').css('visibility', 'visible');
        };

        function closeSubMenu() {
            $(this).find('ul').css('visibility', 'hidden');
        };
    });
</script>

<script type="text/javascript">
    $(function() {
        var pull    = $('#pull');
        menu 		= $('ul#menu');

        $(pull).on('click', function(e) {
            e.preventDefault();
            menu.slideToggle();
        });

        $(window).resize(function(){
            var w = $(window).width();
            if(w > 767 && $('ul#menu').css('visibility', 'hidden')) {
                $('ul#menu').removeAttr('style');
            };
            var menu = $('#menu_wrapper').width();
            $('#pull').width(menu - 20);
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        var menu = $('#menu_wrapper').width();
        $('#pull').width(menu - 20);
    });
</script>  
</body>
<!--<div id="demo-bar">
  <ul>
   
        </ul>
         <span class="jx-separator-left"></span>    
 <div id="info"></div>
  <ul class="jx-bar-button-right">
      <li title="Server Statistics and Information"><a href="#">Game Info</a>
       <?php include('inc/modules/sidebar.php'); ?>  
                
      </li>
  </ul>
<span class="jx-separator-right"></span>


            <span class="jx-separator-right"></span>

</div>-->

</html>
<script type="text/javascript" src="<?=__PATH_TEMPLATE__?>js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="<?=__PATH_TEMPLATE__?>js/format.min.js"></script>
    <script type="text/javascript" src="<?=__PATH_TEMPLATE__?>js/wz_tooltip.js"></script>