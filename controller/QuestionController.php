<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class QuestionController
{
    private $model;

    public function __construct()
    {
        $this->model = new QuestionModel();
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $questionText = $data['questionText'];
        $questionType = $data['questionType'];
        $possibleAnswers = $data['possibleAnswers'];
        $correctAnswer = $data['correctAnswer'] ?? null;
        $moduleId = $data['moduleId'];

        $result = $this->model->createQuestion($questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId);

        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Frage erfolgreich erstellt']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Fehler beim Erstellen der Frage']);
        }
    }

    public function getCatalogQuestions($moduleId)
    {
        $questions = $this->model->getQuestionsByModuleId($moduleId);
        if ($questions) {
            echo json_encode($questions);
        } else {
            echo json_encode(['message' => 'Keine Fragen gefunden']);
        }
    }

    public function getStudentQuestions($moduleId)
    {
        $studentQuestions = $this->model->getStudentQuestionsByModuleId($moduleId);
        if ($studentQuestions) {
            echo json_encode($studentQuestions);
        } else {
            echo json_encode(['message' => 'Keine Studentenfragen gefunden']);
        }
    }

}