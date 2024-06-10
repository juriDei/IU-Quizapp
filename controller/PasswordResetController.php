<?php

require_once dirname(__DIR__,2).'/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

$dbh = new PDO("mysql:host=localhost;dbname=quizapp", "juri", "Renolino!?94");

$config = new PHPAuthConfig($dbh);
$auth = new PHPAuth($dbh, $config);

$email = $_POST['email'];

if(!$auth->requestReset($email)['error'])
{
    header('Location: /quizapp/forget-pass.php');
    exit();
}

$_SESSION['error'] = $auth->requestReset($email)['message'];
header('Location: /quizapp/forget-pass.php');
exit();
?>