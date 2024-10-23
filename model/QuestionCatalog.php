<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class QuestionCatalogModel
{
    private $dbh;

    // Konstruktor der Klasse, der die Datenbankverbindung initialisiert
    public function __construct()
    {
        // Initialisierung der Datenbankverbindung durch Aufruf der Singleton-Methode getDBConnection
        $this->dbh = DBConnection::getDBConnection();
    }

    // Funktion zum Erstellen eines neuen Moduls (Fragenkatalogs)
    // Parameter: $moduleName (Name des Moduls), $moduleAbbreviation (Abkürzung des Moduls), $imageUrl (Bild-URL), $tutor (Name des Tutors)
    public function createModule($moduleName, $moduleAbbreviation, $imageUrl, $tutor)
    {
        // Vorbereitung der SQL-Anweisung zum Einfügen eines neuen Moduls in die Datenbank
        $stmt = $this->dbh->prepare("INSERT INTO question_catalog (module_name, module_alias, `image`, tutor) VALUES (:module_name, :module_alias, :image_url, :tutor)");
        
        // Binden der Parameter zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':module_name', $moduleName); // Bindet den Modulnamen
        $stmt->bindParam(':module_alias', $moduleAbbreviation); // Bindet die Modulabkürzung
        $stmt->bindParam(':image_url', $imageUrl); // Bindet die Bild-URL
        $stmt->bindParam(':tutor', $tutor); // Bindet den Namen des Tutors
        
        // Führt das Statement aus und gibt das Ergebnis zurück (true bei Erfolg, sonst false)
        return $stmt->execute();
    }

    // Funktion zum Abrufen eines Moduls anhand seiner ID
    // Parameter: $id (ID des Moduls)
    public function getModuleById($id)
    {
        // Vorbereitung der SQL-Anweisung zum Abrufen eines Moduls basierend auf seiner ID
        $stmt = $this->dbh->prepare("SELECT * FROM question_catalog WHERE id = :id");
        
        // Binden der Modul-ID zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt das Modul als assoziatives Array zurück
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Funktion zum Abrufen aller Module
    public function getAllModules()
    {
        // Vorbereitung der SQL-Anweisung zum Abrufen aller Module
        $stmt = $this->dbh->prepare("SELECT * FROM question_catalog");
        
        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt alle Module als assoziatives Array zurück
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Funktion zum Aktualisieren eines bestehenden Moduls
    // Parameter: $id (Modul-ID), $moduleName (Name des Moduls), $moduleAbbreviation (Abkürzung des Moduls), $imageUrl (Bild-URL), $tutor (Name des Tutors)
    public function updateModule($id, $moduleName, $moduleAbbreviation, $imageUrl, $tutor)
    {
        // Vorbereitung der SQL-Anweisung zum Aktualisieren eines Moduls in der Datenbank
        $stmt = $this->dbh->prepare("UPDATE question_catalog SET module_name = :module_name, module_alias = :module_alias, `image` = :image_url, tutor = :tutor WHERE id = :id");
        
        // Binden der Parameter zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bindet die Modul-ID
        $stmt->bindParam(':module_name', $moduleName); // Bindet den Modulnamen
        $stmt->bindParam(':module_alias', $moduleAbbreviation); // Bindet die Modulabkürzung
        $stmt->bindParam(':image_url', $imageUrl); // Bindet die Bild-URL
        $stmt->bindParam(':tutor', $tutor); // Bindet den Namen des Tutors
        
        // Führt das Statement aus und gibt das Ergebnis zurück (true bei Erfolg, sonst false)
        return $stmt->execute();
    }

    // Funktion zum Löschen eines Moduls
    // Parameter: $id (Modul-ID)
    public function deleteModule($id)
    {
        // Vorbereitung der SQL-Anweisung zum Löschen eines Moduls basierend auf seiner ID
        $stmt = $this->dbh->prepare("DELETE FROM question_catalog WHERE id = :id");
        
        // Binden der Modul-ID zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Führt das Statement aus und gibt das Ergebnis zurück (true bei Erfolg, sonst false)
        return $stmt->execute();
    }

    // Funktion zum Abrufen der Anzahl der Fragen in einem bestimmten Modul
    // Parameter: $moduleId (ID des Moduls)
    public function getQuestionCountByModuleId($moduleId)
    {
        // Vorbereitung der SQL-Anweisung zum Zählen der Fragen, die einem bestimmten Modul zugeordnet sind
        $stmt = $this->dbh->prepare("SELECT COUNT(*) as question_count FROM questions WHERE module_id = :module_id");
        
        // Binden der Modul-ID zur Vorbereitung des SQL-Statements
        $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
        
        // Führt das Statement aus
        $stmt->execute();
        
        // Gibt die Anzahl der Fragen zurück
        return $stmt->fetch(PDO::FETCH_ASSOC)['question_count'];
    }
}
?>
