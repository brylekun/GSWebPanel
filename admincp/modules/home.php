<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */

echo '<section class="content-header"><h1> Dashboard </h1></section>';

echo '<section class="content">';
      echo '<div class="row">';
        echo '<div class="col-xs-12">';
    	#starting

        // OS
    	echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">PHP version</span>
              <span class="info-box-number">'.phpversion().'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

         // OS
    	echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-ios-gear"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">OS</span>
              <span class="info-box-number">'.PHP_OS.'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

         // Panel version
    	echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-laptop"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Panel version</span>
              <span class="info-box-number">'.__RANPANEL_VERSION__.'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

        // Total Account
        $totalAccounts = glenox::DB('RanUser')->query_fetch_single("SELECT COUNT(*) as result FROM UserInfo");
        echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Registered</span>
              <span class="info-box-number">'.number_format($totalAccounts['result']).'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

        // total character
        $totalCharacters = glenox::DB('RanGame1')->query_fetch_single("SELECT COUNT(*) as result FROM ChaInfo");
        echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="ion ion-ios-people"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Characters</span>
              <span class="info-box-number">'.number_format($totalCharacters['result']).'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

         // total banned
        $bannedAccounts = glenox::DB('RanGame1')->query_fetch_single("SELECT COUNT(*) as result FROM UserInfo WHERE UserBlock = 1");
        echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-locked"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Banned</span>
              <span class="info-box-number">'.number_format($bannedAccounts['result']).'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

         // total online
        $ChaOnline = glenox::DB('RanGame1')->query_fetch_single("SELECT COUNT(*) as result FROM ChaInfo WHERE ChaOnline = 1");
        echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-person"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Online</span>
              <span class="info-box-number">'.number_format($ChaOnline['result']).'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

         // Scheduled Tasks
		$scheduledTasks = glenox::DB('RanPanel')->query_fetch_single("SELECT COUNT(*) as result FROM CRON");
        echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-social-rss"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Task</span>
              <span class="info-box-number">'.number_format($scheduledTasks['result']).'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

        // total EPoints today
        $totalep = glenox::DB('RanPanel')->query_fetch("SELECT LogValue,LogTime FROM LogPoints WHERE LogName = 'EPoints'");
       // var_dump($totalep);
        $a=0;
        $ep=0;

        foreach ($totalep as $data) {
          $midnight = strtotime("tomorrow 00:00:00");
          $now = $midnight - $data['LogTime'];
          if($now < 86400){
            $ep = $ep+$data['LogValue'];
          }
          $a++;
        }

        echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="ion ion-cash"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total EPoint\'s</span>
              <span class="info-box-number">'.number_format($ep).'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

        // total VPoints today
        $totalvp = glenox::DB('RanPanel')->query_fetch("SELECT LogValue,LogTime FROM LogPoints WHERE LogName = 'VPoints'");
        $b=0;
        $vp=0;

        foreach ($totalvp as $data) {
          $midnight = strtotime("tomorrow 00:00:00");
          $now =  $midnight - $data['LogTime'];
          if($now < 86400){
            $vp = $vp+$data['LogValue'];
          }
          $b++;
        }
        
        echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-card"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total VPoint\'s</span>
              <span class="info-box-number">'.number_format($vp).'</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';

        // Admin's
        $admincpUsers = config('admins',true);
        $i=1;
       
       echo '<div class="col-md-8">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Admin\'s / GM\'s</h3>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>';
                   foreach ($admincpUsers as $data => $value) {
                echo '<tr>';
                  	$result = ($value==100)? '<span class="label label-success">Admin</span>':'<span class="label label-danger">GM</span>';
              echo '<td>'.$i.'</td>
                    <td>'.$data.'</td>
                    <td>'.$result.'</td>
                  </tr>';
                  $i++;
              }
              echo'</tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
          </div>
          <!-- /.box -->
        </div>';


    $stock = glenox::DB('RanShop')->query_fetch("SELECT * FROM ShopItemMap WHERE Itemstock = 0");
        echo'<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Out of stock</h3>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Product Num</th>
                    <th>Item Name</th>
                    <th>Shop</th>
                  </tr>
                  </thead>
                  <tbody>';
                   foreach ($stock as $data) {
                    $result = ($data['PremiumItem']!=0)? 'EP-Shop' : 'VP-Shop';
                echo '<tr>';
                    echo '<td>'.$data['ProductNum'].'</td>
                      <td>'.$data['ItemName'].'</td>

                      <td>'.$result.'</td>
                    </tr>';
                  $i++;
              }
              echo'</tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
          </div>
          <!-- /.box -->
        </div>

        </div>';






        $totalC = number_format($totalCharacters['result']);

        $archer = glenox::DB('RanGame1')->query_fetch_single("SELECT COUNT(*) as result FROM ChaInfo WHERE ChaClass IN ('256', '4')");
        $archer = number_format($archer['result']);
        $archerC = number_format((($archer/$totalC)*100));

         $brawler =glenox::DB('RanGame1')->query_fetch_single("SELECT COUNT(*) as result FROM ChaInfo WHERE ChaClass IN ('1', '64')");
         $brawler=number_format($brawler['result']);
         $brawlerC = number_format((($brawler/$totalC)*100));

         $swords =glenox::DB('RanGame1')->query_fetch_single("SELECT COUNT(*) as result FROM ChaInfo WHERE ChaClass IN ('2', '128')");
         $swords=number_format($swords['result']);
         $swordsC = number_format((($swords/$totalC)*100));

         $shaman =glenox::DB('RanGame1')->query_fetch_single("SELECT COUNT(*) as result FROM ChaInfo WHERE ChaClass IN ('8', '512')");
         $shaman=number_format($shaman['result']);
         $shamanC = number_format((($shaman/$totalC)*100));

        echo '<div class="col-md-4">
          <!-- Info Boxes Style 2 -->
          <div class="info-box bg-green">
            <span class="info-box-icon"><img src="img/class/archer.gif" title="Archer"/></span>

            <div class="info-box-content">
              <span class="info-box-text">Archer</span>
              <span class="info-box-number">'.$archer.'</span>

              <div class="progress">
                <div class="progress-bar" style="width: '.$archerC.'%"></div>
              </div>
              <span class="progress-description">
                    '.$archerC.'% of Total Characters
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-red">
            <span class="info-box-icon"><img src="img/class/brawler.gif" title="Brawler"/></span>

            <div class="info-box-content">
              <span class="info-box-text">Brawler</span>
              <span class="info-box-number">'.$brawler.'</span>

              <div class="progress">
                <div class="progress-bar" style="width: '.$brawlerC.'%"></div>
              </div>
              <span class="progress-description">
                   '.$brawlerC.'% of Total Characters
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-black">
            <span class="info-box-icon"><img src="img/class/swordsman.gif" title="Swordsman"></span>

            <div class="info-box-content">
              <span class="info-box-text">Swordsman</span>
              <span class="info-box-number">'.$swords.'</span>

              <div class="progress">
                <div class="progress-bar" style="width: '.$swordsC.'%"></div>
              </div>
              <span class="progress-description">
                    '.$swordsC.'% of Total Characters
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-aqua">
            <span class="info-box-icon"><img src="img/class/shaman.gif" title="Shaman"></span>

            <div class="info-box-content">
              <span class="info-box-text">Shaman</span>
              <span class="info-box-number">'.$shaman.'</span>

              <div class="progress">
                <div class="progress-bar" style="width: '.$shamanC.'%"></div>
              </div>
              <span class="progress-description">
                    '.$shamanC.'% of Total Characters
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->

          

          
          </div>
          <!-- /.box -->
        </div>';






         #end of code
    	echo '</div>'; #end col
       echo '</div>'; #end for row
echo '</section>'; #end for section