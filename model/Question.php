<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class QuestionModel
{
    private $dbh;

    // Konstruktor der Klasse, der die Datenbankverbindung initialisiert
    public function __construct()
    {
        // Initialisierung der Datenbankverbindung durch Aufruf der Singleton-Methode getDBConnection
        $this->dbh = DBConnection::getDBConnection();
    }

    // Funktion zum Erstellen einer neuen Frage
    // Parameter: $questionText (Fragetext), $questionType (Fragetyp), $possibleAnswers (Mögliche Antworten als Array), $correctAnswer (Korrekte Antwort), $moduleId (ID des Moduls)
    public function createQuestion($questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId)
    {
        // Vorbereitung der SQL-Anweisung zum Einfügen einer neuen Frage in die Datenbank
        $stmt = $this->dbh->prepare("INSERT INTO questions (question_text, question_type, possible_answers, correct_answer, module_id) VALUES (:question_text, :question_type, :possible_answers, :correct_answer, :module_id)");
        
        // Binden der Parameter zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':question_text', $questionText); // Bindet den Fragetext
        $stmt->bindParam(':question_type', $questionType); // Bindet den Fragetyp
        $stmt->bindParam(':possible_answers', json_encode($possibleAnswers)); // Kodiert die möglichen Antworten als JSON und bindet sie
        $stmt->bindParam(':correct_answer', $correctAnswer); // Bindet die korrekte Antwort
        $stmt->bindParam(':module_id', $moduleId); // Bindet die Modul-ID
        
        // Führt das Statement aus und gibt das Ergebnis zurück (true bei Erfolg, sonst false)
        return $stmt->execute();
    }

    // Funktion zum Abrufen einer Frage anhand ihrer ID
    // Parameter: $id (ID der Frage)
    public function getQuestionById($id)
    {
        // Vorbereitung der SQL-Anweisung zum Abrufen der Frage basierend auf ihrer ID
        $stmt = $this->dbh->prepare("SELECT * FROM questions WHERE id = :id");
        
        // Binden der Frage-ID zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt die Frage als assoziatives Array zurück
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Funktion zum Abrufen aller aktiven Fragen eines bestimmten Moduls
    // Parameter: $moduleId (ID des Moduls)
    public function getQuestionsByModuleId($moduleId)
    {
        // Vorbereitung der SQL-Anweisung zum Abrufen aller aktiven Fragen eines Moduls
        $stmt = $this->dbh->prepare("SELECT * FROM questions WHERE module_id = :module_id AND is_active = 1");
        
        // Binden der Modul-ID zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
        
        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt die Fragen als assoziatives Array zurück
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Funktion zum Abrufen aller inaktiven (von Studenten gestellten) Fragen eines bestimmten Moduls
    // Parameter: $moduleId (ID des Moduls)
    public function getStudentQuestionsByModuleId($moduleId)
    {
        // Vorbereitung der SQL-Anweisung zum Abrufen aller inaktiven Fragen eines Moduls
        $stmt = $this->dbh->prepare("SELECT * FROM questions WHERE module_id = :module_id AND is_active = 0");
        
        // Binden der Modul-ID zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
        
        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt die Fragen als assoziatives Array zurück
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Funktion zum Abrufen von Fragen eines bestimmten Moduls nach bestimmten Fragetypen
    // Parameter: $moduleId (ID des Moduls), $questionTypes (Array der Fragetypen)
    public function getQuestionsByModuleIdAndTypes($moduleId, $questionTypes = [])
    {
        // Basis-SQL-Anweisung zum Abrufen von Fragen basierend auf der Modul-ID
        $sql = "SELECT * FROM questions WHERE module_id = :module_id";
        $params = [':module_id' => $moduleId];

        // Falls Fragetypen angegeben sind, erweitere die SQL-Anweisung um eine WHERE-Bedingung
        if (!empty($questionTypes)) {
            $inClause = [];
            foreach ($questionTypes as $index => $type) {
                $paramName = ':question_type_' . $index;
                $inClause[] = $paramName;
                $params[$paramName] = $type; // Fügt jeden Fragetyp in die Parameterliste ein
            }
            $sql .= " AND question_type IN (" . implode(',', $inClause) . ")"; // Fragetypen zur SQL-Anweisung hinzufügen
        }

        // Statement vorbereiten
        $stmt = $this->dbh->prepare($sql);

        // Binden der Parameter zur Vorbereitung des Statements
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }

        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt die Fragen als assoziatives Array zurück
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Funktion zum Abrufen von Fragen anhand einer Liste von Frage-IDs
    // Parameter: $questionIds (Array von Frage-IDs)
    public function getQuestionsByIds($questionIds)
    {
        // Falls keine Frage-IDs angegeben sind, gib leeres Array zurück
        if (empty($questionIds)) {
            return [];
        }

        // Platzhalter für die Frage-IDs erstellen
        $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
        $sql = "SELECT * FROM questions WHERE id IN ($placeholders) ORDER BY FIELD(id, $placeholders)";

        // Statement vorbereiten
        $stmt = $this->dbh->prepare($sql);

        // Binden der Frage-IDs zweimal (einmal für WHERE und einmal für ORDER BY)
        $params = array_merge($questionIds, $questionIds);
        foreach ($params as $index => $id) {
            $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
        }

        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt die Fragen als assoziatives Array zurück
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Funktion zum Aktualisieren einer bestehenden Frage
    // Parameter: $id (Frage-ID), $questionText (Fragetext), $questionType (Fragetyp), $possibleAnswers (Mögliche Antworten), $correctAnswer (Korrekte Antwort), $moduleId (Modul-ID)
    public function updateQuestion($id, $questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId)
    {
        // Vorbereitung der SQL-Anweisung zum Aktualisieren der Frage
        $stmt = $this->dbh->prepare("UPDATE questions SET question_text = :question_text, question_type = :question_type, possible_answers = :possible_answers, correct_answer = :correct_answer, module_id = :module_id WHERE id = :id");
        
        // Binden der Parameter zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bindet die Frage-ID
        $stmt->bindParam(':question_text', $questionText); // Bindet den Fragetext
        $stmt->bindParam(':question_type', $questionType); // Bindet den Fragetyp
        $stmt->bindParam(':possible_answers', json_encode($possibleAnswers)); // Kodiert die möglichen Antworten als JSON und bindet sie
        $stmt->bindParam(':correct_answer', $correctAnswer); // Bindet die korrekte Antwort
        $stmt->bindParam(':module_id', $moduleId); // Bindet die Modul-ID
        
        // Führt das Statement aus und gibt das Ergebnis zurück (true bei Erfolg, sonst false)
        return $stmt->execute();
    }

    // Funktion zum Löschen einer Frage
    // Parameter: $id (Frage-ID)
    public function deleteQuestion($id)
    {
        // Vorbereitung der SQL-Anweisung zum Löschen der Frage basierend auf ihrer ID
        $stmt = $this->dbh->prepare("DELETE FROM questions WHERE id = :id");
        
        // Binden der Frage-ID zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Führt das Statement aus und gibt das Ergebnis zurück (true bei Erfolg, sonst false)
        return $stmt->execute();
    }
}
