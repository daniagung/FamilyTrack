<?php
session_start();
include('informme.class.php');
if(isset($_GET['id']))
{
  $id = intval($_GET['id']);
  $_SESSION['user_id'] = $id;
  $_SESSION['logged_in'] = true;
} else
if(empty($_SESSION['logged_in'])){
    header('Location: login.php');
}
else 
{
  if(isset($_SESSION['user_id'])) $id = intval($_SESSION['user_id']);
}


$mobile = false;
$app = new inForm();
$user =  $app->getUser($id);
if($_SERVER['HTTP_X_REQUESTED_WITH'] == "biz.prolike.hereiam") {
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

    <title>Inform me v1.0.0</title>

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

    <!-- main sidebar -->
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
                <li>
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

                <li class="act_section">
                    <a href="/help.php">
                        <span class="menu_icon material-icons">help</span>
                        
                        <?=$lang["menu_help"]?>
                    </a>
                </li>
                <hr>
                <?php
                    if(!$mobile)
                    {
                        ?><li>
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

    <div id="page_content">
        <div id="page_content_inner">

                 <div class="md-card">
                    <div class="md-card-content">
                        <p>
                       Inform Me is the app which help parents to protect their children from strangers that may adversely affect the morale of children through specific calls or sms(which children often do not have the courage to talk openly with their parents about the situation created). And even if someone tries to steal the phone - Inform Me helps you collect data from your child's phone.
                      
                      <br>  Inform Me - is a very accurate location services for family members. It is a safe way to track your children on the map around the clock. All you need - it's a phone with Android on the installed application.  <br> 
                        Who needs a tracking service like Inform Me?   <br> 
                        Parents with children from 6 to 18 years, whose children attend every day on city streets to school, to relatives, walk with friends or family.   <br> 
                        The list of application functions   <br> 
                        ✓ Location and maps:   <br> 
                        ✓ SMS tracking   <br> 
                        ✓ Call tracking  <br> 
                        ✓ Notice of places: receive notifications when family members come to school or home (this is automatic Chekin!).
                        Important: All family members should know that this application is installed and running on their device, and must be willing to monitor their whereabouts   <br> 
                        With Inform Me you can access family information from your smartphone on Android, when you're away from your children and family members  <br> 
                        Warning: any services based on GPS-technology  works when the GPS, Wi-Fi or Mobile internet service is on.   <br> 
                        Inportant: in app purchases for premium account.
                    </p>
                    </div>
                 </div>
        </div>
    </div>
    <!-- google web fonts -->
    <script>
        function addUser()
        {
           $(".errorpage").text("");
          var mail = $("#mail_new_to").val();
          $.get( "http://api.informme.us/follow/add?id=<?=$id?>&child="+mail, function( data ) {
            if(data.message == "success") { 
                $(".uk-modal-close .uk-close").click();
                location.reload();
            } else {
                $(".errorpage").text(data.message);
            }
          });
        }
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
        <!-- d3 -->
        <script src="bower_components/d3/d3.min.js"></script>
        <!-- metrics graphics (charts) -->
        <script src="bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>
        <!-- c3.js (charts) -->
        <script src="bower_components/c3js-chart/c3.min.js"></script>
        <!-- maplace (google maps) -->
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script src="bower_components/maplace.js/src/maplace-0.1.3.js"></script>
        <!-- peity (small charts) -->
        <script src="bower_components/peity/jquery.peity.min.js"></script>
        <!-- easy-pie-chart (circular statistics) -->
        <script src="bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <!-- countUp -->
        <script src="bower_components/countUp.js/countUp.min.js"></script>
        <!-- handlebars.js -->
        <script src="bower_components/handlebars/handlebars.min.js"></script>
        <script src="assets/js/custom/handlebars_helpers.min.js"></script>
        <!-- CLNDR -->
        <script src="bower_components/clndr/src/clndr.js"></script>
        <!-- fitvids -->
        <script src="bower_components/fitvids/jquery.fitvids.js"></script>

        <!--  dashbord functions -->
        <script src="assets/js/pages/dashboard.min.js"></script>


    <!--  settings functions -->
    <script src="assets/js/pages/page_settings.min.js"></script>
    
    
    <!-- enable hires images -->
    <script>
        $(function() {
            altair_helpers.retina_images();
        });
    </script>

</body>
</html>