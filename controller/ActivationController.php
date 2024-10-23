<?php

// Autoload-Funktionalität von Composer laden, um alle benötigten Bibliotheken und Abhängigkeiten bereitzustellen
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Verwendete Klassen aus der PHPAuth-Bibliothek importieren
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

// Controller-Klasse für die Aktivierung des Benutzerkontos
class ActivationController
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

    // Methode zur Aktivierung des Benutzerkontos basierend auf einem Token
    public function activate()
    {
        // Aktivierungs-Token aus dem POST-Request abrufen
        $token = $_POST['token'];

        // PHPAuth-Methode zur Aktivierung des Benutzers mit dem bereitgestellten Token aufrufen
        $activation = $this->auth->activate($token);

        // Prüfen, ob die Aktivierung erfolgreich war
        if (!$activation['error']) {
            // Erfolgsnachricht zum Nachrichtenhandler hinzufügen
            MessageHandlerController::addSuccess('Ihr Konto wurde erfolgreich aktiviert.');
            
            // Benutzer weiterleiten zur Login-Seite
            header('Location: /quizapp/login');
            exit();
        }

        // Fehlermeldung im Nachrichtenhandler speichern, falls die Aktivierung fehlgeschlagen ist
        MessageHandlerController::addError($activation['message']);
        
        // Benutzer weiterleiten zur Aktivierungsseite
        header('Location: /quizapp/activate');
        exit();
    }
}
?>
