<?php
session_start();
include('informme.class.php');
if(isset($_GET['user']))
{
  	$id = intval($_GET['user']);
	$app = new inForm();
	$user =  $app->getUser($id);
	$app->addPro($id);
    header('Location: login.php');

}
