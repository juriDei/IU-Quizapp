<?php

// Autoload-Funktionalität von Composer laden, um alle benötigten Bibliotheken und Abhängigkeiten bereitzustellen
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Verwendete Klassen aus der PHPMailer-Bibliothek importieren
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Controller-Klasse für die Verwaltung von E-Mail-Versand
class MailController
{
    // Instanz der PHPMailer-Klasse zum Versenden von E-Mails
    protected $mail;

    // Konstruktor: Initialisiert die PHPMailer-Instanz und richtet SMTP ein
    public function __construct()
    {
        // PHPMailer-Instanz erstellen
        $this->mail = new PHPMailer();
        
        // SMTP-Konfiguration einrichten
        $this->setupSMTP();
    }

    // Geschützte Methode zum Einrichten des SMTP-Servers
    protected function setupSMTP()
    {
        // SMTP-Modus aktivieren
        $this->mail->isSMTP();
        
        // Hostname des SMTP-Servers setzen (in diesem Fall ist es der lokale Host, z. B. MailHog)
        $this->mail->Host = 'localhost';
        
        // Port für den SMTP-Server (1025 wird häufig mit MailHog verwendet)
        $this->mail->Port = 1025;
        
        // Keine SMTP-Authentifizierung erforderlich (für lokale Testumgebung wie MailHog)
        $this->mail->SMTPAuth = false;
    }

    // Methode zum Versenden einer Aktivierungs-E-Mail
    public function sendActivationEmail($to, $subject, $body)
    {
        try {
            // Absender der E-Mail festlegen
            $this->mail->setFrom('noreply@quizapp.de', 'Quizapp');
            
            // Empfänger der E-Mail hinzufügen
            $this->mail->addAddress($to);

            // E-Mail-Inhalte festlegen
            $this->mail->isHTML(true); // E-Mail als HTML formatieren
            $this->mail->Subject = $subject; // Betreff der E-Mail setzen
            $this->mail->Body = $body; // Inhalt der E-Mail setzen

            // E-Mail senden
            $this->mail->send();
            return true; // Erfolgreiche Zustellung
        } catch (Exception $e) {
            // Fehlerbehandlung: Wenn die E-Mail nicht gesendet werden kann, false zurückgeben
            return false;
        }
    }
}
?>
