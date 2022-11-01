<?php 
    class AssociationDAO{
        
        //Neue Institution wird zur Association-Tabelle hinzugefügt
        public function addAssoociation($name,$db_name) : bool{
            $sql = "INSERT INTO `association` (`name`,`db_name`) VALUES (:name,:db_name)";
            $pdo = DBConnection::getDBConnection()->prepare($sql);

            if($pdo->execute(array('name' => $name,'db_name' => $db_name))){
                return true;
            }
            return false;
        }
    }
?>