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
  $publicRoutes = ['/quizapp/login', '/quizapp/register', '/quizapp/forget-pass', '/quizapp/forget-pass-reset', '/quizapp/activate']; // Fügen Sie hier alle öffentlichen Routen hinzu

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

$router->post('/avatar-upload', function () {
  $controller = new UploadController();
  $controller->upload();
});

$router->post('/quizsession/create', function () {
  $controller = new QuizSessionController();
  $controller->create();
});

$router->post('/quizsession/save-answer', function () {
  $controller = new QuizSessionController($_SESSION['session_id']);
  $controller->saveAnswer();
});

$router->post('/quizsession/complete', function () {
  $controller = new QuizSessionController($_SESSION['session_id']);
  $controller->completeQuizSession();
});

$router->post('/quizsession/cancel', function () {
  $input = json_decode(file_get_contents('php://input'), true);

    // Überprüfen, ob der Parameter 'quiz_session_id' gesendet wurde
    if (isset($input['quiz_session_id'])) {
      $quizSessionId = $input['quiz_session_id'];

      // Hier deinen Controller verwenden, um die Session zu löschen
      $controller = new QuizSessionController($quizSessionId);
      $controller->cancel();

      // Erfolgsnachricht zurücksenden
      echo json_encode(['message' => 'Quizsession erfolgreich abgebrochen!']);
  } else {
      // Fehlernachricht, falls der Parameter fehlt
      http_response_code(400); // Bad Request
      echo json_encode(['message' => 'Fehler: quiz_session_id fehlt.']);
  }
});

$router->post('/quizsession/complete', function () {
  $controller = new QuizSessionController($_SESSION['session_id']);
  $controller->completeQuizSession();
});

$router->post('/questioncatalog/create', function () {
  $controller = new QuestionController();
  $controller->createCatalog();
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

$router->get('/quizsession/get-student-answers', function () {
  $controller = new QuizSessionController($_SESSION['session_id']);
  $controller->getStudentAnswers();
});


// GET-Route für die Quizsession mit Übergabe der quizsession_id
$router->get('/quizsession', function () {
  $_SESSION['view'] = 'Quizsession';
  include("view/pages/quiz-session.php");
});

// GET-Route für die Quizsession mit Übergabe der quizsession_id
$router->get('/quizsessionerror', function () {
  $_SESSION['view'] = 'Quizsession';
  include("view/pages/quiz-session-error.php");
});


// GET-Route für die Auswertung der Quiz-Session
$router->get('/quizsessionresult', function () {
  $sessionId = $_GET['session_id'] ?? null;
  if ($sessionId) {
    $_SESSION['view'] = 'Quizauswertung';
    include("view/pages/quiz-session-result.php");
  } else {
    http_response_code(400);
    echo json_encode(['message' => 'Session ID fehlt']);
  }
});


$router->set404(function () {
  include("view/pages/dashboard.php");
});


//Logoutroute
$router->get('/logout', function () {
  include("view/pages/logout.php");
});


$router->run();
