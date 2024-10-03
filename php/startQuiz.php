<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

header('Content-Type: application/json');

// Überprüfen, ob die Anfrage über POST erfolgt
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generiere eine eindeutige game_id im Backend
    $game_id = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

    // Daten aus dem Formular abrufen
    $mode = $_POST['mode'];
    $question_catalogs = json_decode($_POST['question_catalogs'], true);
    $question_count = intval($_POST['question_count']);
    $time_limit = intval($_POST['time_limit']);
    $question_types = json_decode($_POST['question_types'], true);
    $status = $_POST['status'];
    $players = json_decode($_POST['players'], true);

    // Controller initialisieren
    $controller = new QuizSessionController();

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

    // Quizsession erstellen
    $response = $controller->create($request);

    // JSON-Antwort ausgeben
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Ungültige Anfrage']);
}
?>
