<?php

// Autoload-Funktionalität von Composer laden, um alle benötigten Bibliotheken und Abhängigkeiten bereitzustellen
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Verwendete Klassen aus der PHPAuth-Bibliothek importieren
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

// Controller-Klasse zur Verwaltung der Registrierung
class RegisterController
{
    // Instanzen der benötigten Klassen zur Authentifizierung und für den Mailversand
    protected $auth;
    protected $mailController;

    // Konstruktor: Initialisiert die Verbindung zur Datenbank, die PHPAuth-Instanz und den MailController
    public function __construct()
    {
        // Datenbankverbindung holen
        $dbh = DBConnection::getDBConnection();
        
        // PHPAuth-Konfiguration und Authentifizierungsinstanz initialisieren
        $config = new PHPAuthConfig($dbh);
        $this->auth = new PHPAuth($dbh, $config);
        
        // MailController-Instanz zum Versenden von Bestätigungs-E-Mails
        $this->mailController = new MailController();
    }

    // Methode zur Durchführung der Registrierung eines neuen Benutzers
    public function register()
    {
        // Benutzerdaten aus dem POST-Request abrufen
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $courseOfStudy = $_POST['course_of_study'];
        $domain = $_POST['domain'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['password_repeat'];
        
        // Vollständige E-Mail-Adresse zusammensetzen
        $emailWithDomain = $email . $domain;

        // Registrierungsanfrage an PHPAuth senden
        $register = $this->auth->register($emailWithDomain, $password, $passwordRepeat, [], '', true);

        // Überprüfen, ob die Registrierung erfolgreich war
        if (!$register['error']) {
            // Benutzer wurde erfolgreich registriert
            $userId = $register['uid'];
            $user = new UserModel($userId);
            
            // Benutzerinformationen aktualisieren (Vorname, Nachname, Studiengang)
            $user->setFirstName($firstname);
            $user->setLastName($lastname);
            $user->setCourseOfStudy($courseOfStudy);
            
            // Erfolgsnachricht hinzufügen und Benutzer zur Login-Seite weiterleiten
            MessageHandlerController::addSuccess('Registrierung erfolgreich! Bitte überprüfen Sie Ihre E-Mails zur Bestätigung.');
            header('Location: /quizapp/login');
            exit();
        }
        
        // Fehlermeldung im Nachrichtenhandler speichern und Benutzer zur Registrierungsseite weiterleiten
        MessageHandlerController::addError($register['message']);
        header('Location: /quizapp/register');
        exit();
    }
}
?>