<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;


class RegisterController
{
    protected $auth;
    protected $mailController;

    public function __construct()
    {
        $dbh = DBConnection::getDBConnection();
        $config = new PHPAuthConfig($dbh);
        $this->auth = new PHPAuth($dbh, $config);
        $this->mailController = new MailController();
    }


    public function register()
    {
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $domain = $_POST['domain'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['password_repeat'];
        $emailWithDomain = $email.$domain;

        $register = $this->auth->register($emailWithDomain, $password, $passwordRepeat,[],'',true);

        if (!$register['error']) {
            // Benutzer erfolgreich registriert
            $userId = $register['uid'];
            $user = new UserModel($userId);
            $user->setFirstName($firstname);
            $user->setLastName($lastname);
            /*// Aktivierungslink versenden
            $token = $register['token'];

            $subject = "Bestätigung Ihrer E-Mailadresse";
            $body = "Bitte klicken Sie auf den folgenden Link, um Ihre Registrierung abzuschließen: <a href='http://localhost/quizapp/activate?{$token}'>http://localhost/quizapp/activate?{$token}</a>";

            $this->mailController->sendActivationEmail($email, $subject, $body);*/
            
            MessageHandlerController::addSuccess('Registrierung erfolgreich! Bitte überprüfen Sie Ihre E-Mails zur Bestätigung.');
            header('Location: /quizapp/login');
            exit();
        }
        
        MessageHandlerController::addError($register['message']);
        header('Location: /quizapp/register');
        exit();
    }
}