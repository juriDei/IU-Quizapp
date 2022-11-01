<?php
session_start();
// Autoload files using composer
require_once 'controller/DBConnection.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';


//PHPAuth 
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;



$config = new PHPAuthConfig(DBConnection::getDBConnection());
$auth = new PHPAuth(DBConnection::getDBConnection(), $config);


  // create composer instances
  $router = new \Bramus\Router\Router();
  //POST-Routen/Controller
  $router->post('/controller/register', function() {
    include("controller/RegisterController.php");
  });
  $router->post('/controller/passwordReset', function() {
    include("controller/PasswordResetController.php");
  });
  $router->post('/controller/login', function() {
    include("controller/LoginController.php");
  });


  //GET-Routen/Seiten
  $router->get('/login', function() {
    include("view/pages/login.php");
  });

  $router->get('/register', function() {
    include("view/pages/register.php");
  });
  $router->get('/forget-pass', function() {
    include("view/pages/forget-pass.php");
  });
  $router->get('/dashboard', function() {
    $_SESSION['view'] = 'Dashboard';
    include("view/pages/dashboard.php");
  });
  $router->get('/meldung', function() {
    $_SESSION['view'] = 'Meldung';
    include("view/pages/meldung.php");
  });
  $router->get('/institutionsuebersicht', function() {
    $_SESSION['view'] = 'Institutionsübersicht';
    include("view/pages/institutionsuebersicht.php");
  });

//Logoutroute
$router->get('/logout', function() {
  include("view/pages/logout.php");
});


$router->run();