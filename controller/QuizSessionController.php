<?php

// Autoload-Funktionalität von Composer laden, um alle benötigten Bibliotheken und Abhängigkeiten bereitzustellen
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Verwendete Klassen aus der PHPAuth-Bibliothek importieren
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

// Controller-Klasse zur Verwaltung von Quiz-Sessions
class QuizSessionController
{
    // Instanzen der benötigten Klassen und Variablen für die Quiz-Session
    protected $quizSessionModel;
    protected $auth;
    protected $dbh;
    protected $gameId;
    protected $studentId;

    // Konstruktor: Initialisiert die Verbindung zur Datenbank, die PHPAuth-Instanz und das Quiz-Session-Modell
    public function __construct($gameId = '', $isGameId = true)
    {
        // Datenbankverbindung holen
        $this->dbh = DBConnection::getDBConnection();
        
        // PHPAuth-Konfiguration und Authentifizierungsinstanz initialisieren
        $config = new PHPAuthConfig($this->dbh);
        $this->auth = new PHPAuth($this->dbh, $config);
        
        // Aktuelle Benutzer-ID abrufen
        $this->studentId = $this->auth->getCurrentUser()['id'];
        
        // Spiel-ID setzen
        $this->gameId = $gameId;

        // Quiz-Session-Modell initialisieren, je nachdem, ob eine Spiel-ID übergeben wurde
        if (!empty($gameId)) {
            $this->quizSessionModel = new QuizSessionModel($gameId, $isGameId);
        } else {
            $this->quizSessionModel = new QuizSessionModel();
        }
    }

    // Methode zum Erstellen einer neuen Quiz-Session
    public function create()
    {
        // Content-Type auf JSON setzen
        header('Content-Type: application/json');

        // Prüfen, ob die Anfrage eine POST-Anfrage ist
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

    // Methode zum Abrufen des Status der aktuellen Quiz-Session
    public function getStatus()
    {
        return $this->quizSessionModel->getStatus();
    }

    // Methode zum Abrufen aller Fragen einer bestimmten Quiz-Session
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

    // Methode zum Generieren einer eindeutigen Spiel-ID
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

    // Methode zum Generieren einer Spiel-ID bestehend aus zufälligen Zeichen
    private function generateGameId($length = 8)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Vermeidung ähnlicher Zeichen
        $game_id = '';
        for ($i = 0; $i < $length; $i++) {
            $game_id .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $game_id;
    }

    // Methode zum Aktualisieren des Status der Quiz-Session
    public function updateStatus($status)
    {
        $this->quizSessionModel->updateStatus($status);
        echo json_encode(['success' => 'Status erfolgreich aktualisiert']);
    }

    // Geschützte Methode zur Validierung der Eingabedaten für die Quiz-Session
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

    // Methode zum Speichern der Antwort eines Spielers
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

    // Methode zum Abrufen der Session-ID einer bestimmten Spiel-ID
    public function getSessionId($gameId)
    {
        $result = $this->quizSessionModel->getQuizSessionId($gameId);
    }

    // Methode zum Abrufen aller gespeicherten Antworten eines Studenten
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

    // Methode zum Laden einer Quiz-Session
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
    

    // Methode zur Anzeige der Auswertungsergebnisse
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

    // Methode zum Abschließen der Quiz-Session
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

    // Methode zum Abbrechen einer Quiz-Session
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
?>
