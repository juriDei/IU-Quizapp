<?php
// Starten der Session, falls noch nicht gestartet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialisierung des Controllers mit der Session-ID (game_id)
$quizSessionId = $_GET['session_id'] ?? null;
if (!$quizSessionId) {
    die('Session ID fehlt.');
}
$_SESSION['session_id'] = $quizSessionId;
$quizSessionController = new QuizSessionController($quizSessionId);
$quizSessionData = $quizSessionController->loadSession();

if (!$quizSessionData) {
    die('Quizsession nicht gefunden.');
}

// Extrahieren der Fragen
$questions = $quizSessionData['questions'];

// Gesamtanzahl der Fragen
$totalQuestions = count($questions);

?>
<!DOCTYPE html>
<html lang="de">
<?php require_once("component/head.php"); ?>
<body class="overflow-hidden">
    <!-- NAVBAR -->
    <?php include("component/navbar.php"); ?>
    <div id="main-content" class="container-fluid py-4 overflow-y-auto">
        <div class="container mt-5">
            <div class="card shadow-lg p-4">
                <!-- Versteckte Felder für student_id und session_id -->
                <input type="hidden" id="session-id" value="<?php echo htmlspecialchars($quizSessionData['game_id']); ?>">

                <!-- Fragen-Container -->
                <div id="question-container">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question" data-question-id="<?php echo $question['id']; ?>" data-question-type="<?php echo $question['question_type']; ?>" style="<?php echo $index === 0 ? '' : 'display:none;'; ?>">
                            <h3 class="mb-4 border-bottom pb-4">Frage <?php echo $index + 1; ?> von <?php echo $totalQuestions; ?></h3>
                            <p class="lead"><?php echo htmlspecialchars($question['question_text']); ?></p>
                            <?php
                            // Antwortmöglichkeiten dekodieren
                            $answers = json_decode($question['possible_answers'], true);
                            // Überprüfen, ob das Dekodieren erfolgreich war
                            if ($answers === null && json_last_error() !== JSON_ERROR_NONE) {
                                echo '<p>Fehler beim Dekodieren der Antwortmöglichkeiten.</p>';
                            } else {
                                switch ($question['question_type']) {
                                    case 'single':
                                        // Single-Choice-Frage (Radio-Buttons)
                                        foreach ($answers as $key => $answer) {
                            ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="answer_<?php echo $question['id']; ?>" id="option<?php echo $question['id'] . '_' . $key; ?>" value="<?php echo htmlspecialchars($answer['text']); ?>">
                                                <label class="form-check-label" for="option<?php echo $question['id'] . '_' . $key; ?>">
                                                    <?php echo htmlspecialchars($answer['text']); ?>
                                                </label>
                                            </div>
                                        <?php
                                        }
                                        break;

                                    case 'multiple':
                                        // Multiple-Choice-Frage (Checkboxen)
                                        foreach ($answers as $key => $answer) {
                                        ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="answer_<?php echo $question['id']; ?>[]" id="option<?php echo $question['id'] . '_' . $key; ?>" value="<?php echo htmlspecialchars($answer['text']); ?>">
                                                <label class="form-check-label" for="option<?php echo $question['id'] . '_' . $key; ?>">
                                                    <?php echo htmlspecialchars($answer['text']); ?>
                                                </label>
                                            </div>
                                        <?php
                                        }
                                        break;

                                    case 'open':
                                        // Offene Frage (Textarea)
                                        ?>
                                        <div class="form-group">
                                            <textarea class="form-control" name="answer_<?php echo $question['id']; ?>" rows="5" placeholder="Ihre Antwort"></textarea>
                                        </div>
                            <?php
                                        break;

                                    default:
                                        echo '<p>Unbekannter Fragetyp.</p>';
                                        break;
                                }
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Navigationsbuttons -->
                <div class="mt-4 d-flex justify-content-between">
                    <button id="prev-button" class="btn btn-secondary" disabled>Zurück</button>
                    <button id="next-button" class="btn btn-primary">Weiter</button>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("component/modal/friendListModal.php"); ?>
    <!-- Ihr eigenes JavaScript -->
    <script src="js/quizsession.js"></script>
</body>

</html>