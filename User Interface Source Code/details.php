<?php
session_start();
include('informme.class.php');

if(isset($_SESSION['user_id'])) $id = intval($_SESSION['user_id']);
if(isset($_GET['child'])) $child_id = intval($_GET['child']);



$app = new inForm();
$user =  $app->getUser($id);

$child = $app->getChild($child_id);
$config = $app->getConfig();
$mobile = false;

if($_SERVER['HTTP_X_REQUESTED_WITH'] == $config['packagename']) {
    $mobile  = true;
}


$lang = include "lang.php";
$lang = $lang[$user['lang']];
?><!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" type="image/png" href="assets/img/favicon.ico" sizes="16x16">

    <title><?=$config['site_name']?> v1.0.0</title>

    <!-- additional styles for plugins -->
        <!-- weather icons -->
        <link rel="stylesheet" href="bower_components/weather-icons/css/weather-icons.min.css" media="all">
        <!-- metrics graphics (charts) -->
        <link rel="stylesheet" href="bower_components/metrics-graphics/dist/metricsgraphics.css">
        <!-- c3.js (charts) -->
        <link rel="stylesheet" href="bower_components/c3js-chart/c3.min.css">
    
    <!-- uikit -->
    <link rel="stylesheet" href="bower_components/uikit/css/uikit.almost-flat.min.css" media="all">

    <!-- flag icons -->
    <link rel="stylesheet" href="assets/icons/flags/flags.min.css" media="all">

    <!-- altair admin -->
    <link rel="stylesheet" href="assets/css/main.min.css" media="all">

    <link rel="stylesheet" href="main.css" media="all">
    <style type="text/css">
    .tt {
            height: 20px !important;
            padding: 0px !important;
            padding-left: 15px !important;
            min-height: 20px !important;
            background: #ECECEC !important;
            margin-left: 0px !important;
            border: 0px !important;
        }
    </style>
</head>
<body class="sidebar_main_open">
    <!-- main header -->
    <header id="header_main">
        <div class="header_main_content">
            <nav class="uk-navbar">
                <!-- main sidebar switch -->
                <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                    <span class="sSwitchIcon"></span>
                </a>
            </nav>
        </div>
    </header><!-- main header end -->
  <aside id="sidebar_main">
                <div class="menu-top">
                <div class="menu-top-img">
                    <img alt="<?=$user['firstname']?>" src="http://daemonite.github.io/material/images/samples/landscape.jpg">
                </div>
                <div class="menu-top-info">
                    <a class="menu-top-user" href="javascript:void(0)">
                        <span class="menu-top-avatar pull-left"><img class="md-user-image" src="<?=$user['photo']?>" alt=""/></span>
                        <?=$user['firstname']?>
                    </a>
                </div>
                
            </div>
          <div class="menu_section">
            <ul>
                <li class="act_section">
                    <a href="/">
                        <span class="menu_icon material-icons">dashboard</span>
                        <?=$lang["menu_dashboard"]?>
                    </a>
                </li>
                <li>
                    <a href="/buypro.php">
                        <span class="menu_icon material-icons">shopping_basket</span>
                       
                        <?=$lang["menu_buypro"]?>
                    </a>
                </li>
                <hr>
                <li>
                    <a href="/settings.php">
                       <i class="menu_icon material-icons">settings</i>
                        
                        <?=$lang["menu_settings"]?>
                    </a>
                </li>

                <li>
                    <a href="/help.php">
                        <span class="menu_icon material-icons">help</span>
                        
                        <?=$lang["menu_help"]?>
                    </a>
                </li>
                <?php
                    if(!$mobile)
                    {
                        ?>

                <hr>
                <li>
                    <a href="/login.php?logout=true">
                        <span class="menu_icon material-icons">exit_to_app</span>
                        
                        <?=$lang["menu_signout"]?>
                    </a>
                </li>
                        <?php
                    }
                ?>
              
            </ul>
        </div>
    </aside><!-- main sidebar end -->

<div id="top_bar" style="
    padding: 0px;
    height: auto;display:none;">
        <div class="md-top-bar">
            <div class="uk-width-large-8-10 uk-container-center">
                <div class="uk-clearfix">
                      <ul id="user_profile_tabs"  class="uk-tab uk-tab-grid" data-uk-tab="{connect:'#user_profile_tabs_content', animation:'slide-horizontal'}">
                                <li class="uk-width-1-4"><a href="#"><i class="material-icons">person</i></a></li>
                                <li class="uk-width-1-4"><a href="#"><i class="material-icons">call</i></a></li>
                                <li class="uk-width-1-4"><a href="#"><i class="material-icons">message</i></a></li>
                                <li class="uk-width-1-4"><a href="#"><i class="material-icons">location_on</i></a></li>
                            </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="page_content">
        <div id="page_content_inner" style="padding:5px;">
  <div class="uk-grid" data-uk-grid-margin data-uk-grid-match id="user_profile">
                <div class="uk-width-large-7-10">
                    <div class="md-card">
                        <div class="user_heading">
                            <div class="user_heading_avatar">
                                <img src="<?=$child['user']['photo']?>" alt="user avatar"/>
                            </div>
                            <div class="user_heading_content">
                                <h2 class="heading_b uk-margin-bottom"><span class="uk-text-truncate"><?=$child['user']['firstname']?></span><span class="sub-heading"><?=$child['user']['email']?></span></h2>
                                <ul class="user_stats">
                                    <li>
                                        <h4 class="heading_a"><?=$child['calls']?> <span class="sub-heading"><?=$lang['calls']?></span></h4>
                                    </li>
                                    <li>
                                        <h4 class="heading_a"><?=$child['messages']?> <span class="sub-heading"><?=$lang['messages']?></span></h4>
                                    </li>
                                    <li>
                                        <h4 class="heading_a"><?=$child['locations']?> <span class="sub-heading"><?=$lang['locations']?></span></h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="user_content" style="padding:5px;">
                             <ul id="user_profile_tabs"  class="uk-tab uk-tab-grid" data-uk-tab="{connect:'#user_profile_tabs_content', animation:'slide-horizontal'}">
                               <li class="uk-width-1-4 uk-active"><a href="#"><i class="material-icons">person</i></a></li>
                                <li class="uk-width-1-4"><a href="#"><i class="material-icons">call</i></a></li>
                                <li class="uk-width-1-4"><a href="#"><i class="material-icons">message</i></a></li>
                                <li class="uk-width-1-4"><a href="#"><i class="material-icons">pin_drop</i></a></li>
                            </ul>
                            <ul id="user_profile_tabs_content" class="uk-switcher uk-margin">
                                <li>
                                    <ul class="md-list md-list-addon uk-margin-bottom">
                                        <?php
                                        $parents = $app->getParents($child_id);
                                         foreach ($parents as $key) {
                                        ?>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <img class="md-user-image md-list-addon-avatar" src="<?=$key['photo']?>" alt="">
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?=$key['firstname']?></span>
                                                <span class="uk-text-small uk-text-muted"><?=$key['email']?></span>
                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </li>    
                                <li>
                                    <ul class="md-list md-list-addon">
                                        <?php
                                        $tt = "";
                                        if($app->checkPro($id)){
                                        $calls = $app->getCalls($child_id);
                                        foreach ($calls as $key) {
                                            $gg = date( "Y-m-d", strtotime($key['data'])); 
                                            if($gg != $tt){
                                                echo "<li class='tt'>".$gg."</li>";
                                            }
                                            $tt = $gg;
                                         ?>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <?php
                                                if($key['type'] == "OUTGOING")
                                                 echo '<i class="md-list-addon-icon material-icons"  style="color:#0277bd !important">call_made</i>';
                                                    else   if($key['type'] == "INCOMING")
                                                 echo '<i class="md-list-addon-icon material-icons" style="color:green !important">call_received</i>';    
                                                    else   if($key['type'] == "MISSED")
                                                 echo '<i class="md-list-addon-icon material-icons" style="color:red !important">call_missed</i>';   
                                                ?>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?=$key['name']?></span>
                                                <span class="uk-text-small uk-text-muted"><?=$key['number']?></span>
                                            </div>
                                            <div class="md-list-addon-element" style="right:0px !important;
    left: inherit !important;"><?=$key['duration']?>
                                <br>
                                <?=date( "H:i", strtotime($key['data']))?>

                                            </div>
                                        </li>
                                        <?php } }
                                            else {
                                               echo  '<a  class="md-btn md-btn-block md-btn-primary" href="/buypro.php">Buy Pro Account</a>';
                                            }
                                         ?>
                                    </ul>
                                   
                                </li>
                                <li>
                                     <ul class="md-list md-list-addon">
                                        <?php
                                        if($app->checkPro($id)){
                                        $messages = $app->getMessages($child_id);
                                        foreach ($messages as $key) {

                                            $gg = date( "Y-m-d", strtotime($key['data'])); 
                                            if($gg != $tt){
                                                echo "<li class='tt'>".$gg."</li>";
                                            }
                                            $tt = $gg;
                                         ?>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <?php
                                                if($key['type'] == "1")
                                                 echo '<i class="md-list-addon-icon material-icons"  style="color:green !important"></i>';
                                                    else
                                                echo '<i class="md-list-addon-icon material-icons" style="color:red !important"></i>';     
                                                ?>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?=$key['number']?></span>
                                                <span class="uk-text-small uk-text-muted"><?=$key['body']?></span>
                                            </div><div class="md-list-addon-element" style="right:0px !important;
    left: inherit !important;"><?=date( "H:i", strtotime($key['data']))?>

                                            </div>
                                        </li>
                                        <?php } }
                                            else {
                                               echo  '<a  class="md-btn md-btn-block md-btn-primary" href="/buypro.php">Buy Pro Account</a>';
                                             }?>
                                    </ul>
                                </li>
                                <li>
                                        <?php
                                        $locations = $app->getLocations($child_id);
                                        foreach ($locations as $key) {
                                         ?>
                                            <div class="md-card">
                                                <div class="md-card-head head_background" style="background-image: url('http://maps.googleapis.com/maps/api/staticmap?zoom=15&size=500x180&markers=size:big|color:red|<?=$key['lat']?>,<?=$key['lon']?>')">
                                                    <div class="md-card-head-menu">
                                                        <i class="md-icon material-icons md-icon-light">location_on</i>
                                                    </div>
                                                    
                                                </div>
                                                <div class="md-card-content">
                                                    <ul class="md-list md-list-addon">
                                                        <li>
                                                            <div class="md-list-addon-element">
                                                               
                                                                  <i class="md-icon material-icons">location_on</i>
                                                            </div>
                                                            <div class="md-list-content">
                                                                <span class="md-list-heading">
                                                                    <?php 
                                                                        echo $app->getaddress($key['lat'],$key['lon']);
                                                                    ?>
                                                                </span>
                                                                <span class="uk-text-small uk-text-muted"><?=$key['data']?></span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>    
                                            </div>

                                        <?php } ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            </div>   
    </div>
    <!-- google web fonts -->
    <script>
        WebFontConfig = {
            google: {
                families: [
                    'Source+Code+Pro:400,700:latin',
                    'Roboto:400,300,500,700,400italic:latin'
                ]
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>

    <!-- momentJS date library -->
    <script src="bower_components/moment/min/moment.min.js"></script>

    <!-- common functions -->
    <script src="assets/js/common.min.js"></script>
    <!-- uikit functions -->
    <script src="assets/js/uikit_custom.min.js"></script>
    <!-- altair common functions/helpers -->
    <script src="assets/js/altair_admin_common.min.js"></script>
    <!-- page specific plugins -->
    <!-- magnific popup -->
    <script src="bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>

    <!--  user profile functions -->
    <script src="assets/js/pages/page_user_profile.min.js"></script>
    
    <!-- enable hires images -->
    <script>
        $(function() {
            altair_helpers.retina_images();
            $("body").removeClass("top_bar_active");
            $("#page_content").scroll(function() {
                if ($("#page_content").scrollTop() > 350) {
                     $("#top_bar").slideDown();
                     $("body").addClass("top_bar_active");
                }
                else {
                    $("#top_bar").slideUp();
                    $("body").removeClass("top_bar_active");
                }
            });

        });
    </script><script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-69268273-1', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>