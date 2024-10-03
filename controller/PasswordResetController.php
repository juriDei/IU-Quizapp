<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

class PasswordResetController
{
    protected $auth;

    public function __construct()
    {
        $dbh = DBConnection::getDBConnection();
        $config = new PHPAuthConfig($dbh);
        $this->auth = new PHPAuth($dbh, $config);
    }

    public function requestReset()
    {
        $email = $_POST['email'];
        $resetRequest = $this->auth->requestReset($email,true);

        if (!$resetRequest['error']) {
            MessageHandlerController::addSuccess('Ein Link zum Zurücksetzen des Passworts wurde an Ihre E-Mail-Adresse gesendet.');
            header('Location: /quizapp/login');
            exit();
        }

        MessageHandlerController::addError($resetRequest['message']);
        header('Location: /quizapp/forget-pass');
        exit();
    }

    public function resetPassword()
    {
        $key = $_POST['key'];
        $newPassword = $_POST['password'];
        $passwordRepeat = $_POST['password_repeat'];

        $reset = $this->auth->resetPass($key, $newPassword, $passwordRepeat);

        if (!$reset['error']) {
            MessageHandlerController::addSuccess('Ihr Passwort wurde erfolgreich zurückgesetzt.');
            header('Location: /quizapp/login');
            exit();
        }

        MessageHandlerController::addError($reset['message']);
        header('Location: /quizapp/forget-pass-reset');
        exit();
    }
}
