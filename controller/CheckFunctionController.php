<?php 
require_once dirname(__DIR__) . '/controller/DBConnection.php';

class CheckFunctionController{
    public function dbUserExists($prop_arr){
        try{
            $sql = "SELECT User FROM mysql.user WHERE User = :user";
            $pdo = DBConnection::getDBConnection()->prepare($sql);
            $pdo->execute(array('user' => $prop_arr->username));
            $result = $pdo->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($result)){
                return true;
            }
            return false;
        }
        catch(Exception $e){
            return $e;
        }
    }
}

$func = $_GET['func'];
$prop_arr = json_decode($_GET['prop_arr']);
$checkFunctionObj = new CheckFunctionController();
echo json_encode($checkFunctionObj->{"{$func}"}($prop_arr));

?>