<?php 
class DBConnection{

    public static function getDBConnection($dbname,$user,$pw) : PDO{
        $dbh = new PDO("mysql:host=localhost;dbname={$dbname}", $user, $pw);
        return $dbh;
    }
    
}
?>