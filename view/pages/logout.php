<?php 
require_once dirname(__DIR__,3).'/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

$dbh = new PDO("mysql:host=localhost;dbname=nutzerverwaltung", "juri", "Renolino!?94");

$config = new PHPAuthConfig($dbh);
$auth = new PHPAuth($dbh, $config);
$hash = $auth->getCurrentSessionHash();
$auth->logout($hash);
header('Location: /admin/login');
exit();
