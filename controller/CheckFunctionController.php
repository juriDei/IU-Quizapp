<?php 

// Datei für die Datenbankverbindung einbinden
require_once dirname(__DIR__) . '/controller/DBConnection.php';

// Controller-Klasse, um verschiedene Überprüfungsfunktionen anzubieten
class CheckFunctionController{
    
    // Methode, um zu prüfen, ob ein Benutzer in der Datenbank existiert
    public function dbUserExists($prop_arr){
        try{
            // SQL-Abfrage zur Prüfung, ob der Benutzer in der MySQL-Datenbanktabelle 'user' existiert
            $sql = "SELECT User FROM mysql.user WHERE User = :user";
            
            // Abfrage vorbereiten und Parameter binden
            $pdo = DBConnection::getDBConnection()->prepare($sql);
            
            // SQL-Abfrage ausführen und den Benutzernamen aus dem übergebenen Objekt verwenden
            $pdo->execute(array('user' => $prop_arr->username));
            
            // Ergebnis der Abfrage abrufen
            $result = $pdo->fetchAll(PDO::FETCH_ASSOC);

            // Wenn das Ergebnis nicht leer ist, existiert der Benutzer, also true zurückgeben
            if(!empty($result)){
                return true;
            }
            // Wenn kein Benutzer gefunden wurde, false zurückgeben
            return false;
        }
        // Falls ein Fehler auftritt, den Fehler zurückgeben
        catch(Exception $e){
            return $e;
        }
    }
}

// Funktion und Parameter aus der GET-Anfrage abrufen
$func = $_GET['func'];
$prop_arr = json_decode($_GET['prop_arr']);

// Instanz der CheckFunctionController-Klasse erstellen
$checkFunctionObj = new CheckFunctionController();

// Angeforderte Funktion dynamisch aufrufen und das Ergebnis als JSON kodiert ausgeben
echo json_encode($checkFunctionObj->{"{$func}"}($prop_arr));

?>
