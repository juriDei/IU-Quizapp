<?php 

// Verwenden von PDO und PDOException, um die Datenbankverbindung zu verwalten
use PDO;
use PDOException;

// Klasse zur Verwaltung der Datenbankverbindung
class DBConnection{
    
    // Zeichensatzdefinition für die Datenbankverbindung
    private static $charset = 'utf8mb4';
    
    // Optionen für die PDO-Verbindung, um das Verhalten der Fehlerbehandlung und des Fetch-Modus zu konfigurieren
    private static $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Fehler im Ausnahme-Modus behandeln
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Ergebnisse als assoziative Arrays abrufen
        PDO::ATTR_EMULATE_PREPARES => false, // Keine Emulation vorbereiteter Anweisungen verwenden
    ];

    // Stellt eine Verbindung zur Datenbank her und gibt eine PDO-Instanz zurück
    public static function getDBConnection() : PDO{
        // DSN (Data Source Name) für die Verbindung mit der Datenbank zusammensetzen
        $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=" . self::$charset;
        try {
            // PDO-Instanz erstellen und Verbindung zur Datenbank herstellen
            $dbh = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), self::$options);
            return $dbh; // Datenbankverbindung zurückgeben
        } catch (PDOException $e) {
            // Fehler abfangen, wenn die Verbindung fehlschlägt, und eine Fehlermeldung ausgeben
            // Hier könntest du auch den Fehler protokollieren, um ihn zu überwachen
            die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
        }
    }
    
}
?>