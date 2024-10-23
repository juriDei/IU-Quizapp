<?php

// Autoload-Funktionalität von Composer laden, um alle benötigten Bibliotheken und Abhängigkeiten bereitzustellen
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Verwendete Klassen aus der PHPAuth-Bibliothek importieren
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

// Controller-Klasse für das Zurücksetzen des Passworts
class PasswordResetController
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

    // Methode zum Anfordern eines Passwort-Zurücksetzungs-Links
    public function requestReset()
    {
        // E-Mail aus dem POST-Request abrufen
        $email = $_POST['email'];
        
        // PHPAuth-Methode aufrufen, um einen Passwort-Zurücksetzungs-Link anzufordern
        $resetRequest = $this->auth->requestReset($email, true);

        // Prüfen, ob die Anfrage erfolgreich war
        if (!$resetRequest['error']) {
            // Erfolgsnachricht zum Nachrichtenhandler hinzufügen
            MessageHandlerController::addSuccess('Ein Link zum Zurücksetzen des Passworts wurde an Ihre E-Mail-Adresse gesendet.');
            
            // Benutzer zur Login-Seite weiterleiten
            header('Location: /quizapp/login');
            exit();
        }

        // Fehlermeldung im Nachrichtenhandler speichern, falls die Anfrage fehlgeschlagen ist
        MessageHandlerController::addError($resetRequest['message']);
        
        // Benutzer zur Passwort-vergessen-Seite weiterleiten
        header('Location: /quizapp/forget-pass');
        exit();
    }

    // Methode zum Zurücksetzen des Passworts
    public function resetPassword()
    {
        // Reset-Schlüssel und neues Passwort aus dem POST-Request abrufen
        $key = $_POST['key'];
        $newPassword = $_POST['password'];
        $passwordRepeat = $_POST['password_repeat'];

        // PHPAuth-Methode aufrufen, um das Passwort zurückzusetzen
        $reset = $this->auth->resetPass($key, $newPassword, $passwordRepeat);

        // Prüfen, ob das Zurücksetzen erfolgreich war
        if (!$reset['error']) {
            // Erfolgsnachricht zum Nachrichtenhandler hinzufügen
            MessageHandlerController::addSuccess('Ihr Passwort wurde erfolgreich zurückgesetzt.');
            
            // Benutzer zur Login-Seite weiterleiten
            header('Location: /quizapp/login');
            exit();
        }

        // Fehlermeldung im Nachrichtenhandler speichern, falls das Zurücksetzen fehlgeschlagen ist
        MessageHandlerController::addError($reset['message']);
        
        // Benutzer zur Seite für das Passwort-zurücksetzen weiterleiten
        header('Location: /quizapp/forget-pass-reset');
        exit();
    }
}
?>
