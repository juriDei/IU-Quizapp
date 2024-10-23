<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class AnswerModel
{
    private $dbh;

    // Konstruktor der Klasse, der die Datenbankverbindung initialisiert
    public function __construct()
    {
        // Initialisierung der Datenbankverbindung durch Aufruf der Singleton-Methode getDBConnection
        $this->dbh = DBConnection::getDBConnection();
    }

    // Funktion zum Erstellen einer neuen Antwort
    // Parameter: $questionId (ID der Frage), $answerText (Antworttext), $isCorrect (Bool ob Antwort korrekt ist)
    public function createAnswer($questionId, $answerText, $isCorrect)
    {
        // Vorbereitung der SQL-Anweisung zum Einfügen einer neuen Antwort in die Datenbank
        $stmt = $this->dbh->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (:question_id, :answer_text, :is_correct)");
        
        // Binden der Parameter zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT); // Bindet die Frage-ID als Integer
        $stmt->bindParam(':answer_text', $answerText); // Bindet den Antworttext
        $stmt->bindParam(':is_correct', $isCorrect, PDO::PARAM_BOOL); // Bindet, ob die Antwort korrekt ist (Boolean)
        
        // Führt das Statement aus und gibt das Ergebnis zurück (true bei Erfolg, sonst false)
        return $stmt->execute();
    }

    // Funktion zum Abrufen aller Antworten für eine bestimmte Frage
    // Parameter: $questionId (ID der Frage, deren Antworten abgefragt werden sollen)
    public function getAnswersByQuestionId($questionId)
    {
        // Vorbereitung der SQL-Anweisung zum Abrufen aller Antworten einer bestimmten Frage
        $stmt = $this->dbh->prepare("SELECT * FROM answers WHERE question_id = :question_id");
        
        // Binden der Frage-ID als Integer zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        
        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt die abgerufenen Antworten als assoziatives Array zurück
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
