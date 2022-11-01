<?php

class Association{
    
    public static function getAssociationCount(){
        $query = "SELECT COUNT(asc_id) as 'AssociationCount' FROM nutzerverwaltung.association"; 
        $result = DBConnection::getDBConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['AssociationCount'];
    }
    public static function getAssociationTable(){
        $query = "SELECT `asc_id`as id,`name`,`db_name` as `key`,`created` FROM nutzerverwaltung.association"; 
        $result = DBConnection::getDBConnection()->query($query)->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
}

?>