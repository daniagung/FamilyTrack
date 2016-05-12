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


$app = new inForm();
$user =  $app->getUser($id);
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
                 <li >
                    <a href="/">
                        <span class="menu_icon material-icons">dashboard</span>
                        <?=$lang['menu_dashboard']?>
                    </a>
                </li>
                <li class="act_section">
                    <a href="/buypro.php">
                        <span class="menu_icon material-icons">shopping_basket</span>
                         <?=$lang['menu_buypro']?>
                    </a>
                </li>
                <hr>
                <li>
                    <a href="/settings.php">
                       <i class="menu_icon material-icons">settings</i>
                         <?=$lang['menu_settings']?>
                    </a>
                </li>

                <li>
                    <a href="/help.php">
                        <span class="menu_icon material-icons">help</span>
                         <?=$lang['menu_help']?>
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

    <div id="page_content">
        <div id="page_content_inner">

                 <div class="md-card">
                            <div class="md-card-content">
                                <form action="https://secure.paypal.com/uk/cgi-bin/webscr" method="post" name="paypal" id="paypal">
                                        <input type="hidden" name="cmd" value="_xclick" />
                                        <input type="hidden" name="business" value="<?=$config['paypal_mail']?>" />
                                        <input type="hidden" name="cbt" value="Return to Your Business Name" />
                                        <input type="hidden" name="currency_code" value="USD" />

                                        <!-- Allow the customer to enter the desired quantity -->
                                        <input type="hidden" name="quantity" value="1" />
                                        <input type="hidden" name="item_name" value="Buy Pro Accunt" />

                                        <!-- Custom value you want to send and process back in the IPN -->
                                        <input type="hidden" name="custom" value="<?=$id?>" />
                                        <input type="hidden" name="amount" value="<?=$config['paypal_price']?>" />
                                        <input type="hidden" name="return" value="http://<?php echo $_SERVER['SERVER_NAME']?>/success.php?user=<?=$id?>"/>
                                        <input type="hidden" name="cancel_return" value="http://<?php echo $_SERVER['SERVER_NAME']?>/" />
                                         <input type="submit" class="md-btn md-btn-block md-btn-primary" value="Buy Pro $<?=$config['paypal_price']?>"></input>
                                    </form><br>
                               <ul class="md-list md-list-addon">
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons" style="color:green !important">check</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"> <?=$lang['pro_unlimited']?></span>
                                        <span class="uk-text-small uk-text-muted"><?=$lang['pro_1']?>.</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons" style="color:green !important">check</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?=$lang['pro_unlimited']?></span>
                                        <span class="uk-text-small uk-text-muted"><?=$lang['pro_2']?></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons" style="color:green !important">check</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?=$lang['pro_unlimited']?></span>
                                        <span class="uk-text-small uk-text-muted"><?=$lang['pro_3']?>.</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons" style="color:green !important">check</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?=$lang['pro_unlimited']?></span>
                                        <span class="uk-text-small uk-text-muted"><?=$lang['pro_4']?></span>
                                    </div>
                                </li>
                            </ul>
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

    <script src="assets/js/pages/page_settings.min.js"></script>
    
    
    <!-- enable hires images -->
    <script>
        $(function() {
            altair_helpers.retina_images();
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