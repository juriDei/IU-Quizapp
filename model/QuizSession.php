<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class QuizSessionModel
{
    protected $dbh;
    protected $quizSessionId;
    protected $gameId;
    protected $quizSessionData;

    private $questionCatalogModel;
    private $questionModel;

    public function __construct($idOrGameId = null, $isGameId = true)
    {
        $this->dbh = DBConnection::getDBConnection();

        $this->questionCatalogModel = new QuestionCatalogModel();
        $this->questionModel = new QuestionModel();

        if ($idOrGameId) {
            if ($isGameId) {
                $this->gameId = $idOrGameId;
                $this->quizSessionData = $this->loadQuizSessionDataByGameId();
                if ($this->quizSessionData) {
                    $this->quizSessionId = $this->quizSessionData['id'];
                }
            } else {
                $this->quizSessionId = $idOrGameId;
                $this->quizSessionData = $this->loadQuizSessionDataById();
                if ($this->quizSessionData) {
                    $this->gameId = $this->quizSessionData['game_id'];
                }
            }
        }
    }

    public function getId()
    {
        return $this->quizSessionId;
    }

    public function getGameId()
    {
        return $this->gameId;
    }

    public function getQuestionCatalogs()
    {
        return json_decode($this->quizSessionData['question_catalogs'], true);
    }

    public function getQuestionCount()
    {
        return $this->quizSessionData['question_count'];
    }

    public function getTimeLimit()
    {
        return $this->quizSessionData['time_limit'];
    }

    public function getQuestionTypes()
    {
        return json_decode($this->quizSessionData['question_types'], true);
    }

    public function getStatus()
    {
        return $this->quizSessionData['status'];
    }

    public function getMode()
    {
        return $this->quizSessionData['mode'];
    }

    public function getQuizSessionData()
    {
        return $this->quizSessionData;
    }

    public function create($data)
    {
        // Laden der Fragen basierend auf den Einstellungen
        $questions = $this->getRandomQuestionsFromCatalogs(
            $data['question_catalogs'],
            $data['question_count'],
            $data['question_types']
        );

        // Extrahieren der Frage-IDs
        $questionIds = array_column($questions, 'id');

        // Speichern der Frage-IDs als JSON
        $data['questions'] = json_encode($questionIds);

        $sql = "INSERT INTO quiz_sessions (game_id, question_catalogs, question_count, time_limit, question_types, status, mode, questions) 
                VALUES (:game_id, :question_catalogs, :question_count, :time_limit, :question_types, :status, :mode, :questions)";
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindParam(':game_id', $data['game_id'], PDO::PARAM_STR);
        $stmt->bindParam(':question_catalogs', json_encode($data['question_catalogs']), PDO::PARAM_STR);
        $stmt->bindParam(':question_count', $data['question_count'], PDO::PARAM_INT);
        $stmt->bindParam(':time_limit', $data['time_limit'], PDO::PARAM_INT);
        $stmt->bindParam(':question_types', json_encode($data['question_types']), PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindParam(':mode', $data['mode'], PDO::PARAM_STR);
        $stmt->bindParam(':questions', $data['questions'], PDO::PARAM_STR);

        $stmt->execute();

        $this->quizSessionId = $this->dbh->lastInsertId();
        $this->gameId = $data['game_id'];
        $this->attachPlayer($this->quizSessionId, $data['players'], 'host');
        return $this->quizSessionId;
    }

    public function updateStatus($status)
    {
        $stmt = $this->dbh->prepare("UPDATE quiz_sessions SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->quizSessionId, PDO::PARAM_INT);
        $stmt->execute();
        $this->loadQuizSessionDataById();
    }

    public function attachPlayer($quizSessionId, $playerId, $role = 'player', $status = 'active')
    {
        $sql = "INSERT INTO quiz_session_players (quiz_session_id, player_id, `role`, `status`) 
                VALUES (:quiz_session_id, :player_id, :role, :status)";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':quiz_session_id', $quizSessionId, PDO::PARAM_INT);
        $stmt->bindParam(':player_id', $playerId, PDO::PARAM_INT);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getPlayers()
    {
        $stmt = $this->dbh->prepare("SELECT p.id, p.lastname, p.firstname, p.email, qsp.role, qsp.status
                                     FROM quiz_session_players qsp 
                                     JOIN phpauth_users p ON qsp.player_id = p.id 
                                     WHERE qsp.quiz_session_id = :quiz_session_id");
        $stmt->bindParam(':quiz_session_id', $this->quizSessionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveStudentAnswer($questionId, $studentId, $selectedAnswer)
    {
        // Konvertieren der Antwort in JSON, falls es sich um ein Array handelt (z. B. bei Multiple-Choice)
        if (is_array($selectedAnswer)) {
            $selectedAnswer = json_encode($selectedAnswer);
        }

        // Überprüfen, ob bereits eine Antwort existiert
        $stmt = $this->dbh->prepare("
            SELECT COUNT(*) FROM quiz_session_answers 
            WHERE quiz_session_id = :quiz_session_id AND question_id = :question_id AND student_id = :student_id
        ");
        $stmt->bindParam(':quiz_session_id', $this->quizSessionId, PDO::PARAM_INT);
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        $exists = $stmt->fetchColumn();

        if ($exists) {
            // Update der bestehenden Antwort
            $stmt = $this->dbh->prepare("
                UPDATE quiz_session_answers 
                SET selected_answer = :selected_answer 
                WHERE quiz_session_id = :quiz_session_id AND question_id = :question_id AND student_id = :student_id
            ");
        } else {
            // Einfügen einer neuen Antwort
            $stmt = $this->dbh->prepare("
                INSERT INTO quiz_session_answers (quiz_session_id, question_id, student_id, selected_answer)
                VALUES (:quiz_session_id, :question_id, :student_id, :selected_answer)
            ");
        }

        $stmt->bindParam(':quiz_session_id', $this->quizSessionId, PDO::PARAM_INT);
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':selected_answer', $selectedAnswer, PDO::PARAM_STR);

        return $stmt->execute();
    }


    public function getRandomQuestionsFromCatalogs($questionCatalogs, $questionCount, $questionTypes)
    {
        if (empty($questionCatalogs) || $questionCount <= 0) {
            return [];
        }

        $questions = [];

        foreach ($questionCatalogs as $catalogId) {
            // Hole Fragen aus dem QuestionModel
            $catalogQuestions = $this->questionModel->getQuestionsByModuleIdAndTypes($catalogId, $questionTypes);
            $questions = array_merge($questions, $catalogQuestions);
        }

        // Mische die Fragen
        shuffle($questions);

        // Begrenze die Anzahl der Fragen
        $questions = array_slice($questions, 0, $questionCount);

        return $questions;
    }

    public function getQuestionsByIds($questionIds)
    {
        return $this->questionModel->getQuestionsByIds($questionIds);
    }

    protected function loadQuizSessionDataByGameId()
    {
        $stmt = $this->dbh->prepare("SELECT * FROM quiz_sessions WHERE game_id = :game_id");
        $stmt->bindParam(':game_id', $this->gameId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function loadQuizSessionDataById()
    {
        $stmt = $this->dbh->prepare("SELECT * FROM quiz_sessions WHERE id = :id");
        $stmt->bindParam(':id', $this->quizSessionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Methode zum Abrufen aller Fragen einer Quiz-Session
     */
    public function getAllQuestions($gameId)
    {
        // Zuerst die Fragen-IDs aus der `quiz_sessions`-Tabelle abrufen
        $stmt = $this->dbh->prepare("
            SELECT questions 
            FROM quiz_sessions 
            WHERE game_id = :game_id
        ");
        $stmt->bindParam(':game_id', $gameId, PDO::PARAM_STR);
        $stmt->execute();
        $quizSession = $stmt->fetch(PDO::FETCH_ASSOC);

        // Überprüfen, ob Daten vorhanden sind
        if (!$quizSession || empty($quizSession['questions'])) {
            return []; // Leeres Array zurückgeben, wenn keine Fragen vorhanden sind
        }

        // Fragen-IDs aus dem JSON-Array dekodieren
        $questionIds = json_decode($quizSession['questions'], true);

        if (empty($questionIds)) {
            return []; // Leeres Array zurückgeben, wenn keine Fragen-IDs vorhanden sind
        }

        // Platzhalter für die IN-Klausel erstellen
        $placeholders = implode(',', array_fill(0, count($questionIds), '?'));

        // Abrufen der Fragen basierend auf den IDs
        $stmt = $this->dbh->prepare("
            SELECT id, question_text, question_type, possible_answers 
            FROM questions 
            WHERE id IN ($placeholders)
        ");

        // IDs als Parameter an die Abfrage binden
        foreach ($questionIds as $index => $id) {
            $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Methode zum Abrufen der Antworten eines Studenten
     */
    public function getStudentAnswers($studentId)
    {
        $stmt = $this->dbh->prepare("
            SELECT question_id, selected_answer 
            FROM quiz_session_answers 
            WHERE quiz_session_id = :quiz_session_id AND student_id = :student_id
        ");
        $stmt->bindParam(':quiz_session_id', $this->quizSessionId, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Methode zur Bewertung der Antworten
     */
    public function evaluateAnswers($questions, $studentAnswers)
    {
        $correctCount = 0;
        $evaluatedResults = [];
    
        // Erstellen eines assoziativen Arrays der Antworten des Studenten für schnellen Zugriff
        $studentAnswersAssoc = [];
        foreach ($studentAnswers as $ans) {
            // Versuchen, die Antwort als JSON zu dekodieren
            $decodedAnswer = json_decode($ans['selected_answer'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Wenn die Dekodierung erfolgreich war, speichern wir das dekodierte Ergebnis
                $studentAnswersAssoc[$ans['question_id']] = $decodedAnswer;
            } else {
                // Andernfalls speichern wir die Antwort als einfachen String
                $studentAnswersAssoc[$ans['question_id']] = $ans['selected_answer'];
            }
        }
    
        foreach ($questions as $question) {
            $qId = $question['id'];
            $correctAnswers = [];
    
            // Dekodieren der möglichen Antworten und Sammeln der korrekten Antworten
            $possibleAnswers = json_decode($question['possible_answers'], true);
            if ($possibleAnswers === null && json_last_error() !== JSON_ERROR_NONE) {
                // Fehler beim Dekodieren der Antwortmöglichkeiten
                $possibleAnswers = [];
            }
    
            foreach ($possibleAnswers as $ans) {
                if (isset($ans['correct']) && $ans['correct']) {
                    if($question['question_type'] === 'open'){
                        $correctAnswers[] = $ans['correct'];
                    }
                    else{
                        $correctAnswers[] = $ans['text'];
                    }
                    
                }
            }
    
            // Abrufen der Antwort des Studenten
            $studentAnswer = isset($studentAnswersAssoc[$qId]) ? $studentAnswersAssoc[$qId] : null;
    
            // Bewertung der Antwort
            $isCorrect = false;
            if ($question['question_type'] === 'single') {
                // Single-Choice: Eine korrekte Antwort
                $isCorrect = ($studentAnswer === $correctAnswers[0]);
            } elseif ($question['question_type'] === 'multiple') {
                // Multiple-Choice: Mehrere korrekte Antworten
                if (is_array($studentAnswer)) {
                    // Sortieren beider Arrays, um die Reihenfolge zu ignorieren
                    $studentAnswerSorted = $studentAnswer;
                    $correctAnswersSorted = $correctAnswers;
                    sort($studentAnswerSorted);
                    sort($correctAnswersSorted);
                    $isCorrect = ($studentAnswerSorted === $correctAnswersSorted);
                }
            } elseif ($question['question_type'] === 'open') {
                // Offene Frage: Manuelle Bewertung erforderlich
                // Hier markieren wir sie standardmäßig als nicht korrekt
                // Sie können diese Logik anpassen, um eine automatische Bewertung zu implementieren
                $isCorrect = false;
            }
    
            if ($isCorrect) {
                $correctCount++;
            }
    
            $evaluatedResults[] = [
                'question_id' => $qId,
                'question_text' => $question['question_text'],
                'question_type' => $question['question_type'],
                'student_answer' => $studentAnswer,
                'correct_answers' => $correctAnswers,
                'is_correct' => $isCorrect
            ];
        }
    
        $totalQuestions = ($this->getStatus() != 'cancelled') ? count($questions) : 0;
        $percentage = ($this->getStatus() != 'cancelled') ? ($correctCount / $totalQuestions) * 100 : 0;
    
        // Notenberechnung basierend auf dem Prozentsatz
        $grade = $this->calculateGrade($percentage);
    
        return [
            'evaluated_results' => $evaluatedResults,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'grade' => $grade
        ];
    }
    
    // Funktion zur Berechnung der Note basierend auf dem Prozentsatz
    private function calculateGrade($percentage)
    {
        if ($percentage >= 93) {
            return "1,0";
        } elseif ($percentage >= 87) {
            return "1,3";
        } elseif ($percentage >= 83) {
            return "1,7";
        } elseif ($percentage >= 77) {
            return "2,0";
        } elseif ($percentage >= 73) {
            return "2,3";
        } elseif ($percentage >= 67) {
            return "2,7";
        } elseif ($percentage >= 63) {
            return "3,0";
        } elseif ($percentage >= 57) {
            return "3,3";
        } elseif ($percentage >= 53) {
            return "3,7";
        } elseif ($percentage >= 50) {
            return "4,0";
        } else {
            return "5,0";
        }
    }
    

    public function checkAndCompleteSession()
    {
        // Abfragen der gesamten Anzahl an Fragen in der Session
        $totalQuestions = count(json_decode($this->quizSessionData['questions'], true));

        // Abfragen der Anzahl an Antworten des Studenten
        $stmt = $this->dbh->prepare("
            SELECT COUNT(DISTINCT question_id) 
            FROM quiz_session_answers 
            WHERE quiz_session_id = :quiz_session_id
        ");
        $stmt->bindParam(':quiz_session_id', $this->quizSessionId, PDO::PARAM_INT);
        $stmt->execute();
        $answeredCount = $stmt->fetchColumn();

        // Prüfen, ob alle Fragen beantwortet wurden
        if ($answeredCount >= $totalQuestions) {
            // Setze den Status auf "completed"
            $this->updateStatus('completed');
        }
    }

    public function calculateProgress($playerId)
    {
        // Alle Fragen der Quizsession abrufen
        $questionIds = json_decode($this->quizSessionData['questions'], true);
        $questions = $this->getQuestionsByIds($questionIds);

        // Antworten des Studenten abrufen
        $studentAnswers = $this->getStudentAnswers($playerId);

        // Antworten bewerten
        $evaluation = $this->evaluateAnswers($questions, $studentAnswers);
        
        // Fortschrittsprozentsatz berechnen
        $correctCount = $evaluation['correct_count'];

        $totalQuestions = count($questions);

        if ($totalQuestions > 0) {
            $progressPercentage = ($correctCount / $totalQuestions) * 100;
        } else {
            $progressPercentage = 0;
        }

        return round($progressPercentage);
    }

    public function cancelSession()
    {
        $stmt = $this->dbh->prepare("UPDATE quiz_sessions SET status = 'cancelled' WHERE id = :id");
        $stmt->bindParam(':id', $this->quizSessionId, PDO::PARAM_INT);
        $stmt->execute();
        $this->loadQuizSessionDataById(); // Aktualisieren der Session-Daten
    }

}
