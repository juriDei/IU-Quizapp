<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

class QuestionController
{
    private $questionModel;
    private $questionCatalogModel;

    public function __construct()
    {
        $this->questionModel = new QuestionModel();
        $this->questionCatalogModel = new QuestionCatalogModel();
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $questionText = $data['questionText'];
        $questionType = $data['questionType'];
        $possibleAnswers = $data['possibleAnswers'];
        $correctAnswer = $data['correctAnswer'] ?? null;
        $moduleId = $data['moduleId'];

        $result = $this->questionModel->createQuestion($questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId);

        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Frage erfolgreich erstellt']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Fehler beim Erstellen der Frage']);
        }
    }

    public function getQuestion($id)
    {
        $question = $this->questionModel->getQuestionById($id);
        if ($question) {
            echo json_encode($question);
        } else {
            echo json_encode(['message' => 'Frage nicht gefunden']);
        }
    }

    public function getCatalogQuestions($moduleId)
    {
        $questions = $this->questionModel->getQuestionsByModuleId($moduleId);
        if ($questions) {
            echo json_encode($questions);
        } else {
            echo json_encode(['message' => 'Keine Fragen gefunden']);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $questionText = $data['questionText'];
        $questionType = $data['questionType'];
        $possibleAnswers = $data['possibleAnswers'];
        $correctAnswer = $data['correctAnswer'] ?? null;
        $moduleId = $data['moduleId'];

        $result = $this->questionModel->updateQuestion($id, $questionText, $questionType, $possibleAnswers, $correctAnswer, $moduleId);

        if ($result) {
            echo json_encode(['message' => 'Frage erfolgreich aktualisiert']);
        } else {
            echo json_encode(['message' => 'Fehler beim Aktualisieren der Frage']);
        }
    }

    public function delete($id)
    {
        $result = $this->questionModel->deleteQuestion($id);

        if ($result) {
            echo json_encode(['message' => 'Frage erfolgreich gelöscht']);
        } else {
            echo json_encode(['message' => 'Fehler beim Löschen der Frage']);
        }
    }

    public function getStudentQuestions($moduleId)
    {
        $studentQuestions = $this->questionModel->getStudentQuestionsByModuleId($moduleId);
        if ($studentQuestions) {
            echo json_encode($studentQuestions);
        } else {
            echo json_encode(['message' => 'Keine Studentenfragen gefunden']);
        }
    }

    public function getRandomQuestions($moduleId, $limit = 5)
    {
        $questions = $this->questionModel->getQuestionsByModuleId($moduleId);
        shuffle($questions);
        $questions = array_slice($questions, 0, $limit);

        foreach ($questions as &$question) {
            $answers = json_decode($question['possible_answers'], true);
            shuffle($answers);
            $question['possible_answers'] = $answers;
        }

        if ($questions) {
            echo json_encode($questions);
        } else {
            echo json_encode(['message' => 'Keine Fragen gefunden']);
        }
    }

    /**
     * Methode zum Erstellen eines neuen Fragenkatalogs
     */
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
                http_response_code(400);
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

                    if(move_uploaded_file($fileTmpPath, $dest_path)) {
                        // URL zum gespeicherten Bild (passen Sie die URL entsprechend Ihrer Struktur an)
                        $imageUrl = '/uploads/module_images/' . $newFileName;
                    } else {
                        // Fehler beim Verschieben der Datei
                        $_SESSION['error'] = 'Beim Hochladen des Bildes ist ein Fehler aufgetreten.';
                        header('Location: /quizapp/question-catalog-overview'); // Passen Sie den Redirect-Pfad an
                        exit();
                    }
                } else {
                    // Ungültiges Dateiformat
                    $_SESSION['error'] = 'Ungültiges Dateiformat. Bitte laden Sie ein Bild im JPG, JPEG, PNG oder GIF Format hoch.';
                    header('Location: /quizapp/question-catalog-overview'); // Passen Sie den Redirect-Pfad an
                    exit();
                }
            }
    
            // Daten in die Datenbank einfügen
            $result = $this->questionCatalogModel->createModule($moduleName, $moduleAlias, $imageUrl, $tutorName);
    
            if ($result) {
                http_response_code(201);
                echo json_encode(['message' => 'Fragenkatalog erfolgreich erstellt.']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Fehler beim Erstellen des Fragenkatalogs.']);
            }
        } else {
            // Falls die Methode nicht POST ist
            http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed']);
        }
    }
    

}
