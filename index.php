<?php
session_start();
define('BASE_URL','/quizapp/');
// Autoload files using composer
require_once 'controller/DBConnection.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';


//PHPAuth 
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

function checkAuth($auth,$currentRoute) {
  // Login-Route und möglicherweise andere öffentliche Routen ausschließen
   $publicRoutes = ['/quizapp/login', '/quizapp/register', '/quizapp/forget-pass']; // Fügen Sie hier alle öffentlichen Routen hinzu

  if (in_array($currentRoute, $publicRoutes)) {
        return;
  }
  if (!$auth->isLogged()) {
      header('Location: ' . BASE_URL . 'login');
      exit();
  }
}


$config = new PHPAuthConfig(DBConnection::getDBConnection());
$auth = new PHPAuth(DBConnection::getDBConnection(), $config);


  // create composer instances
  $router = new \Bramus\Router\Router();

  $router->before('GET', '/.*', function() use ($auth) {
    $currentRoute = $_SERVER['REQUEST_URI'];
    if($currentRoute != '/quizapp/login'){
      checkAuth($auth,$currentRoute);
    }
  });

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
  $router->set404(function() {
    include("view/pages/dashboard.php");
  });


//Logoutroute
$router->get('/logout', function() {
  include("view/pages/logout.php");
});


$router->run();