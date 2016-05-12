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
$config = $app->getConfig();
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
    <!-- uikit -->
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
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
                        <?=$lang['menu_dashboard']?>
                    </a>
                </li>
                <li>
                    <a href="/buypro.php">
                        <span class="menu_icon material-icons">shopping_basket</span>
                        <?=$lang['menu_buypro']?>
                    </a>
                </li>
                <hr>
                <li class="act_section" >
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
                        <?=$lang['menu_signout']?>
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
                                <ul class="md-list">
                                    <li>
                                        <a class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" <?php
                                                    if($user['notification'] == "yes") echo "checked";
                                                ?> data-switchery id="settings_seo" name="settings_seo" />
                                            </div>
                                            <span class="md-list-heading"><?=$lang['s_notify']?></span>
                                            <span class="uk-text-muted uk-text-small"><?=$lang['s_notify_desc']?> </span>
                                        </a>
                                    </li>
                                    <li class="md-list-item-disabled">
                                        <a class="md-list-content" href="#password" data-uk-modal="{center:true}">
                                            <span class="md-list-heading"><?=$lang['s_password']?></span>
                                            <span class="uk-text-muted uk-text-small"><?=$lang['s_password_desc']?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="md-list-content"  href="#language" data-uk-modal="{center:true}">
                                            <span class="md-list-heading"><?=$lang['s_lang']?></span>
                                            <span class="uk-text-muted uk-text-small"><?=$lang['s_lang_desc']?></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
        </div></div>

     <div class="uk-modal" id="password">
        <div class="uk-modal-dialog">
            <button class="uk-modal-close uk-close" type="button"></button>
            <form>
                <div class="uk-modal-header">
                    <h3 class="uk-modal-title">Set profile password</h3>
                </div>
                <div class="uk-margin-medium-bottom">
                    <label for="pass1">Old Password</label>
                    <input type="password" class="input-count md-input" id="pass1"/>
                </div> 
                <div class="uk-margin-medium-bottom">
                    <label for="pass2">New Password</label>
                    <input type="password" class="input-count md-input" maxlength="18" id="pass2"/>
                </div>
                <div class="uk-margin-medium-bottom">
                    <label for="pass3">Repeat Password</label>
                    <input type="password" class="input-count md-input" maxlength="18" id="pass3"/>
                </div>
               
                <div class="uk-modal-footer">
                    <span class="errorpage"></span>
                    <button type="button" onclick="addPassword()" class="uk-float-right md-btn md-btn-flat md-btn-flat-primary"><?=$lang['btn_save']?></button>
                </div>
            </form>
        </div>
    </div>

    <div class="uk-modal" id="language">
        <div class="uk-modal-dialog">
            <button class="uk-modal-close uk-close" type="button"></button>
            <form>
                <div class="uk-modal-header">
                    <h3 class="uk-modal-title"><?=$lang['s_lang_choose']?></h3>
                </div>
                  
                    <p>
                        <input type="radio" name="lang" value="en" id="en" data-md-icheck />
                        <label for="en" class="inline-label">English</label>
                    </p>
                    <p>
                        <input type="radio" name="lang" value="ru" id="ru" data-md-icheck />
                        <label for="ru" class="inline-label">Russian</label>
                    </p>
                    <p>
                        <input type="radio" name="lang" value="ro" id="ro" data-md-icheck />
                        <label for="ro" class="inline-label">Romanian</label>
                    </p>
                    <p>
                        <input type="radio" name="lang" value="fr" id="fr" data-md-icheck />
                        <label for="fr" class="inline-label">Francais</label>
                    </p>        
                <div class="uk-modal-footer">
                    <span class="errorpage"></span>
                    <button type="button" onclick="saveLang();" class="uk-float-right md-btn md-btn-flat md-btn-flat-primary"><?=$lang['btn_save']?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- google web fonts -->
    <script>
         $('#settings_seo').change(function() {
            var val = "no";
            if($(this).is(":checked")) {
                UIkit.modal.confirm('Are you sure?', function(){ 
                    $.get( "<?=$config['api']?>/settings/notification?id=<?=$id?>&val=yes");
                 });
             
            }  else {
                UIkit.modal.confirm('Are you sure?', function(){
                    $.get( "<?=$config['api']?>/settings/notification?id=<?=$id?>&val=no");
                 });
            }    
            

          });

         function saveLang(){
            var userid = <?=$id?>;
            var lang   = $("input[name='lang']:checked").val();
            $.get( "<?=$config['api']?>/lang?id=<?=$id?>&lang="+lang,function(data){
                location.reload();
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