<?php
 
ini_set('display_errors', 1);
require_once 'class/inform.class.php';
require 'library/Slim/Slim.php';

require 'library/PayPal/autoload.php';
 
use PayPal\Api\Payment;

\Slim\Slim::registerAutoloader();
$app     = new \Slim\Slim();
$app->contentType('text/html; charset=utf-8');

$app->get('/', function () use ($app) {

	$response['info'] 		= "InForm";
	$response['date'] 		= "2015";
	$response['copyright'] 	= "Calin Staver";


	echoRespnse(200, $response);

});

$app->get('/lang', function () use ($app) {

  $get = $app->request->get();
  //print_r($get);
  $db = new inForm();
  $user = $db->changeLang($get['id'],$get['lang']);
  $response = $user;
  echoRespnse(200, $response);

});

$app->get('/follow/add', function () use ($app) {

  $get = $app->request->get();
  //print_r($get);
  $db = new inForm();
  $user = $db->addFollow($get['id'],$get['child']);
  $response = $user;
  echoRespnse(200, $response);

});

$app->get('/follow/delete', function () use ($app) {

  $get = $app->request->get();
  //print_r($get);
  $db = new inForm();
  $user = $db->deleteFollow($get['id']);
  $response = $user;
  echoRespnse(200, $response);

});

$app->get('/settings/notification', function () use ($app) {

  $get = $app->request->get();
  //print_r($get);
  $db = new inForm();
  $user = $db->changeNotifiaction($get['val'],$get['id']);
  echoRespnse(200, $get['val']);
});

$app->get('/user/add', function () use ($app) {

	$get = $app->request->get();
	//print_r($get);
	$db = new inForm();
	$user = $db->addUser($get);
	if($user['error'] == 0)
	{
		$response["error"] = false;
		$response["user"] = $user['info'];
	} else
	{
		$response["error"] = true;
		$response["message"] = $user['message'];
	}
	echoRespnse(200, $response);

});



$app->get('/test', function () use ($app) {

	$get = $app->request->get();
	$g = json_decode($get['post'], true);
	//print_r($g['nameValuePairs']);
	$sms = $g['nameValuePairs']['sms']['values'];
	$loc = $g['nameValuePairs']['loc']['values'];
	$calls = $g['nameValuePairs']['calls']['values'];
	$battery = $get['battery'];
	$db = new inForm();
	$user = $db->getData($calls,$sms,$loc,$battery);
	if($user['error'] == 0)
	{
		$response["error"] = false;
		$response["info"] = $user['info'];
	} else
	{
		$response["error"] = true;
		$response["message"] = $user['message'];
	}
	echoRespnse(200, $response);

});

$app->get('/user/search', function () use ($app) {

	$get = $app->request->get();
	$db = new inForm();
	$user = $db->userSearch($get);
	if($user['error'] == 0)
	{
		$response["error"] = false;
		$response["info"] = $user['info'];
	} else
	{
		$response["error"] = true;
		$response["message"] = $user['message'];
	}
	echoRespnse(200, $response);

});

$app->get('/calls/search', function () use ($app) {

	$get = $app->request->get();
	$db = new inForm();
	$user = $db->getCalls(intval($get['id']));
	if($user['error'] == 0)
	{
		//$response["error"] = false;
		$response = $user['calls'];
	} else
	{
		$response["error"] = true;
		$response["message"] = $user['message'];
	}
	echoRespnse(200, $response);

});

$app->get('/location/search', function () use ($app) {

	$get = $app->request->get();
	$db = new inForm();
	$user = $db->getLocations(intval($get['id']));
	if($user['error'] == 0)
	{
		//$response["error"] = false;
		$response = $user['locations'];
	} else
	{
		$response["error"] = true;
		$response["message"] = $user['message'];
	}
	echoRespnse(200, $response);

});

$app->get('/location/call', function () use ($app) {

	$get = $app->request->get();
	$db = new inForm();
	$user = $db->getPhoneLocation(intval($get['id']),$get['data']);
	if($user['error'] == 0)
	{
		//$response["error"] = false;
		$response['info'] = $user['locations'];
	} else
	{
		$response["error"] = true;
		$response["message"] = $user['message'];
	}
	echoRespnse(200, $response);

});

$app->get('/messages/search', function () use ($app) {

	$get = $app->request->get();
	$db = new inForm();
	$user = $db->getMessages(intval($get['id']));
	if($user['error'] == 0)
	{
		//$response["error"] = false;
		$response = $user['messages'];
	} else
	{
		$response["error"] = true;
		$response["message"] = $user['message'];
	}
	echoRespnse(200, $response);

});



/**
 * verifying the mobile payment on the server side
 * method - POST
 * @param paymentId paypal payment id
 * @param paymentClientJson paypal json after the payment
 */
$app->post('/verifyPayment', function() use ($app) {
 
            $response["error"] = false;
            $response["message"] = "Payment verified successfully";
 
                $paymentId = $app->request()->post('paymentId');
                $userId    = $app->request()->post('id_user');
                $payment_client = json_decode($app->request()->post('paymentClientJson'), true);
                // Storing the payment in payments table
                $db = new inForm();
                $payment_id_in_db = $db->storePayment($paymentId, $userId);

                echoRespnse(200, $response);
        });


/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $data)
		{
				$app = \Slim\Slim::getInstance();
				// Http response code
				$app->status($status_code);
				// setting response content type to json
				$app->contentType('application/json; charset=utf-8');
				//$app->header('Access-Control-Allow-Origin', '*');
				//$app->contentType('text/html; charset=utf-8');
				$response = $app->response();
				$response->header('Access-Control-Allow-Origin', '*');
				$response->write(json_encode($data));
				//echo json_encode($data);
		}
$app->run();
