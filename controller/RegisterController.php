<?php
require_once dirname(__DIR__,2).'/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

$dbh = new PDO("mysql:host=localhost;dbname=quizapp", "juri", "Renolino!?94");

$config = new PHPAuthConfig($dbh);
$auth = new PHPAuth($dbh, $config);

$email = $_POST['email'];
$password = $_POST['password'];

if(!$auth->register($email,$password,$password)['error'])
{
    header('Location: /quizapp/login');
    exit();
}

$_SESSION['error'] = $auth->register($email,$password,$password)['message'];
header('Location: /quizapp/register');
exit();
