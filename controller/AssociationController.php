<?php 
require_once dirname(__DIR__) . '/controller/DBConnection.php';
require_once dirname(__DIR__) . '/controller/trait/IniFileTrait.php';
require_once dirname(__DIR__) . '/controller/DAO/AssociationDAO.php';


class AssociationController{
    use IniFileTrait;

    public $userName = '';
    public $associationName = '';
    protected AssociationDAO $associationDAO;
    private $userFolder = '../User/';

    public function __construct($userName,$associationName)
    {
        $this->setUserName($userName);
        $this->setAssociationName($associationName);
        $this->associationDAO = new AssociationDAO();
        //$this->createAssociationTable();
    }

    //Getter-Methoden
    public function getUserName(): string{
        return $this->userName;
    }
    public function getAssociationName(): string{
        return $this->associationName;
    }
    public function getUserFolder(): string{
        return $this->userFolder;
    }

    //Setter-Methoden
    public function setUserName($userName): void{
        $this->userName = $userName;
    }
    public function setAssociationName($associationName): void{
        $this->associationName = $associationName;
    }
    public function setUserFolder($userFolder): void{
        $this->userFolder = $userFolder;
    }



    //Erstellung des Nutzers
    private function createTableUserForAssociation($table,$user,$pwd): string{
        try{
            $sql = "CREATE USER '{$user}'@'localhost' IDENTIFIED BY '{$pwd}'";
            DBConnection::getDBConnection()->query($sql);
            $this->setPrivilegesToUser($table,$user);
            $this->createDataForUser($table,$user,$pwd);
        }
        catch(Exception $e){
            return $e;
        }
    }

    //Rechtevergabe für den Erstellten User auf die dazugehörige Tabelle
    private function setPrivilegesToUser($table,$user): string {
        try{
            $sql = "GRANT SELECT, INSERT, UPDATE, DELETE ON {$table}.* TO '{$user}'@'localhost'";
            DBConnection::getDBConnection()->query($sql);
        }
        catch(Exception $e){
            return $e;
        }
    }
    
    //Erstellung der Tabelle und dazugehörigen INI-File mit den notwendigen Daten
    public function createAssociation(): string{
        $table = bin2hex(random_bytes(10));
        $association = $this->getAssociationName();
        $user = $this->getUserName();
        $pwd = bin2hex(openssl_random_pseudo_bytes(8));

        
        $iniData = array(
            'Institution' => array(
                'Name' => $association
            ),
            'Database' => array(
                'DB_Name' => $table,
                'DB_User' => $user,
                'DB_Password' => $pwd
            ),
        );

        try{
            $this->generateIniFile($iniData, "../user_ini_files/{$association}.ini", true);
        }
        catch(Exception $e){
            return $e;
        }
        
        try{
            $this->associationDAO->addAssoociation($association,$table);
            //Tabellenerstellung
            $sql = "CREATE DATABASE {$table}";
            DBConnection::getDBConnection()->query($sql);
        }
        catch(Exception $e){
            return $e;
        }
        

        //Nutzererstellung für die Tabelle
        $this->createTableUserForAssociation($table,$user,$pwd);

    }
    public function createUserFolder(): void{
        mkdir("{$this->getUserFolder()}{$this->getAssociationName()}", 0755, true);
    }
    public function fillUserFolder(): void{
        $zip = new ZipArchive; 
        $res = $zip->open("../4ganize/4ganize.zip");
        $zip->extractTo("{$this->getUserFolder()}{$this->getAssociationName()}/");
    }
    public function fillAssociationTable($dbname,$dbuser,$dbpw): void{
        $dbFillConnection = new PDO("mysql:host=localhost;dbname={$dbname}", $dbuser, $dbpw);
        $query = '';
        $sqlScript = file('../4ganize/4ganize.sql');
        foreach ($sqlScript as $line)	{
            
            $startWith = substr(trim($line), 0 ,2);
            $endWith = substr(trim($line), -1 ,1);
            
            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                continue;
            }
                
            $query = $query . $line;
            if ($endWith == ';') {
                $dbFillConnection->query($query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
                $query= '';		
            }
        }
    }
    private function createDataForUser($dbname,$dbuser,$dbpw): void{
        $this->createUserFolder(); 
        $this->fillUserFolder();
        $this->fillAssociationTable($dbname,$dbuser,$dbpw);
    }    
}

$userName = $_POST['userName'];
$associationName = $_POST['associationName'];
$associationObj = new AssociationController($userName,$associationName);
$associationObj->createAssociation();
?>