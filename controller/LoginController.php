<?php

// Sitzung starten, um Benutzerdaten zu speichern
session_start();

// Autoload-Funktionalität von Composer laden, um alle benötigten Bibliotheken und Abhängigkeiten bereitzustellen
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Verwendete Klassen aus der PHPAuth-Bibliothek importieren
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

// Controller-Klasse für das Login-Management
class LoginController
{
    // Instanz der PHPAuth-Klasse zum Durchführen von Authentifizierungsaufgaben
    protected $auth;

    // Konstruktor: Stellt die Verbindung zur Datenbank her und initialisiert die PHPAuth-Instanz
    public function __construct()
    {
        // Datenbankverbindung holen
        $dbh = DBConnection::getDBConnection();
        
        // PHPAuth-Konfiguration initialisieren
        $config = new PHPAuthConfig($dbh);
        
        // PHPAuth-Instanz erstellen, um später auf Authentifizierungsmethoden zugreifen zu können
        $this->auth = new PHPAuth($dbh, $config);
    }

    // Methode zum Durchführen des Login-Prozesses
    public function login()
    {
        // E-Mail und Passwort aus dem POST-Request abrufen
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Überprüfen, ob das "Angemeldet bleiben"-Checkbox aktiviert ist und den entsprechenden Wert setzen
        $remember_me = ($_POST['remember'] == 'on') ? true : false;

        // PHPAuth-Methode aufrufen, um den Login-Vorgang durchzuführen
        $login = $this->auth->login($email, $password, $remember_me);

        // Prüfen, ob der Login erfolgreich war
        if (!$login['error']) {
            // Benutzer-ID in der Sitzung speichern
            $_SESSION['uid'] = $this->auth->getUID($email);
            
            // Benutzer zur Dashboard-Seite weiterleiten
            header('Location: /quizapp/dashboard');
            exit();
        }

        // Fehlermeldung im Nachrichtenhandler speichern, falls der Login fehlgeschlagen ist
        MessageHandlerController::addError($login['message']);
        
        // Benutzer zur Login-Seite weiterleiten
        header('Location: /quizapp/login');
        exit();
    }
}
?>
