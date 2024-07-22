<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

if (isset($_GET['module_id'])) {
    $moduleId = intval($_GET['module_id']);
    $questionModel = new QuestionModel();
    $questions = $questionModel->getStudentQuestionsByModuleId($moduleId);
    echo json_encode($questions);
} else {
    echo json_encode(['error' => 'Es wurde keine Modul-ID angegeben']);
}

?>