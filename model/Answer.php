<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class AnswerModel
{
    private $dbh;

    public function __construct()
    {
        $this->dbh = DBConnection::getDBConnection();
    }

    public function createAnswer($questionId, $answerText, $isCorrect)
    {
        $stmt = $this->dbh->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (:question_id, :answer_text, :is_correct)");
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $stmt->bindParam(':answer_text', $answerText);
        $stmt->bindParam(':is_correct', $isCorrect, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    public function getAnswersByQuestionId($questionId)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM answers WHERE question_id = :question_id");
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
