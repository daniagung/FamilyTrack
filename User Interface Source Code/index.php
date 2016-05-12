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
$user   =  $app->getUser($id);
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
                <?php
                if($user["type"] == "0") {
                ?>
                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav user_actions">
                        <li data-uk-dropdown="{mode:'click'}">
                            <a href="#" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE7F4;</i><span class="uk-badge">1</span></a>
                            <div class="uk-dropdown uk-dropdown-xlarge uk-dropdown-flip uk-dropdown-scrollable">
                                <ul class="md-list md-list-addon">
                                    <li>
                                        <div class="md-list-addon-element">
                                            <i class="md-list-addon-icon material-icons uk-text-warning">&#xE8B2;</i>
                                        </div>
                                        <div class="md-list-content">
                                            <span class="md-list-heading">Freemium</span>
                                            <span class="uk-text-small uk-text-muted uk-text-truncate">Your freemium expire in <?=$app->expirePro($id)?> days.</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <? } ?>
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
                <hr>
                <li>
                    <a href="/panel.php">
                        <span class="menu_icon material-icons">build</span>
                        Admin (Demo)
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

          <div class="uk-grid-width-small-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 uk-grid-width-xlarge-1-5 hierarchical_show" id="contact_list">
            <!-- Users Lists -->
            <?php
                $childs = $app->getChilds($id);
                foreach ($childs as $key) {
                  //print_r($key);
                 ?>
                  <div data-uk-filter="goodwin-nienow,ima prosacco" id="idik<?=$key[11]?>">
                          <div class="md-card md-card-hover">
                              <div class="md-card-head">
                                  <div class="md-card-head-menu" data-uk-dropdown>
                                      <i class="md-icon material-icons">&#xE5D4;</i>
                                      <div class="uk-dropdown uk-dropdown-small uk-dropdown-flip">
                                          <ul class="uk-nav">
                                              <li><a href="javascript:removeChild('<?=$key[11]?>')"><?=$lang["remove"]?></a></li>
                                          </ul>
                                      </div>
                                  </div>
                                  <div class="uk-text-center">
                                      <img class="md-card-head-avatar" src="<?=$key['photo']?>" alt=""/>
                                  </div>
                                  <h3 class="md-card-head-text uk-text-center">
                                      <?=$key['name']?>
                                      <span class="uk-text-truncate"><?=$key['email']?></span>
                                  </h3>
                              </div>
                              <div class="md-card-footer">
                                  <a class="md-btn md-btn-block md-btn-flat" href="details.php?child=<?=$key['id']?>"><?=$lang["details"]?></a>
                              </div>
                          </div>
                      </div>


                       <?php
                      }
                  ?>
              </div>
        </div>
    </div>

     <div class="md-fab-wrapper">
        <a class="md-fab md-fab-accent" href="#mailbox_new_message" data-uk-modal="{center:true}">
            <i class="material-icons">&#xE145;</i>
        </a>
    </div>

    <div class="uk-modal" id="mailbox_new_message">
        <div class="uk-modal-dialog">
            <button class="uk-modal-close uk-close" type="button"></button>
            <form>
                <div class="uk-modal-header">
                    <h3 class="uk-modal-title"><?=$lang["add_title"]?></h3>
                </div>
                <div class="uk-margin-medium-bottom">
                    <label for="mail_new_to">Email</label>
                    <input type="email" class="md-input" id="mail_new_to"/>
                </div>
               
                <div class="uk-modal-footer">
                    <span class="errorpage"></span>
                    <button type="button" onclick="addUser()" class="uk-float-right md-btn md-btn-flat md-btn-flat-primary"><?=$lang["btn_add"]?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- google web fonts -->
    <script>

        function removeChild(idik)
        {
           $.get( "<?=$config['api']?>follow/delete?id="+idik,function(data){ 
                   location.reload();
               });
        }
        function addUser()
        {
           $(".errorpage").text("");
          var mail = $("#mail_new_to").val();
          $.get( "<?=$config['api']?>follow/add?id=<?=$id?>&child="+mail, function( data ) {
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
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-69268273-1', 'auto');
  ga('send', 'pageview');

</script>

    <!-- momentJS date library -->
    <script src="bower_components/moment/min/moment.min.js"></script>

    <!-- common functions -->
    <script src="assets/js/common.min.js"></script>
    <!-- uikit functions -->
    <script src="assets/js/uikit_custom.min.js"></script>
    <!-- altair common functions/helpers -->
    <script src="assets/js/altair_admin_common.min.js"></script>

    <!--  contact list functions -->
    <script src="assets/js/pages/page_contact_list.min.js"></script>
    
    <!-- enable hires images -->
    <script>
        $(function() {
            altair_helpers.retina_images();
        });
    </script>

</body>
</html>