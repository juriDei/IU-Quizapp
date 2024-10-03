<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

class QuizSessionController
{
    protected $quizSessionModel;
    protected $auth;
    protected $dbh;
    protected $gameId;
    protected $studentId;


    public function __construct($gameId = '', $isGameId = true)
    {
        $this->dbh = DBConnection::getDBConnection();
        $config = new PHPAuthConfig($this->dbh);
        $this->auth = new PHPAuth($this->dbh, $config);
        $this->studentId = $this->auth->getCurrentUser()['id'];
        $this->gameId = $gameId;

        if (!empty($gameId)) {
            $this->quizSessionModel = new QuizSessionModel($gameId, $isGameId);
        } else {
            $this->quizSessionModel = new QuizSessionModel();
        }
    }

    public function create()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Generiere eine eindeutige game_id im Backend
            $game_id = $this->generateUniqueGameId(8);

            // Daten aus der Anfrage abrufen
            $mode = $_POST['mode'] ?? 'singleplayer';
            $question_catalogs = isset($_POST['question_catalogs']) ? $_POST['question_catalogs'] : [];
            $question_count = isset($_POST['question_count']) ? intval($_POST['question_count']) : 0;
            $time_limit = isset($_POST['time_limit']) ? intval($_POST['time_limit']) : 0;
            $question_types = isset($_POST['question_types']) ? $_POST['question_types'] : [];
            $status = $_POST['status'] ?? 'active';
            $players = (isset($_POST['players']) && !empty($_POST['players']) && $mode != 'singleplayer') ? $_POST['players'] : $this->auth->getCurrentUser()['id'];

            // Array mit allen notwendigen Daten für die Session
            $request = [
                'game_id' => $game_id,
                'question_catalogs' => $question_catalogs,
                'question_count' => $question_count,
                'time_limit' => $time_limit,
                'question_types' => $question_types,
                'status' => $status,
                'mode' => $mode,
                'players' => $players
            ];

            // Validierung der Eingabedaten
            if (!$this->validateSessionData($request)) {
                echo json_encode(['error' => 'Ungültige Eingabedaten']);
                exit;
            }

            // Quizsession erstellen
            $response = $this->quizSessionModel->create($request);

            // JSON-Antwort ausgeben
            if (!$response) {
                echo json_encode(['error' => 'Fehler beim Erstellen der Quizsession']);
            } else {
                echo json_encode(['success' => 'Quizsession erfolgreich erstellt', 'quiz_session_id' => $response, 'game_id' => $game_id]);
            }
        } else {
            echo json_encode(['error' => 'Ungültige Anfrage']);
        }
    }

    public function getStatus(){
        return $this->quizSessionModel->getStatus();
    }

    public function getAllQuestions($quizSessionId)
    {
        // Abrufen der Quiz-Session-Daten
        $quizSessionData = $this->quizSessionModel->getQuizSessionData();

        if (!$quizSessionData || empty($quizSessionData['questions'])) {
            http_response_code(404);
            echo json_encode(['message' => 'Keine Fragen gefunden']);
            return;
        }

        // Frage-IDs aus den Quiz-Session-Daten extrahieren
        $questionIds = json_decode($quizSessionData['questions'], true);

        // Abrufen der Fragen anhand der IDs
        $questions = $this->quizSessionModel->getQuestionsByIds($questionIds);

        if ($questions) {
            echo json_encode($questions);
        } else {
            echo json_encode(['message' => 'Keine Fragen gefunden']);
        }
    }

    private function generateUniqueGameId($length = 8)
    {
        do {
            $game_id = $this->generateGameId($length);
            $stmt = $this->dbh->prepare("SELECT COUNT(*) FROM quiz_sessions WHERE game_id = :game_id");
            $stmt->bindParam(':game_id', $game_id, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
        } while ($count > 0);

        return $game_id;
    }

    private function generateGameId($length = 8)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Vermeidung ähnlicher Zeichen
        $game_id = '';
        for ($i = 0; $i < $length; $i++) {
            $game_id .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $game_id;
    }

    public function updateStatus($status)
    {
        $this->quizSessionModel->updateStatus($status);
        echo json_encode(['success' => 'Status erfolgreich aktualisiert']);
    }

    protected function validateSessionData($data)
    {
        if (
            empty($data['game_id']) || empty($data['question_catalogs']) || empty($data['question_count']) ||
            empty($data['time_limit']) || empty($data['question_types']) || empty($data['status']) ||
            empty($data['mode'])
        ) {
            return false;
        }
        return true;
    }

    public function saveAnswer()
    {
        // Daten aus dem POST-Body lesen
        $data = json_decode(file_get_contents('php://input'), true);

        $questionId = $data['question_id'];
        $selectedAnswer = $data['selected_answer'];
        $studentId = $this->studentId;

        $result = $this->quizSessionModel->saveStudentAnswer($questionId, $studentId, $selectedAnswer);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Antwort erfolgreich gespeichert']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Fehler beim Speichern der Antwort']);
        }
    }

    public function getSessionId($gameId)
    {
        $result = $this->quizSessionModel->getQuizSessionId($gameId);
    }

    public function getStudentAnswers()
    {
        // Benutzer-ID aus der Session abrufen
        $studentId = $this->auth->getCurrentUser()['id'];

        // Gespeicherte Antworten aus dem Modell abrufen
        $answers = $this->quizSessionModel->getStudentAnswers($studentId);

        if ($answers) {
            echo json_encode($answers);
        } else {
            echo json_encode([]);
        }
    }

    public function loadSession()
    {
        $sessionData = $this->quizSessionModel->getQuizSessionData();
        
        // Prüfen, ob die Session "completed" ist und weiterleiten
        if ($sessionData['status'] === 'completed') {
            header("Location: /quizapp/quizsessionresult?session_id={$sessionData['game_id']}");
            exit();
        }

        // Dekodieren der gespeicherten Frage-IDs
        $questionIds = json_decode($sessionData['questions'], true);

        // Laden der Fragen anhand der IDs
        $questions = $this->quizSessionModel->getQuestionsByIds($questionIds);

        $sessionData['questions'] = $questions;

        return $sessionData;
    }
    

    /**
     * Methode zur Anzeige der Auswertungsergebnisse
     */
    public function showResults()
    {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (!$this->auth->isLogged()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['message' => 'Nicht autorisiert']);
            exit();
        }

        // Benutzer-ID aus der Session abrufen
        $studentId = $this->auth->getCurrentUser()['id'];

        // Alle Fragen der Quiz-Session abrufen
        $questions = $this->quizSessionModel->getAllQuestions($this->gameId);

        // Alle Antworten des Benutzers abrufen
        $studentAnswers = $this->quizSessionModel->getStudentAnswers($studentId);

        // Bewertung der Antworten
        $evaluation = $this->quizSessionModel->evaluateAnswers($questions, $studentAnswers);


        return $evaluation;
    }

    public function completeQuizSession()
    {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (!$this->auth->isLogged()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['message' => 'Nicht autorisiert']);
            exit();
        }
        $this->updateStatus('completed');
    }

        // Methode zum Abbrechen einer Quizsession
        public function cancel()
        {

            // Initialisieren des QuizSessionModel mit der Quizsession-ID
            $this->quizSessionModel = new QuizSessionModel($this->gameId, true);
    
            // Überprüfen, ob der aktuelle Benutzer berechtigt ist, die Session abzubrechen
            $players = $this->quizSessionModel->getPlayers();
            $currentUserId = $this->studentId;
            $isHost = false;
            foreach ($players as $player) {
                if ($player['id'] == $currentUserId && $player['role'] == 'host') {
                    $isHost = true;
                    break;
                }
            }
    
            if ($isHost) {
                // Session abbrechen
                $this->quizSessionModel->cancelSession();
                echo json_encode(['message' => 'Quizsession erfolgreich abgebrochen.']);
            } else {
                http_response_code(403);
                echo json_encode(['message' => 'Sie sind nicht berechtigt, diese Quizsession abzubrechen.']);
            }
        }
}
