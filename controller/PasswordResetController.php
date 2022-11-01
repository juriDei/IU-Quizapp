<?php
/*
$to_email = "deisling94@gmail.com";
$subject = "Simple Email Test via PHP";
$body = "Hi,nn This is test email send by PHP Script";
$headers = "From: sender\'s email";
 
if (mail($to_email, $subject, $body, $headers)) {
    echo "Email successfully sent to $to_email...";
} else {
    echo "Email sending failed...";
}*/

require_once dirname(__DIR__,2).'/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

$dbh = new PDO("mysql:host=localhost;dbname=nutzerverwaltung", "juri", "Renolino!?94");

$config = new PHPAuthConfig($dbh);
$auth = new PHPAuth($dbh, $config);

$email = $_POST['email'];

if(!$auth->requestReset($email)['error'])
{
    header('Location: ../forget-pass.php');
    exit();
}

$_SESSION['error'] = $auth->requestReset($email)['message'];
header('Location: ../forget-pass.php');
exit();
?>