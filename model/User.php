<?php

class User{
    
    public static function getUserCount(){
        $query = "SELECT COUNT(email) as 'Nutzeranzahl' FROM nutzerverwaltung.phpauth_users"; 
        $result = DBConnection::getDBConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['Nutzeranzahl'];
    }
    public static function getUserTable(){
        $query = "SELECT id,email FROM nutzerverwaltung.phpauth_users"; 
        $result = DBConnection::getDBConnection()->query($query)->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
}

?>