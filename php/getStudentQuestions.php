<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Prüfen, ob eine Modul-ID übergeben wurde
if (isset($_GET['module_id'])) {
    // Modul-ID sicher als Ganzzahl umwandeln
    $moduleId = intval($_GET['module_id']);
    
    // Erstellen eines QuestionModel-Objekts zum Abrufen der Fragen
    $questionModel = new QuestionModel();
    
    // Abrufen der von Studenten gestellten Fragen für das angegebene Modul
    $questions = $questionModel->getStudentQuestionsByModuleId($moduleId);
    
    // Die Fragen als JSON kodiert zurückgeben
    echo json_encode($questions);
} else {
    // Falls keine Modul-ID angegeben wurde, wird eine Fehlermeldung zurückgegeben
    echo json_encode(['error' => 'Es wurde keine Modul-ID angegeben']);
}

?>
