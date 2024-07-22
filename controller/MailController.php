<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->setupSMTP();
    }

    protected function setupSMTP()
    {
        $this->mail->isSMTP();
        $this->mail->Host = 'localhost';
        $this->mail->Port = 1025;
        $this->mail->SMTPAuth = false; // Keine Authentifizierung notwendig für MailHog
    }

    public function sendActivationEmail($to, $subject, $body)
    {
        try {
            // Empfänger
            $this->mail->setFrom('noreply@quizapp.de', 'Quizapp');
            $this->mail->addAddress($to);

            // Inhalte
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            // E-Mail senden
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Hier können Sie das Fehlerprotokoll hinzufügen oder anderweitig mit Fehlern umgehen
            return false;
        }
    }
}
