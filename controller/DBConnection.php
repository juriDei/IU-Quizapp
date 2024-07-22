<?php 

use PDO;
use PDOException;

class DBConnection{
    
    private static $charset = 'utf8mb4';
    private static $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    public static function getDBConnection() : PDO{
        $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=" . self::$charset;
        try {
            $dbh = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), self::$options);
            return $dbh;
        } catch (PDOException $e) {
            // Hier kannst du entscheiden, ob du den Fehler protokollieren, weiterleiten oder einfach nur eine Nachricht anzeigen möchtest
            die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
        }
    }
    
}
?>