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

    public function __construct($userId)
    {
        $this->dbh = DBConnection::getDBConnection();
        $config = new PHPAuthConfig($this->dbh);
        $this->auth = new PHPAuth($this->dbh, $config);
        $this->userId = (!empty($userId)) ? $userId : $this->auth->getCurrentUser()['id'];
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

    public function getCourseOfStudy(){
        return $this->userData['course_of_study'];
    }


    public function getAvatar() {
        if ($this->userData['avatar']) {
            // Bild aus der Datenbank abrufen und in Base64 umwandeln
            $stmt = $this->dbh->prepare("SELECT avatar FROM phpauth_users WHERE id = :id");
            $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
            $stmt->execute();
            $avatarBlob = $stmt->fetchColumn();

            if ($avatarBlob !== false) {
                // Erkennen des Bildtyps für den Data-URL Prefix
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($avatarBlob);
                $base64 = base64_encode($avatarBlob);
                return "data:$mimeType;base64,$base64";
            }
        }
        return null; // Rückgabe von null, falls kein Avatar gesetzt ist
    }

    public function setEmail($email)
    {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET email = :email WHERE id = :id");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setFirstname($firstname)
    {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET firstname = :firstname WHERE id = :id");
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setLastname($lastname)
    {
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

    public function setAvatar($avatarBlob) {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET avatar = :avatar WHERE id = :id");
        $stmt->bindParam(':avatar', $avatarBlob, PDO::PARAM_LOB);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function setCourseOfStudy($courseOfStudy) {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET course_of_study = :courseOfStudy WHERE id = :id");
        $stmt->bindParam(':courseOfStudy', $courseOfStudy, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getOpenQuizSessions(){
        $stmt = $this->dbh->prepare("
            SELECT * FROM `quiz_session_players` as qsp
            INNER JOIN `quiz_sessions` qs ON qsp.quiz_session_id = qs.id
            WHERE qsp.player_id = :userId AND qs.status = 'active'
        ");
        $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        $quizSessionArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $quizSessionArr;
    }


    public function getCompletedQuizSessions(){
        $stmt = $this->dbh->prepare("
            SELECT * FROM `quiz_session_players` as qsp
            INNER JOIN `quiz_sessions` qs ON qsp.quiz_session_id = qs.id
            WHERE qsp.player_id = :userId AND (qs.status = 'completed' OR qs.status = 'cancelled')
            ORDER BY qs.updated_at desc
        ");
        $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        $quizSessionArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $quizSessionArr;
    } 
}
