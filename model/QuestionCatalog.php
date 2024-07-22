<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class QuestionCatalogModel
{
    private $dbh;

    public function __construct()
    {
        $this->dbh = DBConnection::getDBConnection();
    }

    public function createModule($moduleName, $moduleAbbreviation, $imageUrl, $tutor)
    {
        $stmt = $this->dbh->prepare("INSERT INTO question_catalog (module_name, module_alias, image_url, tutor) VALUES (:module_name, :module_alias, :image_url, :tutor)");
        $stmt->bindParam(':module_name', $moduleName);
        $stmt->bindParam(':module_alias', $moduleAbbreviation);
        $stmt->bindParam(':image_url', $imageUrl);
        $stmt->bindParam(':tutor', $tutor);
        return $stmt->execute();
    }

    public function getModuleById($id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM question_catalog WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllModules()
    {
        $stmt = $this->dbh->prepare("SELECT * FROM question_catalog");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateModule($id, $moduleName, $moduleAbbreviation, $imageUrl, $tutor)
    {
        $stmt = $this->dbh->prepare("UPDATE question_catalog SET module_name = :module_name, module_alias = :module_alias, image_url = :image_url, tutor = :tutor WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':module_name', $moduleName);
        $stmt->bindParam(':module_alias', $moduleAbbreviation);
        $stmt->bindParam(':image_url', $imageUrl);
        $stmt->bindParam(':tutor', $tutor);
        return $stmt->execute();
    }

    public function deleteModule($id)
    {
        $stmt = $this->dbh->prepare("DELETE FROM question_catalog WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getQuestionCountByModuleId($moduleId)
    {
        $stmt = $this->dbh->prepare("SELECT COUNT(*) as question_count FROM questions WHERE module_id = :module_id");
        $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['question_count'];
    }
}
?>
