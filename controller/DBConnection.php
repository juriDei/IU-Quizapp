<?php 
class DBConnection{

    public static function getDBConnection() : PDO{
        $dbh = new PDO("mysql:host=localhost;dbname=nutzerverwaltung", "juri", "Renolino!?94");
        return $dbh;
    }
    
}
?>