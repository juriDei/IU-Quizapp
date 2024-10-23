<?php

// Autoload-Funktionalität von Composer laden, um alle benötigten Bibliotheken und Abhängigkeiten bereitzustellen
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Controller-Klasse zur Verwaltung von Fragen und Fragenkatalogen
class QuestionController
{
    // Instanzen der Modelle für Fragen und Fragenkataloge
    private $questionModel;
    private $questionCatalogModel;

    // Konstruktor: Initialisiert die Frage- und Fragenkatalog-Modelle
    public function __construct()
    {
        $this->questionModel = new QuestionModel();
        $this->questionCatalogModel = new QuestionCatalogModel();
    }

    // Methode zum Erstellen einer neuen Frage
    public function create()
    {
        // Daten aus dem HTTP-Request abrufen
        $data = json_decode(file_get_contents('php://input'), true);
        $questionText = $data['questionText'];
        $questionType = $data['questionType'];
        $possibleAnswers = $data['possibleAnswers'];
        $correctAnswer = $data['correctAnswer'] ?? null;
        $moduleId = $data['moduleId'];

        // Frage über das Modell erstellen
        $result = $this->questionModel->createQuestion($questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId);

        // Erfolgreiche Erstellung der Frage prüfen und entsprechende Antwort senden
        if ($result) {
            http_response_code(201); // Statuscode 201: Created
            echo json_encode(['message' => 'Frage erfolgreich erstellt']);
        } else {
            http_response_code(500); // Statuscode 500: Internal Server Error
            echo json_encode(['message' => 'Fehler beim Erstellen der Frage']);
        }
    }

    // Methode zum Abrufen einer Frage anhand ihrer ID
    public function getQuestion($id)
    {
        // Frage über das Modell abrufen
        $question = $this->questionModel->getQuestionById($id);
        if ($question) {
            echo json_encode($question);
        } else {
            echo json_encode(['message' => 'Frage nicht gefunden']);
        }
    }

    // Methode zum Abrufen aller Fragen eines bestimmten Moduls
    public function getCatalogQuestions($moduleId)
    {
        // Fragen für das Modul abrufen
        $questions = $this->questionModel->getQuestionsByModuleId($moduleId);
        if ($questions) {
            echo json_encode($questions);
        } else {
            echo json_encode(['message' => 'Keine Fragen gefunden']);
        }
    }

    // Methode zum Aktualisieren einer bestehenden Frage
    public function update($id)
    {
        // Daten aus dem HTTP-Request abrufen
        $data = json_decode(file_get_contents('php://input'), true);
        $questionText = $data['questionText'];
        $questionType = $data['questionType'];
        $possibleAnswers = $data['possibleAnswers'];
        $correctAnswer = $data['correctAnswer'] ?? null;
        $moduleId = $data['moduleId'];

        // Frage aktualisieren
        $result = $this->questionModel->updateQuestion($id, $questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId);

        // Erfolgreiche Aktualisierung prüfen und entsprechende Antwort senden
        if ($result) {
            echo json_encode(['message' => 'Frage erfolgreich aktualisiert']);
        } else {
            echo json_encode(['message' => 'Fehler beim Aktualisieren der Frage']);
        }
    }

    // Methode zum Löschen einer Frage
    public function delete($id)
    {
        // Frage löschen
        $result = $this->questionModel->deleteQuestion($id);

        // Erfolgreiches Löschen prüfen und entsprechende Antwort senden
        if ($result) {
            echo json_encode(['message' => 'Frage erfolgreich gelöscht']);
        } else {
            echo json_encode(['message' => 'Fehler beim Löschen der Frage']);
        }
    }

    // Methode zum Abrufen von Fragen, die von Studenten eingereicht wurden, für ein bestimmtes Modul
    public function getStudentQuestions($moduleId)
    {
        // Studentenfragen für das Modul abrufen
        $studentQuestions = $this->questionModel->getStudentQuestionsByModuleId($moduleId);
        if ($studentQuestions) {
            echo json_encode($studentQuestions);
        } else {
            echo json_encode(['message' => 'Keine Studentenfragen gefunden']);
        }
    }

    // Methode zum Abrufen einer zufälligen Auswahl an Fragen eines Moduls
    public function getRandomQuestions($moduleId, $limit = 5)
    {
        // Alle Fragen für das Modul abrufen
        $questions = $this->questionModel->getQuestionsByModuleId($moduleId);
        shuffle($questions); // Fragen mischen
        $questions = array_slice($questions, 0, $limit); // Limitierte Anzahl von Fragen nehmen

        // Antworten der Fragen ebenfalls mischen
        foreach ($questions as &$question) {
            $answers = json_decode($question['possible_answers'], true);
            shuffle($answers);
            $question['possible_answers'] = $answers;
        }

        // Fragen als JSON zurückgeben
        if ($questions) {
            echo json_encode($questions);
        } else {
            echo json_encode(['message' => 'Keine Fragen gefunden']);
        }
    }

    // Methode zum Erstellen eines neuen Fragenkatalogs
    public function createCatalog()
    {
        // Überprüfen, ob das Formular gesendet wurde
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validierung der Eingabedaten
            $moduleName = trim($_POST['moduleName']);
            $moduleAlias = trim($_POST['moduleAlias']);
            $tutorName = trim($_POST['tutorName']);
    
            // Überprüfen, ob alle erforderlichen Felder ausgefüllt sind
            if (empty($moduleName) || empty($moduleAlias) || empty($tutorName)) {
                http_response_code(400); // Statuscode 400: Bad Request
                echo json_encode(['message' => 'Bitte füllen Sie alle erforderlichen Felder aus.']);
                exit();
            }
    
            // Bild-Upload verarbeiten
            $imageUrl = null;
            if (isset($_FILES['moduleImage']) && $_FILES['moduleImage']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['moduleImage']['tmp_name'];
                $fileName = $_FILES['moduleImage']['name'];
                $fileSize = $_FILES['moduleImage']['size'];
                $fileType = $_FILES['moduleImage']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // Erlaubte Dateiformate
                $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    // Verzeichnis, in das das Bild hochgeladen wird
                    $uploadFileDir = dirname(__DIR__, 2) . '/uploads/module_images/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $dest_path = $uploadFileDir . $newFileName;

                    // Datei verschieben und Bild-URL festlegen
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $imageUrl = '/uploads/module_images/' . $newFileName;
                    } else {
                        $_SESSION['error'] = 'Beim Hochladen des Bildes ist ein Fehler aufgetreten.';
                        header('Location: /quizapp/question-catalog-overview');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'Ungültiges Dateiformat. Bitte laden Sie ein Bild im JPG, JPEG, PNG oder GIF Format hoch.';
                    header('Location: /quizapp/question-catalog-overview');
                    exit();
                }
            }
    
            // Daten in die Datenbank einfügen
            $result = $this->questionCatalogModel->createModule($moduleName, $moduleAlias, $imageUrl, $tutorName);
    
            if ($result) {
                http_response_code(201); // Statuscode 201: Created
                echo json_encode(['message' => 'Fragenkatalog erfolgreich erstellt.']);
            } else {
                http_response_code(500); // Statuscode 500: Internal Server Error
                echo json_encode(['message' => 'Fehler beim Erstellen des Fragenkatalogs.']);
            }
        } else {
            http_response_code(405); // Statuscode 405: Method Not Allowed
            echo json_encode(['message' => 'Method Not Allowed']);
        }
    }
}
?>
