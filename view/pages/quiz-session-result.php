<?php
// Starten der Session, falls noch nicht gestartet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$quizSessionController = new QuizSessionController($_GET['session_id'] );
$status = $quizSessionController->getStatus();

$evaluation = $quizSessionController->showResults();

// Sicherstellen, dass die notwendigen Daten vorhanden sind
if (!isset($evaluation)) {
    die('Keine Auswertungsdaten vorhanden.');
}
$totalQuestions = count($evaluation['evaluated_results']);
$percentage = ($totalQuestions > 0) ? round(($evaluation['correct_count'] / $totalQuestions ) * 100, 2) : 0;

?>
<!DOCTYPE html>
<html lang="de">
<?php require_once("component/head.php"); ?>
<body class="overflow-hidden">
    <!-- NAVBAR-->
    <?php include("component/navbar.php"); ?>
    <div id="main-content" class="container-fluid py-4 overflow-y-auto">
        <div class="container mt-5">
            <div class="card shadow-lg p-4 mb-5">
                <h3 class="mb-4 border-bottom pb-4">Auswertung Ihrer Quiz-Session</h3>

                <?php if ($status === 'cancelled'): ?>
                    <!-- Nachricht, wenn das Quiz abgebrochen wurde -->
                    <div class="alert alert-warning text-center" role="alert">
                        <h4 class="alert-heading">Quiz wurde abgebrochen</h4>
                        <p class="lead">Leider wurde Ihre Quiz-Session vorzeitig abgebrochen. Keine Auswertung verfügbar.</p>
                    </div>
                <?php else: ?>
                    <!-- Statistiken über der Tabelle, wenn das Quiz nicht abgebrochen wurde -->
                    <div class="row mb-4">
                        <!-- Richtige Antworten -->
                        <div class="col-md-4">
                            <div class="card text-center bg-light shadow-sm p-3">
                                <h4>Richtige Antworten</h4>
                                <p class="display-4 text-info"><?php echo $evaluation['correct_count']; ?> / <?php echo $totalQuestions; ?></p>
                            </div>
                        </div>

                        <!-- Prozentsatz der richtigen Antworten -->
                        <div class="col-md-4">
                            <div class="card text-center bg-light shadow-sm p-3">
                                <h4>Note</h4>
                                <p class="display-4 text-info"><?= $evaluation['grade']." (".(int)$percentage."%)"; ?></p>
                            </div>
                        </div>

                        <!-- Quiz bestanden/nicht bestanden -->
                        <div class="col-md-4">
                            <div class="card text-center bg-light shadow-sm p-3">
                                <h4>Ergebnis</h4>
                                <p class="fs-2">
                                    <?php if ($percentage >= 50): ?>
                                        <span class="badge bg-success">Quiz bestanden</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Quiz nicht bestanden</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tabelle der Antworten nur anzeigen, wenn das Quiz nicht abgebrochen wurde -->
                <?php if ($status !== 'cancelled'): ?>
                    <p class="lead">Hier sehen Sie eine Zusammenfassung Ihrer Antworten:</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Frage</th>
                                    <th>Ihre Antwort</th>
                                    <th>Korrekte Antwort</th>
                                    <th>Ergebnis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($evaluation['evaluated_results'] as $result): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($result['question_text']); ?></td>
                                        <td>
                                            <?php
                                            if ($result['question_type'] === 'multiple') {
                                                echo is_array($result['student_answer']) ? htmlspecialchars(implode(', ', $result['student_answer'])) : '-';
                                            } elseif ($result['question_type'] === 'open') {
                                                echo htmlspecialchars($result['student_answer']) ?: '-';
                                            } else {
                                                echo htmlspecialchars($result['student_answer']) ?: '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($result['question_type'] === 'multiple') {
                                                echo htmlspecialchars(implode(', ', $result['correct_answers']));
                                            } else {
                                                echo htmlspecialchars($result['correct_answers'][0]);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($result['is_correct']): ?>
                                                <span class="badge bg-success">Richtig</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Falsch</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Dashboard Button -->
                <div class="text-end">
                    <a href="dashboard" class="btn btn-primary mt-3">Zurück zum Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("component/modal/friendListModal.php"); ?>
    <script src="js/friendlist.js"></script>
    <script src="js/quizsession.js"></script>
</body>
</html>
