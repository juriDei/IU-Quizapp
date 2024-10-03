<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class QuestionModel
{
    private $dbh;

    public function __construct()
    {
        $this->dbh = DBConnection::getDBConnection();
    }

    public function createQuestion($questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId)
    {
        $stmt = $this->dbh->prepare("INSERT INTO questions (question_text, question_type, possible_answers, correct_answer, module_id) VALUES (:question_text, :question_type, :possible_answers, :correct_answer, :module_id)");
        $stmt->bindParam(':question_text', $questionText);
        $stmt->bindParam(':question_type', $questionType);
        $stmt->bindParam(':possible_answers', json_encode($possibleAnswers));
        $stmt->bindParam(':correct_answer', $correctAnswer);
        $stmt->bindParam(':module_id', $moduleId);
        return $stmt->execute();
    }

    public function getQuestionById($id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM questions WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getQuestionsByModuleId($moduleId)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM questions WHERE module_id = :module_id AND is_active = 1");
        $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentQuestionsByModuleId($moduleId)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM questions WHERE module_id = :module_id AND is_active = 0");
        $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionsByModuleIdAndTypes($moduleId, $questionTypes = [])
    {
        $sql = "SELECT * FROM questions WHERE module_id = :module_id";
        $params = [':module_id' => $moduleId];

        if (!empty($questionTypes)) {
            $inClause = [];
            foreach ($questionTypes as $index => $type) {
                $paramName = ':question_type_' . $index;
                $inClause[] = $paramName;
                $params[$paramName] = $type;
            }
            $sql .= " AND question_type IN (" . implode(',', $inClause) . ")";
        }

        // Statement vorbereiten
        $stmt = $this->dbh->prepare($sql);

        // Parameter binden
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getQuestionsByIds($questionIds)
    {
        if (empty($questionIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
        $sql = "SELECT * FROM questions WHERE id IN ($placeholders) ORDER BY FIELD(id, $placeholders)";

        $stmt = $this->dbh->prepare($sql);

        // Binden der IDs zweimal (für ORDER BY FIELD)
        $params = array_merge($questionIds, $questionIds);
        foreach ($params as $index => $id) {
            $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQuestion($id, $questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId)
    {
        $stmt = $this->dbh->prepare("UPDATE questions SET question_text = :question_text, question_type = :question_type, possible_answers = :possible_answers, correct_answer = :correct_answer, module_id = :module_id WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':question_text', $questionText);
        $stmt->bindParam(':question_type', $questionType);
        $stmt->bindParam(':possible_answers', json_encode($possibleAnswers));
        $stmt->bindParam(':correct_answer', $correctAnswer);
        $stmt->bindParam(':module_id', $moduleId);
        return $stmt->execute();
    }

    public function deleteQuestion($id)
    {
        $stmt = $this->dbh->prepare("DELETE FROM questions WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
