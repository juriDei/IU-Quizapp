<?php
require_once dirname(__DIR__,2).'/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

$dbh = new PDO("mysql:host=localhost;dbname=nutzerverwaltung", "juri", "Renolino!?94");

$config = new PHPAuthConfig($dbh);
$auth = new PHPAuth($dbh, $config);

$email = $_POST['email'];
$password = $_POST['password'];
$remember_me = ($_POST['remember'] == 'on') ? 1 : 0;


if(!$auth->login($email,$password,$remember_me)['error']){
    header('Location: /admin/dashboard');
    exit();
}

$_SESSION['error'] = $auth->login($email,$password)['message'];
header('Location: /admin/login');
exit();


