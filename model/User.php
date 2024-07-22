<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

class UserModel
{
    protected $dbh;
    protected $auth;
    protected $userId;
    protected $userData;

    public function __construct()
    {
        $this->dbh = DBConnection::getDBConnection();
        $config = new PHPAuthConfig($this->dbh);
        $this->auth = new PHPAuth($this->dbh, $config);
        $this->userId = $this->auth->getCurrentUser()['id'];
        $this->loadUserData();
    }

    protected function loadUserData()
    {
        
        $stmt = $this->dbh->prepare("SELECT * FROM phpauth_users WHERE id = :id");
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        $this->userData = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserId()
    {
        return $this->userData['id'];
    }

    public function getEmail()
    {
        return $this->userData['email'];
    }

    public function getFirstname()
    {
        return $this->userData['firstname'];
    }

    public function getLastname()
    {
        return $this->userData['lastname'];
    }

    public function getFullname(){
        return $this->getFirstname()." ".$this->getLastname();
    }

    public function isActive()
    {
        return $this->userData['isactive'];
    }

    public function getLastLogin()
    {
        return $this->userData['last_login'];
    }

    public function setEmail($email)
    {
        $this->userData['email'] = $email;
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET email = :email WHERE id = :id");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setFirstname($firstname)
    {
        $this->userData['firstname'] = $firstname;
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET firstname = :firstname WHERE id = :id");
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setLastname($lastname)
    {
        $this->userData['lastname'] = $lastname;
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET lastname = :lastname WHERE id = :id");
        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setPassword($password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
