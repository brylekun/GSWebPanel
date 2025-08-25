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

// ACCESS
define('admincp', true);

try {
	
	// Load system core
	if(!@include_once('../system/core.php')) throw new Exception('Could not load core.');

	// Check if user is logged in
	if(!isLoggedIn()) { redirect(); }

	// Check if user has access
	if(!canAccessAdminCP($_SESSION['username'])) { redirect(); }

  //var_dump($_SESSION['username']);
	// Load AdminCP Tools
	if(!@include_once(__PATH_ADMINCP_INC__ . 'functions.php')) throw new Exception('Could not load AdminCP functions.');
	
	// Check Configurations
	if(!@include_once(__PATH_ADMINCP_INC__ . 'check.php')) throw new Exception('Could not load AdminCP configuration check.');
	
} catch (Exception $ex) {
	$errorPage = file_get_contents('../system/error.html');
	echo str_replace("{ERROR_MESSAGE}", $ex->getMessage(), $errorPage);
	die();
}

$admincpSidebar = array(

	array("News Management", array(
		"addnews" => "Publish",
    "remitance" => "Remitance Post",
		"managenews" => "Edit / Delete",
	), "fa-newspaper-o"),
	array("Account", array(
		"searchaccount" => "Search",
		"accountsfromip" => "Find Accounts from IP",
		"onlineaccounts" => "Online Accounts",
		"newregistrations" => "New Registrations",
		"accountinfo" => "", // HIDDEN
	), "fa-users"),
	array("Character", array(
		"searchcharacter" => "Search",
		"editcharacter" => "", // HIDDEN
	), "fa-user"),
	array("Bans", array(
		"searchban" => "Search",
		"banaccount" => "Ban Account",
		"latestbans" => "Latest Bans",
		"blockedips" => "Block IP (web)",
	), "fa-exclamation-circle"),
	array("Donation", array(
		"latestpaypal" => "PayPal Donations",
    "topup" => "Top up",
    "pointslog" => "Points Log",
    "logpurchase" => "Purchase Log",
	), "fa-money"),
	array("Website Configuration", array(
		"admincp_access" => "AdminCP Access",
		"admincp_permissions" => "AdminCP Permissions",
		"website_settings" => "Website Settings",
		"modules_manager" => "Modules Manager",
		"navbar" => "Navigation Menu",
		"usercp" => "UserCP Menu",
	), "fa-toggle-on"),
	array("Plugins", array(
		"plugins" => "Plugins Manager",
		"plugin_install" => "Import Plugin",
	), "fa-plug"),
	array("Scheduled Tasks", array(
		"addcron" => "Add New",
		"managecron" => "Manage Cron Jobs",
	), "fa-tasks"),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="RanPanel v2">
  <meta name="author" content="Dev Glenox">
  <title>Ran.Admin CP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./css/fonts/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="./css/Ionicons/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/skin.css">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <!-- function for error / success msg -->

    <script src="https://code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script> 
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script> 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- end of function -->
  <!-- Google Font 
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">-->
</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
<header class="main-header">
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>R</b>AN</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>RAN </b>Panel</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">0</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 0 ticket</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  
                </ul>
              </li>
              <li class="footer"><a href="#">See All ticket</a></li>
            </ul>
          </li>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="img/icon-45x45.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?=$_SESSION['username'] ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="img/icon-45x45.jpg" class="img-circle" alt="User Image">

                <p>
                  <?=$_SESSION['username'] ?>
                  <small>Member since 2017</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo __BASE_URL__; ?>" class="btn btn-default btn-flat" target="_blank">Home</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo __BASE_URL__; ?>logout/" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="img/icon-45x45.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=$_SESSION['username'] ?></p>
          
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <!--<li><a href="#/profile"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li><a href="#/grade"><i class="fa fa-table"></i> <span>Grade</span></a></li>-->
        <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <?php
							foreach($admincpSidebar as $sidebarItem) {
								if(check_value($_GET['module']) && array_key_exists($_GET['module'], $sidebarItem[1])) {
									echo '<li class="active treeview menu-open">';
								} else {
									echo '<li class="treeview">';
								}
									$itemIcon = (check_value($sidebarItem[2]) ? '<i class="fa '.$sidebarItem[2].'"></i>' : '');
									if(is_array($sidebarItem[1])) {
										echo ' <a href="#">'.$itemIcon.'<span> '.$sidebarItem[0].' </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
										echo '<ul class="treeview-menu">';
											foreach($sidebarItem[1] as $sidebarSubItemModule => $sidebarSubItemTitle) {
												if(check_value($sidebarSubItemTitle)) echo '<li><a href="'.admincp_base($sidebarSubItemModule).'"><i class="fa fa-circle-o"></i>'.$sidebarSubItemTitle.'</a></li>';
											}
										echo '</ul>';
									} else {
										echo '<a href="'.admincp_base($sidebarItem[1]).'">'.$itemIcon.$sidebarItem[0].'</a>';
									}
								echo '</li>';
							}
							
							if(check_value($extra_admincp_sidebar)) {
								if(is_array($extra_admincp_sidebar)) {
									echo '<li class="treeview">';
										echo '<a href="#"><i class="fa fa-book"></i> <span> Active Plugins </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
										echo '<ul class="treeview-menu">';
											foreach($extra_admincp_sidebar as $pluginSidebarItem) {
												if(is_array($pluginSidebarItem) && is_array($pluginSidebarItem[1])) {
													echo '<li class="treeview">';
														echo '<a href="#"><span>'.$pluginSidebarItem[0].'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
														echo '<ul class="treeview-menu">';
															foreach($pluginSidebarItem[1] as $pluginSidebarSubItem) {
																echo '<li><a href="'.admincp_base($pluginSidebarSubItem[1]).'"><i class="fa fa-circle-o"></i>'.$pluginSidebarSubItem[0].'</a></li>';
															}
														echo '</ul>';
													echo '</li>';
												}
											}
										echo '</ul>';
									echo '</li>';
								}
							}
						?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">
    
    	<?php glenox::loadAdminCPModule($_REQUEST['module']); ?>

  </div>
  
  <!-- /.content-wrapper -->


  
  <!-- Add the sidebars background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>


</div>

<!-- ./wrapper -->
<!-- jQuery -->
<script src="./js/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="./js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="./js/jquery.dataTables.min.js"></script>
<script src="./js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="./js/adminlte.min.js"></script>
<script>
  $(function () {
    $('#Search').DataTable()
    $('#Info').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>


