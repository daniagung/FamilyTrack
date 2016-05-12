<?php
session_start(); //session start

require_once ('libraries/Google/autoload.php');
include('informme.class.php');
$app = new inForm();
$config = $app->getConfig();

//Insert your cient ID and secret 
//You can get it from : https://console.developers.google.com/
$client_id = $config["client_id"]; 
$client_secret = $config["client_secret"];
$redirect_uri =  $config["site_url"]."login.php";

//database
$db_username =  $config["username"]; //Database Username
$db_password =  $config["password"]; //Database Password
$host_name =    $config["server"]; //Mysql Hostname
$db_name =      $config["database"]; //Database Name

        $connection =  mysql_connect($host_name, $db_username, $db_password) or die ('Error');
        mysql_set_charset('utf8',$connection);
        mysql_select_db($db_name,$connection);

//incase of logout request, just unset the session var
if (isset($_GET['logout'])) {
  unset($_SESSION['access_token']);
  unset($_SESSION['user_id']);
  unset($_SESSION['logged_in']);
}

/************************************************
  Make an API request on behalf of a user. In
  this case we need to have a valid OAuth 2.0
  token for the user, so we need to send them
  through a login flow. To do this we need some
  information from our API console project.
 ************************************************/
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

/************************************************
  When we create the service here, we pass the
  client to it. The client then queries the service
  for the required scopes, and uses that when
  generating the authentication URL later.
 ************************************************/
$service = new Google_Service_Oauth2($client);

/************************************************
  If we have a code back from the OAuth 2.0 flow,
  we need to exchange that with the authenticate()
  function. We store the resultant access token
  bundle in the session, and redirect to ourself.
*/
  
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
  exit;
}

/************************************************
  If we have an access token, we can make
  requests, else we generate an authentication URL.
 ************************************************/
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $authUrl = $client->createAuthUrl();
}


if (isset($authUrl)){ 

?>

<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="assets/img/favicon.ico" sizes="16x16">

    <title>Informme v1.0.0 - Login Page</title>

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>

    <!-- uikit -->
    <link rel="stylesheet" href="bower_components/uikit/css/uikit.almost-flat.min.css"/>

    <!-- altair admin login page -->
    <link rel="stylesheet" href="assets/css/login_page.min.css" />

</head>
<body class="login_page">

    <div class="login_page_wrapper">
        <div class="md-card" id="login_card">
            <div class="md-card-content large-padding" id="login_form">
                <div class="login_heading">
                    <div class="user_avatar"></div>
                </div>
                <form action="dashboard.html">
                    <div class="uk-margin-top">
                       <a href="<?=$authUrl?>">
                       		<img src="http://www.kitchenplatter.com/vendor/content/images/googleplus_button.png">
                       </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- common functions -->
    <script src="assets/js/common.min.js"></script>
    <!-- altair core functions -->
    <script src="assets/js/altair_admin_common.min.js"></script>

    <!-- altair login page functions -->
    <script src="assets/js/pages/login_page.min.js"></script>

</body>
</html>
<?php
	
} else {
	
	$user = $service->userinfo->get(); //get user info 

	$query = mysql_query("SELECT * FROM users WHERE email = '".$user->email."' LIMIT 1");
  $nr = mysql_num_rows($query);
	if($nr == 0)
	{
		$query = mysql_query("INSERT INTO users(email,firstname,photo,type,data) VALUES('".$user->email."','".$user->name."','".$user->picture."?sz=50','0',NOW())");
    if($query)
		{
				$id = mysql_insert_id();
				$_SESSION['user_id'] = $id;
				$_SESSION['logged_in'] = true;
		}
	}
	else {
		$row = mysql_fetch_array($query);
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['logged_in'] = true;
	}

 	header('Location: ' . filter_var($config["site_url"], FILTER_SANITIZE_URL));
}

?>

