<?php
session_start();

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;


class LoginController
{
    protected $auth;

    public function __construct()
    {
        $dbh = DBConnection::getDBConnection();
        $config = new PHPAuthConfig($dbh);
        $this->auth = new PHPAuth($dbh, $config);
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $remember_me = ($_POST['remember'] == 'on') ? true : false;

        $login = $this->auth->login($email, $password, $remember_me);

        if (!$login['error']) {
            $_SESSION['uid'] = $this->auth->getUID($email);
            header('Location: /quizapp/dashboard');
            exit();
        }

        MessageHandlerController::addError($login['message']);
        header('Location: /quizapp/login');
        exit();
    }
}
