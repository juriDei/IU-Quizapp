<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

class ActivationController
{
    protected $auth;

    public function __construct()
    {
        $dbh = DBConnection::getDBConnection();
        $config = new PHPAuthConfig($dbh);
        $this->auth = new PHPAuth($dbh, $config);
    }

    public function activate()
    {
        $token = $_POST['token'];

        $activation = $this->auth->activate($token);

        if (!$activation['error']) {
            MessageHandlerController::addSuccess('Ihr Konto wurde erfolgreich aktiviert.');
            header('Location: /quizapp/login');
            exit();
        }

        MessageHandlerController::addError($activation['message']);
        header('Location: /quizapp/activate');
        exit();
    }
}