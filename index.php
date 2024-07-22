<?php
session_start();
define('BASE_URL', '/quizapp/');
// Autoload files using composer
require_once dirname(__DIR__,) . '/vendor/autoload.php';


//PHPAuth 
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;


function checkAuth($auth, $currentRoute)
{
  // Login-Route und möglicherweise andere öffentliche Routen ausschließen
  $publicRoutes = ['/quizapp/login', '/quizapp/register', '/quizapp/forget-pass', '/quizapp/activate']; // Fügen Sie hier alle öffentlichen Routen hinzu

  if (in_array($currentRoute, $publicRoutes)) {
    return;
  }
  if (!$auth->isLogged()) {
    header('Location: ' . BASE_URL . 'login');
    exit();
  }
}

$dbh = DBConnection::getDBConnection();
$config = new PHPAuthConfig(DBConnection::getDBConnection());
$auth = new PHPAuth(DBConnection::getDBConnection(), $config);


// create composer instances
$router = new \Bramus\Router\Router();

$router->before('GET', '/.*', function () use ($auth) {
  $currentRoute = $_SERVER['REQUEST_URI'];

  if ($currentRoute != '/quizapp/login') {
    checkAuth($auth, $currentRoute);
  }
});

//POST-Routen/Controller
$router->post('/register', function () {
  $controller = new RegisterController();
  $controller->register();
});
$router->post('/forget-pass-request', function () {
  $controller = new PasswordResetController();
  $controller->requestReset();
});
$router->post('/forget-pass-reset', function () {
  $controller = new PasswordResetController();
  $controller->resetPassword();
});
$router->post('/login', function () {
  $controller = new LoginController();
  $controller->login();
});
$router->post('/activate', function () {
  $controller = new ActivationController();
  $controller->activate();
});
$router->post('/questions/create', function () {
  $controller = new QuestionController();
  $controller->create();
});


//GET-Routen/Seiten
$router->get('/login', function () {
  include("view/pages/login.php");
});

$router->get('/register', function () {
  include("view/pages/register.php");
});

$router->get('/activate', function () {
  include("view/pages/activate.php");
});
$router->get('/forget-pass', function () {
  include("view/pages/forget-pass.php");
});
$router->get('/forget-pass-reset', function () {
  include("view/pages/forget-pass-reset.php");
});
$router->get('/dashboard', function () {
  $_SESSION['view'] = 'Dashboard';
  include("view/pages/dashboard.php");
});
$router->get('/statistics', function () {
  $_SESSION['view'] = 'Statistiken';
  include("view/pages/statistics.php");
});
$router->get('/question-catalog-overview', function () {
  $_SESSION['view'] = 'Fragenkatalogübersicht';
  include("view/pages/question-catalog-overview.php");
});

$router->get('/questions/catalog-questions', function () {
  $moduleId = $_GET['module_id'] ?? null;
  if ($moduleId) {
    $controller = new QuestionController();
    $controller->getCatalogQuestions($moduleId);
  } else {
    http_response_code(400);
    echo json_encode(['message' => 'Module ID fehlt']);
  }
});

$router->get('/questions/student-questions', function () {
  $moduleId = $_GET['module_id'] ?? null;
  if ($moduleId) {
    $controller = new QuestionController();
    $controller->getStudentQuestions($moduleId);
  } else {
    http_response_code(400);
    echo json_encode(['message' => 'Module ID fehlt']);
  }
});

$router->get('/logout', function () use ($auth) {
  $userHash = $auth->getCurrentSessionHash();
  $auth->logout($userHash);
  header('Location: ' . BASE_URL . 'login');
  exit();
});
$router->set404(function () {
  include("view/pages/dashboard.php");
});


//Logoutroute
$router->get('/logout', function () {
  include("view/pages/logout.php");
});


$router->run();
