<?php 
class DBConnection{
    private static $host = 'localhost';
    private static $dbname = 'quizapp';
    private static $username = 'juri';
    private static $password = 'Renolino!?94';
    private static $charset = 'utf8mb4';
    private static $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    public static function getDBConnection() : PDO{
        $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
        try {
            $dbh = new PDO($dsn, self::$username, self::$password, self::$options);
            return $dbh;
        } catch (PDOException $e) {
            // Hier kannst du entscheiden, ob du den Fehler protokollieren, weiterleiten oder einfach nur eine Nachricht anzeigen möchtest
            die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
        }
    }
    
}
?>