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

    // Konstruktor der Klasse, der die Datenbankverbindung initialisiert und das Authentifizierungsobjekt erstellt
    // Parameter: $userId (ID des Benutzers, wenn nicht vorhanden, wird der aktuelle Benutzer verwendet)
    public function __construct($userId)
    {
        // Initialisierung der Datenbankverbindung durch Aufruf der Singleton-Methode getDBConnection
        $this->dbh = DBConnection::getDBConnection();
        
        // Initialisierung der PHPAuth-Konfiguration und des Authentifizierungsobjekts
        $config = new PHPAuthConfig($this->dbh);
        $this->auth = new PHPAuth($this->dbh, $config);
        
        // Setzt die Benutzer-ID auf den übergebenen Wert oder den aktuell angemeldeten Benutzer
        $this->userId = (!empty($userId)) ? $userId : $this->auth->getCurrentUser()['id'];
        
        // Laden der Benutzerdaten
        $this->loadUserData();
    }

    // Geschützte Funktion zum Laden der Benutzerdaten basierend auf der Benutzer-ID
    protected function loadUserData()
    {
        $stmt = $this->dbh->prepare("SELECT * FROM phpauth_users WHERE id = :id");
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        $this->userData = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Funktion zum Abrufen der Benutzer-ID
    public function getUserId()
    {
        return $this->userData['id'];
    }

    // Funktion zum Abrufen der E-Mail-Adresse des Benutzers
    public function getEmail()
    {
        return $this->userData['email'];
    }

    // Funktion zum Abrufen des Vornamens des Benutzers
    public function getFirstname()
    {
        return $this->userData['firstname'];
    }

    // Funktion zum Abrufen des Nachnamens des Benutzers
    public function getLastname()
    {
        return $this->userData['lastname'];
    }

    // Funktion zum Abrufen des vollen Namens des Benutzers
    public function getFullname()
    {
        return $this->getFirstname() . " " . $this->getLastname();
    }

    // Funktion zum Prüfen, ob der Benutzer aktiv ist
    public function isActive()
    {
        return $this->userData['isactive'];
    }

    // Funktion zum Abrufen des letzten Login-Zeitpunkts des Benutzers
    public function getLastLogin()
    {
        return $this->userData['last_login'];
    }

    // Funktion zum Abrufen des Studiengangs des Benutzers
    public function getCourseOfStudy()
    {
        return $this->userData['course_of_study'];
    }

    // Funktion zum Abrufen des Avatars des Benutzers
    public function getAvatar()
    {
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

    // Funktion zum Setzen der E-Mail-Adresse des Benutzers
    public function setEmail($email)
    {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET email = :email WHERE id = :id");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Funktion zum Setzen des Vornamens des Benutzers
    public function setFirstname($firstname)
    {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET firstname = :firstname WHERE id = :id");
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Funktion zum Setzen des Nachnamens des Benutzers
    public function setLastname($lastname)
    {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET lastname = :lastname WHERE id = :id");
        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Funktion zum Setzen des Passworts des Benutzers
    public function setPassword($password)
    {
        // Das Passwort wird mit bcrypt gehasht, bevor es in die Datenbank gespeichert wird
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Funktion zum Setzen des Avatars des Benutzers
    // Parameter: $avatarBlob (Binärdaten des Avatars)
    public function setAvatar($avatarBlob)
    {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET avatar = :avatar WHERE id = :id");
        $stmt->bindParam(':avatar', $avatarBlob, PDO::PARAM_LOB);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Funktion zum Setzen des Studiengangs des Benutzers
    // Parameter: $courseOfStudy (Studiengang des Benutzers)
    public function setCourseOfStudy($courseOfStudy)
    {
        $stmt = $this->dbh->prepare("UPDATE phpauth_users SET course_of_study = :courseOfStudy WHERE id = :id");
        $stmt->bindParam(':courseOfStudy', $courseOfStudy, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Funktion zum Abrufen der offenen Quiz-Sessions des Benutzers
    public function getOpenQuizSessions()
    {
        $stmt = $this->dbh->prepare("SELECT * FROM `quiz_session_players` as qsp
                                      INNER JOIN `quiz_sessions` qs ON qsp.quiz_session_id = qs.id
                                      WHERE qsp.player_id = :userId AND qs.status = 'active'");
        $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        $quizSessionArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $quizSessionArr;
    }

    // Funktion zum Abrufen der abgeschlossenen Quiz-Sessions des Benutzers
    public function getCompletedQuizSessions()
    {
        $stmt = $this->dbh->prepare("SELECT * FROM `quiz_session_players` as qsp
                                      INNER JOIN `quiz_sessions` qs ON qsp.quiz_session_id = qs.id
                                      WHERE qsp.player_id = :userId AND (qs.status = 'completed' OR qs.status = 'cancelled')
                                      ORDER BY qs.updated_at desc");
        $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        $quizSessionArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $quizSessionArr;
    }
}
